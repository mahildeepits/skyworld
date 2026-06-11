@extends('layout.main')

@section('css')
<style>
    .trade-card {
        background: linear-gradient(135deg, #07122e 0%, #0c2260 55%, #034bb3 100%);
        border-radius: 20px !important;
        padding: 40px !important;
        position: relative;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 10px 40px rgba(3,75,179,0.3) !important;
    }
    .trade-card::before {
        content: '';
        position: absolute; top: -20%; right: -10%;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(0,226,251,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .trade-card::after {
        content: '';
        position: absolute; bottom: -20%; left: -5%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(3,75,179,0.2) 0%, transparent 70%);
        pointer-events: none;
    }
    .trade-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        backdrop-filter: blur(10px);
        margin-bottom: 20px;
    }
    .status-pending { background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.4); color: #f59e0b; }
    .status-completed { background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.4); color: #22c55e; }
    .status-ready { background: rgba(0, 226, 251, 0.2); border: 1px solid rgba(0, 226, 251, 0.4); color: #00e2fb; }
    .status-locked { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #ef4444; }

    .trade-stat-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    .trade-stat-label { font-size: 0.75rem; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .trade-stat-value { font-size: 1.5rem; font-weight: 700; }

    .btn-trade {
        background: linear-gradient(90deg, #00e2fb, #034bb3);
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        color: #fff;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 8px 25px rgba(0, 226, 251, 0.4);
        transition: all 0.3s;
    }
    .btn-trade:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 226, 251, 0.6);
    }
    .btn-trade:disabled {
        background: #475569;
        box-shadow: none;
        opacity: 0.6;
        cursor: not-allowed;
    }

    .trade-progress {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin: 30px 0;
    }
    .trade-progress-inner {
        height: 100%;
        background: linear-gradient(90deg, #00e2fb, #034bb3);
        width: 0%;
        transition: width 1s linear;
    }

    /* Loader Overlay */
    #tradeLoaderOverlay {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(5, 15, 50, 0.94);
        z-index: 99999; /* Higher than sidebar (1100) */
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        color: white;
        text-align: center;
    }
    .loader-content {
        max-width: 450px;
        width: 100%;
        padding: 20px;
    }
    .trading-animation-box {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto 30px;
    }
    .loader-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 50%; /* Make it circular */
        box-shadow: 0 0 50px rgba(0, 226, 251, 0.3);
        animation: rotateHUD 12s linear infinite;
    }
    @keyframes rotateHUD {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .trading-animation-box::after {
        content: '';
        position: absolute;
        top: -15px; left: -15px; right: -15px; bottom: -15px;
        border: 2px dashed rgba(0, 226, 251, 0.2);
        border-radius: 50%;
        animation: rotateHUD 20s linear infinite reverse;
    }
    .loader-timer {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #00e2fb, #034bb3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 30px rgba(0, 226, 251, 0.4);
    }

    /* Pulse animation for trading */
    @keyframes pulse-trading {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 226, 251, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(0, 226, 251, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 226, 251, 0); }
    }
    .trading-active { animation: pulse-trading 2s infinite; }

    .trade-guidelines {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        margin-top: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="trade-card text-center">
                @if(!$isEligible)
                    <div class="trade-status-badge status-locked">
                        <i class="icon-lock"></i> INELIGIBLE
                    </div>
                    <h2 class="mb-3">Trade Feature Locked</h2>
                    <p class="mb-4 opacity-75">You need an active package and a minimum deposit of $50 to participate in Daily Trade.</p>
                @elseif($todayTrade && $todayTrade->status == 'completed')
                    <div class="trade-status-badge status-completed">
                        <i class="icon-check"></i> TRADE COMPLETED
                    </div>
                    <h2 class="mb-3">Daily Trade Finished</h2>
                    <p class="mb-4 opacity-75">Your profit has been credited to your wallet. Come back tomorrow!</p>
                @elseif($todayTrade && $todayTrade->status == 'pending')
                    <div class="trade-status-badge status-pending">
                        <i class="icon-clock"></i> TRADE IN PROGRESS
                    </div>
                    <h2 class="mb-3">Market Analysis Active...</h2>
                    <p class="mb-4 opacity-75">Our AI is analyzing market liquidity and signal patterns for your trade. Please wait for completion.</p>
                    <div class="trade-progress">
                        <div class="trade-progress-inner" id="innerProgressBar" style="width: 0%;"></div>
                    </div>
                @else
                    <div class="trade-status-badge status-ready">
                        <i class="icon-rocket"></i> MARKET READY
                    </div>
                    <h2 class="mb-2">Start Your Daily Trade</h2>
                    <p class="mb-4 opacity-75">Click the button below to execute today's automated trade based on your wallet balance.</p>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="trade-stat-box h-100">
                            <div class="trade-stat-label">Trading Base</div>
                            <div class="trade-stat-value">${{ number_format($tradingBalance, 2) }}</div>
                            <small class="opacity-50 x-small" style="font-size: 0.65rem;">
                                @if($limit && $totalWalletBalance > $limit)
                                    (Capped at ${{ number_format($limit, 0) }})
                                @else
                                    (Current Wallet Balance)
                                @endif
                            </small>
                        </div>
                    </div>
                    <!-- <div class="col-6 col-md-3">
                        <div class="trade-stat-box h-100">
                            <div class="trade-stat-label">Total Wallet</div>
                            <div class="trade-stat-value">${{ number_format($totalWalletBalance, 2) }}</div>
                            <small class="opacity-50 x-small" style="font-size: 0.65rem;">(Inc. Other Incomes)</small>
                        </div>
                    </div> -->
                    <div class="col-6 col-md-4">
                        <div class="trade-stat-box h-100">
                            <div class="trade-stat-label">Daily Rate</div>
                            <div class="trade-stat-value text-info">{{ number_format($massiveOrderRate, 2) }}%</div>
                            <small class="opacity-50 x-small" style="font-size: 0.65rem;">({{ $agentCategory->name ?? '' }})</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="trade-stat-box h-100">
                            <div class="trade-stat-label">Est. Profit</div>
                            <div class="trade-stat-value text-success">${{ number_format(($tradingBalance * $massiveOrderRate / 100), 2) }}</div>
                            <small class="opacity-50 x-small" style="font-size: 0.65rem;">(Today's Earning)</small>
                        </div>
                    </div>
                </div>

                @if($isEligible && !$todayTrade)
                    <form action="{{ route('member.trade.start') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-trade">Start Trading Now</button>
                    </form>
                @else
                    <button class="btn btn-trade" style="font-size:1.2rem!important;" disabled>
                        @if(!$isEligible)
                            Locked
                        @elseif($todayTrade->status == 'completed')
                            <span id="nextTradeCountdown">Processing...</span>
                        @elseif($todayTrade->status == 'pending')
                            <span id="processingTimer">Analysing Market...</span>
                        @endif
                    </button>
                @endif
            </div>

            <!-- Trade Loader Overlay -->
            <div id="tradeLoaderOverlay">
                <div class="loader-content">
                    <div class="trading-animation-box">
                        <img src="{{ asset('circular_trading_loader.png') }}" alt="Analysing" class="loader-image">
                    </div>
                    <div class="loader-timer" id="overlayCountdown">40</div>
                    <h3 class="fw-bold mb-2">Executing Smart Trade</h3>
                    <p class="opacity-75">System is matching order signals with liquidity pools. Please do not close or refresh this page.</p>
                </div>
            </div>

            <div class="trade-guidelines">
                <h5 class="fw-bold mb-3"><i class="icon-info me-2 text-primary"></i> Trade Guidelines</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex gap-2">
                        <i class="icon-check text-success mt-1"></i>
                        <span>Trades can be performed once every 24 hours.</span>
                    </li>
                    <li class="mb-2 d-flex gap-2">
                        <i class="icon-check text-success mt-1"></i>
                        <span>Profit is calculated based on your **Total Wallet Balance** at the time of trade closing.</span>
                    </li>
                    <li class="mb-2 d-flex gap-2">
                        <i class="icon-check text-success mt-1"></i>
                        <span>Your daily profit rate is determined by your active Agent Category package.</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="icon-check text-success mt-1"></i>
                        <span>Holding your profits in the wallet increases the base amount for the next day's trade (Compounding).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Midnight Countdown for next trade
        const nextTradeTimer = document.getElementById('nextTradeCountdown');
        if (nextTradeTimer) {
            function updateNextTradeTimer() {
                const now = new Date();
                const midnight = new Date();
                midnight.setHours(24, 0, 0, 0);

                let diff = midnight.getTime() - now.getTime();
                if (diff <= 0) {
                    nextTradeTimer.innerHTML = 'Available Now';
                    location.reload();
                    return;
                }

                let h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                let s = Math.floor((diff % (1000 * 60)) / 1000);

                h = (h < 10) ? "0" + h : h;
                m = (m < 10) ? "0" + m : m;
                s = (s < 10) ? "0" + s : s;

                nextTradeTimer.innerHTML = `<i class="icon-clock me-1"></i> ` + h + `:` + m + `:` + s;
            }
            updateNextTradeTimer();
            setInterval(updateNextTradeTimer, 1000);
        }

        // 2. Processing Countdown (40 seconds logic)
        @if(isset($remainingSeconds) && $remainingSeconds > 0)
            let timeLeft = {{ $remainingSeconds }};
            const overlay = document.getElementById('tradeLoaderOverlay');
            const overlayTimer = document.getElementById('overlayCountdown');
            const processingBtnTimer = document.getElementById('processingTimer');
            const progressBar = document.getElementById('innerProgressBar');

            // Show overlay
            overlay.style.display = 'flex';

            function updateProcessingTimer() {
                if (timeLeft <= 0) {
                    overlayTimer.innerHTML = '0';
                    completeTrade();
                    return;
                }

                overlayTimer.innerHTML = timeLeft;
                if (processingBtnTimer) processingBtnTimer.innerHTML = `Wait ${timeLeft}s`;
                
                // Update progress bar
                let percent = ((40 - timeLeft) / 40) * 100;
                if (progressBar) progressBar.style.width = percent + '%';

                timeLeft--;
                setTimeout(updateProcessingTimer, 1000);
            }

            function completeTrade() {
                overlayTimer.innerHTML = 'Finalizing...';
                
                fetch('{{ route("member.trade.complete") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonText: 'Great'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    location.reload();
                });
            }

            updateProcessingTimer();
        @endif
    });
</script>
@endsection
