@extends('layouts.master-admin')

@section('page_title', 'Settings')
@section('page_subtitle', 'Super Administrator account settings')

@section('content')
<section class="panel">
    <div class="panel-head"><h3> Profile Information</h3></div>
    <p><strong> Name:</strong> {{ auth()->user()->full_name }}</p>
    <p><strong> Email:</strong> {{ auth()->user()->email }}</p>
    <p><strong> Role:</strong> {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</p>
    <p><strong> Status:</strong> {{ auth()->user()->is_active ? 'Active' : 'Deactivated' }}</p>
</section>
@endsection
