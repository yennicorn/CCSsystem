@extends('layouts.master-admin')

@section('page_title', 'Official Reports')
@section('page_subtitle', 'Enrollment and demographic summary')

@section('content')
<section class="panel">
    <div class="panel-head"><h2> Official Reports</h2><a class="btn" href="{{ route('master.reports.export.csv') }}"> Export CSV</a></div>

    <div class="grid mb-10">
        <div class="stat"><span class="stat-label">Total Applicants</span><span class="stat-value">{{ $report['total_applicants'] }}</span></div>
        <div class="stat"><span class="stat-label">Approved Students</span><span class="stat-value">{{ $report['total_approved_students'] }}</span></div>
    </div>

    <div class="split">
        <div class="panel panel-no-margin">
            <h3> Enrollment per Grade Level</h3>
            @forelse($report['enrollment_per_grade'] as $grade => $total)
                <p><strong>{{ $grade }}</strong>: {{ $total }}</p>
            @empty
                <p>No grade-level data.</p>
            @endforelse
        </div>
        <div class="panel panel-no-margin">
            <h3> Gender Distribution</h3>
            @forelse($report['gender_distribution'] as $gender => $total)
                <p><strong>{{ $gender }}</strong>: {{ $total }}</p>
            @empty
                <p>No gender data.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
