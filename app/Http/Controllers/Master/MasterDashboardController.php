<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\SchoolYear;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MasterDashboardController extends Controller
{
    private const GRADE_LEVELS = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];

    private const DISABILITY_TYPES = [
        'visual_impairment',
        'hearing_impairment',
        'learning_disability',
        'intellectual_disability',
        'autism_spectrum_disorder',
        'emotional_behavioral_disorder',
        'orthopedic_physical_handicap',
        'speech_language_disorder',
        'cerebral_palsy',
        'special_health_problem',
        'multiple_disorder',
        'other_disability',
    ];

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

        $notificationCount = (int) $stats['reviewed'];

        return view('master.dashboard', compact('stats', 'genderStats', 'notificationCount'));
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

        return view('master.monitoring', compact('applicationsByGrade', 'nameFilter'));
    }

    public function showMonitoringApplication(Application $application)
    {
        abort_unless(auth()->user()?->role === 'super_admin', 403);

        $canEdit = session()->has($this->unlockSessionKey($application->id));

        return view('master.monitoring-show', compact('application', 'canEdit'));
    }

    public function unlockMonitoringEdit(Request $request, Application $application)
    {
        abort_unless(auth()->user()?->role === 'super_admin', 403);
        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->password, (string) auth()->user()->password)) {
            return back()->withErrors(['password' => 'Invalid password. Edit access denied.']);
        }

        session()->put($this->unlockSessionKey($application->id), true);

        return back()->with('success', 'Edit access granted. You can now update this enrollment form.');
    }

    public function updateMonitoringApplication(Request $request, Application $application)
    {
        abort_unless(auth()->user()?->role === 'super_admin', 403);

        if (!session()->has($this->unlockSessionKey($application->id))) {
            return back()->withErrors(['password' => 'Please unlock editing with your password first.']);
        }

        $validated = $request->validate($this->monitoringFormRules());
        $application->update($this->payloadFromValidated($validated));

        session()->forget($this->unlockSessionKey($application->id));

        AuditLogger::log('application_form_updated_by_super_admin', 'application', $application->id, [
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Enrollment form updated successfully.');
    }

    public function toggleEnrollment(SchoolYear $schoolYear)
    {
        $schoolYear->enrollment_open = !$schoolYear->enrollment_open;
        $schoolYear->save();

        AuditLogger::log('school_year_enrollment_toggled', 'school_year', $schoolYear->id, [
            'enrollment_open' => $schoolYear->enrollment_open,
        ]);

        return back()->with('success', 'Enrollment status updated.');
    }

    public function setActive(SchoolYear $schoolYear)
    {
        SchoolYear::query()->update(['is_active' => false]);
        $schoolYear->is_active = true;
        $schoolYear->save();

        AuditLogger::log('school_year_activated', 'school_year', $schoolYear->id, [
            'name' => $schoolYear->name,
        ]);

        return back()->with('success', 'Active school year updated.');
    }

    public function enrollment()
    {
        $applications = Application::where('status', 'reviewed')->latest()->paginate(10);

        return view('master.enrollment', compact('applications'));
    }

    public function schoolYears()
    {
        $schoolYears = SchoolYear::latest()->get();
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        return view('master.school-years', compact('schoolYears', 'activeSchoolYear'));
    }

    public function backup()
    {
        $recentAuditLogs = AuditLog::latest()->take(8)->get();

        return view('master.backup', compact('recentAuditLogs'));
    }

    private function unlockSessionKey(int $applicationId): string
    {
        return 'monitoring_edit_unlocked_'.$applicationId;
    }

    private function monitoringFormRules(): array
    {
        return [
            'grade_level' => 'required|in:'.implode(',', self::GRADE_LEVELS),
            'with_lrn' => 'required|boolean',
            'lrn' => 'nullable|string|max:20|required_if:with_lrn,1',
            'returning_learner' => 'required|boolean',
            'psa_birth_certificate_no' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'mother_tongue' => 'nullable|string|max:255',
            'has_ip_affiliation' => 'required|boolean',
            'ip_affiliation' => 'nullable|string|max:255|required_if:has_ip_affiliation,1',
            'is_4ps_beneficiary' => 'required|boolean',
            'four_ps_household_id' => 'nullable|string|max:255|required_if:is_4ps_beneficiary,1',
            'is_lwd' => 'required|boolean',
            'disability_types' => 'nullable|array',
            'disability_types.*' => 'in:'.implode(',', self::DISABILITY_TYPES),
            'current_house_no' => 'nullable|string|max:255',
            'current_street' => 'nullable|string|max:255',
            'current_barangay' => 'nullable|string|max:255',
            'current_municipality' => 'nullable|string|max:255',
            'current_province' => 'nullable|string|max:255',
            'current_country' => 'nullable|string|max:255',
            'current_zip_code' => 'nullable|string|max:20',
            'permanent_house_no' => 'nullable|string|max:255',
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_municipality' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zip_code' => 'nullable|string|max:20',
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_contact_number' => 'nullable|string|max:50',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_contact_number' => 'nullable|string|max:50',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_contact_number' => 'nullable|string|max:50',
        ];
    }

    private function payloadFromValidated(array $validated): array
    {
        $middle = trim((string) ($validated['middle_name'] ?? ''));
        $fullName = trim(
            ($validated['last_name'] ?? '').', '.($validated['first_name'] ?? '').($middle !== '' ? ' '.$middle : '')
        );

        return [
            'learner_full_name' => $fullName,
            'grade_level' => $validated['grade_level'],
            'with_lrn' => (bool) $validated['with_lrn'],
            'lrn' => $validated['with_lrn'] ? ($validated['lrn'] ?? null) : null,
            'returning_learner' => (bool) $validated['returning_learner'],
            'psa_birth_certificate_no' => $validated['psa_birth_certificate_no'] ?? null,
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'birthdate' => $validated['birthdate'],
            'place_of_birth' => $validated['place_of_birth'],
            'gender' => $validated['gender'],
            'mother_tongue' => $validated['mother_tongue'] ?? null,
            'has_ip_affiliation' => (bool) $validated['has_ip_affiliation'],
            'ip_affiliation' => $validated['has_ip_affiliation'] ? ($validated['ip_affiliation'] ?? null) : null,
            'is_4ps_beneficiary' => (bool) $validated['is_4ps_beneficiary'],
            'four_ps_household_id' => $validated['is_4ps_beneficiary'] ? ($validated['four_ps_household_id'] ?? null) : null,
            'is_lwd' => (bool) $validated['is_lwd'],
            'disability_types' => $validated['is_lwd'] ? ($validated['disability_types'] ?? []) : [],
            'current_house_no' => $validated['current_house_no'] ?? null,
            'current_street' => $validated['current_street'] ?? null,
            'current_barangay' => $validated['current_barangay'] ?? null,
            'current_municipality' => $validated['current_municipality'] ?? null,
            'current_province' => $validated['current_province'] ?? null,
            'current_country' => $validated['current_country'] ?? null,
            'current_zip_code' => $validated['current_zip_code'] ?? null,
            'permanent_house_no' => $validated['permanent_house_no'] ?? null,
            'permanent_street' => $validated['permanent_street'] ?? null,
            'permanent_barangay' => $validated['permanent_barangay'] ?? null,
            'permanent_municipality' => $validated['permanent_municipality'] ?? null,
            'permanent_province' => $validated['permanent_province'] ?? null,
            'permanent_country' => $validated['permanent_country'] ?? null,
            'permanent_zip_code' => $validated['permanent_zip_code'] ?? null,
            'father_last_name' => $validated['father_last_name'] ?? null,
            'father_first_name' => $validated['father_first_name'] ?? null,
            'father_middle_name' => $validated['father_middle_name'] ?? null,
            'father_contact_number' => $validated['father_contact_number'] ?? null,
            'mother_last_name' => $validated['mother_last_name'] ?? null,
            'mother_first_name' => $validated['mother_first_name'] ?? null,
            'mother_middle_name' => $validated['mother_middle_name'] ?? null,
            'mother_contact_number' => $validated['mother_contact_number'] ?? null,
            'guardian_last_name' => $validated['guardian_last_name'] ?? null,
            'guardian_first_name' => $validated['guardian_first_name'] ?? null,
            'guardian_middle_name' => $validated['guardian_middle_name'] ?? null,
            'guardian_contact_number' => $validated['guardian_contact_number'] ?? null,
        ];
    }
}
