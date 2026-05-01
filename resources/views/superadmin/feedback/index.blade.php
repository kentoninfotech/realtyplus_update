@extends('superadmin.layout')
@section('title','Feedback')
@section('content')
<form class="d-flex gap-2 mb-3">
    <select class="form-select" name="status" style="max-width:200px;" onchange="this.form.submit()">
        <option value="">All statuses</option>
        @foreach(['new','in_review','responded','closed'] as $s)
            <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</form>
<div class="card p-3">
    <table class="table align-middle">
        <thead><tr><th>From</th><th>Subject</th><th>Category</th><th>Status</th><th>Received</th><th></th></tr></thead>
        <tbody>
        @forelse($feedback as $f)
            <tr>
                <td>{{ $f->name }}<br><small class="text-muted">{{ $f->email }}</small></td>
                <td>{{ $f->subject ?: Str::limit($f->message, 60) }}</td>
                <td>{{ ucfirst($f->category) }}</td>
                <td><span class="badge badge-status-{{ $f->status }} px-2 py-1">{{ $f->status }}</span></td>
                <td>{{ optional($f->created_at)->diffForHumans() }}</td>
                <td><a href="{{ route('superadmin.feedback.show', $f) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted text-center">No feedback.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $feedback->links() }}
</div>
@endsection
