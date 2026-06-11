$(function(){
    // Sponsor Validation
    $('input[name=sponsor]').blur(function(){
        if($(this).val().trim() != ''){
            $.ajax({
                type: 'GET',
                url: route()+'/member/sponsor/validate',
                data: {
                    sponsor: $(this).val(),
                    register:true,
                },
                success: function(result){
                    if(result.error_code == 0){
                        $('input[name=sponsor_name]').val(result.sponsor);
                        $('#sponsor_info').html('<span class="text-success"><i class="icon-check"></i> Sponsor: ' + result.sponsor + '</span>').show();
                        $('.ajax-error').hide();
                    }else{
                        $('.ajax-error').html(result.error).show();
                        $('#sponsor_info').hide();
                        $('input[name=sponsor_name]').val('');
                    }
                }
            });
        }else{
            $('.ajax-error').hide();
            $('#sponsor_info').hide();
        }
    });

    setTimeout(()=>{
        if($('input[name=sponsor]').val() != ''){
            $('input[name=sponsor]').trigger('blur');
        }
    },1000);

    // Registration Form Submission (Intercept for OTP)
    $('.pt-3').on('submit', function(e){
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.text();

        submitBtn.prop('disabled', true).text('SENDING OTP...');
        $('.text-danger.text-info').remove();

        $.ajax({
            type: 'POST',
            url: route() + '/member/register/send-otp',
            data: form.serialize(),
            success: function(response){
                if(response.status){
                    $('#displayEmail').text($('input[name="email"]').val());
                    $('#otpModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value){
                        const input = $('[name="' + key + '"]');
                        if(input.length){
                            input.closest('.form-group').append('<span class="text-danger text-info">' + value[0] + '</span>');
                        } else if(key === 'error') {
                            form.prepend('<div class="alert alert-danger py-2 text-info" style="font-size: 0.85rem;">' + value[0] + '</div>');
                        }
                    });
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            complete: function(){
                submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    });

    // Verify OTP
    $('#btnVerifyOtp').on('click', function(){
        const otp = $('#reg_otp').val();
        const email = $('input[name="email"]').val();
        const btn = $(this);

        if(otp.length !== 6){
            $('#otp_error').text('Enter a valid 6-digit OTP');
            return;
        }

        btn.prop('disabled', true).text('VERIFYING...');
        $('#otp_error').text('');

        $.ajax({
            type: 'POST',
            url: route() + '/member/register/verify-otp',
            data: {
                _token: $('input[name="_token"]').val(),
                email: email,
                otp: otp
            },
            success: function(response){
                if(response.status){
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr){
                if(xhr.status === 422){
                    $('#otp_error').text(xhr.responseJSON.errors.otp[0]);
                } else {
                    $('#otp_error').text(xhr.responseJSON.message || 'Verification failed');
                }
            },
            complete: function(){
                btn.prop('disabled', false).text('VERIFY & REGISTER');
            }
        });
    });

    // Resend OTP
    $('#resendOtp').on('click', function(){
        $('.pt-3').trigger('submit');
        $('#otpModal').modal('hide');
    });
});
