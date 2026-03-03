@extends('layouts.master')

@section('page_title', 'Account Settings')
@section('page_subtitle', 'Compatibility settings view')
@section('sidebar')
<a class="sidebar-link" href="{{ route('dashboard') }}">Dashboard</a>
<a class="sidebar-link active" href="#">Settings</a>
@endsection

@section('content')
<section class="panel">
    <div class="panel-head"><h2> Profile</h2></div>
    <p><strong> Name:</strong> {{ auth()->user()->full_name ?? 'N/A' }}</p>
    <p><strong> Email:</strong> {{ auth()->user()->email ?? 'N/A' }}</p>
    <p><strong> Role:</strong> {{ auth()->user()->role ?? 'N/A' }}</p>
</section>
@endsection
