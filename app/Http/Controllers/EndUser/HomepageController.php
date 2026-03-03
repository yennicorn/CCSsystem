<?php

namespace App\Http\Controllers\EndUser;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Application;
use App\Models\SchoolYear;

class HomepageController extends Controller
{
    public function index()
    {
        return redirect()->route('homepage.feed');
    }

    public function feed()
    {
        $announcements = Announcement::where(function ($q) {
            $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
        })->latest('publish_at')->latest('created_at')->get();

        $application = Application::where('user_id', auth()->id())->latest()->first();

        return view('enduser.feed', compact('announcements', 'application'));
    }

    public function enrollment()
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $application = Application::where('user_id', auth()->id())->latest()->first();

        if ($application) {
            $application->load([
                'statusLogs' => fn ($query) => $query->orderBy('changed_at'),
                'statusLogs.changedBy',
                'documents',
            ]);
        }

        return view('enduser.homepage', compact('activeSchoolYear', 'application'));
    }
}
