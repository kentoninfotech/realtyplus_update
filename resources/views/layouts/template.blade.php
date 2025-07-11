<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Realty Plus | Dashboard</title>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->

    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <style>
        .card,
        .card-body,
        table {
            width: 100% !important;
            overflow: auto !important;
        }

        .m-0{
            font-size: 1.5em !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/realtyplus_logo.png') }}" alt="RealtyPlus"
                height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ '/home' }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ (isset($login_user->personnel->picture) && $login_user->personnel->picture !== null) ? asset('public/personnel/pictures/' .$login_user->personnel->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($login_user->name) }}" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        {{ auth()->user()->name }}
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>

                        <a href="{{ url('tasks') }}" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true"
                        href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ '/home' }}" class="brand-link">
                <img src="{{ asset('dist/img/realtyplus_logo.png') }}" alt="RealtyPlus"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Realty Plus</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ (isset($login_user->personnel->picture) && $login_user->personnel->picture !== null) ? asset('public/personnel/pictures/' .$login_user->personnel->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($login_user->name) }}" class="img-circle elevation-2"
                            alt="User Image"> <span style="color: white">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">

                        </a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-dashboard"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                
                 @canany(['view client', 'create client', 'edit client', 'delete client'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Clients
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('clients') }}" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Clients</p>
                                    </a>
                                </li>
                             @can('view owner')
                                <li class="nav-item">
                                    <a href="{{ route('owners') }}" class="nav-link">
                                        <i class="fas fa-user-tie nav-icon"></i>
                                        <p>Owners</p>
                                    </a>
                                </li>
                             @endcan
                             @can('view tenant')
                                <li class="nav-item">
                                    <a href="{{-- route('tenants') --}}" class="nav-link">
                                        <i class="fas fa-user-tie nav-icon"></i>
                                        <p>Tenants</p>
                                    </a>
                                </li>
                             @endcan
                             @can('create client')
                                <li class="nav-item">
                                    <a href="{{ url('new-client') }}" class="nav-link">
                                        <i class="fa fa-plus-square nav-icon"></i>
                                        <p>New Client</p>
                                    </a>
                                </li>
                             @endcan

                            </ul>
                        </li>
                 @endcanany

                 @canany(['view project', 'create project', 'edit project', 'delete project'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p>
                                    Projects
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('projects') }}" class="nav-link">
                                        <i class="fas fa-briefcase nav-icon"></i>
                                        <p>View Projects</p>
                                    </a>
                                </li>
                            @can('create project')
                                <li class="nav-item">
                                    <a href="{{ url('addproject') }}" class="nav-link">
                                        <i class="fas fa-building nav-icon"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                            @endcan

                            </ul>
                        </li>
                  @endcanany

                  @canany(['view task', 'create task', 'edit task', 'delete task'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>
                                    Tasks
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('tasks') }}" class="nav-link">
                                        <i class="fas fa-tasks nav-icon"></i>
                                        <p>View Tasks</p>
                                    </a>
                                </li>
                             @can('create task')
                                <li class="nav-item">
                                    <a href="{{ url('new-task') }}" class="nav-link">
                                        <i class="fa fa-plus-square nav-icon"></i>
                                        <p>New Task</p>
                                    </a>
                                </li>
                             @endcan

                            </ul>
                        </li>
                  @endcanany

                  @canany(['view material_checkout', 'create material_checkout', 'edit material_checkout', 'delete material_checkout',
                            'view material_damage', 'create material_damage', 'edit material_damage', 'delete material_damage',
                            'view material_supply', 'create material_supply', 'edit material_supply', 'delete material_supply',
                            'view material_stock', 'create material_stock', 'edit material_stock', 'delete material_stock'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cubes"></i>
                                <p>
                                    Building Materials
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                              @canany(['view material', 'view material_stock'])
                                <li class="nav-item">
                                    <a href="{{ url('materials') }}" class="nav-link">
                                        <i class="fa fa-cube nav-icon"></i>
                                        <p>Manage Materials</p>
                                    </a>
                                </li>
                              @endcanany
                              @can('view material_checkout')
                                <li class="nav-item">
                                    <a href="{{ url('mcheckouts') }}" class="nav-link">
                                        <i class="fas fa-cart-arrow-down nav-icon"></i>
                                        <p>Material Checkout</p>
                                    </a>
                                </li>
                               @endcan
                               @can('view material_supply')
                                <li class="nav-item">
                                    <a href="{{ url('suppliers') }}" class="nav-link">
                                        <i class="fas fa-cubes nav-icon"></i>
                                        <p>Suppliers</p>
                                    </a>
                                </li>
                               @endcan
                               @canany(['view material', 'view material_stock'])
                                <li class="nav-item">
                                    <a href="{{ url('supplies') }}" class="nav-link">
                                        <i class="fa fa-cart-plus nav-icon"></i>
                                        <p>Material Supplies</p>
                                    </a>
                                </li>
                               @endcanany


                            </ul>
                        </li>
                 @endcanany

                 @canany(['view accounthead', 'create accounthead', 'edit accounthead', 'delete accounthead', 
                         'view transaction', 'create transaction', 'edit transaction', 'delete transaction'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-credit-card"></i>
                                <p>
                                    Finance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                              @can('view transaction')
                                <li class="nav-item">
                                    <a href="{{ url('transactions') }}" class="nav-link">
                                        <i class="far fa-credit-card nav-icon"></i>
                                        <p>Manage Transactions</p>
                                    </a>
                                </li>
                              @endcan
                              @can('view accounthead')
                                <li class="nav-item">
                                    <a href="{{ url('account-heads') }}" class="nav-link">
                                        <i class="fas fa-money-check nav-icon"></i>
                                        <p>Manage Account Heads</p>
                                    </a>
                                </li>
                              @endcan

                            </ul>
                        </li>
                 @endcanany


                 @canany(['view user', 'create user', 'edit user', 'delete user'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Personnel
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                              @can('create user')
                                <li class="nav-item">
                                    <a href="{{ route('new.personnel') }}" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>New Personnel</p>
                                    </a>
                                </li>
                              @endcan
                                <li class="nav-item">
                                    <a href="{{ route('personnel') }}" class="nav-link">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>All Personnel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('staffs') }}" class="nav-link">
                                        <i class="fas fa-user-tie nav-icon"></i>
                                        <p>Staffs</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('workers') }}" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Workers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{-- route('agents') --}}" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Agents</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('contractors') }}" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Contractors</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                @endcanany

                @can('manage settings')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('settings') }}" class="nav-link" data-toggle="modal"
                                        data-target="#settings">
                                        <i class="fas fa-cog nav-icon"></i>
                                        <p>System Settings</p>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.role') }}" class="nav-link">
                                        <i class="fas fa-user-lock nav-icon"></i>
                                        <p>Manage Role & Perm..</p>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('categories') }}" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>



                            </ul>
                        </li>
                @endcan

                        <li class="nav-item">
                            <a href="{{ url('logout') }}" class="nav-link">
                                <i class="fa fa-sign-out nav-icon text-info"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">


 
                <!--------FEEDBACK RESPONSE -->
                    @if (Session::get('message'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                            <i class="fa fa-check-circle"></i> {!! Session::get('message') !!}
                        </div>
                    @endif
                    @if (Session::get('error'))
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                            <i class="fa fa-check-circle"></i> {!! Session::get('error') !!}
                        </div>
                    @endif

                    <!-----------------------------START YIELD PAGE CONTENT -->
                    @yield('content')
                    <!----------------------------END YIELD PAGE CONTENT -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="">Realty Plus</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.2.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- The Settings Modal -->
    <div class="modal" id="settings">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Application Settings</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-check">
                        <input type="checkbox" name="create_new_ministry" id="create_new_ministry"
                            class="form-check-input">
                        <label class="form-check-label"><small style="color: {{ Auth::user()->businesses->primary_color }}"><i>Click Here to
                                    Create New Business</i></small></label>
                    </div>

                    <!-- {{--
                     
                    <form method="POST" action="{{ route('settings') }}" id="settingsform"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ Auth::user()->businesses->id }}">

                        <input type="hidden" name="oldlogo" value="{{ Auth::user()->businesses->logo }}">

                        <input type="hidden" name="oldbackground" value="{{ Auth::user()->businesses->background }}">

                        <input type="hidden" name="newministry" id="newministry" value="">

                        <div class="form-group">
                            <label for="ministrygroup_id" class="control-label ">Ministry Group/Headquarter</label>
                            <select class="form-control" name="ministrygroup_id" id="ministrygroup_id">
                                <option value="{{ $businesses->businessgroup_id }}" selected>
                                     $businessgroups->where('id', $businesses->businessgroup_id)->first()->businessgroup_name 
                                </option>
                                @foreach ($businessgroups as $mg)
                                    <option value="{{ $mg->id }}">{{ $mg->businessgroup_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ministry_name">Ministry / Church Name</label>
                            <input type="text" name="ministry_name" id="ministry_name" class="form-control"
                                value="{{ Auth::user()->businesses->business_name }}">
                        </div>

                        <div class="form-group">
                            <label for="motto">Motto</label>
                            <input type="text" name="motto" id="motto" class="form-control"
                                value="{{ Auth::user()->businesses->motto }}">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control"
                                value="{{ Auth::user()->businesses->address }}">
                        </div>




                        <div class="form-group">
                            <label for="logo">Upload Logo Image</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="background">Upload Background Image</label>
                            <input type="file" name="background" id="background" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="color">Choose System Colour</label>
                            <input type="color" name="color" id="color" class="form-control"
                                value="{{ Auth::user()->businesses->color }}">
                        </div>

                        <div class="form-group">
                            <label for="user_id" class="control-label ">Admin User</label>
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="{{ Auth::user()->businesses->user_id }}" selected>{{ Auth::user()->businesses->user->name }}
                                </option>
                                @foreach ($staff as $hm)
                                    <option value="{{ $hm->id }}">{{ $hm->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mode">Mode</label>
                            <select class="form-control" name="mode" id="mode">
                                <option value="{{ $businesses->mode }}">{{ Auth::user()->businesses->mode }}</option>
                                <option value="Active" selected>Active</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save Settings') }}
                            </button>
                        </div>


                    </form>
                    --}} -->

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- User role permission select  -->
    <script src="{{ asset('plugins/js/permissions-select.js') }}" defer></script>

    @if (isset($pagetype) && $pagetype == 'Dashboard')
        <!-- ChartJS -->
        <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

        <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
        
    @endif

    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>

    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // On Page Load

            //Date picker

            $('.date').datetimepicker({
                format: 'YYYY-MM-DD'
            });



            $('.select2').select2();

            $('.wyswygeditor').summernote()

        });

        function material(accid) {

            var name = $('#ach' + accid).attr("data-name");
            var type = $('#ach' + accid).attr("data-type");
            var measurement_unit = $('#ach' + accid).attr("data-measurement_unit");
            var size = $('#ach' + accid).attr("data-size");
            var picture = $('#ach' + accid).attr("data-picture");

            var cost_per = $('#ach' + accid).attr("data-cost_per");
            var business_id = $('#ach' + accid).attr("data-business_id");
            var category = $('#ach' + accid).attr("data-category");

            $('#id').val(accid);
            $('#name').val(name);
            $('#type').val(type).attr("selected", "selected");
            $('#measurement_unit').val(measurement_unit);
            $('#size').val(size);



            $('#cost_per').val(cost_per);
            $('#oldpicture').val(picture);
            $('#category').val(category).attr("selected", "selected");
            $('#business_id').val(business_id).attr("selected", "selected");
            $('#matbutton').html("Update Material");

        }

        function supplier(accid) {

            var supplier_name = $('#ach' + accid).attr("data-supplier_name");
            var company_name = $('#ach' + accid).attr("data-company_name");
            var phone_number = $('#ach' + accid).attr("data-phone_number");
            var details = $('#ach' + accid).attr("data-details");
            var address = $('#ach' + accid).attr("data-address");
            var business_id = $('#ach' + accid).attr("data-business_id");
            var category = $('#ach' + accid).attr("data-category");

            $('#id').val(accid);
            $('#company_name').val(company_name);
            $('#supplier_name').val(supplier_name);
            $('#phone_number').val(phone_number);
            $('#details').val(details);
            $('#address').val(address);
            $('#category').val(category).attr("selected", "selected").change();
            $('#business_id').val(business_id).attr("selected", "selected").change();
            $('#supbutton').html("Update Supplier");

        }

        function materialcheckout(accid) {

            $("#forcheckout").show();

            $("#materiallist").hide();

            var material_name = $('#ach' + accid).attr("data-material_name");

            $("#material_named").text(material_name).change();

            $("#quantity").attr('type', 'number').change();

            var checkout_by = $('#ach' + accid).attr("data-checkout_by");
            var approved_by = $('#ach' + accid).attr("data-approved_by");
            var task_id = $('#ach' + accid).attr("data-task_id");

            var material_id = $('#ach' + accid).attr("data-material_id");
            var quantity = $('#ach' + accid).attr("data-quantity");
            var business_id = $('#ach' + accid).attr("data-business_id");
            var dated = $('#ach' + accid).attr("data-dated");
            var details = $('#ach' + accid).attr("data-details");

            var date_supplied = $('#ach' + accid).attr("data-date_supplied");


            $('#id').val(accid);
            $('#task_id').val(task_id).attr("selected", "selected").change();
            $('#checkout_by').val(checkout_by).attr("selected", "selected").change();
            $('#material_id').val(material_id).attr("selected", "selected").change();
            $('#quantity').val(quantity);
            $('#dated').val(dated);
            $('#details').val(details);
            $('#approved_by').val(approved_by).attr("selected", "selected").change();

            $('#business_id').val(business_id).attr("selected", "selected").change();
            $('#mtcbutton').html("Update Checkout");

        }

        function accountHead(accid) {
            var title = $('#ach' + accid).attr("data-title");
            var category = $('#ach' + accid).attr("data-category");
            var type = $('#ach' + accid).attr("data-type");
            var description = $('#ach' + accid).attr("data-description");

            $('#id').val(accid);
            $('#title').val(title);
            $('#category').val(category).attr("selected", "selected");
            $('#type').val(type);
            $('#description').val(description);

        }

        function category(accid) {
            var title = $('#ach' + accid).attr("data-title");
            var category_group = $('#ach' + accid).attr("data-category_group");
            var description = $('#ach' + accid).attr("data-description");
            var business_id = $('#ach' + accid).attr("data-business_id");


            $('#id').val(accid);
            $('#title').val(title);
            $('#category_group').val(category_group).attr("selected", "selected");
            $('#description').val(description);
            $('#catbutton').html("Update Category");
            $('#business_id').val(business_id);

        }

        function transaction(accid) {


            var title = $('#ach' + accid).attr("data-title");
            var date = $('#ach' + accid).attr("data-date");
            var account_head = $('#ach' + accid).attr("data-account_head");
            var amount = $('#ach' + accid).attr("data-amount");
            var reference_no = $('#ach' + accid).attr("data-reference_no");
            var detail = $('#ach' + accid).attr("data-detail");
            var project = $('#ach' + accid).attr("data-project");
            var from = $('#ach' + accid).attr("data-from");
            var to = $('#ach' + accid).attr("data-to");
            var approved_by = $('#ach' + accid).attr("data-approved_by");
            var recorded_by = $('#ach' + accid).attr("data-recorded_by");

            $('#title').val(title);
            $('#id').val(accid);
            $('#date').val(date);
            $('#account_head').val(account_head).attr("selected", "selected");
            $('#project_id').val(project).attr("selected", "selected");
            $('#amount').val(amount);
            $('#reference_no').val(reference_no);
            $('#detail').val(detail);
            $('#from').val(from).attr("selected", "selected");
            $('#to').val(to).attr("selected", "selected");
            $('#approved_by').val(approved_by).attr("selected", "selected");
            $('#recorded_by').val(recorded_by).attr("selected", "selected");

        }

        // ADD MATERIAL CHECKOUT
        $(".add_item").click(function() {
            // alert("Am here!");
            // $(".spechead").show();
            var item_class = $(".add_item").attr("id");
            var old_class = parseFloat(item_class);
            new_class = old_class + 1;
            $(".add_item").prop('id', new_class);

            $("#1").clone().attr('id', new_class).appendTo("#item_list");
            $("#" + new_class + " a").prop('id', "re" + new_class);

            // $("table tbody#item_list").append("<tr scope='row' class='row"+new_class+"'><td class='input-field'><input type='text' name='property[]' value='' placeholder='e.g. Color, Brand etc'></td><td class='input-field'><td class='input-field'><input type='text' name='value[]' value='' placeholder='e.g. Red, HP etc'></td><td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow("+new_class+")'><i class='small material-icons'>remove</i></a></td></tr>");

        });

        $(document.body).on('click', '.removeitem', function(event) {

            var item_id = $(this).attr("id");
            item_id = item_id.substr(2);

            $("#" + item_id).remove();
            $("#itrow" + item_id).remove();
        });


        // ADD STAFF CHECKOUT
        $(".adds_item").click(function() {
            var item_class = $(".adds_item").attr("id");
            var old_class = parseFloat(item_class);
            new_class = old_class + 1;
            $(".adds_item").prop('id', new_class);

            $("#100").clone().attr('id', new_class).appendTo("#staff_list");
            $("#" + new_class + " a").prop('id', "res" + new_class);

            // $("table tbody#item_list").append("<tr scope='row' class='row"+new_class+"'><td class='input-field'><input type='text' name='property[]' value='' placeholder='e.g. Color, Brand etc'></td><td class='input-field'><td class='input-field'><input type='text' name='value[]' value='' placeholder='e.g. Red, HP etc'></td><td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow("+new_class+")'><i class='small material-icons'>remove</i></a></td></tr>");

        });

        $(document.body).on('click', '.removesitem', function(event) {

            var item_id = $(this).attr("id");
            item_id = item_id.substr(3);

            $("#" + item_id).remove();
            $("#itrow2" + item_id).remove();

        });
    </script>

    @if (isset($pagetype) && $pagetype == 'Table')
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

        <script>
            // TABLES WITH FILTERS
            $('#products thead tr').clone(true).appendTo('#products thead');
            $('#products thead tr:eq(1) th:not(:last)').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" value="" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#products').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                "order": [
                    [0, "asc"]
                ],
                "paging": true,
                "pageLength": 50,
                "filter": true,
                "ordering": true,
                deferRender: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        </script>
    @endif

    @yield('script')
    

</body>

</html>
