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
                                    <label for="amount">Amount (USDT) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" id="amount" placeholder="0.00" required step="0.01" max="{{ $transferable }}" min="1">
                                    <div class="d-flex flex-column mt-1 text-muted small">
                                        <span>Available Balance: <b>${{ number_format($totalBalance, 2) }}</b></span>
                                        <span>Locked Level Reserve ({{ $activeCategory ? $activeCategory->name : 'None' }}): <b>${{ number_format($reserveAmount, 2) }}</b></span>
                                        <strong>Transferable Balance: <span class="text-success">${{ number_format($transferable, 2) }}</span></strong>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group my-0">
                                    <p class="m-0 py-2"><b>Transfer Fee ( 5% )</b></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group my-0">
                                    <input type="text" class="text-end form-control-plaintext py-2" style="font-weight: 600;" id="transfer_fee" value="$0.00" placeholder="$0.00" disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group my-0">
                                    <p class="m-0 py-2"><b>Receiver Will Receive</b></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group my-0">
                                    <input type="text" class="text-end form-control-plaintext py-2" style="font-weight: 600;" value="$0.00" id="receiver_receive" placeholder="$0.00" disabled >
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
                                
                                <div class="form-group my-2">
                                    <label>Google Authenticator Code <span class="text-danger">*</span></label>
                                    <input type="text" name="google_2fa" class="form-control mt-1" placeholder="6-digit code" required>
                                    <small class="text-muted">
                                        <a href="javascript:void(0)" class="open-qr-modal">Setup/View QR Code</a>
                                    </small>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            @if($transferable < 1)
                                <div class="col-12 mt-3">
                                    <div class="alert alert-warning border-0 small mb-0">
                                        Insufficient transferable balance. Minimum $1 above your package reserve (${{ number_format($reserveAmount, 2) }}) is required to transfer.
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-main w-100 text-white" {{ $transferable < 1 ? 'disabled' : '' }}>Confirm Transfer</button>
                            </div>

                            <div class="col-12 mt-3" >
                                <ul class="p-3" style="list-style: none; font-weight:500; padding-inline-start: 0;border-radius: 10px;background-color: #b1b0f9d4;">
                                    <li class="d-flex align-items-start">
                                        <i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i>
                                        <p class="m-0 pt-1">Transfer Fee Deduction Is 5% on all transfers</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google 2FA Setup Modal -->
<div class="modal fade" id="google2fa-modal" tabindex="-1" role="dialog" aria-labelledby="google2fa-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="google2fa-modal-label">Google Authenticator Setup</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Scan this QR code with your Google Authenticator App</p>
                <div style="max-width:270px; margin:20px auto; background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    @if($qrCode)
                        {!! $qrCode !!}
                    @else
                        <div class="alert alert-danger py-1 small">Unable to generate QR</div>
                    @endif
                </div>
                <p class="text-muted small">Secret Key: {{ authUser()->google2fa_secret }}</p>
                <div class="alert alert-info py-2 small" style="border-radius: 8px;">
                    Make sure to save your secret key in a safe place.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Move modal to body to prevent stacking context issues where the backdrop overlaps the modal
        $('#google2fa-modal').appendTo('body');

        // Dynamic fee calculator
        $(document).on('keyup input', '#amount', function() {
            let amount = parseFloat($(this).val()) || 0;
            let fee = (amount * 0.05).toFixed(2);
            let net = (amount - fee).toFixed(2);
            $('#transfer_fee').val('$' + fee);
            $('#receiver_receive').val('$' + net);
        });

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
            ajaxFormSubmit($(this)); 
        });
    });

    // Native Bootstrap 5 Modal Trigger
    $(document).on('click', '.open-qr-modal', function(e) {
        e.preventDefault();
        try {
            var myModalEl = document.getElementById('google2fa-modal');
            var modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
            modal.show();
        } catch (err) {
            console.error('BS5 Modal error:', err);
            if (typeof $.fn.modal !== 'undefined') {
                $('#google2fa-modal').modal('show');
            } else {
                $('#google2fa-modal').addClass('show').css('display', 'block');
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }
        }
    });

    $(document).on('click', '[data-bs-dismiss="modal"]', function() {
        var myModalEl = document.getElementById('google2fa-modal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        if (modal) {
            modal.hide();
        } else {
            $('#google2fa-modal').removeClass('show').css('display', 'none');
            $('.modal-backdrop').remove();
        }
    });
</script>
@endsection
