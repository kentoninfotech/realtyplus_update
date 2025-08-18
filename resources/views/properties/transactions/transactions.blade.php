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


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create property')
            <a href="{{ route('new.transaction') }}" class="btn btn-primary mb-2" style="float: right;">Make Payment</a>
          @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Purpose</th>
                        <th>For (+/-)</th>
                        <th>By</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr @if ($transaction->status == 'completed') style="background-color: azure !important;" @endif>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ Str::headline($transaction->purpose) }}</td>
                            <td>{{ class_basename($transaction->transactionable) }}
                                @if ($transaction->type == 'credit')
                                    <span class="badge badge-success">+</span>
                                @else
                                    <span class="badge badge-danger">-</span>
                                @endif
                            </td>
                            <td>{{ $transaction->payer->name }}</td>
                            <td>{{ $transaction->transaction_date->format('d M, Y') }} </td>
                            <td>{{ $transaction->payment_method }}</td>
                            <td>{{ $transaction->reference_number }}</td>
                            <td>
                                @if ($transaction->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($transaction->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif ($transaction->status == 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @elseif ($transaction->status == 'reversed')
                                    <span class="badge badge-info">Reversed</span>
                                @else
                                    <span class="badge badge-secondary">{{ Str::headline($transaction->status) }}</span>
                                @endif
                            </td>
                            <td>₦{{ number_format($transaction->amount, 0, '.', ',') }}</td>
                            <td>
                              <div class="btn-group">

                                <div class="dropdown">
                                    <button type="button" class="btn btn-secondary btn-xs" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/></svg>
                                    </button>
                                    <div class="dropdown-menu text-center">
                                        @can('view property')
                                            <a class="dropdown-item" href="{{ route('show.transaction', $transaction->id) }}"><i class="fa fa-eye"></i> View</a>
                                        @endcan
                                        @can('edit property')
                                            <a class="dropdown-item" href="{{ route('edit.transaction', $transaction->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                        @endcan
                                        @can('delete property')
                                            <div class="dropdown-divider"></div>
                                            <form class="d-inline" action="{{ route('delete.transaction', $transaction->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this transaction?');">
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
@endsection
