@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transactions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Transactions</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        <div class="card">
            <div class="card-heading" style="text-align: center">
               @canany(['create transaction', 'create payment'])
                <a href="#" class="btn btn-primary float-right" style="margin: 10px;" data-toggle="modal"
                    data-target="#transaction"> <i class="fa fa-plus"></i> Add New</a>
               @endcanany


            </div>
            <div class="card-body">
                <table class="table responsive-table" id="products" style="font-size: 0.7em !important;">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Account Head</th>
                            <th>Date</th>
                            <th>Ref. No</th>
                            <th>Detail</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Approved By</th>
                            <th>Entered By</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transact)
                            <tr>
                                <td>{{ $transact->title }} <br> <i
                                        style="color: green;"><small>{{ $transact->project_id != '' ? $transact->project->title : '' }}</small></i>
                                </td>
                                <td>{{ $transact->amount }}</td>
                                <td>{{ $transact->account_head }}</td>
                                <td>{{ $transact->date }}</td>
                                <td>{{ $transact->reference_no }}</td>
                                <td>{{ $transact->detail }}</td>
                                <td>{{ is_numeric($transact->from) && $users->where('id', $transact->from)->first() ? $users->where('id', $transact->from)->first()->name : $transact->from }}
                                </td>
                                <td>{{ is_numeric($transact->to) && $users->where('id', $transact->to)->first() ? $users->where('id', $transact->to)->first()->name : $transact->to }}
                                </td>
                                <td>{{ is_numeric($transact->approved_by) && $users->where('id', $transact->approved_by)->first() ? $users->where('id', $transact->approved_by)->first()->name : $transact->approved_by }}
                                </td>
                                <td>{{ is_numeric($transact->recorded_by) && $users->where('id', $transact->recorded_by)->first() ? $users->where('id', $transact->recorded_by)->first()->name : $transact->recorded_by }}
                                </td>
                                <td>
                                   <div class="btn-group">
                                        @canany(['edit transaction', 'edit payment'])
                                        <button class="btn btn-primary btn-xs" id="ach{{ $transact->id }}"
                                            onclick="transaction({{ $transact->id }})" data-toggle="modal"
                                            data-target="#transaction" data-title="{{ $transact->title }}"
                                            data-amount="{{ $transact->amount }}"
                                            data-account_head="{{ $transact->account_head }}"
                                            data-date="{{ $transact->date }}" data-project="{{ $transact->project_id }}"
                                            data-reference_no="{{ $transact->reference_no }}"
                                            data-detail="{{ $transact->detail }}" data-from="{{ $transact->from }}"
                                            data-to="{{ $transact->to }}" data-approved_by="{{ $transact->approved_by }}"
                                            data-recorded_by="{{ $transact->recorded_by }}">Edit</button>
                                        @endcanany
                                        @canany(['delete transaction', 'delete payment'])
                                        <a href="/delete-trans/{{ $transact->id }}" class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure you want to delete {{ $transact->detail }}\'s Financial Record?')">Delete</a>
                                        @endcanany
                                   </div>
                                    </td>

                            </tr>
                        @endforeach


                    </tbody>
                </table>
                <div style="text-align: right">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

    </div>


    <!-- Button to Open the Modal -->


    <!-- The Modal -->
    <div class="modal" id="transaction">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Transction Record</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <form method="POST" action="{{ route('addtransaction') }}">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" value="0">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">Transaction Date</label>
                                <div class="input-group date" id="end_date_activator" data-target-input="nearest">
                                    <input type="text" name="date" class="form-control datetimepicker-input"
                                        data-target="#end_date_activator" id="date">
                                    <div class="input-group-append" data-target="#end_date_activator"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="e.g. Payment for Pure Water">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="account_head" class="control-label">Account Head</label>
                                <select class="form-control" name="account_head" id="account_head">

                                    @foreach ($accountheads as $account)
                                        <option value="{{ $account->title }}">{{ $account->title }} -
                                            ({{ $account->category }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>





                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="reference_no">Reference</label>
                                <input type="text" name="reference_no" id="reference_no" class="form-control"
                                    placeholder="e.g. Check Number, Transfer re, teller no">
                            </div>


                            <div class="form-group col-md-6">
                                <label for="project_id">Select Project</label>
                                <select class="form-control" name="project_id" id="project_id">
                                    <option value="" selected>Not Applicable</option>
                                    @foreach ($business->projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="detail">More Info</label>
                            <input type="text" name="detail" id="detail" class="form-control"
                                placeholder="e.g. Check Number, Transfer re, teller no">
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="from" class="control-label">From/Sender</label>

                                <select class="form-control select2" name="from" id="from">
                                    <option value="{{ $business->id }}">{{ $business->business_name }}</option>
                                    <option value="Others">Others</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="to" class="control-label">To/Receiver</label>
                                <select class="form-control select2" name="to" id="to">
                                    <option value="{{ $business->id }}">{{ $business->business_name }}</option>
                                    <option value="Workers" selected>Workers</option>
                                    <option value="Others">Others</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="approved_by" class="control-label">Approved By</label>
                                <select class="form-control select2" name="approved_by" id="approved_by">
                                    <option value="{{ $business->business_name }}">{{ $business->business_name }}
                                    </option>
                                    <option value="Workers" selected>Workers</option>
                                    <option value="Others">Others</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="recorded_by" class="control-label">Delivered / Recorded By</label>
                                <select class="form-control select2" name="recorded_by" id="recorded_by">

                                    <option value="Others">Others</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save Transaction') }}
                            </button>
                        </div>


                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection
