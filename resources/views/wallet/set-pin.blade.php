@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Change Pin' sub-menu='My Information' />
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-10 col-12 grid-margin stretch-card">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle p-3 rounded-circle mr-3" style="background: rgba(3, 75, 179, 0.1); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                            <i class="icon-shield text-primary m-0" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bold mb-0" style="font-size: 1.1rem;">Wallet Security</h4>
                            <p class="text-muted small mb-0">Update your secure wallet transaction pin</p>
                        </div>
                    </div>
                    
                    <form action="{{route('wallet.pin')}}" method="post" id="set-pin-form">
                        @csrf
                        <div class="form-group mb-4">
                            {!! Form::label('current_password','Current Password',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                            <div class="input-group shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-lock"></i></span>
                                </div>
                                {!! Form::password('current_password',['class'=>'form-control','id' => 'current_password','placeholder'=>'Enter current password']) !!}
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                {!! Form::label('new_pin','New 4-Digit Pin',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                                <input type="hidden" name="wallet_pin" id="wallet_pin" value="" />
                                <div class="d-flex justify-content-between my-2 pin-container" id="pin">
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center pin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center pin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center pin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center pin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                {!! Form::label('confirm_pin','Confirm Pin',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                                <input type="hidden" name="confirm_pin" id="confirm_pin" value="" />
                                <div class="d-flex justify-content-between my-2 pin-container" id="cpin">
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center cpin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center cpin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center cpin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                    <input type="password" inputmode="numeric" maxlength="1" class="form-control text-center cpin-input shadow-sm" style="width: 50px; height: 50px; font-size: 20px; border-radius: 12px; border: 1px solid #e2e8f0;" />
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="otp-section border rounded p-3 mb-4 bg-light shadow-sm" style="border-radius: 15px !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="font-weight-bold m-0 small text-uppercase text-muted"><i class="icon-envelope-letter mr-1"></i> OTP Verification</label>
                                @if (authUser()->member_id == 'company' || authUser()->member_id == 'Company')
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="skip_otp" name="skip_otp" value="1">
                                        <label class="custom-control-label small" for="skip_otp">Skip OTP</label>
                                    </div>
                                @endif
                            </div>
                            <div class="input-group">
                                {!! Form::text('otp',null,['class'=>'form-control','placeholder'=>'Enter OTP', 'id' => 'email_otp']) !!}
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-main px-4" id="send_otp">Send OTP</button>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Will be sent to: <span class="text-primary font-weight-bold">{{ authUser()?->email }}</span></small>
                            <div class="invalid-feedback"></div>
                            <div class="mt-2 small d-none" id="otp_resend"></div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-main btn-lg btn-block shadow-sm py-3 font-weight-bold">
                                <i class="icon-check mr-2"></i> SET WALLET PIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
<script>
    $(document).ready(function(){
        $('.pin-input').on('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 1) {
                $(this).next('.pin-input').focus();
            }
        });
        $('.cpin-input').on('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 1) {
                $(this).next('.cpin-input').focus();
            }
        });
        $('.pin-input').on('keydown', function (e) {
            if (e.key === 'Backspace' && this.value.length === 0) {
                $(this).prev('.pin-input').focus();
            }
        });
        $('.cpin-input').on('keydown', function (e) {
            if (e.key === 'Backspace' && this.value.length === 0) {
                $(this).prev('.cpin-input').focus();
            }
        });
        $('#set-pin-form').on('submit', function(e) {
            e.preventDefault();
            // $('#pin').parents('.form-group').find('.invalid-feedback').removeClass('d-block').html('');
            // $('#cpin').parents('.form-group').find('.invalid-feedback').removeClass('d-block').html('');
            let pin = '';
            let cpin = '';
            $('.pin-input').each(function() {
                pin += $(this).val();
            });
            $('.cpin-input').each(function() {
                cpin += $(this).val();
            });
            // if (pin.length !== 4 || !/^\d{4}$/.test(pin)) {
            //     console.log(pin,cpin);
            //     $('#pin').parents('.form-group').find('.invalid-feedback').addClass('d-block').html('<span class="text-dander">4 digits Pin is required</span>');
            //     return;
            // }
            // if (cpin.length !== 4 || !/^\d{4}$/.test(cpin)) {
            //     $('#cpin').parents('.form-group').find('.invalid-feedback').addClass('d-block').html('<span class="text-dander">4 digits Confirm Pin is required</span>');
            //     return;
            // }

            $('#wallet_pin').val(pin);
            $('#confirm_pin').val(pin);
            ajaxFormSubmit($(this)); // Submit the form manually
        });
    })
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
            }
        });
    });
</script>

@endsection
