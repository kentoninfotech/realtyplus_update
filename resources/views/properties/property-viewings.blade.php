@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $property->name }}' Viewing</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $property->name }}' Viewing</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create property')
            <a href="#" onclick="openCreateModal()" data-toggle="modal" data-target="#viewing-modal" 
                class="btn btn-primary" style="float: right;">+ Add Viewing</a>
          @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Client</th>
                        <th>Agent</th>
                        <th>Scheduled</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewings as $viewing)
                        <tr 
                            data-viewing-id="{{ $viewing->id }}"
                            data-client-name="{{ $viewing->client_name }}"
                            data-client-email="{{ $viewing->client_email }}"
                            data-client-phone="{{ $viewing->client_phone }}"
                            data-scheduled-at="{{ \Carbon\Carbon::parse($viewing->scheduled_at)->format('Y-m-d\TH:i') }}"
                            data-agent-id="{{ $viewing->agent_id }}"
                            data-status="{{ $viewing->status }}"
                            data-notes="{{ $viewing->notes }}" >
                            <td>{{ $viewing->id }}</td>
                            <td>{{ $viewing->client_name }} <br>
                                <span class="text-muted">{{ $viewing->client_email }} - {{ $viewing->client_phone }}</span>
                            </td>
                            <td>{{ $viewing->agent->full_name }} <br>
                                <span class="text-muted">({{ $viewing->agent->phone_number }})</span>
                            </td>
                            <td>{{ $viewing->scheduled_at->format('d M, Y h:m A') }}</td>
                            <td>
                                @if ($viewing->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($viewing->status == 'scheduled')
                                    <span class="badge badge-primary">Scheduled</span>
                                @elseif ($viewing->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @elseif ($viewing->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-secondary">{{ Str::headline($viewing->status) }}</span>
                                @endif
                            </td>
                            <td>
                              <div class="btn-group">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-secondary btn-xs" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/></svg>
                                    </button>
                                    <div class="dropdown-menu text-center">
                                        @can('view property')
                                            <a class="dropdown-item" href="{{-- route('show.viewing', $viewing->id) --}}"><i class="fa fa-eye"></i> View</a>
                                        @endcan
                                        @can('edit property')
                                            <a class="dropdown-item" href="#" onclick="openEditModal(this)" data-toggle="modal" data-target="#viewing-modal"><i class="fa fa-edit"></i> Edit</a>
                                        @endcan
                                        @can('delete property')
                                            <div class="dropdown-divider"></div>
                                            <form class="d-inline" action="{{ route('delete.viewing', ['id' => $viewing->id, 'redir_to' => 'property']) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this viewing?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                                                Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                              </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

<!-- Viewing Modal -->
<div class="modal fade" id="viewing-modal" tabindex="-1" role="dialog" aria-labelledby="viewingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="viewingModalLabel">Schedule New Viewing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="viewing-form" action="{{ route('create.viewing', 'property') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" value="{{ $property->id }}">
                <input type="hidden" id="viewing-method" name="_method" value="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="client_name">Client Name</label>
                        <input type="text" id="client_name" name="client_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="client_email">Client Email</label>
                        <input type="email" id="client_email" name="client_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="client_phone">Client Phone</label>
                        <input type="tel" id="client_phone" name="client_phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="scheduled_at">Scheduled At</label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="agent_id">Agent</label>
                        <select id="agent_id" name="agent_id" class="form-control" required>
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            @foreach($viewingStatus as $status)
                                <option value="{{ $status }}">{{ Str::headline($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="modal-submit-btn" class="btn btn-primary">Save Viewing</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@if(request('modal') == 'viewings')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout( function () {
                openCreateModal();

                const modal = document.getElementById('viewing-modal');
                if (modal){
                    const bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            }, 600)
        });
    </script>
@endif

<script>
     document.addEventListener('DOMContentLoaded', function () {
        // Get DOM elements once on page load
        const viewingModalElement = document.getElementById('viewing-modal');
        const form = document.getElementById('viewing-form');
        const modalTitle = document.getElementById('viewingModalLabel');
        const viewingMethodInput = document.getElementById('viewing-method');
        const modalSubmitBtn = document.getElementById('modal-submit-btn');

        // Initialize Bootstrap modal instance
        const viewingModal = new bootstrap.Modal(viewingModalElement);

        // Function to open the modal for creating a new viewing
        window.openCreateModal = function() {
            form.reset();
            modalTitle.textContent = 'Schedule New Viewing';
            form.action = `{{ route('create.viewing', 'property') }}`;
            viewingMethodInput.value = 'POST';
            modalSubmitBtn.textContent = 'Save Viewing';
            viewingModal.show();
        };

        // Function to open the modal for editing an existing viewing
        window.openEditModal = function(button) {
            const row = button.closest('tr');

            // Get data from data attributes
            const viewingId = row.dataset.viewingId;
            const clientName = row.dataset.clientName;
            const clientEmail = row.dataset.clientEmail;
            const clientPhone = row.dataset.clientPhone;
            const scheduledAt = row.dataset.scheduledAt;
            const agentId = row.dataset.agentId;
            const status = row.dataset.status;
            const notes = row.dataset.notes;

            // Update modal title and form action
            modalTitle.textContent = 'Edit Viewing';
            form.action = `{{ route('update.viewing', ['id' => 'VIEWING_ID', 'redir_to' => 'property']) }}`.replace('VIEWING_ID', viewingId);
            viewingMethodInput.value = 'PUT';
            modalSubmitBtn.textContent = 'Update Viewing';

            // Populate form fields
            document.getElementById('client_name').value = clientName;
            document.getElementById('client_email').value = clientEmail;
            document.getElementById('client_phone').value = clientPhone;
            document.getElementById('scheduled_at').value = scheduledAt;
            document.getElementById('agent_id').value = agentId;
            document.getElementById('status').value = status;
            document.getElementById('notes').value = notes || '';

            viewingModal.show();
        };

    });
</script>

<!-- <script src="{{ asset('plugins/js/property-scripts.js') }}"></script> -->
