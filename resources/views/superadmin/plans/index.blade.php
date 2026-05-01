@extends('superadmin.layout')
@section('title','Plans')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <p class="text-muted mb-0">Manage subscription plans shown on the landing page.</p>
    <a href="{{ route('superadmin.plans.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New plan</a>
</div>
<div class="card p-3">
    <table class="table align-middle">
        <thead><tr><th>Name</th><th>Price</th><th>Cycle</th><th>Trial</th><th>Featured</th><th>Active</th><th></th></tr></thead>
        <tbody>
        @forelse($plans as $p)
            <tr>
                <td><strong>{{ $p->name }}</strong><br><small class="text-muted">{{ $p->slug }}</small></td>
                <td>{{ $p->currency }} {{ number_format($p->price,0) }}</td>
                <td>{{ ucfirst($p->billing_cycle) }}</td>
                <td>{{ $p->trial_days }}d</td>
                <td>{!! $p->is_featured ? '<i class="fas fa-star text-warning"></i>' : '—' !!}</td>
                <td>{!! $p->is_active ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-muted"></i>' !!}</td>
                <td class="text-end">
                    <a href="{{ route('superadmin.plans.edit', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></a>
                    <form method="POST" action="{{ route('superadmin.plans.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this plan?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted">No plans yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $plans->links() }}
</div>
@endsection
