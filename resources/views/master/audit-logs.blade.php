@extends('layouts.master-admin')

@section('page_title', 'System Activity Logs')
@section('page_subtitle', 'System-sensitive activity records')

@section('content')
<section class="panel">
    <div class="panel-head"><h3> Audit Trail</h3></div>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Timestamp</th>
                <th>Action</th>
                <th>Entity</th>
                <th>Entity ID</th>
                <th>User ID</th>
                <th>IP Address</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->entity_type }}</td>
                    <td>{{ $log->entity_id ?? '-' }}</td>
                    <td>{{ $log->user_id ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No logs found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $logs->links() }}</div>
</section>
@endsection
