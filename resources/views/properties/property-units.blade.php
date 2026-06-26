@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $property->name }} Units</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $property->name }} Units</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">
        <div class="card-body" style="overflow: auto;">
            @can('create property')
                <a href="{{ route('new.unit', $property->id) }}" class="btn btn-primary m-2" style="float: right;">Add New</a>
            @endcan
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Tenant/Buyer</th>
                        <th>Feat</th>
                        <th>Status</th>
                        <th>Rent (₦)</th>
                        <th>Price (₦)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        @php
                            // Get active lease if unit is leased
                            $activeLease = $unit->leases()->where('status', 'active')->first();
                            $tenant = $activeLease ? $activeLease->tenant : null;
                            
                            // Check if unit is sold by looking at transactions
                            $saleTransaction = $unit->property->transactions()
                                ->where('purpose', 'sale')
                                ->where('status', 'completed')
                                ->latest()
                                ->first();
                            $buyer = $saleTransaction && $saleTransaction->payer ? $saleTransaction->payer->full_name ?? 'N/A' : null;
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ asset(' Image_path') }}" alt="" width="50" height="50">
                            </td>
                            <td>{{ $unit->unit_number }}</td>
                            <td>{{ $unit->unit_type ?? 'N/A' }}</td>
                            <td>
                                @if($tenant)
                                    <span class="badge badge-info">Tenant</span><br>
                                    <strong>{{ $tenant->full_name ?? 'N/A' }}</strong>
                                @elseif($buyer)
                                    <span class="badge badge-success">Buyer</span><br>
                                    <strong>{{ $buyer }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @if ($unit->bedrooms || $unit->bathrooms)
                              <td>{{ $unit->bedrooms ?? '0' }} Bedroom/ {{ $unit->bathrooms ?? '0' }} Bathroom</td>
                            @else
                              <td>{{ $unit->area_sqm }} SQM</td>
                            @endif
                            <td>{{ $unit->status ?? 'N/A' }}</td>
                            <td>{{ $unit->rent_price ? '₦'. number_format($unit->rent_price, 0, '.',',') : 'N/A' }}</td>
                            <td>{{ $unit->sale_price ? '₦'. number_format($unit->sale_price, 0, '.',',') : 'N/A' }}</td>
                            <td width="150">
                                <div class="btn-group btn-group-sm" role="group">
                                  @if ($unit->status == 'available')
                                    <a href="{{ route('unit.lease.form', $unit->id) }}" class="btn btn-primary" title="Lease/Rent this unit">
                                        <i class="fas fa-home"></i> Buy
                                    </a>
                                    <a href="{{ route('unit.sale.form', $unit->id) }}" class="btn btn-success" title="Sell this unit">
                                        <i class="fas fa-cash-register"></i> Sell
                                    </a>
                                  @endif
                                  @can('edit property')
                                    <a href="{{ route('edit.unit', $unit->id) }}" class="btn btn-info" title="Edit Unit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                  @endcan
                                  @can('view property')
                                    <a href="{{ route('show.unit', $unit->id) }}"
                                        class="btn btn-secondary" title="View Dashboard">
                                        <i class="fa fa-dashboard"></i> Dashboard
                                    </a>
                                  @endcan
                                </div>
                            </td>

                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection
