@extends('layouts.master-admin')

@section('page_title', 'Enrollment Form')
@section('page_subtitle', 'View, print, and securely edit enrollment application')

@section('content')
<section class="panel print-hide">
    <div class="panel-head">
        <h3>Application #{{ $application->id }}</h3>
        <p class="muted">Status: <span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span></p>
    </div>
    <div class="action-row">
        <a class="btn btn-secondary" href="{{ route('master.monitoring') }}">Back to Monitoring</a>
        <button class="btn" type="button" onclick="window.print()">Print A4</button>
    </div>

    @if(!$canEdit)
        <form method="POST" action="{{ route('master.monitoring.unlock-edit', $application) }}">
            @csrf
            <label>Super Admin Password (required to edit)</label>
            <input type="password" name="password" required>
            <button class="btn mt-8" type="submit">Unlock Edit</button>
        </form>
    @else
        <div class="alert alert-success mt-10">Edit mode unlocked for this form. Save your updates below.</div>
    @endif
</section>

<section class="enrollment-paper-wrap">
    <article class="enrollment-paper">
        <div class="enrollment-paper-head">
            <h3>Basic Education Enrollment Form</h3>
            <p>Cabugbugan Community School | Annex 1</p>
        </div>

        @if($canEdit)
            <form method="POST" action="{{ route('master.monitoring.update', $application) }}">
                @csrf
                @method('PUT')
                @include('enduser.partials.enrollment-form-fields', [
                    'application' => $application,
                    'readonly' => false,
                ])
                <div class="enrollment-actions print-hide">
                    <button class="btn" type="submit">Save Changes</button>
                </div>
            </form>
        @else
            @include('enduser.partials.enrollment-form-fields', [
                'application' => $application,
                'readonly' => true,
            ])
        @endif
    </article>
</section>
@endsection
