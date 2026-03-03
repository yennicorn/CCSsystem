@extends('layouts.admin')

@section('page_title', 'Applications')
@section('page_subtitle', 'Review pending applications and move them to reviewed status')

@section('content')
<section class="panel">
    <div class="panel-head">
        <h3>Application Review Queue</h3>
        <p class="muted">Admin can review pending applications.</p>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Applicant</th>
                <th>Grade Level</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($applications as $app)
                <tr>
                    <td>
                        <div class="applicant-cell">
                            <div class="app-avatar">{{ strtoupper(substr($app->learner_full_name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $app->learner_full_name }}</strong>
                                <p class="muted">Application #{{ $app->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{ $app->grade_level }}</td>
                    <td><span class="badge {{ $app->status }}">{{ strtoupper($app->status) }}</span></td>
                    <td>
                        @if($app->status === 'pending')
                            <form method="POST" action="{{ route('admin.applications.review', $app) }}">
                                @csrf
                                <input type="text" name="remarks" placeholder="Review remarks (optional)">
                                <button class="btn mt-8" type="submit">Mark as Reviewed</button>
                            </form>
                        @else
                            <span class="muted">No action</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No applications found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $applications->links() }}</div>
</section>
@endsection
