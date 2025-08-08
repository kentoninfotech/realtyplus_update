@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tasks for {{ $property->name }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tasks for {{ $property->name }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create property')
            <button type="button" class="btn btn-primary float-right" id="open-modal-btn">
                <i class="fas fa-plus mr-2"></i>Add New Task
            </button>
          @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Task</th>
                        <th>Description</th>
                        <th>Assignee</th>
                        <th>Due</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr data-task='@json($task)' @if ($task->status == 'in_progress') style="background-color: azure !important;" @endif>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->assignee->name }}</td>
                            <td>{{ optional($task->due_date)->format('d M, Y') ?? 'N/A' }}</td>
                            <td>
                                @if ($task->priority == 'high')
                                    <span class="badge badge-danger">Hign</span>
                                @elseif ($task->priority == 'medium')
                                    <span class="badge badge-success">Medium</span>
                                @elseif ($task->priority == 'low')
                                    <span class="badge badge-secondary">Low</span>
                                @else
                                <span class="badge badge-info">{{ Str::headline($task->priority) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($task->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($task->status == 'in_progress')
                                    <span class="badge badge-primary">In Progress</span>
                                @elseif ($task->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @elseif ($task->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-secondary">{{ Str::headline($task->status) }}</span>
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
                                            <a class="dropdown-item" href="{{-- route('show.task', $task->id) --}}"><i class="fa fa-eye"></i> View</a>
                                        @endcan
                                        @can('edit property')
                                            <button type="button" class="dropdown-item btn btn-link p-0 mr-2 edit-task-btn" data-task-id="{{ $task->id }}"><i class="fa fa-edit"></i> Edit</button>
                                        @endcan
                                        @can('delete property')
                                            <div class="dropdown-divider"></div>
                                            <form class="d-inline" action="{{ route('delete.task', $task->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this task?');">
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


    <!-- Add/Edit Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Create New Task</h5>
                    <button type="button" class="close" id="close-modal-btn" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="task-form" action="{{ route('create.task') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- <input type="hidden" name="taskable_type" value="App\Models\Property"> -->
                        <input type="hidden" name="taskable_id" value="{{ $property->id }}">

                        <div class="form-group">
                            <label for="taskable_type">Task For</label>
                            <select id="taskable_type" name="taskable_type" class="form-control">
                                <option value="App\Models\Property" selected>Property</option>
                                <option value="App\Models\Lease">Lease</option>
                                <option value="App\Models\MaintenanceRequest">Maintenance Request</option>
                                <option value="App\Models\Lead">Lead</option>
                            </select>
                        </div>

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
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="assigned_to_user_id">Assigned To</label>
                                <select id="assigned_to_user_id" name="assigned_to_user_id" class="form-control select2">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control">
                                    @foreach($taskStatus as $status)
                                        <option value="{{ $status }}">{{ Str::headline($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="priority">Priority</label>
                                <select id="priority" name="priority" class="form-control">
                                    @foreach($priorities as $priority)
                                        <option value="{{ $priority }}">{{ Str::headline($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-modal-footer-btn">Close</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taskModal = document.getElementById('taskModal');
        const openModalBtn = document.getElementById('open-modal-btn');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const closeModalFooterBtn = document.getElementById('close-modal-footer-btn');
        const taskForm = document.getElementById('task-form');
        const modalTitle = document.getElementById('modal-title');
        const editTaskBtns = document.querySelectorAll('.edit-task-btn');

        const urlStore = "{{ route('create.task') }}";
        const urlUpdate = (id) => "{{ route('update.task', ['id' => ':id']) }}".replace(':id', id);

        function openModal() {
            taskModal.classList.add('show');
            taskModal.style.display = 'block';
            taskModal.setAttribute('aria-modal', 'true');
            taskModal.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(backdrop);
        }

        function closeModal() {
            taskModal.classList.remove('show');
            taskModal.style.display = 'none';
            taskModal.setAttribute('aria-hidden', 'true');
            taskModal.removeAttribute('aria-modal');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        openModalBtn.addEventListener('click', function() {
            modalTitle.textContent = 'Create New Task';
            taskForm.action = urlStore;
            
            // Remove the PUT method input if it exists
            const putInput = taskForm.querySelector('input[name="_method"][value="PUT"]');
            if (putInput) {
                putInput.remove();
            }

            // Reset the form and set the default taskable type
            taskForm.reset();
            document.getElementById('taskable_type').value = 'App\\Models\\Property';
            
            openModal();
        });

        closeModalBtn.addEventListener('click', closeModal);
        closeModalFooterBtn.addEventListener('click', closeModal);

        editTaskBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const taskRow = this.closest('tr');
                const taskData = JSON.parse(taskRow.dataset.task);

                modalTitle.textContent = 'Edit Task';
                taskForm.action = urlUpdate(taskData.id);

                // Add the PUT method input for updates
                let putInput = taskForm.querySelector('input[name="_method"][value="PUT"]');
                if (!putInput) {
                    putInput = document.createElement('input');
                    putInput.setAttribute('type', 'hidden');
                    putInput.setAttribute('name', '_method');
                    putInput.setAttribute('value', 'PUT');
                    taskForm.appendChild(putInput);
                }

                // Populate form fields
                document.getElementById('taskable_type').value = taskData.taskable_type;
                document.getElementById('title').value = taskData.title;
                document.getElementById('description').value = taskData.description || '';
                document.getElementById('due_date').value = taskData.due_date ? taskData.due_date.slice(0, 10) : '';
                document.getElementById('assigned_to_user_id').value = taskData.assigned_to_user_id || '';
                document.getElementById('status').value = taskData.status;
                document.getElementById('priority').value = taskData.priority;

                openModal();
            });
        });
    });
</script>