@extends('layouts.master')

@section('page_title', 'Admin Dashboard')
@section('page_subtitle', 'Operational Review and Monitoring')

@section('sidebar')
<div class="role-chip">Role: ADMIN</div>

<a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
    <span class="nav-ico"><x-icon name="dashboard" /></span> Dashboard
</a>
<a class="sidebar-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">
    <span class="nav-ico"><x-icon name="applications" /></span> Applications
</a>
<a class="sidebar-link {{ request()->routeIs('admin.monitoring') ? 'active' : '' }}" href="{{ route('admin.monitoring') }}">
    <span class="nav-ico"><x-icon name="monitor" /></span> Monitoring
</a>
<a class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
    <span class="nav-ico"><x-icon name="announcements" /></span> Announcements
</a>
@endsection
