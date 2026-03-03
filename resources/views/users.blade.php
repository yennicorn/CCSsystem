@extends('layouts.master')

@section('page_title', 'User Management')
@section('page_subtitle', 'Compatibility users view')
@section('sidebar')
<a class="sidebar-link" href="{{ route('dashboard') }}">Dashboard</a>
<a class="sidebar-link active" href="#">Users</a>
@endsection

@section('content')
<section class="panel">
    <div class="panel-head"><h2> All Users</h2></div>
    @if(isset($users) && count($users))
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form method="POST" action="/users/{{ $user->id }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit"> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No users found.</p>
    @endif
</section>
@endsection
