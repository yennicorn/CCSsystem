@extends('layouts.master-admin')

@section('page_title', 'Super Administrator Dashboard')
@section('page_subtitle', 'Enrollment statistics and chart analytics')

@section('content')
@php
    $gradeTotal = max(1, (int) collect($stats['by_grade'])->sum());
    $genderTotal = max(1, (int) collect($genderStats)->sum());
    $male = (int) ($genderStats['male'] ?? 0);
    $female = (int) ($genderStats['female'] ?? 0);
    $other = (int) ($genderStats['other'] ?? 0);
    $unspecified = (int) ($genderStats['unspecified'] ?? 0);
    $malePct = round(($male / $genderTotal) * 100);
    $femalePct = round(($female / $genderTotal) * 100);
    $otherPct = round(($other / $genderTotal) * 100);
    $unspecifiedPct = max(0, 100 - $malePct - $femalePct - $otherPct);
@endphp

<section class="dashboard-topbar panel">
    <div class="dash-head-left">
        <h2>Analytics Overview</h2>
        <p class="muted">Dashboard view for enrollment statistics and chart insights.</p>
    </div>
    <div class="dash-search">
        <input type="text" placeholder="Search analytics by grade or gender..." disabled>
    </div>
    <div class="dash-head-right">
        <a class="notif notif-link" href="{{ route('master.monitoring') }}" title="Reviewed applications awaiting action">
            <x-icon name="bell" />
            @if(($notificationCount ?? 0) > 0)
                <span class="notif-badge">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
            @endif
        </a>
        <div class="avatar">S</div>
    </div>
</section>

<section class="stats-grid dashboard-stats">
    <article class="stat-hero stat-blue"><span class="icon"><x-icon name="total" /></span><div><h3>{{ $stats['total'] }}</h3><p>Total Enrollees</p></div></article>
    <article class="stat-hero stat-lightblue"><span class="icon"><x-icon name="pending" /></span><div><h3>{{ $stats['pending'] }}</h3><p>Pending Review</p></div></article>
    <article class="stat-hero stat-purple"><span class="icon"><x-icon name="reviewed" /></span><div><h3>{{ $stats['reviewed'] }}</h3><p>Reviewed Applications</p></div></article>
    <article class="stat-hero stat-green"><span class="icon"><x-icon name="approved" /></span><div><h3>{{ $stats['approved'] }}</h3><p>Enrolled Students</p></div></article>
</section>

<section class="split">
    <article class="panel chart-panel">
        <div class="panel-head"><h3>Enrollment Distribution per Grade Level</h3></div>
        @forelse($stats['by_grade'] as $grade => $count)
            @php $pct = round(($count / $gradeTotal) * 100); @endphp
            <div class="bar-row">
                <div class="bar-label">{{ $grade }}</div>
                <div class="bar-track"><div class="bar-fill" style="width: {{ $pct }}%;"></div></div>
                <div class="bar-value">{{ $count }}</div>
            </div>
        @empty
            <p class="muted">No enrollment data yet.</p>
        @endforelse
    </article>

    <article class="panel chart-panel">
        <div class="panel-head"><h3>Gender Distribution</h3></div>
        <div class="pie-wrap">
            <div class="pie-chart" style="background: conic-gradient(#2477b8 0 {{ $malePct }}%, #5f8ee8 {{ $malePct }}% {{ $malePct + $femalePct }}%, #67a0d8 {{ $malePct + $femalePct }}% {{ $malePct + $femalePct + $otherPct }}%, #d6e6f7 {{ $malePct + $femalePct + $otherPct }}% 100%);"></div>
            <div class="pie-legend">
                <p><span class="dot d1"></span> Male: {{ $male }}</p>
                <p><span class="dot d2"></span> Female: {{ $female }}</p>
                <p><span class="dot d3"></span> Other: {{ $other }}</p>
                <p><span class="dot d4"></span> Unspecified: {{ $unspecified }}</p>
            </div>
        </div>
    </article>
</section>
@endsection
