@extends('layouts.enduser')

@section('page_title', 'Enrollment Navigation')
@section('page_subtitle', 'Complete and submit the enrollment form, then track status updates.')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h2><span class="icon-inline"><x-icon name="enrollment" /> Enrollment Overview</span></h2>
    </div>

    @if(!$activeSchoolYear || !$activeSchoolYear->enrollment_open)
        <div class="alert alert-warning">Enrollment is currently closed.</div>
    @else
        <p>Enrollment is open for school year: <strong>{{ $activeSchoolYear->name }}</strong>.</p>
    @endif

    @if($application)
        <div class="enduser-status-strip">
            <div>
                <p class="muted">Current Application Status</p>
                <span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span>
            </div>
            <div>
                <p class="muted">Submitted</p>
                <strong>{{ optional($application->submitted_at)->format('M d, Y h:i A') ?? 'Not available' }}</strong>
            </div>
            <div class="enduser-status-cta">
                <a class="btn btn-secondary" href="{{ route('applications.show', $application) }}">Open Full Timeline</a>
            </div>
        </div>
    @endif
</section>

<section class="panel">
    <div class="panel-head">
        <h3><span class="icon-inline"><x-icon name="document" /> Basic Education Enrollment Form</span></h3>
        @if($application)
            <span class="badge {{ $application->status }}">{{ strtoupper($application->status) }}</span>
        @endif
    </div>

    @if($application)
        <form method="POST" action="{{ route('applications.update', $application) }}" enctype="multipart/form-data" class="js-enduser-enrollment-form" data-readonly="{{ $application->status !== 'pending' ? '1' : '0' }}">
            @csrf
            @method('PUT')
            <div class="enrollment-paper">
                <div class="enrollment-paper-head">
                    <h3>Basic Education Enrollment Form</h3>
                    <p class="muted">This form is not for sale</p>
                </div>
                @include('enduser.partials.enrollment-form-fields', [
                    'application' => $application,
                    'readonly' => $application->status !== 'pending',
                ])
            </div>

            @if($application->status === 'pending')
                <label>Supporting Image (optional)</label>
                <input type="file" name="supporting_image" accept=".jpg,.jpeg,.png">
                <button class="btn" type="submit" style="margin-top:10px;">Submit Updates</button>
            @else
                <p class="muted mt-10">Editing is disabled because your application is already {{ $application->status }}.</p>
            @endif
        </form>
    @elseif($activeSchoolYear && $activeSchoolYear->enrollment_open)
        <form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data" class="js-enduser-enrollment-form" data-readonly="0">
            @csrf
            <div class="enrollment-paper">
                <div class="enrollment-paper-head">
                    <h3>Basic Education Enrollment Form</h3>
                    <p class="muted">This form is not for sale</p>
                </div>
                @include('enduser.partials.enrollment-form-fields', [
                    'application' => null,
                    'readonly' => false,
                ])
            </div>

            <label>Supporting Image (optional)</label>
            <input type="file" name="supporting_image" accept=".jpg,.jpeg,.png">
            <button class="btn" type="submit" style="margin-top:10px;">Submit Application</button>
        </form>
    @else
        <p class="muted">Please wait for the school to open enrollment before submitting a form.</p>
    @endif
</section>

@if($application)
<section class="panel">
    <div class="panel-head">
        <h3><span class="icon-inline"><x-icon name="timeline" /> Status Tracking</span></h3>
    </div>

    @if($application->documents->count())
        <div class="panel panel-no-margin">
            <h4>Uploaded Documents</h4>
            @foreach($application->documents as $doc)
                <p><a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank">{{ $doc->original_name }}</a></p>
            @endforeach
        </div>
    @endif

    <div class="table-wrap mt-10">
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Changed By</th>
            </tr>
            </thead>
            <tbody>
            @forelse($application->statusLogs as $log)
                <tr>
                    <td>{{ optional($log->changed_at)->format('M d, Y h:i A') }}</td>
                    <td><span class="badge {{ $log->status }}">{{ strtoupper($log->status) }}</span></td>
                    <td>{{ $log->remarks ?: '-' }}</td>
                    <td>{{ optional($log->changedBy)->full_name ?? 'System' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No status logs yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endif

<script>
(() => {
    const forms = document.querySelectorAll('.js-enduser-enrollment-form');

    if (!forms.length) {
        return;
    }

    const bindConditionalField = (form, radioName, targetSelector) => {
        const radios = form.querySelectorAll(`input[name="${radioName}"]`);
        const targets = form.querySelectorAll(targetSelector);

        if (!radios.length || !targets.length) {
            return;
        }

        const refresh = () => {
            const checked = form.querySelector(`input[name="${radioName}"]:checked`);
            const enabled = checked && checked.value === '1';

            targets.forEach((target) => {
                target.disabled = !enabled;
                if (!enabled) {
                    if (target.type === 'checkbox' || target.type === 'radio') {
                        target.checked = false;
                    } else {
                        target.value = '';
                    }
                }
            });
        };

        radios.forEach((radio) => {
            radio.addEventListener('change', refresh);
        });

        refresh();
    };

    forms.forEach((form) => {
        if (form.dataset.readonly === '1') {
            return;
        }

        bindConditionalField(form, 'with_lrn', 'input[name="lrn"]');
        bindConditionalField(form, 'has_ip_affiliation', 'input[name="ip_affiliation"]');
        bindConditionalField(form, 'is_4ps_beneficiary', 'input[name="four_ps_household_id"]');
        bindConditionalField(form, 'is_lwd', 'input[name="disability_types[]"]');
    });
})();
</script>
@endsection
