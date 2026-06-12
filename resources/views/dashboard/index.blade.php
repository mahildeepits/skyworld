@extends('layout.main')

@section('css')
<style>
  /* ── Dashboard Specific Styles ── */
  .db-welcome-card {
    background: #04c;
    border-radius: 18px !important;
    padding: 28px 30px !important;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(3,75,179,0.35) !important;
  }
  .db-welcome-card::before {
    content: '';
    position: absolute; top: -30%; right: -5%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,226,251,0.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .db-welcome-card::after {
    content: '';
    position: absolute; bottom: -40%; left: 30%;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(3,75,179,0.2) 0%, transparent 70%);
    pointer-events: none;
  }
  .db-welcome-title {
    color: #fff !important;
    font-size: 1.7rem !important;
    font-weight: 700 !important;
    margin: 0 0 4px !important;
    line-height: 1.2;
  }
  .db-welcome-sub { color: rgba(255,255,255,0.65); font-size: 0.82rem; }
  .db-wallet-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(0,226,251,0.15);
    border: 1px solid rgba(0,226,251,0.35);
    color: #00e2fb;
    padding: 8px 18px; border-radius: 50px;
    font-weight: 600; font-size: 0.88rem;
    backdrop-filter: blur(10px);
    text-decoration: none !important;
    transition: all 0.3s;
  }
  .db-wallet-badge:hover {
    background: rgba(0,226,251,0.25); color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,226,251,0.3);
  }
  .db-wallet-badge small { font-size: 0.68rem; opacity: 0.7; display: block; line-height: 1; }
  .db-wallet-badge strong { font-size: 1rem; display: block; line-height: 1.2; }
  .db-welcome-avatar {
    width: 48px; height: 48px;
    border: 2px solid rgba(0,226,251,0.5);
    box-shadow: 0 0 16px rgba(0,226,251,0.3);
    object-fit: cover;
  }

  /* Stat Cards */
  .db-stat-card {
    background: #fff !important;
    border-radius: 14px !important;
    padding: 20px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
    border: none !important;
    transition: all 0.3s;
    max-height: 380px;
  }
  .db-stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(3,75,179,0.12) !important; }
  .db-stat-label { font-size: 0.77rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 600; margin-bottom: 8px; }
  .db-stat-value { font-size: 1.7rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .db-stat-sub { font-size: 0.75rem; color: #94a3b8; margin-top: 6px; }
  .db-stat-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
  }
  .db-stat-arrow {
    width: 26px; height: 26px;
    border-radius: 50%; background: #f1f5f9;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; color: #64748b;
  }

  /* Wallet Info Card (Blue gradient) */
  .db-wallet-card {
    background: linear-gradient(135deg, #034bb3 0%, #0099cc 100%) !important;
    border-radius: 14px !important;
    padding: 22px !important;
    color: #fff !important;
    box-shadow: 0 8px 28px rgba(3,75,179,0.3) !important;
    border: none !important;
    height: 100%;
  }
  .db-wallet-card-label { font-size: 0.78rem; opacity: 0.75; text-transform: uppercase; letter-spacing: 0.06em; }
  .db-wallet-card-amount { font-size: 2rem; font-weight: 700; margin: 6px 0; line-height: 1.1; }
  .db-wallet-card-sub { font-size: 0.75rem; opacity: 0.65; }
  .db-wallet-card-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    font-size: 0.82rem;
  }
  .db-wallet-card-row:last-child { border-bottom: none; }
  .db-wallet-card-row .label { opacity: 0.75; }
  .db-wallet-card-row .value { font-weight: 600; }

  /* Wallet Info rows (white card) */
  .db-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.83rem;
  }
  .db-info-row:last-child { border-bottom: none; }
  .db-info-row .key { color: #64748b; }
  .db-info-row .val { font-weight: 600; color: #0f172a; }
  .db-info-row .val.positive { color: #22c55e; }
  .db-info-row .val.negative { color: #ef4444; }

  /* Section headers */
  .db-section-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px;
  }
  .db-section-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; }
  .db-section-link { font-size: 0.8rem; color: #034bb3; text-decoration: none; font-weight: 500; }
  .db-section-link:hover { color: #00e2fb; }

  /* Package Cards */
  .db-pkg-card {
    background: #fff !important;
    border-radius: 16px !important;
    border: 1px solid #f1f5f9 !important;
    overflow: hidden;s
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
  }
  .db-pkg-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important; }
  .db-pkg-header {
    background: linear-gradient(135deg, #034bb3, #00b4d8);
    padding: 30px 20px; text-align: center;
    position: relative;
  }
  .db-pkg-header img { width: 80px; height: 80px; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.2)); }
  .db-pkg-body { padding: 25px; }
  .db-pkg-title { font-size: 1.1rem; font-weight: 800; color: #fff; margin-top: 10px; letter-spacing: 1px; }
  
  .db-progress-bar { height: 8px; border-radius: 10px; background: #f1f5f9; margin: 8px 0 12px; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
  .db-progress-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg, #034bb3, #00e2fb); position: relative; }
  
  /* Quick Action Buttons */
  .quick-action-btn {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 14px;
    padding: 20px 10px;
    text-align: center;
    transition: all 0.3s;
    text-decoration: none !important;
    display: block;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
  }
  .quick-action-btn:hover {
    background: #f8fafc;
    border-color: #034bb3;
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(3,75,179,0.08);
  }
  .quick-action-btn i {
    font-size: 1.5rem;
    margin-bottom: 10px;
    display: block;
  }
  .quick-action-btn span {
    font-size: 0.8rem;
    font-weight: 700;
    color: #334155;
  }

  /* Reviews */
  .db-review-card {
    background: #fff !important;
    border-radius: 14px !important; border: none !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05) !important;
    padding: 16px;
  }
  .db-star { color: gold; font-size: 0.85rem; }

  /* Swiper Customization */
  .packages-swiper {
    /* padding-bottom: 30px !important; */
    position: relative;
    overflow: hidden;
    padding: 10px 0 !important; /* Added padding to allow hover lift and shadow without clipping */
    margin-top: -10px !important;
  }
  .swiper-button-next, .swiper-button-prev {
    width: 30px !important;
    height: 30px !important;
    background: #fff !important;
    border-radius: 50% !important;
    color: #034bb3 !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    top: 25% !important;
    transform: translateY(-50%) !important;
    display: none !important; /* Hidden by default, shown on desktop */
  }
  .swiper-button-next::after, .swiper-button-prev::after {
    font-size: 12px !important;
    font-weight: bold;
  }
  @media (min-width: 992px) {
    .swiper-button-next, .swiper-button-prev {
      display: flex !important;
    }
    .swiper-button-next { right: 10px !important; }
    .swiper-button-prev { left: 10px !important; }
  }
  .db-pkg-card {
    height: 100%;
  }

  /* Increase modal z-index to cover sidebar (which is 1100) */
  #announcementModal {
    z-index: 1150 !important;
  }
  .modal-backdrop {
    z-index: 1140 !important;
  }

</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

@endsection

@section('content')
@php
  use Carbon\Carbon;
  $user = authUser();
  $walletIncomes = $user->walletIncomesByKey('all');
  $unsettledROI = $user->getCurrentMonthAccumulatedROI();
  $unsettledLevelROI = $user->getCurrentMonthAccumulatedLevelROI();
  $walletAmount = ($user->walletIncomesByKey('totalIncome') ?? 0) + $unsettledROI + $unsettledLevelROI;
  $lockedBalance = $user->lockedStakings()->sum('amount') ?? 0;
  
  $loginCount = $user->checkIns->count();
  $redeemedDays = $user->checkIns->pluck('count')->toArray();
  $totalCoins = $user->payouts()->where('income_type','task_income')->sum('amount') ?? 0;
  $walletReturn = $user->payouts()->where('income_type','referral_income')->sum('amount') ?? 0;

  // Incomes Mapping for the detailed table (Using UnifiedTransaction now)
  $dailyIncomes = [
      'trade'         => $user->today_trade_income,
      'team'          => $user->today_team_commission + $user->getCurrentMonthDailyLevelROI(),
      'direct'        => $user->today_direct_income,
      'bonus'         => $user->today_bonus_income,
      'roi'           => $user->getCurrentMonthDailyROI(),
  ];
  
  $totalIncomes = [
      'trade'         => $walletIncomes['tradeIncome'] ?? 0,
      'team'          => ($walletIncomes['teamCommission'] ?? 0) + $unsettledLevelROI,
      'direct'        => $walletIncomes['directIncome'] ?? 0,
      'bonus'         => $walletIncomes['bonusIncome'] ?? 0,
      'roi'           => ($walletIncomes['roiIncome'] ?? 0) + $unsettledROI,
  ];

  // Team Stats
  $teamACount = $user->allChildMembers->where('is_paid', 1)->count(); // Active Team A
  $teamAInactive = $user->allChildMembers->where('is_paid', 0)->count(); // Inactive Team A
  $teamATotal = $teamACount + $teamAInactive;

  $totalPaidDownline = $user->allPiadChildsExceptSelf()->count();
  $totalUnpaidDownline = $user->allUnPaidChildsExceptSelf()->count();
  
  $teamBCCount = max(0, $user->getSecondAndThirdLevelDirects(1)->count()); // Active Team B+C
  $teamBCInactive = max(0, $user->getSecondAndThirdLevelDirects(0)->count()); // Inactive Team B+C
  $teamBCTotal = $teamBCCount + $teamBCInactive;

  // Order Stats
  $totalOrders = $user->saleEntries()->count();
  $currentLevel = $user->agentCategory();
  $todayIncome = $user->today_total_commission;
  $taskCompleted = $user->today_tasks_income > 0;
  $todayTrade = \App\Models\DailyTrade::where('user_id', $user->id)
      ->where('trade_date', now()->toDateString())
      ->first();
  $nextLevel = \App\Models\AgentCategory::where('unlock_balance', '>', $currentLevel->unlock_balance ?? 0)
      ->orderBy('unlock_balance', 'asc')
      ->first();
@endphp

{{-- ═══════════════ WELCOME BANNER ═══════════════ --}}
<div class="db-welcome-card">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
      <p class="db-welcome-sub mb-1">{{ now()->format('l, d M Y') }}</p>
      <h2 class="db-welcome-title">Welcome, {{ $user->name }}! 👋</h2>
      <p class="db-welcome-sub mb-0">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }} — have a great day ahead!</p>
    </div>
    <!-- <div class="d-flex d-md-none align-items-center gap-3">
      <a href="{{ route('account.profile') }}">
        <img src="{{ $user->profile_image_url }}" class="db-welcome-avatar rounded-circle" alt="{{ $user->name }}">
      </a>
      <div class="d-sm-block">
        <small class="db-welcome-sub d-block">Member ID</small>
        <div class="d-flex align-items-center gap-2">
          <strong class="text-white member-id-display" data-full-id="{{ $user->member_id }}">{{ substr($user->member_id, 0,3) }}****</strong>
          <i class="fa fa-eye toggle-member-id cursor-pointer" style="font-size: 0.85rem; color: rgba(255,255,255,0.7); cursor: pointer;"></i>
        </div>
      </div>
    </div> 
    <div class="d-none d-md-flex align-items-center gap-3">
      <div class="text-end d-sm-block">
        <small class="db-welcome-sub d-block">Member ID</small>
        <div class="d-flex align-items-center justify-content-end gap-2">
          <strong class="text-white member-id-display" data-full-id="{{ $user->member_id }}">{{ substr($user->member_id, 0,3) }}****</strong>
          <i class="fa fa-eye toggle-member-id cursor-pointer" style="font-size: 0.85rem; color: rgba(255,255,255,0.7); cursor: pointer;"></i>
        </div>
      </div>
      <a href="{{ route('account.profile') }}">
        <img src="{{ $user->profile_image_url }}" class="db-welcome-avatar rounded-circle" alt="{{ $user->name }}">
      </a>
    </div> -->
  </div>

  {{-- Unified Income & Referral Section inside Welcome Card --}}
  <div class="mt-4 pt-3 border-top border-light border-opacity-10">
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4">
      
      {{-- Group 1: Today's Income & Overview --}}
      <!-- <div class="d-flex align-items-center text-md-start" style="justify-content: space-between; width: 100%;">
        <div>
          <p class="db-welcome-sub mb-1" style="font-size: 0.7rem; letter-spacing: 1px; font-weight: 600; text-transform: uppercase;">Today's Income</p>
          <h3 class="text-white mb-0" style="font-weight: 800; font-size: 1.8rem;">${{ number_format($todayIncome, 2) }}</h3>
        </div>
        <a href="{{ route('member.wallet') }}" class="db-wallet-badge" style="padding: 10px 18px; font-size: 0.75rem; white-space: nowrap;">
          <i class="icon-graph me-1"></i> Overview
        </a>
      </div> -->
      
      {{-- Group 2: Integrated Referral Box --}}
      <div class="flex-grow-1 w-100">
          <div style="background: rgba(255,255,255,0.08); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); border-radius: 14px; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
              <div style="flex: 1; min-width: 0;">
                  <p  class="db-welcome-sub mb-1" style="font-size: 0.7rem; letter-spacing: 1px; font-weight: 600; text-transform: uppercase;">Your Invite Link</p>
                  <span id="referralUrl" style="font-size: 0.88rem; font-weight: 600; color: #fff; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block;">{{ route('register') }}?sponsor={{ $user->member_id }}</span>
              </div>
              <button onclick="copyReferralLink()" style="background: rgba(0, 226, 251, 0.15); color: #00e2fb; border: 1px solid rgba(0, 226, 251, 0.3); width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s;">
                  <i class="icon-docs" style="font-size: 1.1rem;"></i>
              </button>
          </div>
      </div>

    </div>
  </div>
</div>

{{-- ═══════════════ TOP STAT CARDS ═══════════════ --}}
<div class="row g-3 mb-4">
  {{-- Announcement Modal --}}
  @if(isset($announcement) && $announcement != null && !session()->has('announcement_shown'))
    <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border:none; border-radius:18px; overflow:hidden; background: #fff;">
          
          <button type="button" class="close" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#announcementModal').modal('hide');" aria-label="Close" style="position:absolute; top:12px; right:16px; z-index:15; font-size:1.5rem; text-shadow:0 2px 4px rgba(0,0,0,0.3); border:none; background:transparent;">
              <span aria-hidden="true" style="{{ $announcement->image ? 'color:#fff;' : 'color:#333;' }}">&times;</span>
          </button>

          @if($announcement->image)
            <div style="position:relative; width:100%; max-height:60vh; overflow:hidden; background:#000; display:flex; align-items:center; justify-content:center;">
              <img src="{{ $announcement->image_path }}" class="img-fluid" alt="Announcement" style="width:100%; object-fit:cover;">
              
              @if($announcement->description)
              <!-- Faint & Blurred Text Overlay -->
              <div style="position:absolute; bottom:0; left:0; width:100%; padding:20px; background:rgba(255,255,255,1); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); border-top:1px solid rgba(255,255,255,0.3);">
                <p class="mb-0" style="font-weight:700; font-size:1.05rem; text-align:center; text-shadow: 0 1px 2px rgba(255,255,255,0.5); color: black" >
                  {{ $announcement->description }}
                </p>
              </div>
              @endif
            </div>
          @else
            <!-- Text Only layout -->
            <div class="modal-body text-center p-4">
              <div class="mb-3">
                 <i class="icon-bell text-main" style="font-size:2.5rem; background:linear-gradient(135deg, #034bb3, #00b4d8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
              </div>
              <p class="mb-0 text-dark" style="font-size:1.05rem; font-weight:500;">
                 {{ $announcement->description }}
              </p>
            </div>
          @endif

          <div class="modal-footer justify-content-center border-0 p-3 pt-0">
             <button type="button" class="btn btn-main rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal" onclick="$('#announcementModal').modal('hide');" style="box-shadow: 0 4px 15px rgba(3,75,179,0.3);">OK, Got it</button>
          </div>

        </div>
      </div>
    </div>
  @endif

  
  {{-- Wallet Balance Card --}}
  <div class="col-md-3 col-6">
    <a href="{{ route('member.wallet') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(3,75,179,0.1); color: #034bb3; width: 35px; height: 35px;">
          <i class="icon-wallet" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">USDT Balance</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">${{ number_format($walletAmount, 2) }}</h3>
      </div>
    </a>
  </div>

  {{-- My Level Card --}}
  <div class="col-md-3 col-6">
    <a href="{{ route('member.levels') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="db-stat-icon" style="background: rgba(0,226,251,0.1); color: #00e2fb; width: 35px; height: 35px;">
            <i class="icon-badge" style="font-size: 1rem;"></i>
          </div>
          @if($nextLevel && $walletAmount >= $nextLevel->unlock_balance)
            <a href="{{ route('upgrade.package', encrypt($nextLevel->id)) }}" class="btn btn-sm btn-primary" style="font-size: 0.65rem; padding: 0.4rem!important;">Upgrade Now</a>
          @endif
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">My Level</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;" title="{{ $currentLevel->name ?? 'N/A' }}">{{ $currentLevel->name ?? 'N/A' }}</h3>
      </div>
    </a>
  </div>

  {{-- Locked USDT Card --}}
  <!-- <div class="col-md-3 col-6">
    <a href="{{ route('member.locking') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(245,158,11,0.1); color: #f59e0b; width: 35px; height: 35px;">
          <i class="icon-lock" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">Locked USDT</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">${{ number_format($lockedBalance, 2) }}</h3>
      </div>
    </a>
  </div> -->

  {{-- Today Total Income Card --}}
  <!-- <div class="col-md-3 col-6">
    <div class="db-stat-card">
      <div class="db-stat-icon mb-2" style="background: rgba(34,197,94,0.1); color: #22c55e; width: 35px; height: 35px;">
        <i class="icon-graph" style="font-size: 1rem;"></i>
      </div>
      <p class="db-stat-label" style="font-size: 0.65rem;">Today Income</p>
      <h3 class="db-stat-value" style="font-size: 1.2rem;">${{ number_format($todayIncome, 2) }}</h3>
    </div>
  </div> -->

  {{-- ── Second Row of Action Boxes ── --}}
  
  {{-- Daily Trade --}}
  <!-- <div class="col-md-3 col-6">
    <a href="{{ route('member.trade') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(102,102,255,0.1); color: #6666ff; width: 35px; height: 35px;">
          <i class="icon-refresh" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">Daily Trade</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">
          @if(!$currentLevel)
            <span class="text-danger">Inactive</span>
          @elseif($todayTrade && $todayTrade->status == 'completed')
            <span class="text-success">Completed</span>
          @elseif($todayTrade && $todayTrade->status == 'pending')
            <span class="text-primary">In Progress</span>
          @else
            <span class="text-warning">Pending</span>
          @endif
        </h3>
      </div>
    </a>
  </div> -->

  {{-- Trade Points --}}
  <!-- <div class="col-md-3 col-6">
    <div class="db-stat-card">
      <div class="db-stat-icon mb-2" style="background: rgba(249, 115, 22, 0.1); color: #f97316; width: 35px; height: 35px;">
        <i class="icon-star" style="font-size: 1rem;"></i>
      </div>
      <p class="db-stat-label" style="font-size: 0.65rem;">Trade Points</p>
      <h3 class="db-stat-value" style="font-size: 1.2rem;">{{ number_format($user->reward_points ?? 0, 0) }}</h3>
    </div>
  </div> -->

  {{-- Deposit --}}
  <!-- <div class="col-md-3 col-6">
    <a href="{{ route('wallet.deposit') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(16,185,129,0.1); color: #10b981; width: 35px; height: 35px;">
          <i class="icon-plus" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">Deposit</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">Add Funds</h3>
      </div>
    </a>
  </div> -->

  {{-- Withdrawal --}}
  <div class="col-md-3 col-6">
    <a href="{{ route('wallet.withdrawl') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(244,63,94,0.1); color: #f43f5e; width: 35px; height: 35px;">
          <i class="icon-cloud-upload" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">Withdrawal</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">Cash Out</h3>
      </div>
    </a>
  </div>

  {{-- Tree View --}}
  <div class="col-md-3 col-6">
    <a href="{{ route('member.tree', 1) }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(20,184,166,0.1); color: #14b8a6; width: 35px; height: 35px;">
          <i class="icon-organization" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">Tree View</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">Network</h3>
      </div>
    </a>
  </div>

  {{-- My ID Card --}}
  <!-- <div class="col-md-3 col-6">
    <a href="{{ route('id-card') }}" class="text-decoration-none">
      <div class="db-stat-card">
        <div class="db-stat-icon mb-2" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; width: 35px; height: 35px;">
          <i class="icon-user-following" style="font-size: 1rem;"></i>
        </div>
        <p class="db-stat-label" style="font-size: 0.65rem;">ID Card</p>
        <h3 class="db-stat-value" style="font-size: 1.2rem;">My Identity</h3>
      </div>
    </a>
  </div> -->


</div>

{{-- ═══════════════ INCOME & TEAM STATS ═══════════════ --}}
<div class="row g-3 mb-5">
  <div class="col-md-6 col-12">
    <div class="db-stat-card h-100">
        <div class="db-section-header">
            <h6 class="db-section-title">Income Overview</h6>
            <span class="badge" style="background: rgba(3,75,179,0.1); color: #034bb3;">Today & Total</span>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="text-muted" style="border-bottom: 1px solid #f1f5f9;">
                    <tr>
                        <th class="ps-0">Category</th>
                        <th class="text-center">Today</th>
                        <th class="text-end pe-0">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([ 'roi' => 'Profit Income', 'team' => 'IB Income'] as $key => $label)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="ps-0 py-3">
                            <span class="fw-600 text-dark">{{ $label }}</span>
                        </td>
                        <td class="text-center py-3">
                            <span class="text-success fw-bold">+${{ number_format($dailyIncomes[$key], 2) }}</span>
                        </td>
                        <td class="text-end pe-0 py-3">
                            <span class="text-dark fw-bold">${{ number_format($totalIncomes[$key], 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>

  <div class="col-md-6 col-12">
    <div class="db-stat-card h-100">
        <div class="db-section-header">
            <h6 class="db-section-title">Community & Team Stats</h6>
            <a href="{{ route('my-downline') }}" class="db-section-link">View Details &rsaquo;</a>
        </div>
        <div class="row g-3">
          <div class="col-6">
            <div class="py-3 px-2 border rounded-3 bg-light">
              <small class="text-muted d-block mb-1">Active Members</small>
              <h5 class="fw-bold text-success mb-0">{{ $teamBCCount + $teamACount  }}</h5>
            </div>
          </div>
          <div class="col-6">
            <div class="py-3 px-2 border rounded-3 bg-light">
              <small class="text-muted d-block mb-1">Inactive Members</small>
              <h5 class="fw-bold text-danger mb-0">{{ $teamBCInactive + $teamAInactive  }}</h5>
            </div>
          </div>
          <!-- <div class="col-12 mt-3">
              <div class="db-info-row">
                  <span class="key">Team A Enthusiasts</span>
                  <span class="val text-end">
                      <span class="fw-bold">{{ $teamATotal }}</span> 
                      <span class="text-muted fw-normal ms-1" style="font-size:0.75rem;">({{ $teamACount }} Active, {{ $teamAInactive }} Inactive)</span>
                  </span>
              </div>
              <div class="db-info-row">
                  <span class="key">Team B+C Enthusiasts</span>
                  <span class="val text-end">
                      <span class="fw-bold">{{ $teamBCTotal }}</span> 
                      <span class="text-muted fw-normal ms-1" style="font-size:0.75rem;">({{ $teamBCCount }} Active, {{ $teamBCInactive }} Inactive)</span>
                  </span>
              </div>
          </div> -->
        </div>
    </div>
  </div>
</div>

{{-- ═══════════════ MEMBER PACKAGES (Temporarily Hidden) ═══════════════ --}}
{{-- 
<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="db-section-header">
      <h6 class="db-section-title">Member Packages</h6>
      <span class="db-section-link">Swipe to explore &rsaquo;</span>
    </div>
  </div>

  <div class="col-12">
    <div class="packages-swiper swiper">
      <div class="swiper-wrapper">
        @foreach($agentCategories as $category)
          @php
            $balanceProgress = $category->unlock_balance > 0 ? min(100, round(($walletAmount / $category->unlock_balance) * 100, 0)) : 100;
            $teamAProgress = ($category->team_a ?? 0) > 0 ? min(100, round(($teamACount / $category->team_a) * 100, 0)) : 100;
            $teamBCProgress = ($category->team_b_c ?? 0) > 0 ? min(100, round(($teamBCCount / $category->team_b_c) * 100, 0)) : 100;
            
            $isUnlocked = ($walletAmount >= $category->unlock_balance) && 
                          ($teamACount >= ($category->team_a ?? 0)) && 
                          ($teamBCCount >= ($category->team_b_c ?? 0));
          @endphp
          <div class="swiper-slide">
            <div class="db-pkg-card h-100">
              <div class="db-pkg-header">
                <div class="swiper-button-prev" style="left: 10px; top: 50%;"></div>
                <div class="swiper-button-next" style="right: 10px; top: 50%;"></div>
                <img src="{{ $category->image_path ?? asset('images/dashboard.png') }}" alt="{{ $category->name }}">
                <h5 class="db-pkg-title">{{ strtoupper($category->name) }}</h5>
              </div>
              <div class="db-pkg-body">
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1 small fw-bold">
                    <span>Balance Required</span>
                    <span>${{ number_format($category->unlock_balance, 0) }} ({{ $balanceProgress }}%)</span>
                  </div>
                  <div class="db-progress-bar"><div class="db-progress-fill" style="width:{{ $balanceProgress }}%;"></div></div>
                </div>

                <div class="mb-4">
                  <p class="small text-muted mb-2">Team Requirements:</p>
                  <div class="d-flex justify-content-between small mb-1">
                    <span>Team A ({{ $teamACount }}/{{ $category->team_a ?? 0 }})</span>
                    <span class="text-success">{{ $teamAProgress }}%</span>
                  </div>
                  <div class="d-flex justify-content-between small">
                    <span>Team B&C ({{ $teamBCCount }}/{{ $category->team_b_c ?? 0 }})</span>
                    <span class="text-primary">{{ $teamBCProgress }}%</span>
                  </div>
                </div>

                <a href="{{ route('explore.category', encrypt($category->id)) }}"
                   class="btn btn-main w-100 py-2 rounded-pill {{ !$isUnlocked ? 'disabled' : '' }}">
                  {{ $isUnlocked ? 'Explore Now' : 'Locked Plan' }}
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
--}}


{{-- ═══════════════ QUICK ACTIONS ═══════════════ --}}
<!-- <div class="row g-3 mb-5">
    <div class="col-12">
        <h6 class="db-section-title mb-1">Quick Services</h6>
        <p class="text-muted small mb-3">Access your essential tools quickly</p>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('account.profile') }}" class="quick-action-btn">
            <i class="icon-settings text-primary"></i>
            <span>Settings</span>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('joining-pins') }}" class="quick-action-btn">
            <i class="icon-layers text-success"></i>
            <span>Joining Pins</span>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('wallet.deposit') }}" class="quick-action-btn">
            <i class="icon-plus text-info"></i>
            <span>Deposit Fund</span>
        </a>
    </div>
    <div class="col-md-3 col-6">
        <a href="{{ route('wallet.withdrawl') }}" class="quick-action-btn">
            <i class="icon-wallet text-warning"></i>
            <span>Withdraw</span>
        </a>
    </div>
</div> -->

{{-- ═══════════════ RECENT REVIEWS ═══════════════ --}}
@if($reviews->count() > 0)
<div class="mb-4">
  <div class="db-section-header mb-3">
    <h6 class="db-section-title">Recent Feedbacks</h6>
  </div>
  <div class="row g-3">
    @foreach($reviews->take(3) as $review)
    <div class="col-md-4">
      <div class="db-review-card">
        <div class="d-flex align-items-center gap-2 mb-2">
          <img src="{{ $review->user->profile_image_url ?? '' }}" class="rounded-circle" style="width:35px;height:35px;object-fit:cover;" alt="">
          <div>
            <p class="mb-0 x-small fw-bold text-dark">{{ $review->user->name ?? 'User' }}</p>
            <span class="db-star">@for($s=1; $s<=5; $s++) {{ $s <= $review->rating ? '★' : '☆' }} @endfor</span>
          </div>
        </div>
        <p class="small text-muted mb-0">{{ Str::limit($review->review, 60) }}</p>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- Sliding Check-in Panel --}}
<div id="checkinForm" class="position-fixed bg-white shadow" style="top:65px;right:-400px;width:340px;z-index:9998;transition:right 0.4s ease;overflow-y:auto;border-radius:14px 0 0 14px;">
  <div style="background: linear-gradient(135deg, #034bb3, #00b4d8); padding: 18px 20px; border-radius: 14px 0 0 0; position:relative;">
    <h6 class="text-white mb-0 fw-bold">Weekly Check-in</h6>
    <i class="icon-close text-white" id="cancelCheckin" style="position:absolute;top:16px;right:14px;cursor:pointer;font-size:1.1rem;"></i>
  </div>
  <div class="p-4">
    @php
      $dayInCycle = ($loginCount % 7 == 0) ? 7 : ($loginCount % 7);
      $totalCycles = floor($loginCount / 7);
    @endphp
    <form method="POST" action="{{ route('weekly.checkin') }}" onsubmit="ajaxFormSubmit($(this))">
      @csrf
      <div class="row g-2 justify-content-center mb-3">
        @for($i = 1; $i <= 7; $i++)
          @php $globalDayNumber = ($totalCycles * 7) + $i; $isRedeemed = in_array($globalDayNumber, $redeemedDays); $isCurrentDay = $i == $dayInCycle; @endphp
          <div class="col-3 text-center">
            <div class="card p-2" style="border-radius:10px !important; border: 2px solid {{ $isCurrentDay ? '#034bb3' : '#f1f5f9' }} !important; background: {{ $isRedeemed ? 'rgba(34,197,94,0.08)' : '#fff' }} !important;">
              <img src="{{ $isRedeemed ? asset('images/checked.png') : asset('images/daily-reward-'.$i.'.png') }}" style="height:35px; object-fit:contain;" alt="">
              <small class="d-block mt-1" style="font-size:0.65rem;">Day {{$i}}</small>
            </div>
          </div>
        @endfor
      </div>
      <button type="submit" {{ in_array($loginCount, $redeemedDays) ? 'disabled' : '' }} class="btn btn-main w-100 rounded-pill">CHECK IN NOW</button>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Show announcement modal if it exists
    if ($('#announcementModal').length) {
        $('#announcementModal').appendTo('body').modal('show');
        
        // When modal is actually closed by the user, mark it as shown in session via AJAX
        $('#announcementModal').on('hidden.bs.modal', function () {
            $.post("{{ route('announcement.dismiss') }}", {_token: "{{ csrf_token() }}"});
        });
    }

    $('#cancelCheckin').click(function() { $('#checkinForm').css('right', '-400px'); });
    new Swiper('.packages-swiper', {
      slidesPerView: 1, spaceBetween: 0, loop: false,
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
    });

    // Toggle Member ID Visibility
    $('.toggle-member-id').on('click', function() {
        const firstDisplay = $('.member-id-display').first();
        const fullIdExample = firstDisplay.data('full-id');
        const maskedIdExample = fullIdExample.toString().substring(0,3) + '****' ;
        const isCurrentlyMasked = firstDisplay.text() === maskedIdExample;
        
        $('.member-id-display').each(function() {
            const display = $(this);
            const fullId = display.data('full-id');
            const maskedId = fullId.toString().substring(0,3) + '****';
            
            if (isCurrentlyMasked) {
                display.text(fullId);
            } else {
                display.text(maskedId);
            }
        });

        if (isCurrentlyMasked) {
            $('.toggle-member-id').removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $('.toggle-member-id').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
  });

  function copyReferralLink() {
    const url = document.getElementById('referralUrl').innerText;
    const tempInput = document.createElement('input');
    tempInput.value = url;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    // UI Feedback via Toaster (Available in main layout)
    if (typeof toasterMessage === 'function') {
        toasterMessage('success', 'Referral Link Copied!');
    } else {
        alert('Referral Link Copied!');
    }
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endsection
