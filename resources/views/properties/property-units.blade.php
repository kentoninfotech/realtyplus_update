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
                        <th>Feat</th>
                        <th>Status</th>
                        <th>Rent (₦)</th>
                        <th>Price (₦)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td>
                                <img src="{{ asset('public/ Image_path') }}" alt="" width="50" height="50">
                            </td>
                            <td>{{ $unit->unit_number }}</td>
                            <td>{{ $unit->unit_type ?? 'N/A' }}</td>
                            @if ($unit->bedrooms || $unit->bathrooms)
                              <td>{{ $unit->bedrooms ?? '0' }} Bedroom/ {{ $unit->bathrooms ?? '0' }} Bathroom</td>
                            @else
                              <td>{{ $unit->area_sqm }} SQM</td>
                            @endif
                            <td>{{ $unit->status ?? 'N/A' }}</td>
                            <td>{{ $unit->rent_price ? '₦'. number_format($property->rent_price, 0, '.',',') : 'N/A' }}</td>
                            <td>{{ $unit->sale_price ? '₦'. number_format($property->sale_price, 0, '.',',') : 'N/A' }}</td>
                            <td width="90">



                                <div class="btn-group">
                                  @can('edit property')
                                    <a href="{{ route('edit.unit', $unit->id) }}" class="btn btn-default btn-xs">Edit
                                    </a>
                                  @endcan
                                  @can('view property')
                                    <a href=" {{ route('show.unit', $unit->id) }}"
                                        class="btn btn-primary btn-sm">Dashboard</a>
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
