@extends('layout.main')
@section('css')
<style>
    .bank-details-card {
        background: linear-gradient(135deg, #fbfcfd 0%, #f1f4f8 100%);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .bank-details-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05) !important;
        transform: translateY(-1px);
    }
    .guideline-box {
        border-radius: 12px;
        background-color: #f8fafc;
        border-left: 4px solid var(--blue);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
</style>
@endsection

@section('content')
@php
$route = 'wallet.withdrawl';
$method = 'post';
$user = authUser();
$userWallets = $user->wallet_addresses ?? [];
$unsettledROI = $user->getCurrentMonthAccumulatedROI();
$unsettledLevelROI = $user->getCurrentMonthAccumulatedLevelROI();
$totalUnsettled = $unsettledROI + $unsettledLevelROI;
@endphp

    <x-page-breadcrumb current-page='Withdrawl' sub-menu='Wallet' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card" >
                <div class="card-body">
                    <div style="max-width: 500px; margin: 0 auto;">
                        <form action="{{ route($route) }}" id="withdrawal-form" method="post">
                            @csrf
                            <input type="hidden" name="transaction_fees_percentage" id="transaction_fees_percentage" value="{{ $transaction_fees_percentage }}" />
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <label class="form-label mb-2 font-weight-bold text-dark">Wallet Address (BEP-20) <span class="text-danger">*</span></label>
                                        @if(isset($userWallets['BEP-20']) && !empty($userWallets['BEP-20']))
                                            <div class="bank-details-card px-2 py-1 mb-2" style="border-radius: 8px;">
                                                <!-- <div class="d-flex align-items-center mb-3">
                                                    <div class="bank-icon-wrapper d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 40px; height: 40px; border: 1px solid #e2e8f0; color: var(--blue);">
                                                        <i class="fa fa-wallet" style="font-size: 18px;"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.95rem;">BEP-20 Wallet</h6>
                                                        <small class="text-muted">USDT Withdrawal Address</small>
                                                    </div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="font-weight-bold text-dark text-break" style="font-size: 0.85rem;">{{ $userWallets['BEP-20'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-danger d-flex align-items-center p-3" style="border-radius: 12px; border: 1px dashed #dc3545; background-color: #fdf2f2;">
                                                <i class="fa fa-exclamation-triangle mr-2 text-danger" style="font-size: 18px;"></i>
                                                <div class="text-dark small">
                                                    <strong>No BEP-20 Wallet Address found!</strong> Please update your wallet address in your profile settings before making withdrawals.
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label for="amount" class="font-weight-bold text-dark mb-0">Amount <span class="text-danger">*</span></label>
                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" id="max-amount-btn" style="font-size: 0.72rem; border-radius: 4px; line-height: 1.5;">Use Max</button>
                                        </div>
                                        <input type="text" name="amount" class="form-control" id="amount" placeholder="0.00" style="font-weight: 600;">
                                        <div class="d-flex justify-content-between mt-1">
                                            <span>
                                                <small class="text-muted">Available Balance: <b class="text-dark">{{ $availableBalance ?? '0.00' }} USDT </b> </small>
                                                @if($totalUnsettled > 0)
                                                    <br/><small class="text-muted text-wrap font-italic" style="font-size: 0.72rem; line-height: 1.1; display: inline-block; max-width: 280px;"><i class="fa fa-info-circle"></i> Excludes ${{ number_format($totalUnsettled, 2) }} unsettled monthly ROI & Level commissions</small>
                                                @endif
                                            </span>
                                            @if($lockedTrading > 0)
                                                <span><small class="text-danger">Trading Locked: <b>{{ $lockedTrading }} USDT</b></small></span>
                                            @endif
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-12 my-3">
                                    <div class="p-3" style="background-color: #fafbfc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted" style="font-size: 0.85rem;">Requested Amount</span>
                                            <span class="font-weight-bold text-dark" id="summary_requested" style="font-size: 0.85rem;">0.00 USDT</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted" style="font-size: 0.85rem;">TDS Deduction ({{ $transaction_fees_percentage * 100 }}%)</span>
                                            <span class="text-danger font-weight-bold" style="font-size: 0.85rem;">- <span id="summary_fee">0.00</span> USDT</span>
                                        </div>
                                        <hr class="my-2" style="border-top: 1px dashed #ced4da;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">You Will Receive</span>
                                            <span class="text-success font-weight-bold" style="font-size: 1.1rem;"><span id="summary_receive">0.00</span> USDT</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden fields for submission compatibility -->
                                    <input type="hidden" name="transaction_fees" value="" class="transaction_fee" />
                                    <input type="hidden" value="" name="total_amount" class="total_amount"/>
                                </div>

                                <!-- Verification Section -->
                                <div class="col-12 mt-2">
                                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">Verification Required</h6>
                                    <div class="form-group my-2">
                                        <label class="text-muted small mb-1">Email OTP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="email_otp" class="form-control" placeholder="Enter OTP">
                                            <div class="input-group-append">
                                                <button type="button" class="btn text-white px-4 font-weight-bold" id="send-otp-btn" style="background-color: var(--blue); border: none;">Send OTP</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-main w-100 text-white py-2" style="font-size: 1rem; border-radius: 9px;">Withdraw Now</button>
                                </div>
                                
                                <!-- <div class="col-12 mt-3">
                                    <div class="guideline-box p-3">
                                        <h6 class="mb-2 text-dark font-weight-bold" style="font-size: 0.9rem;">
                                            <i class="fa fa-info-circle mr-1 text-primary"></i> Withdrawal Guidelines
                                        </h6>
                                        <ul class="pl-3 mb-0 text-muted" style="font-size: 0.82rem; line-height: 1.6;">
                                            <li class="mb-1">Withdrawal Process Takes 24-72 Hours</li>
                                            <li class="mb-1">The Minimum Withdrawal Amount Is 10 USDT</li>
                                            @if(isset($maxSingleLimit) && $maxSingleLimit > 0)
                                                <li class="mb-1">Max withdrawal per transaction for your level (<b>{{ $activeCategory->name }}</b>) is <b>{{ $maxSingleLimit }} USDT</b></li>
                                            @endif
                                            <li class="mb-1">TDS Deduction Is 6% on all withdrawals</li>
                                            <li class="mb-1">User will be eligible for only 5 transactions per month</li>
                                            <li class="mb-1">Max withdrawal is 2x of your total deposits. Each paid direct referral increases your limit by 1x of your deposit amount.</li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handle amount changes
    function updateCalculations() {
        let amount = parseFloat($('#amount').val()) || 0;
        let percentage = parseFloat($('#transaction_fees_percentage').val()) || 0;
        let transaction_fee = (amount * percentage).toFixed(2);
        let total_amount = (amount - transaction_fee).toFixed(2);
        
        $('.transaction_fee').val(transaction_fee).trigger('change');
        $('#transaction_fee').val(transaction_fee);
        $('.total_amount').val(total_amount).trigger('change');
        $('#total_amount').val(total_amount);

        // Update premium summary layout
        $('#summary_requested').text(amount.toFixed(2) + ' USDT');
        $('#summary_fee').text(transaction_fee);
        $('#summary_receive').text(total_amount);
    }

    $(document).on('keyup change', '#amount', updateCalculations);

    // Max amount button click handler
    $(document).on('click', '#max-amount-btn', function() {
        let available = parseFloat("{{ $availableBalance ?? 0 }}") || 0;
        let maxLimit = parseFloat("{{ $maxSingleLimit ?? 0 }}") || 0;
        let targetAmount = available;
        if (maxLimit > 0 && targetAmount > maxLimit) {
            targetAmount = maxLimit;
        }
        $('#amount').val(targetAmount);
        updateCalculations();
    });

    $(document).ready(function(){
        $('#send-otp-btn').on('click', function() {
            let btn = $(this);
            btn.prop('disabled', true).text('Sending...');
            $.get("{{ route('send.otp') }}", function(response) {
                if (response.status) {
                    alert('OTP sent to your email successfully!');
                    let countdown = 60;
                    let timer = setInterval(function() {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(timer);
                            btn.prop('disabled', false).text('Resend OTP');
                        } else {
                            btn.text('Resend in ' + countdown + 's');
                        }
                    }, 1000);
                } else {
                    alert('Failed to send OTP. Please try again.');
                    btn.prop('disabled', false).text('Send OTP');
                }
            }).fail(function() {
                alert('An error occurred. Please try again.');
                btn.prop('disabled', false).text('Send OTP');
            });
        });
    });

    $('#withdrawal-form').on('submit', function(e){
        e.preventDefault();
        ajaxFormSubmit($(this)); 
    });
</script>
@endsection
