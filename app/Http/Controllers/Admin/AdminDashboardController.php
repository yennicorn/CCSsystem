<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    private const GRADE_LEVELS = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];

    public function index()
    {
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'reviewed' => Application::where('status', 'reviewed')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'by_grade' => Application::selectRaw('grade_level, COUNT(*) as total')->groupBy('grade_level')->pluck('total', 'grade_level'),
        ];

        $genderStats = Application::selectRaw("COALESCE(NULLIF(gender, ''), 'unspecified') as gender, COUNT(*) as total")
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $notificationCount = (int) $stats['pending'];

        return view('admin.dashboard', compact('stats', 'genderStats', 'notificationCount'));
    }

    public function applications()
    {
        $applications = Application::whereIn('status', ['pending', 'reviewed'])->latest()->paginate(12);

        return view('admin.applications', compact('applications'));
    }

    public function monitoring(Request $request)
    {
        $nameFilter = trim((string) $request->input('name', ''));

        $applicationsQuery = Application::query()
            ->whereIn('grade_level', self::GRADE_LEVELS);

        if ($nameFilter !== '') {
            $applicationsQuery->where(function ($query) use ($nameFilter) {
                $query->where('learner_full_name', 'like', '%'.$nameFilter.'%')
                    ->orWhere('last_name', 'like', '%'.$nameFilter.'%')
                    ->orWhere('first_name', 'like', '%'.$nameFilter.'%')
                    ->orWhere('middle_name', 'like', '%'.$nameFilter.'%');
            });
        }

        $applications = $applicationsQuery
            ->latest('submitted_at')
            ->latest('created_at')
            ->get();

        $applicationsByGrade = collect(self::GRADE_LEVELS)
            ->mapWithKeys(fn ($grade) => [$grade => $applications->where('grade_level', $grade)->values()]);

        return view('admin.monitoring', compact('applicationsByGrade', 'nameFilter'));
    }

    public function showMonitoringApplication(Application $application)
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        return view('admin.monitoring-show', compact('application'));
    }
}
