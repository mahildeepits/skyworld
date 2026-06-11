@extends('layout.main')

@section('css')
<style>
    /* ── Levels Page Styles ── */
    .levels-header {
        background: linear-gradient(135deg, #07122e 0%, #0c2260 55%, #034bb3 100%);
        border-radius: 0 0 30px 30px;
        padding: 40px 20px;
        text-align: center;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(3,75,179,0.2);
    }
    .levels-header h2 { font-weight: 800; color: #fff; margin-bottom: 10px; }
    .levels-header p { opacity: 0.8; font-size: 0.9rem; }

    .level-card {
        background: #fff;
        border-radius: 24px;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 2px solid transparent;
        overflow: hidden;
        transition: all 0.3s;
        position: relative;
    }
    
    /* ── Current/Active Level ── */
    .level-card.current-level {
        border-color: #22c55e;
        transform: scale(1.02);
        box-shadow: 0 15px 45px rgba(34, 197, 94, 0.15);
        z-index: 5;
    }
    .level-card.current-level .level-card-header { background: #f0fdf4; }
    
    /* ── Past Levels ── */
    .level-card.past-level {
        opacity: 0.7;
        border-color: #f1f5f9;
        filter: grayscale(0.4);
    }
    
    /* ── Locked Levels ── */
    .level-card.locked-level {
        opacity: 0.85;
        border: 2px dashed #e2e8f0;
        background: #fafafa;
    }
    .level-card.locked-level .benefit-icon { color: #94a3b8; }
    .level-card.locked-level .req-item { border-style: dashed; }
    
    .level-card-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .level-info-header { display: flex; align-items: center; gap: 15px; }
    .level-icon-box {
        width: 50px; height: 50px;
        border-radius: 14px;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .level-icon-box img { width: 32px; height: 32px; object-fit: contain; }
    .level-icon-box i { font-size: 1.5rem; color: #034bb3; }
    
    .level-title-text h4 { margin: 0; font-weight: 800; color: #1e293b; font-size: 1.2rem; }
    .level-status-pill {
        font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 50px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .pill-current { background: #dcfce7; color: #166534; }
    .pill-past { background: #f1f5f9; color: #64748b; }
    .pill-locked { background: #fee2e2; color: #991b1b; }
    
    .level-card-body { padding: 25px; }
    
    .section-label {
        font-size: 0.75rem; font-weight: 800; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-label::after { content: ''; flex-grow: 1; height: 1px; background: #f1f5f9; }

    /* Requirements Grid */
    .req-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 25px; }
    .req-item {
        background: #fff; border: 1px solid #f1f5f9; border-radius: 16px; padding: 15px;
        display: flex; flex-direction: column; gap: 8px;
    }
    .req-item-label { font-size: 0.7rem; color: #64748b; font-weight: 600; }
    .req-item-value { font-size: 1rem; font-weight: 700; color: #0f172a; }
    
    .progress-minimal { height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
    .progress-minimal-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg, #034bb3, #00e2fb); }
    .req-unmet { color: #ef4444 !important; }
    .req-met { color: #22c55e !important; }

    /* Benefits Grid */
    .benefit-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .benefit-item {
        background: #f8fafc; border-radius: 14px; padding: 12px 15px;
        display: flex; align-items: center; gap: 12px;
    }
    .benefit-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: #fff; display: flex; align-items: center; justify-content: center;
        color: #034bb3; font-size: 0.9rem; border: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .benefit-text { line-height: 1.2; }
    .benefit-text span { display: block; font-size: 0.65rem; color: #64748b; font-weight: 600; }
    .benefit-text strong { font-size: 0.85rem; color: #1e293b; font-weight: 700; }
    
    .current-badge {
        position: absolute; top: 15px; right: -30px;
        background: #22c55e; color: #fff; font-size: 0.65rem; font-weight: 800;
        padding: 5px 35px; transform: rotate(45deg);
        box-shadow: 0 4px 10px rgba(34,197,94,0.2);
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="levels-header">
    <div class="container">
        <h2>Growth Path</h2>
        <p>Monitor your progress and unlock high-yield levels by meeting the milestones.</p>
    </div>
</div>

<div class="container pb-5">
    @php
        $isFoundCurrent = false;
    @endphp

    @foreach($agentCategories as $category)
            @php
                $isPast = false;
                $isCurrent = false;
                $isLocked = true;
                
                // Refined Logic
                if($currentLevel) {
                    if(!$isFoundCurrent) {
                        if($currentLevel->id == $category->id) {
                            $isCurrent = true;
                            $isLocked = false;
                            $isFoundCurrent = true;
                        } else {
                            $isPast = true;
                            $isLocked = false;
                        }
                    }
                } else {
                    // If user has NO level, then ALL levels are locked
                    $isLocked = true;
                }
                
                // Progress calculations (still useful to show how close user is even if locked)
                $progressBal = $category->unlock_balance > 0 ? min(100, ($walletAmount / $category->unlock_balance) * 100) : 100;
                $progressPoints = ($category->required_points ?? 0) > 0 ? min(100, (($user->reward_points ?? 0) / $category->required_points) * 100) : 100;
                $progressA   = ($category->team_a ?? 0) > 0 ? min(100, ($teamACount / $category->team_a) * 100) : 100;
                $progressBC  = ($category->team_b_c ?? 0) > 0 ? min(100, ($teamBCCount / $category->team_b_c) * 100) : 100;
                
                $cardClass = $isCurrent ? 'current-level' : ($isPast ? 'past-level' : 'locked-level');
                $statusLabel = $isCurrent ? 'Active Now' : ($isPast ? 'Achieved' : 'Locked');
                $statusPill = $isCurrent ? 'pill-current' : ($isPast ? 'pill-past' : 'pill-locked');
            @endphp

            <div class="level-card {{ $cardClass }}">
                @if($isCurrent)
                    <div class="current-badge">ACTIVE</div>
                @endif
                
                <div class="level-card-header">
                    <div class="level-info-header">
                        <div class="level-icon-box">
                            @if($category->image)
                                <img src="{{ $category->image_path }}" alt="{{ $category->name }}">
                            @else
                                <i class="icon-trophy"></i>
                            @endif
                        </div>
                        <div class="level-title-text">
                            <h4>{{ $category->name }}</h4>
                            <span class="level-status-pill {{ $statusPill }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                    <div>
                       <i class="{{ $isCurrent ? 'icon-check text-success' : ($isPast ? 'icon-check text-muted' : 'icon-lock text-danger') }}" style="font-size: 1.2rem;"></i>
                    </div>
                </div>

                <div class="level-card-body">
                    {{-- Requirements Section --}}
                    <div class="section-label">Unlock Requirements</div>
                    <div class="req-grid">
                        {{-- Balance --}}
                        <div class="req-item">
                            <span class="req-item-label">Wallet Balance</span>
                            <span class="req-item-value {{ $walletAmount >= $category->unlock_balance ? 'req-met' : 'req-unmet' }}">
                                ${{ number_format($walletAmount, 0) }} / ${{ number_format($category->unlock_balance, 0) }}
                            </span>
                            <div class="progress-minimal">
                                <div class="progress-minimal-fill" style="width: {{ $progressBal }}%;"></div>
                            </div>
                        </div>

                        {{-- Points --}}
                        @if(($category->required_points ?? 0) > 0)
                        <div class="req-item">
                            <span class="req-item-label">Required Points</span>
                            <span class="req-item-value {{ ($user->reward_points ?? 0) >= $category->required_points ? 'req-met' : 'req-unmet' }}">
                                {{ number_format($user->reward_points ?? 0, 0) }} / {{ number_format($category->required_points, 0) }}
                            </span>
                            <div class="progress-minimal">
                                <div class="progress-minimal-fill" style="width: {{ $progressPoints }}%;"></div>
                            </div>
                        </div>
                        @endif
                    
                    {{-- Team A --}}
                    @if($category->team_a > 0)
                    <div class="req-item">
                        <span class="req-item-label">Team A Active</span>
                        <span class="req-item-value {{ $teamACount >= $category->team_a ? 'req-met' : 'req-unmet' }}">
                            {{ $teamACount }} / {{ $category->team_a }}
                        </span>
                        <div class="progress-minimal">
                            <div class="progress-minimal-fill" style="width: {{ $progressA }}%;"></div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Team B & C --}}
                    @if($category->team_b_c > 0)
                    <div class="req-item">
                        <span class="req-item-label">Team B & C Total</span>
                        <span class="req-item-value {{ $teamBCCount >= $category->team_b_c ? 'req-met' : 'req-unmet' }}">
                            {{ $teamBCCount }} / {{ $category->team_b_c }}
                        </span>
                        <div class="progress-minimal">
                            <div class="progress-minimal-fill" style="width: {{ $progressBC }}%;"></div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Benefits Section --}}
                <div class="section-label">Level Rewards & Benefits</div>
                <div class="benefit-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-graph"></i></div>
                        <div class="benefit-text">
                            <span>Trade Profit Rate</span>
                            <strong>{{ $category->massive_order_rate }}% / Day</strong>
                        </div>
                    </div>
                    <!-- <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-layers"></i></div>
                        <div class="benefit-text">
                            <span>Daily Workload</span>
                            <strong>{{ $category->daily_order_limit }} Tasks</strong>
                        </div>
                    </div> -->
                    <!-- <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-share"></i></div>
                        <div class="benefit-text">
                            <span>Referral Bonus</span>
                            <strong>{{ $category->community_bonus_rate }}% Rate</strong>
                        </div>
                    </div> -->
                    @if($category->level_upgrade_income)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-arrow-up-circle"></i></div>
                        <div class="benefit-text">
                            <span>Upgrade Bonus</span>
                            <strong>${{ number_format($category->level_upgrade_income, 2) }}</strong>
                        </div>
                    </div>
                    @endif
                    
                    @if($category->team_a_profit)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-people"></i></div>
                        <div class="benefit-text">
                            <span>Team A Profit</span>
                            <strong>{{ $category->team_a_profit }}%</strong>
                        </div>
                    </div>
                    @endif
                    
                    @if($category->team_b_profit)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-people"></i></div>
                        <div class="benefit-text">
                            <span>Team B Profit</span>
                            <strong>{{ $category->team_b_profit }}%</strong>
                        </div>
                    </div>
                    @endif

                    @if($category->team_c_profit)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-people"></i></div>
                        <div class="benefit-text">
                            <span>Team C Profit</span>
                            <strong>{{ $category->team_c_profit }}%</strong>
                        </div>
                    </div>
                    @endif
                    
                    @if($category->valid_downline)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="icon-user-following"></i></div>
                        <div class="benefit-text">
                            <span>Total Requirement</span>
                            <strong>{{ $category->valid_downline }} Members</strong>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($category->description)
                <div class="mt-4 p-3 bg-light rounded-4 small text-muted border-start border-primary border-4">
                    <i class="icon-info me-2"></i> {{ $category->description }}
                </div>
                @endif
                
                @if($isLocked)
                <div class="mt-4">
                    <button class="btn btn-outline-danger w-100 rounded-pill disabled" style="font-size: 0.8rem; font-weight: 700;">
                        <i class="icon-lock me-2"></i> LEVEL LOCKED
                    </button>
                </div>
                @elseif($isPast)
                <div class="mt-4">
                    <button class="btn btn-outline-secondary w-100 rounded-pill disabled" style="font-size: 0.8rem; font-weight: 700;">
                        <i class="icon-check me-2"></i> PREVIOUS LEVEL
                    </button>
                </div>
                @else
                <div class="mt-4">
                    <button class="btn btn-success w-100 rounded-pill" style="font-size: 0.8rem; font-weight: 700; background: linear-gradient(90deg, #22c55e, #16a34a);">
                        <i class="icon-energy me-2"></i> ACTIVE LEVEL
                    </button>
                </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
