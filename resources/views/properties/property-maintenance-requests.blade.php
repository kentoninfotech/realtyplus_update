@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $property->name }}' Maintenance Requests</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $property->name }}' Maintenance Requests</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create property')
            <button type="button" class="btn btn-primary float-right" id="open-modal-btn">
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
                        <tr data-request='@json($request)' @if ($request->status == 'open') style="background-color: azure !important;" @endif>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->title }}</td>
                            <td>{{ $request->description }}</td>
                            <td>{{ $request->assignedPersonnel->name ?? "N/A" }}</td>
                            <td>{{ $request->reporter->name ?? 'N/A'}}</td>
                            <td>{{ $request->reported_at ?? '' }}</td>
                            <td>
                                @if ($request->priority == 'hign')
                                    <span class="badge badge-warning">Hign</span>
                                @elseif ($request->priority == 'medium')
                                    <span class="badge badge-success">Medium</span>
                                @elseif ($request->priority == 'low')
                                    <span class="badge badge-primary">Low</span>
                                @else
                                    <span class="badge badge-info">{{ $request->priority }}</span>
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
                                    <span class="badge badge-primary float-right">In Progress</span>
                                @else
                                    <span class="badge badge-info float-right">{{ $request->status }}</span>
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
                                            <button type="button" class="dropdown-item btn btn-link p-0 mr-2 edit-request-btn" data-request-id="{{ $request->id }}"><i class="fa fa-edit"></i> Edit</button>
                                        @endcan
                                        @can('delete property')
                                            <div class="dropdown-divider"></div>
                                            <form class="d-inline" action="{{ route('delete.maintenanceRequest', ['id' => $request->id, 'redir_to' => 'property']) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this request?');">
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

    <!-- Add/Edit Maintenance Request Modal -->
    <div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal-title">Create New Request</h5>
                    <button type="button" class="close" id="close-modal-btn" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="maintenance-form" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-modal-footer-btn">Close</button>
                        <button type="submit" class="btn btn-primary" id="save-request-btn">Save Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const maintenanceModal = document.getElementById('maintenanceModal');
        const openModalBtn = document.getElementById('open-modal-btn');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const closeModalFooterBtn = document.getElementById('close-modal-footer-btn');
        const maintenanceForm = document.getElementById('maintenance-form');
        const modalTitle = document.getElementById('modal-title');
        const saveRequestBtn = document.getElementById('save-request-btn'); 
        const editRequestBtns = document.querySelectorAll('.edit-request-btn');

        const urlStore = "{{ route('create.maintenanceRequest', 'property') }}";
        const urlUpdate = (id) => "{{ route('update.maintenanceRequest', ['redir_to' => 'property', 'id' => ':id']) }}".replace(':id', id);

        function openModal() {
            maintenanceModal.classList.add('show');
            maintenanceModal.style.display = 'block';
            maintenanceModal.setAttribute('aria-modal', 'true');
            maintenanceModal.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(backdrop);
        }

        function closeModal() {
            maintenanceModal.classList.remove('show');
            maintenanceModal.style.display = 'none';
            maintenanceModal.setAttribute('aria-hidden', 'true');
            maintenanceModal.removeAttribute('aria-modal');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        openModalBtn.addEventListener('click', function() {
            modalTitle.textContent = 'Create New Request';
            saveRequestBtn.textContent = 'Create Request'; // Update button text
            maintenanceForm.action = urlStore;
            
            const putInput = maintenanceForm.querySelector('input[name="_method"][value="PUT"]');
            if (putInput) {
                putInput.remove();
            }

            maintenanceForm.reset();
            
            openModal();
        });

        closeModalBtn.addEventListener('click', closeModal);
        closeModalFooterBtn.addEventListener('click', closeModal);

        editRequestBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const requestRow = this.closest('tr');
                const requestData = JSON.parse(requestRow.dataset.request);

                modalTitle.textContent = 'Edit Request';
                saveRequestBtn.textContent = 'Update Request'; 
                maintenanceForm.action = urlUpdate(requestData.id);

                let putInput = maintenanceForm.querySelector('input[name="_method"][value="PUT"]');
                if (!putInput) {
                    putInput = document.createElement('input');
                    putInput.setAttribute('type', 'hidden');
                    putInput.setAttribute('name', '_method');
                    putInput.setAttribute('value', 'PUT');
                    maintenanceForm.appendChild(putInput);
                }

                document.getElementById('title').value = requestData.title;
                document.getElementById('description').value = requestData.description || '';
                // document.getElementById('property_unit_id').value = requestData.property_unit_id || '';
                document.getElementById('reported_by_user_id').value = requestData.reported_by_user_id || '';
                document.getElementById('assigned_to_personnel_id').value = requestData.assigned_to_personnel_id || '';
                document.getElementById('status').value = requestData.status;
                document.getElementById('priority').value = requestData.priority;
                document.getElementById('reported_at').value = requestData.reported_at ? requestData.reported_at.slice(0, 10) : '';
                document.getElementById('completed_at').value = requestData.completed_at ? requestData.completed_at.slice(0, 10) : '';

                openModal();
            });
        });
    });
</script>