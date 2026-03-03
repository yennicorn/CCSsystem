@extends('layouts.master-admin')

@section('page_title', 'School Year Control')
@section('page_subtitle', 'Manage active year and enrollment availability')

@section('content')
<section class="panel schoolyear-panel">
    <div class="panel-head"><h3> School Year Governance</h3></div>
    <div class="sy-main">
        <div>
            <p>Active School Year</p>
            <span class="big-badge">{{ $activeSchoolYear?->name ?? 'No Active Year' }}</span>
        </div>
        <div>
            <p>Enrollment Status</p>
            <span class="big-badge {{ $activeSchoolYear && $activeSchoolYear->enrollment_open ? 'approved' : 'rejected' }}">
                {{ $activeSchoolYear && $activeSchoolYear->enrollment_open ? 'Open' : 'Closed' }}
            </span>
        </div>
        @if($activeSchoolYear)
            <form method="POST" action="{{ route('master.school-years.toggle', $activeSchoolYear) }}">
                @csrf
                <button class="btn btn-secondary" type="submit"> Toggle Open / Closed</button>
            </form>
        @endif
    </div>
    <div class="sy-list">
        @foreach($schoolYears as $sy)
            <form method="POST" action="{{ route('master.school-years.set-active', $sy) }}">
                @csrf
                <button class="btn {{ $sy->is_active ? 'btn-secondary' : '' }}" type="submit"> Set {{ $sy->name }} as Active
                </button>
            </form>
        @endforeach
    </div>
</section>
@endsection
