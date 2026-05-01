@extends('layouts.template')
@php
    $pagetype = 'Dashboard';
    $currency = config('app.currency_symbol', '₦');
@endphp
@section('content')
    <style>
        .rp-stat-card{border:0;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);transition:transform .15s ease, box-shadow .15s ease;}
        .rp-stat-card:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(0,0,0,.10);}
        .rp-stat-card .body{padding:18px 20px;display:flex;align-items:center;justify-content:space-between;color:#fff;}
        .rp-stat-card .label{font-size:.85rem;letter-spacing:.04em;text-transform:uppercase;opacity:.95;font-weight:600;}
        .rp-stat-card .value{font-size:2.1rem;font-weight:800;line-height:1;}
        .rp-stat-card .icon{font-size:2.4rem;opacity:.55;}
        .rp-stat-card .footer{display:block;background:rgba(0,0,0,.12);color:#fff;font-size:.8rem;padding:7px 20px;text-decoration:none;}
        .rp-stat-card .footer:hover{background:rgba(0,0,0,.22);color:#fff;}
        .rp-bg-properties{background:linear-gradient(135deg,#0d6efd,#3b82f6);}
        .rp-bg-tenants   {background:linear-gradient(135deg,#16a34a,#22c55e);}
        .rp-bg-projects  {background:linear-gradient(135deg,#f59e0b,#f97316);}
        .rp-bg-clients   {background:linear-gradient(135deg,#7c3aed,#a855f7);}

        .rp-section-card{border:0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);}
        .rp-section-card .card-header{background:#fff;border-bottom:1px solid #eef0f3;font-weight:700;color:#0f172a;}
        .rp-section-card .card-header small{font-weight:500;color:#64748b;}

        .rp-list-row{padding:10px 14px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;}
        .rp-list-row:last-child{border-bottom:0;}
        .rp-list-row .title{font-weight:600;color:#0f172a;font-size:.92rem;}
        .rp-list-row .meta {font-size:.78rem;color:#64748b;}

        .rp-pill{font-size:.7rem;padding:2px 9px;border-radius:999px;font-weight:700;letter-spacing:.03em;}
        .rp-pill-critical{background:#fee2e2;color:#b91c1c;}
        .rp-pill-high   {background:#fde68a;color:#92400e;}
        .rp-pill-medium {background:#dbeafe;color:#1d4ed8;}
        .rp-pill-low    {background:#dcfce7;color:#166534;}
        .rp-pill-active {background:#dcfce7;color:#166534;}
        .rp-pill-pending{background:#fef3c7;color:#92400e;}
        .rp-pill-overdue{background:#fee2e2;color:#b91c1c;}

        .rp-progress{height:6px;border-radius:6px;background:#eef2f7;overflow:hidden;}
        .rp-progress > div{height:100%;background:linear-gradient(90deg,#0d6efd,#22c55e);}

        .rp-mini-stat{padding:14px 16px;border:1px solid #eef2f7;border-radius:10px;background:#fff;}
        .rp-mini-stat .lbl{color:#64748b;font-size:.78rem;text-transform:uppercase;font-weight:600;letter-spacing:.04em;}
        .rp-mini-stat .val{font-size:1.35rem;font-weight:800;color:#0f172a;}
        .rp-empty{padding:28px 14px;text-align:center;color:#94a3b8;font-size:.88rem;}
    </style>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                    <small class="text-muted">Welcome back, {{ Auth::user()->name }} — here's what's happening today.</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Top KPI cards --}}
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="rp-stat-card rp-bg-properties">
                        <div class="body">
                            <div>
                                <div class="label">Properties</div>
                                <div class="value">{{ number_format($propertiesCount) }}</div>
                            </div>
                            <div class="icon"><i class="fas fa-building"></i></div>
                        </div>
                        <a href="{{ url('properties') }}" class="footer">Manage properties <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="rp-stat-card rp-bg-tenants">
                        <div class="body">
                            <div>
                                <div class="label">Tenants</div>
                                <div class="value">{{ number_format($tenantsCount) }}</div>
                            </div>
                            <div class="icon"><i class="fas fa-user-friends"></i></div>
                        </div>
                        <a href="{{ url('tenants') }}" class="footer">Manage tenants <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="rp-stat-card rp-bg-projects">
                        <div class="body">
                            <div>
                                <div class="label">Projects</div>
                                <div class="value">{{ number_format($projectsCount) }}</div>
                            </div>
                            <div class="icon"><i class="fas fa-tasks"></i></div>
                        </div>
                        @can('view project')
                            <a href="{{ url('projects') }}" class="footer">Manage projects <i class="fas fa-arrow-right ml-1"></i></a>
                        @else
                            <span class="footer">Projects overview</span>
                        @endcan
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="rp-stat-card rp-bg-clients">
                        <div class="body">
                            <div>
                                <div class="label">Clients</div>
                                <div class="value">{{ number_format($clientsCount) }}</div>
                            </div>
                            <div class="icon"><i class="fas fa-users"></i></div>
                        </div>
                        @can('view client')
                            <a href="{{ url('clients') }}" class="footer">Manage clients <i class="fas fa-arrow-right ml-1"></i></a>
                        @else
                            <span class="footer">Clients overview</span>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Mini KPIs row --}}
            <div class="row">
                <div class="col-md-3 col-6 mb-3">
                    <div class="rp-mini-stat">
                        <div class="lbl">Occupancy</div>
                        <div class="val">{{ $occupancyRate }}%</div>
                        <div class="rp-progress mt-2"><div style="width: {{ $occupancyRate }}%"></div></div>
                        <small class="text-muted">{{ $occupiedUnits }} / {{ $totalUnits }} units occupied</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="rp-mini-stat">
                        <div class="lbl">Collected this month</div>
                        <div class="val">{{ $currency }}{{ number_format($totalCollectedMonth, 0) }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('F Y') }}</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="rp-mini-stat">
                        <div class="lbl">Expected rent</div>
                        <div class="val">{{ $currency }}{{ number_format($totalDueMonth, 0) }}</div>
                        <small class="text-muted">From active leases</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="rp-mini-stat">
                        <div class="lbl">Open maintenance</div>
                        <div class="val">{{ $maintenanceRequests->count() }}</div>
                        <small class="text-muted">Pending or in progress</small>
                    </div>
                </div>
            </div>

            {{-- Two-column main area --}}
            <div class="row">
                {{-- LEFT COLUMN --}}
                <div class="col-lg-7">

                    {{-- Due Rents --}}
                    <div class="card rp-section-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-money-bill-wave text-success mr-2"></i> Due Rents</span>
                            <small>Next payments and renewals</small>
                        </div>
                        <div class="card-body p-0">
                            @forelse($dueRents as $lease)
                                @php
                                    $renewal = $lease->renewal_date ?? $lease->end_date;
                                    $isOverdue = $renewal && \Carbon\Carbon::parse($renewal)->isPast();
                                    $tenantName = optional($lease->tenant)->full_name
                                        ?? trim((optional($lease->tenant)->first_name ?? '') . ' ' . (optional($lease->tenant)->last_name ?? ''))
                                        ?: 'Tenant';
                                @endphp
                                <div class="rp-list-row">
                                    <div>
                                        <div class="title">{{ $tenantName }}</div>
                                        <div class="meta">
                                            {{ optional($lease->property)->name ?? 'Property' }}
                                            @if(optional($lease->propertyUnit)->unit_number) · Unit {{ $lease->propertyUnit->unit_number }} @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="title">{{ $currency }}{{ number_format($lease->rent_amount, 0) }}</div>
                                        <span class="rp-pill {{ $isOverdue ? 'rp-pill-overdue' : 'rp-pill-pending' }}">
                                            {{ $isOverdue ? 'Overdue' : 'Due' }} {{ $renewal ? \Carbon\Carbon::parse($renewal)->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="rp-empty"><i class="fas fa-check-circle mr-1"></i> No rents currently due. You're all caught up.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Active projects --}}
                    <div class="card rp-section-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-tasks text-warning mr-2"></i> Active Projects</span>
                            @can('view project')
                                <a href="{{ url('projects') }}" class="text-primary" style="font-size:.85rem;font-weight:600;">View all</a>
                            @endcan
                        </div>
                        <div class="card-body p-0">
                            @forelse($activeProjects as $p)
                                @php
                                    $total = $p->milestones->count();
                                    $done  = $p->milestones->where('status', 'Completed')->count();
                                    $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
                                @endphp
                                <div class="rp-list-row" style="display:block;">
                                    <div class="d-flex justify-content-between">
                                        <div class="title">{{ $p->title }}</div>
                                        <div class="meta">{{ $done }}/{{ $total ?: '—' }} milestones · {{ $pct }}%</div>
                                    </div>
                                    <div class="rp-progress mt-2"><div style="width:{{ $pct }}%"></div></div>
                                </div>
                            @empty
                                <div class="rp-empty">No active projects.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent payments --}}
                    @if($recentPayments->count())
                        <div class="card rp-section-card mb-3">
                            <div class="card-header"><i class="fas fa-receipt text-info mr-2"></i> Recent Payments</div>
                            <div class="card-body p-0">
                                @foreach($recentPayments as $pay)
                                    @php $tn = optional(optional($pay->lease)->tenant); @endphp
                                    <div class="rp-list-row">
                                        <div>
                                            <div class="title">{{ trim(($tn->first_name ?? '').' '.($tn->last_name ?? '')) ?: 'Payment' }}</div>
                                            <div class="meta">{{ $pay->payment_method ?? '—' }} · {{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="title">{{ $currency }}{{ number_format($pay->amount, 0) }}</div>
                                            <span class="rp-pill rp-pill-active">{{ ucfirst($pay->status) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-5">

                    {{-- My Tasks --}}
                    <div class="card rp-section-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clipboard-list text-primary mr-2"></i> My Tasks</span>
                            <small>{{ $myTasks->count() }} open</small>
                        </div>
                        <div class="card-body p-0">
                            @forelse($myTasks as $t)
                                <div class="rp-list-row">
                                    <div>
                                        <div class="title">{{ $t->title ?? $t->name ?? 'Task #'.$t->id }}</div>
                                        <div class="meta">
                                            {{ optional($t->project)->title ?? 'No project' }}
                                            @if(!empty($t->due_date)) · Due {{ \Carbon\Carbon::parse($t->due_date)->format('M d') }} @endif
                                        </div>
                                    </div>
                                    <span class="rp-pill rp-pill-pending">{{ $t->status ?? 'Pending' }}</span>
                                </div>
                            @empty
                                <div class="rp-empty">No tasks assigned to you.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Maintenance Requests --}}
                    <div class="card rp-section-card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-tools text-danger mr-2"></i> Maintenance Requests</span>
                            <small>Highest priority first</small>
                        </div>
                        <div class="card-body p-0">
                            @forelse($maintenanceRequests as $m)
                                @php
                                    $pri = strtolower($m->priority ?? 'medium');
                                    $pillCls = 'rp-pill-medium';
                                    if ($pri === 'critical') $pillCls = 'rp-pill-critical';
                                    elseif ($pri === 'high') $pillCls = 'rp-pill-high';
                                    elseif ($pri === 'low')  $pillCls = 'rp-pill-low';
                                @endphp
                                <div class="rp-list-row">
                                    <div>
                                        <div class="title">{{ $m->title }}</div>
                                        <div class="meta">
                                            {{ optional($m->property)->name ?? 'Property' }}
                                            @if(optional($m->propertyUnit)->unit_number) · Unit {{ $m->propertyUnit->unit_number }} @endif
                                            · {{ \Carbon\Carbon::parse($m->reported_at ?? $m->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span class="rp-pill {{ $pillCls }}">{{ ucfirst($pri) }}</span>
                                </div>
                            @empty
                                <div class="rp-empty">No open maintenance requests.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Project performance chart (preserved from old dashboard) --}}
                    <div class="card rp-section-card mb-3">
                        <div class="card-header"><i class="fas fa-chart-bar text-secondary mr-2"></i> Ongoing Project Performance</div>
                        <div class="card-body">
                            <canvas id="canvas" style="max-height:280px;"></canvas>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    @php
        $out = '';
        foreach ($projects->where('status', 'In Progress') as $o) {
            $out .= '"' . addslashes($o->title) . '",';
        }
        $allPtitles = '[' . substr($out, 0, -1) . ']';

        $setmilestones = '';
        foreach ($projects->where('status', 'In Progress') as $pmiles) {
            $setmilestones .= (isset($pmiles->milestones) ? $pmiles->milestones->count() : 0) . ',';
        }
        $allPMilestoneCount = '[' . substr($setmilestones, 0, -1) . ']';

        $setmilestonesc = '';
        foreach ($projects->where('status', 'In Progress') as $pmiles) {
            $setmilestonesc .= (isset($pmiles->milestones) ? $pmiles->milestones->where('status', 'Completed')->count() : 0) . ',';
        }
        $allPMilestoneCCount = '[' . substr($setmilestonesc, 0, -1) . ']';
    @endphp
    <script>
        (function(){
            var canvas = document.getElementById("canvas");
            if (!canvas || typeof Chart === 'undefined') return;
            var labels = {!! $allPtitles ?: '[]' !!};
            if (!labels.length) { canvas.parentNode.innerHTML = '<div class="rp-empty">No ongoing projects to chart.</div>'; return; }
            new Chart(canvas.getContext("2d"), {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        { label: "Milestones", backgroundColor: "#cbd5e1", borderColor: "#94a3b8", borderWidth: 1, data: {!! $allPMilestoneCount ?: '[]' !!} },
                        { label: "Completed",  backgroundColor: "#22c55e", borderColor: "#16a34a", borderWidth: 1, data: {!! $allPMilestoneCCount ?: '[]' !!} }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    legend: { position: "top" },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
                }
            });
        })();
    </script>
@endsection
