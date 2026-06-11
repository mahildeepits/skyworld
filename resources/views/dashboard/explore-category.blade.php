@extends('layout.main')
@section('content')
@php
$user = authUser();
@endphp
<style>
    .fontsize-14{
        font-size: 12px;
    }

</style>
    <div class="container-fluid py-2">
        <x-page-breadcrumb :currentPage="'Explore Category'" />
        <div class="row">
            <div class="col-md-5">
                <div class="card mx-2 card-slider bg-main" style="color: white!important;color: white;border-radius: 10px;">
                    <div class="card-body p-4">
                        <div class="bg-muted text-center mb-4">
                            <img src="{{ $category->image_path ?? 'https://via.placeholder.com/150x150' }}" width="150px" class="rounded" />
                            <h2 class="text-white">{{ strtoupper($category->name) ?? 'N/A' }}</h2>
                        </div>
                        <div class="mb-2  d-flex justify-content-between align-items-center">
                            <span>Daily assigned orders </span>
                            <p class="m-0"><b>{{ $attendedTasks->count() ?? 0 }}/{{ $category->daily_order_limit ?? 0 }}</b></p>
                        </div>
                        <div class="progress mb-2 bg-white">
                            <div class="progress-bar bg-color-light" role="progressbar" aria-valuenow="20" aria-valuemin="20" style="width: {{$progress}}%;" aria-valuemax="100"></div>
                        </div>
                        <div class="mt-3">
                            @if($user->lockedStakings->count() == 0)
                                <a href="{{route('attend.task', encrypt($category->id))}}" class="btn btn-main btn-sm {{ $attendedTasks->count() >= $category->daily_order_limit ? 'disabled' : ''}}" style="border-radius:10px;"  >Start Task</a>
                            @endif
                        </div>
                        {{-- <div class="d-flex justify-content-around align-items-center mt-3">
                            <button class="btn btn-dark "  style="border-radius:10px;" >Boost</button>

                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-7 text-center">
                <div class="d-flex flex-wrap gap-2 mt-3" style="padding-left:25px;">
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Personal Commission Today</h6>
                            <p class="m-0"><b>{{round( getIncomeByCategory($category->id, 'todayTasks'), 2) ?? 0}}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Personal Commission Yesterday</h6>
                            <p class="m-0"><b>{{round( getIncomeByCategory($category->id, 'yesterdayTasks'), 2) ?? 0}}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Available Balance</h6>
                            <p class="m-0"><b>{{ round($user->walletIncomesByKey(), 2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Complete Orders</h6>
                            <p class="m-0"><b>{{ $attendedTasks->count() ?? 0 }}/{{ $category->daily_order_limit ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Pending Orders</h6>
                            <p class="m-0"><b>{{$user->pendingTasks($category->id)->count() ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">DIrect Income</h6>
                            <p class="m-0"><b>{{round($user->walletIncomesByKey('directIncome'),2) ?? 0}}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Team Commission Yesterday</h6>
                            <p class="m-0"><b>{{ round($user->yesterday_team_commission,2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Layer 1 Team Commission Yesterday</h6>
                            <p class="m-0"><b>{{ round($user->yesterday_level_1_commission,2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Layer 2 Team Commission Yesterday</h6>
                            <p class="m-0"><b>{{ round($user->yesterday_level_2_commission,2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Layer 3 Team Commission Yesterday</h6>
                            <p class="m-0"><b>{{ round($user->yesterday_level_3_commission,2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Community Bonus</h6>
                            <p class="m-0"><b>{{ round($user->walletIncomesByKey('communityBonus'),2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Upline Bonus</h6>
                            <p class="m-0"><b>{{ round($user->walletIncomesByKey('uplineBonus'),2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    <div class="card p-0 card-commissions" >
                        <div class="card-body text-center p-2 align-content-center">
                            <h6 class="fontsize-14">Locking Profit Yesterday</h6>
                            <p class="m-0"><b>{{ round($user->yesterday_locking_profit,2) ?? 0 }}</b></p>
                        </div>
                    </div>
                    {{-- <div class="card" style="border-radius: 10px; width: 20%; min-width: 125px;">
                        <div class="card-body text-center">
                            <h6 class="fontsize-14">income name</h6>
                            <p class="m-0"><b>000</b></p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
