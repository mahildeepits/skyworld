@extends('layout.main')
@section('content')
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
        opacity: 0.6;
    }
</style>
@php
$user = authUser();
$array = [
    70 => [
        'logo_downline' => 10,
        'logo_direct' => 1,
    ],
    150 => [
        'logo_downline' => 20,
        'logo_direct' => 2,
        'downline' => 150,
        'direct' => 10,
    ],
    300 => [
        'logo_downline' => 30,
        'logo_direct' => 3,
        'downline' => 400,
        'direct' => 20,
    ],
    400 => [
        'downline' => 1500,
        'direct' => 30,
    ],
    500 => [
        'downline' => 3000,
        'direct' => 60,
    ],
];
// \App\Helpers\RewardHelper::giveRewards();
@endphp
    @if(isset($type) && $type == 'points')
    <div class="row mb-2">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <x-page-breadcrumb :currentPage="'My Reward Points'" />
            <p class="mb-0">Reward Points : {{authUser()->reward_points ?? 0}}</p>
        </div>
    </div>
    @else
    <x-page-breadcrumb :currentPage="'My Rewards'" />
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="row">
        {{-- @if($rewards->isEmpty())
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h3>No reward archived yet!</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
        @if(!$rewards->isEmpty())
            <div class="row">
                @foreach ($rewards as $reward)
                @php
                $logocheck = false;
                if(\Str::contains(strtolower($reward->name), 'logo')){
                    $logocheck = true;
                }
                @endphp
                    <div class="col-md-4 parent">
                        <div class="card" style="border-radius:10px;">
                            @if($reward->type == 'points')
                                <div style="width:70%; margin:0 auto;">
                                    <img class="" src="{{$reward->image_path ?? '' }}" style="width:100%; max-width: 400px;border-radius:10px;" alt="Card image cap">
                                </div>
                            @else
                                <div class="d-flex gap-2 justify-content-center align-items-center" style="border-bottom:2px solid #dadada;">
                                    <img class="" src="{{$reward->image_path ?? '' }}" style="width:100px;border-radius:10px;" alt="Card image cap">
                                    <h1>
                                        $ {{ $reward->amount ?? 0 }}
                                    </h1>
                                </div>    
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p>
                                        <b>{{ $reward->name }}</b>
                                    </p>
                                    @php
                                    $redeemable = false;
                                    if($logocheck){
                                        if(isset($array[$reward->pairs]['logo_direct']) && $user->allChildMembers()->where('is_paid',1)->count() >= $array[$reward->pairs]['logo_direct'] && $user->reward_points >= $reward->pairs && $user->allPiadChildsExceptSelf()->count() >= $array[$reward->pairs]['logo_downline']){
                                            $redeemable = true;
                                        }
                                    }else{
                                        if(isset($array[$reward->pairs]) && $user->allChildMembers()->where('is_paid',1)->count() >= $array[$reward->pairs]['direct'] && $user->reward_points >= $reward->pairs && $user->allPiadChildsExceptSelf()->count() >= $array[$reward->pairs]['downline']){
                                            if(checkRewardEligibility($user,$reward)){
                                                $redeemable = true;
                                            }
                                        }else if(!isset($array[$reward->pairs])){
                                            if(checkRewardEligibility($user,$reward)){
                                                $redeemable = true;
                                            }
                                        }
                                    }
                                    @endphp
                                    <a href="{{route('redeem.rewards',$reward->id)}}" class="px-2 py-1 bg-main text-white redeem float-end {{(!$redeemable)? 'disabled' : '' }}" style="font-size:0.7rem; border-radius:30px"> REDEEM </a>
                                </div>
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p class="text-main"> <b>Rules & Ragulations</b></p>
                                    @if(!$logocheck)
                                        <p class="card-text">
                                            <b>{{ $user->allChildMembers()->where('is_paid',1)->count() ?? 0 }} / {{ $reward->pairs ?? 0 }}</b>
                                        </p>
                                    @endif
                                </div>
                                <div class="mb-2">

                                    <div><b>{{$reward->pairs ?? 0}} {{$reward->type == 'points' ? 'Reward Points' : 'First Valid downline '}}</b></div>
                                    @if($reward->type == 'points' && isset($array[$reward->pairs]))
                                    @if (!$logocheck)
                                    <div><b>{{$array[$reward->pairs]['downline'] ?? 0}}  Valid downline</b></div>
                                    <div><b>{{$array[$reward->pairs]['direct'] ?? 0}}  First layer Valid downline</b></div>
                                    @else
                                    <div><b>{{$array[$reward->pairs]['logo_downline'] ?? 0}}  Valid downline</b></div>
                                    <div><b>{{$array[$reward->pairs]['logo_direct'] ?? 0}}  First layer Valid downline</b></div>
                                    @endif
                                    @endif
                                </div>
                                @if($reward->days > 0)
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <p class="card-title"> Countdown </p>
                                        <p class="card-text">
                                            @if($user->email_verified_at != null)
                                                <span class="time_interval text-danger" data-valid-downline="{{ $user->allChildMembers()->where('is_paid',1)->count() ?? 0 }}" data-downline="{{ $reward->pairs ?? 0 }}" data-time="{{$user->email_verified_at != null? \Carbon\Carbon::parse($user->email_verified_at)->addDays($reward->days) : 'N/A' }}"> {{ ($user->email_verified_at != null)? \Carbon\Carbon::parse($user->email_verified_at)->diff(now())->format('%a Days %H:%I:%S') : 'N/A' }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                @if(!$logocheck)
                                    @if ($reward->pairs == 11999)
                                        <p>1. The reward's deadline is October 1, 2032</p>
                                        <p>2. Every user should only have one BITTICO account. BITTICO reserves the right to cancel your participating rewards if we suspect you are engaged in fraud, dishonesty or wrongful acts such as multiple account registrations with the same identity, multiple logins from the same IP address and login multiple accounts using a VPN.</p>
                                    @else
                                        <p>1. The participation period for the event is {{$reward->days ?? '0'}} {{ $reward->days > 1 ? 'days' : 'day'}} from the date you registered your BITTICO account. Fulfilling the requirement after {{$reward->days ?? 'N/A'}} {{ $reward->days > 1 ? 'days' : 'day'}} will result in invalid usage.</p>
                                        <p>Example: If Member Michael joins BITTICO on February 1st, he/she must have {{ isset($array[$reward->pairs]) ? $array[$reward->pairs]['downline'] : ($reward->pairs ?? 0)}} valid downline within {{$reward->days ?? '0'}} {{ $reward->days > 1 ? 'days' : 'day'}} of joining to be eligible for redemption.</p>
                                        @if(isset($array[$reward->pairs]))
                                            <p><b>This includes having: </b> {{$array[$reward->pairs]['directs'] ?? 0}} valid downline in the first layer.</p>
                                        @endif
                                        <p>2. Each user must have only one BITTICO account. BITTICO reserves the right to revoke your participation rewards if it suspects that you have engaged in fraudulent, dishonest or unfair actions, such as registering multiple accounts with the same ID, logging in multiple times from the same IP address, and logging in to multiple accounts using a VPN.</p>
                                        <p>3. A valid downline is defined as a minimum of 50 USDT deposited into the account.</p>
                                    @endif
                                @else
                                    <p>1. The gift is redeemable after fulfilling the requirements.</p>
                                    <p>Example: After member Michael joins BITTICO he must achieve a total of {{ isset($array[$reward->pairs]['logo_downline']) ? $array[$reward->pairs]['logo_downline'] : 0 }} active downlines and {{$reward->pairs ?? 0}} checkin points.</p>
                                    @if(isset($array[$reward->pairs]))
                                        <p><b>This includes having: </b> {{$array[$reward->pairs]['logo_direct'] ?? 0}} active downlines in the first layer.</p>
                                    @endif
                                    <p>2. Every user should only have one BITTICO account. BITTICO reserves the right to cancel your participating rewards if we suspect you are engaged in fraud, dishonesty or wrongful acts such as multiple account registrations with the same identity, multiple logins from the same IP address and login multiple accounts using a VPN.</p>
                                    <p>3. Active downline define as the account total deposited a minimum of 50 USDT</p>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
            </div>
        </div>
    </div>@endsection
@section('css')
    @parent
    <style>
        .reward-bg{
            background: url("../images/reward-bg.png") no-repeat;
            background-size: cover;
            height: 700px;
        }
        .reward-image{
            border-radius: 8px;
            box-shadow: 3px 3px 12px 8px #d3d3d3;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script>
        function updateTimeDifference(element) {
            const now = new Date();
            const futureDateTime = new Date(element.data('time'));
            const validDownline = new Date(element.data('valid-downline'));
            const downline = new Date(element.data('downline'));
            let diff = futureDateTime - now;
            let buttonElement = element.parents('.parent').find('.redeem');
            // let amount = parseFloat(amountElement.data('amount'));
            // let new_amount = ((amount * 0.01) + amount).toFixed(2);
            if(diff < 0 && validDownline >= downline){

                buttonElement.removeClass('disabled');
            }
            if (diff < 0) {
                // amountElement.text(new_amount);
                // buttonElement.removeClass('disabled');
                element.text(`${00} Days ${pad(0)}:${pad(0)}:${pad(0)}`);
                return;
            }

            const seconds = Math.floor((diff / 1000) % 60);
            const minutes = Math.floor((diff / 1000 / 60) % 60);
            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            const formatted = `${days} Days ${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            element.text(formatted);
        }
        function pad(num) {
            return num.toString().padStart(2, '0');
        }
        $(document).ready(function () {
            setInterval(() => { 
                $('.time_interval').each(function () {
                    updateTimeDifference($(this));
                });
            }, 1000);
        })
    </script>
@endsection
