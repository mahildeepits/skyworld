@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 my-3">
            <h4 class="card-title">Dashboard</h4>
        </div>
    </div>
    <style>
        .premium-card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: white !important;
        }
        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .grad-1 { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); } /* Purple-Blue */
        .grad-2 { background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); } /* Sunset Orange */
        .grad-3 { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); } /* Emerald Green */
        .grad-4 { background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); } /* Vivid Pink-Orange */
        .grad-5 { background: linear-gradient(135deg, #02aab0 0%, #00cdac 100%); } /* Teal-Green */
        .grad-6 { background: linear-gradient(135deg, #4568dc 0%, #b06ab3 100%); } /* Royal Purple-Pink */
        
        .card-body h4 { font-weight: 700 !important; font-size: 1.5rem; }
        .card-body span { font-size: 0.9rem; opacity: 0.9; }
        .w-circle-icon { background: rgba(255, 255, 255, 0.2); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    </style>

    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card premium-card grad-1">
                <div class="card-body">
                    <div class="media justify-content-between">
                        <div class="media-body text-left">
                            <h4 class=""> 
                                {{ \App\Models\User::where(\DB::raw('date(created_at)'),\Carbon\Carbon::now()->format('Y-m-d'))->count() }}
                            </h4>
                            <span class="">Joinings Today</span>
                        </div>
                        <div class="align-self-center w-circle-icon rounded-circle">
                            <i class="fas fa-users font-30"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <a href="{{ route('admin.withdrawal.requests', ['from_date' => \Carbon\Carbon::today()->format('Y-m-d'), 'to_date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" style="text-decoration: none; width: 100%;">
                <div class="card premium-card grad-2">
                    <div class="card-body">
                        <div class="media justify-content-between">
                            <div class="media-body text-left">
                                <h4 class=""> 
                                    {{ \App\Models\UserPayout::whereDate('is_requested',\Carbon\Carbon::now()->format('Y-m-d'))->count() }}
                                </h4>
                                <span class="">Today Withdrawl Requests</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle">
                                <i class="fas fa-file-invoice-dollar font-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card premium-card grad-3">
                <div class="card-body">
                    <div class="media justify-content-between">
                        <div class="media-body text-left">
                            <h4 class=""> 
                                {{ \App\Models\User::whereNotIn('member_id',['admin','company','Company','Top','top','TOP'])->count() }}
                            </h4>
                            <span class="">Total Users</span>
                        </div>
                        <div class="align-self-center w-circle-icon rounded-circle">
                            <i class="fas fa-user-friends font-30"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 grid-margin stretch-card">
            <a href="{{ route('admin.company.revenue.report') }}" style="text-decoration: none; width: 100%;">
                <div class="card premium-card grad-4">
                    <div class="card-body">
                        <div class="media justify-content-between">
                            <div class="media-body text-left">
                                <h4 class="">$ {{ number_format($total_revenue, 2) }}</h4>
                                <span class="">Total Revenue</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle">
                                <i class="fas fa-money-bill-wave font-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <a href="{{ route('admin.company.revenue.report', ['from_date' => \Carbon\Carbon::today()->format('Y-m-d'), 'to_date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" style="text-decoration: none; width: 100%;">
                <div class="card premium-card grad-5">
                    <div class="card-body">
                        <div class="media justify-content-between">
                            <div class="media-body text-left">
                                <h4 class="">$ {{ number_format($today_revenue, 2) }}</h4>
                                <span class="">Today Revenue</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle">
                                <i class="fas fa-chart-line font-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            @php
                $lastMonthStart = \Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $lastMonthEnd = \Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
            @endphp
            <a href="{{ route('admin.company.revenue.report', ['from_date' => $lastMonthStart, 'to_date' => $lastMonthEnd]) }}" style="text-decoration: none; width: 100%;">
                <div class="card premium-card grad-6">
                    <div class="card-body">
                        <div class="media justify-content-between">
                            <div class="media-body text-left">
                                <h4 class="">$ {{ number_format($last_month_revenue, 2) }}</h4>
                                <span class="">Last Month Revenue</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle">
                                <i class="fas fa-calendar-alt font-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
