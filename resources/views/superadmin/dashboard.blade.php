@extends('superadmin.layout')
@section('title','Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Businesses</div><div class="stat-num">{{ $stats['businesses'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Users</div><div class="stat-num">{{ $stats['users'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">Active subs</div><div class="stat-num">{{ $stats['subscriptions_active'] }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">New feedback</div><div class="stat-num">{{ $stats['feedback'] }}</div></div></div>
</div>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Latest businesses</h6>
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Owner</th><th>Status</th><th>Joined</th></tr></thead>
                <tbody>
                    @forelse($latestBusinesses as $b)
                        <tr>
                            <td><a href="{{ route('superadmin.accounts.show', $b) }}">{{ $b->business_name }}</a></td>
                            <td>{{ optional($b->user)->email }}</td>
                            <td><span class="badge badge-status-{{ optional($b->user)->status ?? 'pending' }} px-2 py-1">{{ optional($b->user)->status ?? 'pending' }}</span></td>
                            <td>{{ optional($b->created_at)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center">No businesses yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Recent feedback</h6>
            @forelse($latestFeedback as $f)
                <div class="border-bottom py-2">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $f->name ?: 'Anonymous' }}</strong>
                        <small class="text-muted">{{ optional($f->created_at)->diffForHumans() }}</small>
                    </div>
                    <div class="text-muted small">{{ Str::limit($f->message, 100) }}</div>
                </div>
            @empty
                <p class="text-muted">No feedback yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
