<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\Student;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DecisionController extends Controller
{
    public function decide(Request $request, Application $application)
    {
        abort_unless(auth()->user()?->role === 'super_admin', 403);
        $request->validate([
            'status' => 'required|in:approved',
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($application->status !== 'reviewed') {
            return back()->withErrors(['status' => 'Only reviewed applications can receive final decisions.']);
        }

        DB::transaction(function () use ($request, $application) {
            $application->update([
                'status' => $request->status,
                'finalized_at' => now(),
                'finalized_by' => auth()->id(),
                'remarks' => $request->remarks,
            ]);

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'changed_by' => auth()->id(),
                'status' => $request->status,
                'remarks' => $request->remarks,
                'changed_at' => now(),
            ]);

            if ($request->status === 'approved') {
                Student::firstOrCreate(
                    ['application_id' => $application->id],
                    [
                        'user_id' => $application->user_id,
                        'student_no' => 'CCS-'.now()->format('Y').'-'.str_pad((string) $application->id, 5, '0', STR_PAD_LEFT),
                        'full_name' => $application->learner_full_name,
                        'grade_level' => $application->grade_level,
                    ]
                );
            }
        });

        AuditLogger::log('application_finalized', 'application', $application->id, [
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Final approval saved.');
    }
}
