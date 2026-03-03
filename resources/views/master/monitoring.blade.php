@extends('layouts.master-admin')

@section('page_title', 'Monitoring')
@section('page_subtitle', 'Enrollment application records grouped by grade level')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h3>Filter by Learner Name</h3>
        <p class="muted">Search by full name, first name, middle name, or last name.</p>
    </div>
    <form method="GET" action="{{ route('master.monitoring') }}" class="action-inline">
        <input type="text" name="name" value="{{ $nameFilter ?? '' }}" placeholder="Enter learner name..." style="max-width: 360px;">
        <button class="btn" type="submit">Search</button>
        @if(!empty($nameFilter))
            <a class="btn btn-secondary" href="{{ route('master.monitoring') }}">Clear</a>
        @endif
    </form>
</section>

@foreach($applicationsByGrade as $grade => $items)
    <section class="panel">
        <div class="panel-head">
            <h3>{{ $grade }}</h3>
            <p class="muted">{{ $items->count() }} application(s)</p>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Application #</th>
                    <th>Learner Name</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->learner_full_name }}</td>
                        <td><span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span></td>
                        <td>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? '-' }}</td>
                        <td class="action-row">
                            <a class="btn" href="{{ route('master.monitoring.show', $application) }}">View / Edit / Print</a>
                            @if($application->status === 'reviewed')
                                <form method="POST" action="{{ route('master.applications.decide', $application) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-secondary" type="submit">Approve</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No applications in {{ $grade }}.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endforeach
@endsection
