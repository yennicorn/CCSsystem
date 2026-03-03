@extends('layouts.enduser')

@section('page_title', 'Information Feed')
@section('page_subtitle', 'School announcements and official updates for parents and students.')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h2><span class="icon-inline"><x-icon name="announcements" /> Announcement Feed</span></h2>
        @if($application)
            <a class="btn btn-secondary" href="{{ route('homepage.enrollment') }}">
                <x-icon name="timeline" /> Track Enrollment Status
            </a>
        @endif
    </div>

    @if($application)
        <div class="enduser-status-strip">
            <div>
                <p class="muted">Your Current Enrollment Status</p>
                <span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span>
            </div>
            <div class="enduser-status-cta">
                <a class="btn" href="{{ route('homepage.enrollment') }}">Go to Enrollment</a>
            </div>
        </div>
    @endif

    @forelse($announcements as $a)
        <article class="feed-post">
            <h4>{{ $a->title }}</h4>
            <div class="feed-meta">{{ optional($a->publish_at)->format('M d, Y h:i A') ?? $a->created_at->format('M d, Y h:i A') }}</div>
            <p>{{ $a->content }}</p>
            @if($a->image_path)
                <img src="{{ asset('storage/'.$a->image_path) }}" alt="Announcement image">
            @endif
        </article>
    @empty
        <p>No announcements available.</p>
    @endforelse
</section>
@endsection
