@extends('layouts.master-admin')

@section('page_title', 'Backup')
@section('page_subtitle', 'Database backup and recent system activity')

@section('content')
<section class="split">
    <article class="panel">
        <div class="panel-head"><h3> System Activity Timeline</h3></div>
        <div class="timeline">
            @forelse($recentAuditLogs as $log)
                <div class="timeline-item">
                    <div class="timeline-dot"> </div>
                    <div>
                        <p><strong>{{ ucwords(str_replace('_', ' ', $log->action)) }}</strong></p>
                        <p class="muted">{{ $log->entity_type }} @if($log->entity_id)#{{ $log->entity_id }}@endif | {{ $log->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @empty
                <p class="muted">No audit activity yet.</p>
            @endforelse
        </div>
    </article>

    <article class="panel backup-card">
        <div class="panel-head"><h3> Database Backup</h3></div>
        <p class="muted">Generate a local backup file for records recovery and safekeeping.</p>
        <form method="POST" action="{{ route('master.backup.database') }}">
            @csrf
            <button class="btn btn-backup" type="submit"> Generate Backup</button>
        </form>
    </article>
</section>
@endsection
