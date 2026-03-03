@php($prefix = auth()->user()->role === 'super_admin' ? 'master' : 'admin')
@extends(auth()->user()->role === 'super_admin' ? 'layouts.master-admin' : 'layouts.admin')

@section('page_title', 'Announcements')
@section('page_subtitle', 'Create, schedule, and manage official posts')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h2> Announcements Management</h2>
        <a class="btn" href="{{ route($prefix.'.announcements.create') }}"> Create Announcement</a>
    </div>

    @forelse($announcements as $a)
        <article class="feed-post">
            <h3>{{ $a->title }}</h3>
            <div class="feed-meta">Scheduled: {{ optional($a->publish_at)->format('M d, Y h:i A') ?? 'Publish immediately' }}</div>
            <p>{{ $a->content }}</p>
            @if($a->image_path)
                <img src="{{ asset('storage/'.$a->image_path) }}" alt="Announcement image" class="img-preview">
            @endif
            <div class="action-inline mt-10">
                <a class="btn btn-secondary" href="{{ route($prefix.'.announcements.edit', $a) }}"> Edit</a>
                <form method="POST" action="{{ route($prefix.'.announcements.destroy', $a) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit"> Delete</button>
                </form>
            </div>
        </article>
    @empty
        <p>No announcements found.</p>
    @endforelse

    <div class="pagination-wrap">{{ $announcements->links() }}</div>
</section>
@endsection
