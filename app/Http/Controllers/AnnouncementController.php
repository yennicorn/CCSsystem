<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'super_admin'], true), 403);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->hasFile('image')
            ? $request->file('image')->store('announcements', 'public')
            : null;

        $announcement = Announcement::create([
            'author_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'publish_at' => $request->publish_at,
            'image_path' => $path,
        ]);

        AuditLogger::log('announcement_created', 'announcement', $announcement->id);

        $dashboardRoute = auth()->user()->role === 'super_admin' ? 'master.dashboard' : 'admin.dashboard';
        return redirect()->route($dashboardRoute)->with('success', 'Announcement created.');
    }

    public function edit(Announcement $announcement)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'super_admin'], true), 403);
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'super_admin'], true), 403);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $announcement->image_path = $request->file('image')->store('announcements', 'public');
        }

        $announcement->title = $request->title;
        $announcement->content = $request->content;
        $announcement->publish_at = $request->publish_at;
        $announcement->save();

        AuditLogger::log('announcement_updated', 'announcement', $announcement->id);

        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'super_admin'], true), 403);
        $announcement->delete();
        AuditLogger::log('announcement_deleted', 'announcement', $announcement->id);

        return back()->with('success', 'Announcement deleted.');
    }
}
