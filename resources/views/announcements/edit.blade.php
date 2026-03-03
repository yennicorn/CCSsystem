@php($prefix = auth()->user()->role === 'super_admin' ? 'master' : 'admin')
@extends(auth()->user()->role === 'super_admin' ? 'layouts.master-admin' : 'layouts.admin')

@section('page_title', 'Edit Announcement')
@section('page_subtitle', 'Update existing announcement content')

@section('content')
<section class="panel">
    <div class="panel-head"><h2> Edit Announcement</h2></div>
    <form method="POST" action="{{ route($prefix.'.announcements.update', $announcement) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required>

        <label>Content</label>
        <textarea name="content" rows="5" required>{{ old('content', $announcement->content) }}</textarea>

        <label>Schedule Publish Time (optional)</label>
        <input type="datetime-local" name="publish_at" value="{{ old('publish_at', optional($announcement->publish_at)->format('Y-m-d\\TH:i')) }}">

        <label>Replace Image (optional)</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png">

        <button class="btn mt-10" type="submit"> Update Announcement</button>
    </form>
</section>
@endsection
