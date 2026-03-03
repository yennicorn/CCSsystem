@php($prefix = auth()->user()->role === 'super_admin' ? 'master' : 'admin')
@extends(auth()->user()->role === 'super_admin' ? 'layouts.master-admin' : 'layouts.admin')

@section('page_title', 'Create Announcement')
@section('page_subtitle', 'Publish an official update for users')

@section('content')
<section class="panel">
    <div class="panel-head"><h2> Create Announcement</h2></div>
    <form method="POST" action="{{ route($prefix.'.announcements.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Content</label>
        <textarea name="content" rows="5" required>{{ old('content') }}</textarea>

        <label>Schedule Publish Time (optional)</label>
        <input type="datetime-local" name="publish_at" value="{{ old('publish_at') }}">

        <label>Image (optional, JPG/JPEG/PNG)</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png">

        <button class="btn mt-10" type="submit"> Save Announcement</button>
    </form>
</section>
@endsection
