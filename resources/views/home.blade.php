@extends('layouts.template')
@php
    $pagetype = 'Dashboard';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Member Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Small boxes (Stat box) -->
            <div class="row">
               
              @can('view client')
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $clients->count() }}</h3>

                            <p>Clients</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-persons"></i>
                        </div>
                     @can('view client')
                        <a href="{{ url('clients') }}" class="small-box-footer">View All Clients <i
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
                            <h3>{{ $projects->count() }}</h3>

                            <p>Projects</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                      @can('view project')
                        <a href="{{ url('projects') }}" class="small-box-footer">More info <i
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
                            <h3>{{ $projects->where('status', 'Completed')->count() }}
                            </h3>

                            <p>Projects Completed</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                      @can('view project')
                        <a href="{{ url('projects') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                      @endcan
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $projects->where('status', 'In Progress')->count() }}</h3>

                            <p>Ongoing Projects</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                      @can('view project')
                        <a href="{{ url('projects') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                      @endcan
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <div id="container" class="row">
                <canvas id="canvas"></canvas>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <style>
        body {
            background: #eee;
        }

        .card-box {
            position: relative;
            color: #fff;
            padding: 20px 10px 40px;
            margin: 20px 0px;
        }

        .card-box:hover {
            text-decoration: none;
            color: #f1f1f1;
        }

        .card-box:hover .icon i {
            font-size: 100px;
            transition: 1s;
            -webkit-transition: 1s;
        }

        .card-box .inner {
            padding: 5px 10px 0 10px;
        }

        .card-box h3 {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 8px 0;
            white-space: nowrap;
            padding: 0;
            text-align: left;
        }

        .card-box p {
            font-size: 15px;
        }

        .card-box .icon {
            position: absolute;
            top: auto;
            bottom: 5px;
            right: 5px;
            z-index: 0;
            font-size: 72px;
            color: rgba(0, 0, 0, 0.15);
        }

        .card-box .card-box-footer {
            position: absolute;
            left: 0px;
            bottom: 0px;
            text-align: center;
            padding: 3px 0;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(0, 0, 0, 0.1);
            width: 100%;
            text-decoration: none;
        }

        .card-box:hover .card-box-footer {
            background: rgba(0, 0, 0, 0.3);
        }

        .bg-blue {
            background-color: #00c0ef !important;
        }

        .bg-green {
            background-color: #00a65a !important;
        }

        .bg-orange {
            background-color: #f39c12 !important;
        }

        .bg-red {
            background-color: #d9534f !important;
        }
    </style>
    @php
        $out = '';
        foreach ($projects->where('status', 'In Progress') as $o) {
            $out .= '"' . $o->title . '",';
        }
        $allPtitles = '[' . substr($out, 0, -1) . ']';
        
        // GET ALL MILESTONES ARRAY
        $setmilestones = '';
        foreach ($projects->where('status', 'In Progress') as $pmiles) {
            if (isset($pmiles->milestones)) {
                $setmilestones .= $pmiles->milestones->count() . ',';
            } else {
                $setmilestones .= '0,';
            }
        }
        $allPMilestoneCount = '[' . substr($setmilestones, 0, -1) . ']';
        
        // GET COMPLETED MILESTONES ARRAY
        $setmilestonesc = '';
        foreach ($projects->where('status', 'In Progress') as $pmiles) {
            if (isset($pmiles->milestones)) {
                $setmilestonesc .= $pmiles->milestones->where('status', 'Completed')->count() . ',';
            } else {
                $setmilestonesc .= '0,';
            }
        }
        $allPMilestoneCCount = '[' . substr($setmilestonesc, 0, -1) . ']';
    @endphp
    <script>
        var barChartData = {
            labels: {!! $allPtitles !!},

            @php
                
            @endphp
            datasets: [{
                    label: "Milestones",
                    backgroundColor: "grey",
                    borderColor: "red",
                    borderWidth: 1,
                    data: {!! $allPMilestoneCount !!}
                },
                {
                    label: "Completed Milestones",
                    backgroundColor: "lightblue",
                    borderColor: "blue",
                    borderWidth: 1,
                    data: {!! $allPMilestoneCCount !!}
                }
            ]
        };

        var chartOptions = {
            responsive: true,
            legend: {
                position: "top"
            },
            title: {
                display: true,
                text: "Ongoing Projects Performance Monitor"
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: chartOptions
            });
        };
    </script>
@endsection
