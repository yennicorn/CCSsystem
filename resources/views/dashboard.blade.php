@extends('layouts.master')

@section('page_title', 'Legacy Dashboard')
@section('page_subtitle', 'Compatibility route for existing bookmarks')
@section('sidebar')
<a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
<a class="sidebar-link" href="{{ route('homepage') }}">Homepage</a>
@endsection

@section('content')
<section class="panel">
    <div class="panel-head"><h2> System Dashboard</h2></div>
    <p>This legacy dashboard page is retained for compatibility. Active role dashboards are now under:</p>
    <ul>
        <li><strong>Super Admin:</strong> <code>/super-dashboard</code></li>
        <li><strong>Admin:</strong> <code>/admin-dashboard</code></li>
        <li><strong>Parent/Student:</strong> <code>/homepage</code></li>
    </ul>
</section>
@endsection
