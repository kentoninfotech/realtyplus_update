@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Data Constraint Violation</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger mb-4">
                        <strong>⚠️ Error:</strong> Your request could not be completed due to a data constraint violation.
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle text-info"></i> What does this mean?</h5>
                        <p class="text-muted">
                            The system detected an issue with the data you submitted. This typically occurs when:
                        </p>
                        <ul class="text-muted">
                            <li><strong>Invalid Reference:</strong> You selected an item that no longer exists in the system (e.g., a deleted user or owner)</li>
                            <li><strong>Missing Required Data:</strong> A mandatory field is empty or invalid</li>
                            <li><strong>Duplicate Entry:</strong> You're trying to create something that already exists</li>
                            <li><strong>Database Inconsistency:</strong> The system has an internal data mismatch</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-lightbulb text-warning"></i> How to fix this</h5>
                        <ol class="text-muted">
                            <li><strong>Check your selections:</strong> Make sure all selected items (owners, agents, properties) still exist</li>
                            <li><strong>Fill required fields:</strong> Ensure all required fields have valid values</li>
                            <li><strong>Verify your data:</strong> Double-check that the information you entered is correct</li>
                            <li><strong>Try again:</strong> Reload the form and resubmit your request</li>
                        </ol>
                    </div>

                    <div class="mb-4 p-3 bg-light border border-info rounded">
                        <h6 class="mb-2 text-info"><i class="fas fa-tools"></i> Technical Details</h6>
                        <small class="text-muted d-block">
                            <strong>Error Type:</strong> Integrity Constraint Violation<br>
                            <strong>Timestamp:</strong> {{ now()->format('Y-m-d H:i:s') }}<br>
                            @if(config('app.debug'))
                                <strong>Message:</strong> {{ $exception->getMessage() }}<br>
                                <strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}
                            @endif
                        </small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="javascript:history.back()" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Go to Home
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">
                        If this error persists, it may indicate a system issue. Try the following:
                    </p>
                    <ul class="text-muted">
                        <li>Refresh the page and try again</li>
                        <li>Clear your browser cache</li>
                        <li>Contact system administrator if the issue continues</li>
                        <li>Check if the item you're referencing has been recently deleted</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
