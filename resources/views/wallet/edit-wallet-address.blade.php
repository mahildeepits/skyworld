@extends('layout.main')
@section('content')
@php
$user = authUser();
$userWallets = $user->wallet_addresses ?? [];
@endphp

    <x-page-breadcrumb current-page='Addresses' sub-menu='Wallet' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card" >
                <div class="card-body">
                    <div style="max-width: 500px; margin: 0 auto;">
                        <form action="{{ route('edit.wallet.address') }}" method="post" onsubmit="ajaxFormSubmit($(this))">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <select name="wallet_type" id="wallet_type" class="form-select form-control" disabled>
                                            <option value="crypto"> Crypto </option>
                                        </select>
                                    </div>  
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-2">
                                        <select name="currency" id="currency" class="form-select form-control" disabled>
                                            <option value="USDT"> USDT </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group my-2">
                                        <select name="type_display" id="type_display" class="form-select form-control" disabled>
                                            <option value="BEP-20" selected> BEP-20 </option>
                                        </select>
                                        <input type="hidden" name="type" value="BEP-20">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group my-2">
                                        <label for="wallet_address">Wallet Address <span class="text-danger">*</span></label>
                                        <input type="text" name="wallet_address" class="form-control" value="{{$userWallets['BEP-20'] ?? ''}}" id="wallet_address" placeholder="Enter your wallet address">
                                    </div>
                                </div>

                                <div class="col-12 otp">
                                    <div class="form-group mb-3">
                                        <div class="d-flex justify-content-between">
                                            {!! Form::label('otp','OTP Verification') !!}
                                            @if (authUser()->member_id == 'company' || authUser()->member_id == 'Company')
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="skip_otp" name="skip_otp" value="1">
                                                    <label for="skip_otp">Skip OTP</label>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            {!! Form::text('otp',null,['class'=>'form-control','placeholder'=>'Enter Email OTP', 'id' => 'email_otp', 'disabled' => false]) !!}
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-main text-white " id="send_otp">Send OTP</button>
                                            </div>
                                        </div>
                                        <small class="text-secondary">The OTP will be sent on <b>{{ authUser()?->email }}</b> </small>
                                        <div class="invalid-feedback"></div>
                                        <div class="d-none" id="otp_resend"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label>Google Authenticator Code <span class="text-danger">*</span></label>
                                        <input type="text" name="google_2fa" class="form-control" placeholder="6-digit code">
                                        <small class="text-muted">
                                            <a href="javascript:void(0)" class="open-qr-modal">Setup/View QR Code</a>
                                        </small>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="submit" value="Add/Update Wallet Address" class="btn btn-main w-100 text-white">
                                </div>
                                <!-- <div class="col-12 mt-3" >
                                    <ul class="p-3" style="list-style: none; font-weight:500; padding-inline-start: 0;border-radius: 10px;background-color: #b1b0f9d4;">
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> The Minimum Withdrawal Amount Is 30 USDT</p></li>
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Transaction Fee Is 5% ( 3% On First Withdrawal )</p></li>
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Minimum Completion of 25 Daily Tasks</p></li>
                                        <li class="d-flex align-items-start"><i class='bx bxs-error text-dark px-1' style="font-size:16px;"></i><p class="m-0 pt-1"> Active Downline Defined As The Account Total Deposited A Minimum Of 30 USDT</p></li>
                                    </ul>
                                </div> -->
                            </div>
                        </form>
                    </div>
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
                <div style="max-width:220px; margin:20px auto; background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    @isset($qrCode)
                        {!! $qrCode !!}
                    @else
                        <div class="alert alert-danger py-1 small">Unable to generate QR</div>
                    @endisset
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
    let walletAddress = @json($userWallets) ?? [];

    // Native Bootstrap 5 Modal Trigger
    $(document).on('click', '.open-qr-modal', function(e) {
        e.preventDefault();
        try {
            var myModalEl = document.getElementById('google2fa-modal');
            var modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
            modal.show();
        } catch (err) {
            console.error('BS5 Modal error:', err);
            // jquery fallback if bootstrap is not available as global
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

    // Handle amount changes
    $(document).on('keyup','#amount',function(){
        let amount = parseFloat($(this).val()) || 0;
        let percentage = parseFloat($('#transaction_fees_percentage').val()) || 0;
        let transaction_fee = (amount * percentage).toFixed(2);
        let total_amount = (amount - transaction_fee).toFixed(2);
        $('.transaction_fee').val(transaction_fee);
        $('.total_amount').val(total_amount);
    });

    // OTP logic stays the same
    $(document).on('click','#send_otp',function(){
        $(this).attr('disabled',true);
        $.ajax({
            url:`{{route('send.otp')}}`,
            type:'get',
            success:function(res){
                if(res.status){
                    toasterMessanger.success('Success',res.message);
                }else{
                    toasterMessanger.error('Error',res.message);
                }
                $('#otp_resend').removeClass('d-none');
                var time = 60;
                let timer = setInterval(() => {
                    time--;
                    if(time > 0){
                        $('#otp_resend').html(`<small class="text-muted">Wait for ${time} seconds to resend OTP, if you did not receive it.`);
                    } else {
                        clearInterval(timer);
                        $('#send_otp').removeAttr('disabled');
                        $('#otp_resend').addClass('d-none');
                    }
                }, 1000);
            },
            error: function(err) {
                $(this).attr('disabled', false);
                $('#otp_resend').addClass('d-none');
                toasterMessanger.error('Error', 'An error occurred while sending OTP.');
            }
        });
    });
</script>
@endsection
