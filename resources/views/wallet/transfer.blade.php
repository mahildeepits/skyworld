@extends('layout.main')
@section('content')
@php
$route = 'wallet.transfer';
$method = 'post';
$user = authUser();
@endphp

<x-page-breadcrumb current-page='Transfer' sub-menu='Wallet' />
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="max-width: 500px; margin: 0 auto;">
                    <form action="{{ route($route) }}" id="transfer-form" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group my-2">
                                    <label for="to_user">Receiver Member ID <span class="text-danger">*</span></label>
                                    <input type="text" name="to_user" class="form-control" placeholder="Enter ID" required id="transfer_to_user">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group my-2">
                                    <label class="form-label mb-2 font-weight-bold text-dark">Select Wallet <span class="text-danger">*</span></label>
                                    <select name="income_type" id="income_type" class="form-control" style="font-weight: 600;">
                                        <option value="roi">Profit Wallet (Available: {{ number_format($roiBalance ?? 0, 2) }} USDT)</option>
                                        <option value="ib">IB Wallet (Available: {{ number_format($ibBalance ?? 0, 2) }} USDT)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group my-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="amount" class="font-weight-bold mb-0">Amount (USDT) <span class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" id="max-amount-btn" style="font-size: 0.72rem; border-radius: 4px; line-height: 1.5;">Use Max</button>
                                    </div>
                                    <input type="number" name="amount" class="form-control" id="amount" placeholder="0.00" required step="0.01" min="10" style="font-weight: 600;">
                                    <div class="d-flex flex-column mt-1 text-muted small">
                                        <span>Available Balance: <b id="display-available-balance">{{ number_format($roiBalance ?? 0, 2) }} USDT</b></span>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <!-- Verification Section -->
                            <div class="col-12 mt-3">
                                <h6>Verification Required</h6>
                                <div class="form-group my-2">
                                    <label>Email OTP <span class="text-danger">*</span></label>
                                    <div class="input-group mt-1">
                                        <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                                        <button type="button" class="btn btn-outline-primary" id="send-otp-btn">Send OTP</button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 d-none" id="insufficient-balance-alert">
                                <div class="alert alert-warning border-0 small mb-0">
                                    Insufficient transferable balance. Minimum $10 is required to transfer.
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-main w-100 text-white" id="confirm-btn">Confirm Transfer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        let balances = {
            'roi': parseFloat("{{ $roiBalance ?? 0 }}") || 0,
            'ib': parseFloat("{{ $ibBalance ?? 0 }}") || 0
        };

        function updateAvailableBalance() {
            let type = $('#income_type').val();
            let available = balances[type];
            $('#display-available-balance').text(available.toFixed(2) + ' USDT');
            $('#amount').attr('max', available);

            if (available < 10) {
                $('#insufficient-balance-alert').removeClass('d-none');
                $('#confirm-btn').prop('disabled', true);
            } else {
                $('#insufficient-balance-alert').addClass('d-none');
                $('#confirm-btn').prop('disabled', false);
            }
        }

        $(document).on('change', '#income_type', function() {
            updateAvailableBalance();
            $('#amount').val('');
        });

        $(document).on('click', '#max-amount-btn', function() {
            let type = $('#income_type').val();
            let available = balances[type];
            $('#amount').val(available);
        });

        // Initialize state on load
        updateAvailableBalance();

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

        // User Validation
        $(document).on('blur', '#transfer_to_user', function() {
            let username = $(this).val();
            let $feedback = $(this).next('.invalid-feedback');
            if(!username) return;

            $.ajax({
                type: 'GET',
                url: route()+'/member/sponsor/validate',
                data: { sponsor: username, is_for: 'transfer_money' },
                success: function(res) {
                    if(res.status) {
                        $feedback.text(res.message).removeClass('text-danger').addClass('text-success d-block');
                    } else {
                        $feedback.text(res.message).removeClass('text-success').addClass('text-danger d-block');
                    }
                }
            });
        });

        $('#transfer-form').on('submit', function(e){
            e.preventDefault();
            let amount = parseFloat($('#amount').val()) || 0;
            if (amount < 10) {
                alert('The minimum transfer amount is 10 USDT.');
                return false;
            }
            let type = $('#income_type').val();
            let available = balances[type];
            if (amount > available) {
                alert('Insufficient balance in the selected wallet.');
                return false;
            }
            ajaxFormSubmit($(this)); 
        });
    });
</script>
@endsection
