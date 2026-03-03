@extends('layouts.admin')

@section('page_title', 'Enrollment Form')
@section('page_subtitle', 'View, print, and review enrollment application')

@section('content')
<section class="panel print-hide">
    <div class="panel-head">
        <h3>Application #{{ $application->id }}</h3>
        <p class="muted">Status: <span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span></p>
    </div>
    <div class="action-row">
        <a class="btn btn-secondary" href="{{ route('admin.monitoring') }}">Back to Monitoring</a>
        <button class="btn" type="button" onclick="window.print()">Print A4</button>
    </div>
    @if($application->status === 'pending')
        <form method="POST" action="{{ route('admin.applications.review', $application) }}">
            @csrf
            <label>Review Remarks (optional)</label>
            <input type="text" name="remarks" placeholder="Enter remarks">
            <button class="btn mt-8" type="submit">Mark as Reviewed</button>
        </form>
    @endif
</section>

<section class="enrollment-paper-wrap">
    <article class="enrollment-paper">
        <div class="enrollment-paper-head">
            <h3>Basic Education Enrollment Form</h3>
            <p>Cabugbugan Community School | Annex 1</p>
        </div>
        @include('enduser.partials.enrollment-form-fields', [
            'application' => $application,
            'readonly' => true,
        ])
    </article>
</section>
@endsection
