@extends('superadmin.layout')
@section('title','Feedback')
@section('content')
<div class="card p-4" style="max-width:800px;">
    <h5 class="fw-bold">{{ $feedback->subject ?: 'Feedback' }}</h5>
    <p class="text-muted small mb-3">From <strong>{{ $feedback->name }}</strong> &lt;{{ $feedback->email }}&gt; — {{ optional($feedback->created_at)->format('M d, Y H:i') }}</p>
    <div class="bg-light p-3 rounded mb-4">{!! nl2br(e($feedback->message)) !!}</div>
    <form method="POST" action="{{ route('superadmin.feedback.respond', $feedback) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                @foreach(['new','in_review','responded','closed'] as $s)
                    <option value="{{ $s }}" @selected($feedback->status===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Response (internal note)</label>
            <textarea class="form-control" name="admin_response" rows="5">{{ old('admin_response', $feedback->admin_response) }}</textarea>
        </div>
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('superadmin.feedback.index') }}" class="btn btn-link">Back</a>
    </form>
</div>
@endsection
