@extends('superadmin.layout')
@section('title', $business->business_name)
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card p-3">
            <h5 class="fw-bold">{{ $business->business_name }}</h5>
            <p class="text-muted">{{ $business->address }}</p>
            <hr>
            <p><strong>Owner:</strong> {{ optional($business->user)->name }} ({{ optional($business->user)->email }})</p>
            <p><strong>Status:</strong> <span class="badge badge-status-{{ optional($business->user)->status ?? 'pending' }} px-2 py-1">{{ optional($business->user)->status ?? 'pending' }}</span></p>
            <p><strong>Joined:</strong> {{ optional($business->created_at)->format('M d, Y') }}</p>
            <div class="d-flex gap-2 mt-3">
                <form method="POST" action="{{ route('superadmin.accounts.activate', $business) }}">@csrf<button class="btn btn-sm btn-success">Activate</button></form>
                <form method="POST" action="{{ route('superadmin.accounts.suspend', $business) }}">@csrf<button class="btn btn-sm btn-warning">Suspend</button></form>
                <form method="POST" action="{{ route('superadmin.accounts.destroy', $business) }}" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-3 mb-3">
            <h6 class="fw-bold">Subscriptions</h6>
            <table class="table mb-0"><thead><tr><th>Plan</th><th>Status</th><th>Trial ends</th><th>Ends</th></tr></thead><tbody>
                @forelse($business->subscriptions as $sub)
                    <tr><td>{{ optional($sub->plan)->name ?? '—' }}</td><td>{{ ucfirst($sub->status) }}</td><td>{{ optional($sub->trial_ends_at)->format('M d, Y') }}</td><td>{{ optional($sub->ends_at)->format('M d, Y') }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No subscriptions.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="card p-3">
            <h6 class="fw-bold">Team ({{ $personnel->count() }})</h6>
            <table class="table mb-0"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr></thead><tbody>
                @foreach($personnel as $p)
                    <tr><td>{{ $p->name }}</td><td>{{ $p->email }}</td><td>{{ $p->user_type }}</td><td>{{ $p->status }}</td></tr>
                @endforeach
            </tbody></table>
        </div>
    </div>
</div>
<a href="{{ route('superadmin.accounts.index') }}" class="btn btn-link mt-3"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
