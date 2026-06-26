@extends('superadmin.layout')
@section('title','Businesses')
@section('content')
<form class="row g-2 mb-3">
    <div class="col-md-5"><input class="form-control" name="q" value="{{ $q }}" placeholder="Search by business name..."></div>
    <div class="col-md-3">
        <select class="form-select" name="status">
            <option value="">All statuses</option>
            @foreach(['pending','active','suspended'] as $s)
                <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
</form>
<div class="card p-3">
    <table class="table align-middle">
        <thead><tr><th>Business</th><th>Owner</th><th>Plan</th><th>Status</th><th>Joined</th><th></th></tr></thead>
        <tbody>
        @forelse($businesses as $b)
            <tr>
                <td><a href="{{ route('superadmin.accounts.show', $b) }}"><strong>{{ $b->business_name }}</strong></a><br><small class="text-muted">{{ $b->address }}</small></td>
                <td>{{ optional($b->user)->name }}<br><small class="text-muted">{{ optional($b->user)->email }}</small></td>
                <td>{{ optional(optional($b->activeSubscription)->plan)->name ?? '—' }}</td>
                <td><span class="badge badge-status-{{ optional($b->user)->status ?? 'pending' }} px-2 py-1">{{ optional($b->user)->status ?? 'pending' }}</span></td>
                <td>{{ optional($b->created_at)->format('M d, Y') }}</td>
                <td class="text-end">
                    <form method="POST" action="{{ route('superadmin.accounts.activate', $b) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success" title="Activate"><i class="fas fa-check"></i></button></form>
                    <form method="POST" action="{{ route('superadmin.accounts.suspend', $b) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-warning" title="Suspend"><i class="fas fa-ban"></i></button></form>
                    <form method="POST" action="{{ route('superadmin.accounts.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Delete this business and ALL its data?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted text-center">No businesses match.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $businesses->links() }}
</div>
@endsection
