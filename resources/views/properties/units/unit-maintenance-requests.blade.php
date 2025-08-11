@extends('layouts.template')

@php
    $pagetype = 'Table';
@endphp

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $unit->unit_number }}' Maintenance Requests</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $unit->unit_number }}' Maintenance Requests</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body" style="overflow: auto;">
            @can('create property')
                <button type="button" class="btn btn-primary float-right mb-3" onclick="openCreateMaintenanceRequestModal()">
                    <i class="fas fa-plus mr-2"></i>Add New Request
                </button>
            @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Reporter</th>
                        <th>Assignee</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maintenanceRequests as $request)
                        <tr
                            data-maintenance-request-id="{{ $request->id }}"
                            data-title="{{ $request->title }}"
                            data-description="{{ $request->description }}"
                            data-reported-by-user-id="{{ $request->reporter->id ?? '' }}"
                            data-assigned-to-personnel-id="{{ $request->assignedPersonnel->id ?? '' }}"
                            data-status="{{ $request->status }}"
                            data-priority="{{ $request->priority }}"
                            data-reported-at="{{ $request->reported_at ?? '' }}"
                            data-completed-at="{{ $request->completed_at ?? '' }}"
                            @if ($request->status == 'open') style="background-color: azure !important;" @endif>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ $request->description }}</td>
                            {{-- Corrected column order: Reporter then Assignee --}}
                            <td>{{ $request->reporter->name ?? 'N/A'}}</td>
                            <td>{{ $request->assignedPersonnel->name ?? "N/A" }}</td>
                            <td>{{ $request->reported_at ?? '' }}</td>
                            <td>
                                @if ($request->priority == 'high')
                                    <span class="badge badge-warning">High</span>
                                @elseif ($request->priority == 'medium')
                                    <span class="badge badge-success">Medium</span>
                                @elseif ($request->priority == 'low')
                                    <span class="badge badge-primary">Low</span>
                                @else
                                    <span class="badge badge-info">{{ Str::headline($request->priority) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($request->status == 'pending')
                                    <span class="badge badge-warning float-right">Pending</span>
                                @elseif ($request->status == 'completed')
                                    <span class="badge badge-success float-right">Completed</span>
                                @elseif ($request->status == 'cancelled')
                                    <span class="badge badge-danger float-right">Cancelled</span>
                                @elseif ($request->status == 'open')
                                    <span class="badge badge-primary float-right">Open</span>
                                @elseif ($request->status == 'in_progress')
                                    <span class="badge badge-info float-right">In Progress</span>
                                @else
                                    <span class="badge badge-secondary float-right">{{ Str::headline($request->status) }}</span>
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
                                                <a class="dropdown-item" href="{{-- route('show.request', $request->id) --}}"><i class="fa fa-eye"></i> View</a>
                                            @endcan
                                            @can('edit property')
                                                <button type="button" class="dropdown-item btn btn-link p-0 mr-2" onclick="openEditMaintenanceRequestModal(this)"><i class="fa fa-edit"></i> Edit</button>
                                            @endcan
                                            @can('delete property')
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="dropdown-item text-danger" onclick="openDeleteConfirmModal('{{ route('delete.maintenanceRequest', ['id' => $request->id, 'redir_to' => 'unit']) }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                                                    Delete
                                                </button>
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

    <!-- Add/Edit Maintenance Request Modal -->
    <div class="modal fade" id="maintenance-request-modal" tabindex="-1" role="dialog" aria-labelledby="maintenanceRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="maintenanceRequestModalLabel">Create New Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="maintenance-request-form" method="POST">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <input type="hidden" id="maintenance-request-method" name="_method" value="POST">
                    <div class="modal-body">
                        {{-- The property_id is now passed as part of the URL in the JS, so we'll remove it here --}}
                        <input type="hidden" name="property_id" value="{{ $unit->property->id }}">
                        <input type="hidden" name="property_unit_id" value="{{ $unit->id }}">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="reported_by_user_id">Reported By</label>
                                <select id="reported_by_user_id" name="reported_by_user_id" class="form-control" required>
                                    <option value="">Select a user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reported_at">Reported At</label>
                                <input type="date" class="form-control" id="reported_at" name="reported_at">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="assigned_to_personnel_id">Assigned To</label>
                                <select id="assigned_to_personnel_id" name="assigned_to_personnel_id" class="form-control">
                                    <option value="">Unassigned</option>
                                    @foreach($personnel as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">Select Status</option>
                                    @foreach($requestStatus as $status)
                                        <option value="{{ $status }}">{{ Str::headline($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="priority">Priority</label>
                                <select id="priority" name="priority" class="form-control">
                                    <option value="">Select Priority</option>
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority }}">{{ Str::headline($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="completed_at">Completed At</label>
                            <input type="date" class="form-control" id="completed_at" name="completed_at">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="modal-submit-btn">Save Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this maintenance request? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="delete-form" action="" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@if(request('modal') == 'requests')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout( function () {
                openCreateMaintenanceRequestModal();

                const modal = document.getElementById('maintenance-request-modal');
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
        // --- Modal Instances ---
        const maintenanceRequestModal = document.getElementById('maintenance-request-modal');
        const deleteConfirmModal = document.getElementById('delete-confirm-modal');
        const maintenanceRequestModalInstance = new bootstrap.Modal(maintenanceRequestModal);
        const deleteConfirmModalInstance = new bootstrap.Modal(deleteConfirmModal);

        // --- DOM Elements ---
        const form = document.getElementById('maintenance-request-form');
        const modalTitle = document.getElementById('maintenanceRequestModalLabel');
        const maintenanceRequestMethodInput = document.getElementById('maintenance-request-method');
        const modalSubmitBtn = document.getElementById('modal-submit-btn');

        // --- Blade-generated routes (These are strings in JS) ---
        const createRoute = '{{ route('create.maintenanceRequest', ['id' => $unit->id, 'redir_to' => 'unit']) }}';
        const updateRouteTemplate = '{{ route('update.maintenanceRequest', ['id' => 'MAINTENANCE_REQUEST_ID', 'redir_to' => 'unit']) }}';
        const deleteForm = document.getElementById('delete-form');
        
        /**
         * Opens the modal for creating a new maintenance request.
         */
        window.openCreateMaintenanceRequestModal = function() {
            form.reset();
            modalTitle.textContent = 'Create New Maintenance Request';
            form.action = createRoute;
            maintenanceRequestMethodInput.value = 'POST';
            modalSubmitBtn.textContent = 'Save Request';
            maintenanceRequestModalInstance.show();
        };

        /**
         * Opens the modal for editing an existing maintenance request.
         */
        window.openEditMaintenanceRequestModal = function(button) {
            const row = button.closest('tr');
            
            const maintenanceRequestId = row.dataset.maintenanceRequestId;
            const title = row.dataset.title;
            const description = row.dataset.description;
            const reportedByUserId = row.dataset.reportedByUserId;
            const assignedToPersonnelId = row.dataset.assignedToPersonnelId;
            const status = row.dataset.status;
            const priority = row.dataset.priority;
            const reportedAt = row.dataset.reportedAt;
            const completedAt = row.dataset.completedAt;

            modalTitle.textContent = 'Edit Maintenance Request';
            // Dynamically set the form's action URL with the specific ID
            form.action = updateRouteTemplate.replace('MAINTENANCE_REQUEST_ID', maintenanceRequestId);
            maintenanceRequestMethodInput.value = 'PUT';
            modalSubmitBtn.textContent = 'Update Request';

            // Populate form fields with the retrieved data
            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('reported_by_user_id').value = reportedByUserId;
            document.getElementById('assigned_to_personnel_id').value = assignedToPersonnelId;
            document.getElementById('status').value = status;
            document.getElementById('priority').value = priority;
            document.getElementById('reported_at').value = reportedAt;
            document.getElementById('completed_at').value = completedAt;
            
            maintenanceRequestModalInstance.show();
        };

        /**
         * Opens the delete confirmation modal.
         */
        window.openDeleteConfirmModal = function(deleteUrl) {
            deleteForm.action = deleteUrl;
            deleteConfirmModalInstance.show();
        };
    });
</script>
