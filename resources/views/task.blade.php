@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Task</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Task</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-widget">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-header" style="padding: 10px;">
            <h1>{{ $task->subject }}</h1>

            @isset($task->milestone_id)
                <h5><i>As part of Milestone: </i>{{ $task->milestone->title }}</h5>
            @endisset
            @isset($task->project_id)
                <h5><i>Project Name: </i>{{ $task->project->title }}, <i>Client: </i>{{ $task->project->client->name }}</h5>
            @endisset
            <div class="row">
                <div class="col-md-2">
                    Status: <span class="badge badge-pill badge-primary">{{ $task->status }}</span>
                </div>



                <div class="col-md-2">
                  @canany(['edit task', 'edit project'])
                    <form action="{{ route('change_task_status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <select name="change_status" id="change_status" class="form-control" onchange="this.form.submit()">
                            <option value="" selected>Change Status</option>
                            <option value="Completed">Completed</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Paused">Paused</option>
                            <option value="Terminated">Terminated</option>
                        </select>
                    </form>
                  @endcanany
                </div>
            </div>


            <hr>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    {!! $task->details !!}
                </div>
                <div class="col-md-6">


                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fa fa-reports"></i></span>

                        <div class="info-box-content">

                            <div class="progress">
                                <div class="progress-bar progress-bar-striped" style="width: 70%"></div>
                            </div>
                            <span class="progress-description">
                                <b>Expected Duration:</b> From: {{ $task->start_date }} To: {{ $task->end_date }}
                                <hr>
                                <b>Staff-in-Charge:</b> {{ $task->assignedTo->name }}

                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>


                    <div class="list-group">
                     @canany(['create milestone_report', 'create project', 'create task', 'edit project', 'edit task'])
                        <a href="{{ url('new-task-report/' . $task->id) }}"
                            class="list-group-item list-group-item-action active">Tasks Reports <span
                                class="btn btn-default" style="float: right;">Add New</span></a>
                     @else
                        <a href="#" class="list-group-item list-group-item-action active">Tasks Reports</a>
                     @endcanany
                        @foreach ($task->reports as $tr)
                            <div class="list-group-item list-group-item-action"><a
                                    href="{{ url('task-report/' . $tr->id) }}">{{ $tr->subject }}</a>
                                <div class="btn-group" style="float: right;">
                                    <a href="#" class="btn btn-xs btn-primary">View</a>
                                    <a href="#" class="btn btn-xs btn-success">Edit</a>
                                    <a href="#" class="btn btn-xs btn-danger">Delete</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class=" card card-primary card-outline" style="padding: 10px !important;">
                <div class="widget-heading">Add Workers</div>
                <hr>
                <form action="{{ route('addWorkers') }}" method="POST" id="addWorkers">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <div style="float: right;">
                        <label>Date Work Done:</label>
                        <div class="input-group date" id="start_date_activator" data-target-input="nearest">
                            <input type="text" name="work_date" class="form-control datetimepicker-input"
                                data-target="#start_date_activator">
                            <div class="input-group-append" data-target="#start_date_activator"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>


                    <table class="table" id="workerslist">
                        <thead>
                            <tr class="spechead">
                                <th class="form-group" style="width: 60% !important">Staff/Personnel</th>
                                <th class="form-group">Amount Paid</th>
                                <th class="form-group">.</th>
                            </tr>
                        </thead>
                        <tbody id="staff_list">

                            <tr id="100">
                                <td class="form-group">
                                    <select class="form-control" name="worker[]" required>
                                        @foreach ($staff as $sta)
                                            <option value="{{ $sta->id }}">
                                                {{ $sta->name }} -
                                                <small>{{ $sta->phone_number }}</small> - <i>({{ $sta->category }})</i>
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="number" name="amountpaid[]" class="form-control" step="0.01" required>
                                </td>

                                <td class="form-group">
                                    <a href="#staff_list" class="btn btn-sm btn-danger removesitem"
                                        id="res100">Remove<i class="lnr lnr-remove"></i></a>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <a class="btn btn-sm btn-success adds_item" href="#addWorkers" id="100">
                        Add Worker/Personnel
                        <i class="lnr lnr-add"></i>
                    </a>

                    <div class="form-group float-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline" style="padding: 10px !important;">
                <div class="widget-heading">Add Materials Used</div>
                <hr>
                <form action="{{ route('addMaterialsUsed') }}" method="POST" id="addMaterialsUsed">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <div style="float: right;">
                        <label>Material Checkout Date:</label>
                        <div class="input-group date" id="end_date_activator" data-target-input="nearest">
                            <input type="text" name="dated" class="form-control datetimepicker-input"
                                data-target="#end_date_activator" required>
                            <div class="input-group-append" data-target="#end_date_activator"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <table class="table" id="materiallist">
                        <thead>
                            <tr class="spechead">
                                <th class="form-group" style="width:60% !important">Material /Unit</th>
                                <th class="form-group">Quantity</th>
                                <th class="form-group">.</th>
                            </tr>
                        </thead>
                        <tbody id="item_list">

                            <tr id="1">
                                <td class="form-group">
                                    <select class="form-control" name="materialname[]" required>
                                        @foreach ($materials as $sma)
                                            <option value="{{ $sma->id }}">
                                                {{ $sma->name }} -
                                                <small>{{ $sma->measurement_unit }}</small>
                                            </option>
                                        @endforeach

                                    </select>
                                </td>
                                <td class="form-group">
                                    <input type="number" name="quantityused[]" class="form-control" step="0.01"
                                        required>
                                </td>

                                <td class="form-group">
                                    <a href="#item_list" class="btn btn-sm btn-danger removeitem" id="re1">Remove<i
                                            class="lnr lnr-remove"></i></a>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <a class="btn btn-sm btn-success add_item" href="#addMaterialsUsed" id="1">
                        Add Material
                        <i class="lnr lnr-add"></i>
                    </a>

                    <div class="row">

                        <div class="form-group col-md-4">

                            <label for="checkout_by" class="control-label">Checked Out By</label>
                            <select class="form-control" name="checkout_by" id="checkout_by">
                                <!--{{-- @foreach ($business->personnel as $usr) --}} -->
                                @foreach ($staff as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="approved_by">Approved By</label>
                            <select class="form-control" name="approved_by" id="approved_by">
                             <!--{{-- @foreach ($business->personnel as $usr) --}} -->
                                @foreach ($staff as $usr)
                                    <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Confirm and Save</label>
                            <button type="submit" class="btn btn-primary form-control">Save</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 card">
            <div class="card-body">
                <h4 class="card-title">WORKERS AND PAYMENTS</h4>
                <hr>
                <table class="table responsive-table">
                    <thead>
                        <tr style="color: ">
                            <th>Name</th>
                            <th>Job Date</th>

                            <th>Amount Paid(<s>N</s>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->workers as $twkrs)
                            <tr>
                                <td>{{ $twkrs->worker->name }}</td>
                                <td>{{ $twkrs->work_date }}</td>
                                <td>{{ $twkrs->amount_paid }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6 card">
            <div class="card-body">
                <h4 class="card-title">MATERIALS USED</h4>
                <hr>
                <table class="table responsive-table">
                    <thead>
                        <tr style="color: ">
                            <th>Item Name</th>
                            <th>Date Taken</th>
                            <th>Checked out By</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->materialsUsed as $tmt)
                            <tr>
                                <td>{{ $tmt->material->name }}</td>
                                <td>{{ $tmt->dated }}</td>
                                <td>{{ $tmt->checkoutby->name }}</td>
                                <td>{{ $tmt->quantity }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
