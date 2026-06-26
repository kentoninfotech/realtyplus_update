@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Units</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Units</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-body" style="overflow: auto;">
            <table class="table responsive-table" id="units-table">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Unit</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Rent Price (₦)</th>
                        <th>Sale Price (₦)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>
                                @if($unit->images && $unit->images->count() > 0)
                                    <img src="{{ asset(''. $unit->images->first()->image_path) }}" alt="" width="50" height="50">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="" width="50" height="50">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('show.unit', $unit->id) }}">{{ $unit->unit_number }}</a>
                            </td>
                            <td>
                                <a href="{{ route('show.property', $unit->property->id) }}">{{ $unit->property->name }}</a>
                            </td>
                            <td>{{ $unit->unit_type ?? 'N/A' }}</td>
                            <td>
                                @if($unit->status == 'available')
                                    <span class="badge badge-success">Available</span>
                                @elseif($unit->status == 'rented' || $unit->status == 'leased')
                                    <span class="badge badge-info">Leased</span>
                                @elseif($unit->status == 'sold')
                                    <span class="badge badge-danger">Sold</span>
                                @elseif($unit->status == 'under_maintenance')
                                    <span class="badge badge-warning">Under Maintenance</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $unit->status)) }}</span>
                                @endif
                            </td>
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
                                        <i class="fa fa-dashboard"></i> View
                                    </a>
                                  @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No units found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(function () {
            $("#units-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#units-table_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
