@extends('layout.main')
@section('content')
@php
$route = 'wallet.withdrawl';
$method = 'post';
$user = authUser();
$userWallets = $user->wallet_addresses ?? [];
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
                                        <label for="bank_details">Bank Details <span class="text-danger">*</span></label>
                                        <textarea class="form-control editable" id="bank_details" rows="3" readonly disabled>A/C: {{ $bankDetails->account_number ?? 'N/A' }}
IFSC: {{ $bankDetails->ifsc_code ?? 'N/A' }}
Bank: {{ $bankDetails->bank_name ?? 'N/A' }}
Name: {{ $bankDetails->account_holder_name ?? 'N/A' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input type="text" name="amount" class="form-control" id="amount" placeholder="0.00">
                                        <div class="d-flex justify-content-between mt-1">
                                            <span><small>Available Balance: <b>{{ $availableBalance ?? '0.00' }} USDT </b> </small></span>
                                            @if($lockedTrading > 0)
                                                <span><small class="text-danger">Trading Locked: <b>{{ $lockedTrading }} USDT</b></small></span>
                                            @endif
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <!-- <div class="col-12 mb-2">
                                    <div class="alert alert-warning py-2 mb-0" style="border-radius: 12px; border: 1px dashed #f5a623; background-color: #fff9f0;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="small fw-bold text-dark">Remaining Withdrawal Limit:</div>
                                            <div class="small fw-bold text-dark">${{ number_format($user->getRemainingWithdrawalLimit(), 2) }} / ${{ number_format($user->getWithdrawalLimit(), 2) }}</div>
                                        </div>
                                        <div class="progress mt-1" style="height: 6px; background-color: #e9ecef;">
                                            @php
                                                $limit = $user->getWithdrawalLimit();
                                                $withdrawn = $user->getTotalWithdrawals();
                                                $percentage = $limit > 0 ? (min(100, ($withdrawn / $limit) * 100)) : 100;
                                            @endphp
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="mt-1 d-flex justify-content-between align-items-center">
                                             <small class="text-muted" style="font-size: 11px;">Total Deposits: ${{ number_format($user->getTotalDeposits(), 2) }}</small>
                                             <small class="text-muted" style="font-size: 11px;">Paid Directs: {{ $user->getPaidDirectsCount() }}</small>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-6">
                                    <div class="form-group my-0">
                                        <p class="m-0 py-2"><b>TDS ( 6% )</b></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-0">
                                        <input type="hidden" name="transaction_fees" value="" class="transaction_fee" />
                                        <input type="text" class="text-end form-control-plaintext transaction_fee py-2" style="font-weight: 600;" id="transaction_fee" value="0.00" placeholder="0.00" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-0">
                                        <p class="m-0 py-2"><b>You Will Receive</b></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-0">
                                        <input type="hidden" value="" name="total_amount" class="total_amount"/>
                                        <input type="text" class="text-end form-control-plaintext total_amount py-2" style="font-weight: 600;" value="0.00" id="total_amount" placeholder="0.00" disabled >
                                    </div>
                                </div>

                                <!-- Verification Section -->
                                <div class="col-12 mt-3">
                                    <h6>Verification Required</h6>
                                    <div class="form-group my-2">
                                        <label>Email OTP <span class="text-danger">*</span></label>
                                        <div class="input-group mt-1">
                                            <input type="text" name="email_otp" class="form-control" placeholder="Enter OTP">
                                            <button type="button" class="btn btn-outline-primary" id="send-otp-btn">Send OTP</button>
                                        </div>
                                    </div>
                                    

                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-main w-100 text-white">Withdraw Now</button>
                                </div>
                                <div class="col-12 mt-3" >
                                    <ul class="p-3" style="list-style: none; font-weight:500; padding-inline-start: 0;border-radius: 10px;background-color: #b1b0f9d4;">
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Withdrawal Process Takes 24-72 Hours</p></li>
                                        <!-- <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Withdrawal can be done after 3 days of activation.</p></li> -->

                                        <!-- <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> KYC is mandatory; please update your KYC documents first (available 3 days after activation).</p></li> -->
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> The Minimum Withdrawal Amount Is 50 USDT</p></li>
                                        @if(isset($maxSingleLimit) && $maxSingleLimit > 0)
                                            <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Max withdrawal per transaction for your level (<b>{{ $activeCategory->name }}</b>) is <b>{{ $maxSingleLimit }} USDT</b></p></li>
                                        @endif
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> TDS Deduction Is 6% on all withdrawals</p></li>
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> User will be eligible for only 5 transactions per month</p></li>
                                        <!-- <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Trading principal and income are locked if a trade is pending today.</p></li> -->
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Max withdrawal is 2x of your total deposits. Each paid direct referral increases your limit by 1x of your deposit amount.</p></li>
                                    </ul>
                                </div>
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
    $(document).on('keyup','#amount',function(){
        let amount = parseFloat($(this).val()) || 0;
        let percentage = parseFloat($('#transaction_fees_percentage').val()) || 0;
        let transaction_fee = (amount * percentage).toFixed(2);
        let total_amount = (amount - transaction_fee).toFixed(2);
        $('.transaction_fee').val(transaction_fee).trigger('change');
        $('#transaction_fee').val(transaction_fee);
        $('.total_amount').val(total_amount).trigger('change');
        $('#total_amount').val(total_amount);
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
