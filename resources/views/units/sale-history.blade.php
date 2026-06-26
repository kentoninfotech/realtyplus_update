@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Unit Sale History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
                        <li class="breadcrumb-item active">Sale History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> Sales for Unit {{ $unit->unit_number }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Property:</strong> {{ $unit->property->name }} <br>
                <strong>Unit:</strong> {{ $unit->unit_number }} ({{ $unit->unit_type }}) <br>
                <strong>Current Status:</strong> <span class="badge badge-{{ $unit->status === 'sold' ? 'danger' : ($unit->status === 'available' ? 'success' : 'secondary') }}">{{ ucfirst($unit->status) }}</span>
            </div>

            @if($sales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Sale Date</th>
                                <th>Buyer</th>
                                <th>Buyer Type</th>
                                <th>Sale Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>
                                        @if($sale->sale_date)
                                            {{ $sale->sale_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $sale->buyer_name }}</strong><br>
                                        <small class="text-muted">{{ $sale->buyer_email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ class_basename($sale->buyer_type) }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">₦{{ number_format($sale->sale_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($sale->status === 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($sale->status === 'draft')
                                            <span class="badge badge-warning">Draft</span>
                                        @elseif($sale->status === 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sale->transaction())
                                            <a href="{{ route('show.transaction', $sale->transaction()->id) }}" class="btn btn-xs btn-primary" title="View receipt">
                                                <i class="fas fa-receipt"></i> Receipt
                                            </a>
                                        @endif
                                        <a href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="popover" 
                                            title="Sale Details" 
                                            data-content="<strong>Notes:</strong><br>{{ $sale->notes ?? 'None' }}">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="row mt-3">
                    <div class="col-md-12">
                        {{ $sales->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    No sales history for this unit yet.
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('show.property', $unit->property->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Property
                </a>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                trigger: 'hover'
            })
        })
    </script>
@endsection
