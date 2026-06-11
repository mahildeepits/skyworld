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

@endsection
@section('scripts')
<script>
    let walletAddress = @json($userWallets) ?? [];

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
