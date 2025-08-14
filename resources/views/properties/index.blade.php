@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Properties</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Properties</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- SMALL DASHBOARD CARD/BOX -->
    <div class="container-fluid">
        <div class="row">
            @can('view client')
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $tenants->count() }}</h3>

                        <p>Tenants</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-persons"></i>
                    </div>
                    @can('view property')
                    <a href="{{ route('tenants') }}" class="small-box-footer">View All tenants <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            @endcan

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $properties->count() }}</h3>

                        <p>Properties</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    @can('view property')
                    <a href="{{ route('properties') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan 
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $units->count() }}
                        </h3>

                        <p>Units</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    @can('view project')
                    <a href="{{ url('properties') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ '0' }}</h3>

                        <p>PLACEHOLDER</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    @can('view project')
                    <a href="{{ url('alink') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            <!-- ./col -->
        </div> <!-- End Of /row -->
        <div class="row">
            @can('view client')
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $tenants->count() }}</h3>

                        <p>Tenants</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-persons"></i>
                    </div>
                    @can('view property')
                    <a href="{{ route('tenants') }}" class="small-box-footer">View All tenants <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            @endcan

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $properties->count() }}</h3>

                        <p>Properties</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    @can('view property')
                    <a href="{{ route('properties') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan 
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $properties->where('status', 'available')->count() }}
                        </h3>

                        <p>Available Property</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    @can('view project')
                    <a href="{{ url('properties') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ '0' }}</h3>

                        <p>PLACEHOLDER</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    @can('view project')
                    <a href="{{ url('alink') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            <!-- ./col -->
        </div>
    </div> <!-- END CARD/BOX -->

    <div class="card">
        <div class="card-body" style="overflow: auto;">
            @can('create property')
                <a href="{{ route('new.property') }}" class="btn btn-primary m-2" style="float: right;">Add New</a>
            @endcan
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Price (₦)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                        <tr>
                            <td>
                                <img src="{{ asset('public/ Image_path') }}" alt="" width="50" height="50">
                            </td>
                            <td>{{ $property->name }}</td>
                            <td>{{ $property->propertyType->name ?? '' }}</td>
                            <td>{{ $property->address ?? '' }}</td>
                            <td>{{ $property->owner->full_name ?? '' }}</td>
                            <td>{{ $property->sale_price ? '₦'. number_format($property->sale_price, 0, '.',',') : '' }}</td>
                            <td width="90">



                                <div class="btn-group">
                                  @can('edit property')
                                    <a href="{{ route('edit.property', $property->id) }}" class="btn btn-default btn-xs">Edit
                                    </a>
                                  @endcan
                                  @can('view property')
                                    <a href=" {{ route('show.property', $property->id) }}"
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
