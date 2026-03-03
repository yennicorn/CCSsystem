@extends('layouts.master')

@section('page_title', 'Legacy Reports')
@section('page_subtitle', 'Compatibility reporting view')
@section('sidebar')
<a class="sidebar-link" href="{{ route('dashboard') }}">Dashboard</a>
<a class="sidebar-link active" href="#">Reports</a>
@endsection

@section('content')
<section class="panel">
    <div class="panel-head"><h2> Monthly Registrations</h2></div>
    @if(isset($monthlyUsers) && count($monthlyUsers))
        <div class="table-wrap">
            <table>
                <thead><tr><th>Month</th><th>Total Users</th></tr></thead>
                <tbody>
                @foreach($monthlyUsers as $data)
                    <tr>
                        <td>{{ $data->month }}</td>
                        <td>{{ $data->total }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No monthly registration data available.</p>
    @endif
</section>
@endsection
