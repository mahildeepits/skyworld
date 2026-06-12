@extends('layout.auth')
@section('auth-col', 'col-lg-8')

@section('content')
<div class="auth-form-glass text-left">
    <div class="brand-logo text-center">
        <img src="{{ asset('images/54.png') }}" style="width:160px;" alt="logo">
    </div>
    <h4>New here?</h4>
    <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
    
    @if($errors->has('error'))
        <div class="alert alert-danger py-2" style="font-size: 0.85rem;">
            <i class="icon-info me-2"></i> {{ $errors->first('error') }}
        </div>
    @endif

    {!! Form::open(['route'=>'register','class'=>'pt-3','method'=>'POST','files'=>true]) !!}
        {!! Form::hidden('parent_id',request()->parent) !!}
        
        @php
            $pin = request()->pin;
        @endphp
        
        <div class="row">
            @if(request()->has('no_pin') && request()->no_pin == "true")
                @php
                    $pin = '1231231';
                @endphp
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-key"></i></span>
                            </div>
                            {!! Form::text('epin',$pin,['class'=>'form-control','placeholder'=>'Enter E-Pin*']) !!}
                        </div>
                        @error('epin')
                            <span class="text-danger text-info">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-user-follow"></i></span>
                        </div>
                        {!! Form::text('sponsor',request()->sponsor,['class'=>'form-control','placeholder'=>'Sponsor/Referral Username*']) !!}
                    </div>
                    <div id="sponsor_info" class="invalid-feedback d-block mt-1" style="font-size: 0.85rem;"></div>
                    {!! Form::hidden('sponsor_name',null) !!}
                    @error('sponsor')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-user"></i></span>
                        </div>
                        {!! Form::text('full_name',null,['class'=>'form-control','placeholder'=>'Enter Full Name*']) !!}
                    </div>
                    @error('full_name')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-envelope"></i></span>
                        </div>
                        {!! Form::email('email',null,['class'=>'form-control','placeholder'=>'Enter Email*']) !!}
                    </div>
                    @error('email')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-phone"></i></span>
                        </div>
                        {!! Form::text('mobile',null,['class'=>'form-control','placeholder'=>'Enter Mobile*']) !!}
                    </div>
                    @error('mobile')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-lock"></i></span>
                        </div>
                        {!! Form::password('password',['class'=>'form-control','placeholder'=>'Enter Password*']) !!}
                    </div>
                    @error('password')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-lock-open"></i></span>
                        </div>
                        {!! Form::password('confirm_password',['class'=>'form-control','placeholder'=>'Enter Confirm Password*']) !!}
                    </div>
                    @error('confirm_password')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-layers"></i></span>
                        </div>
                        @php
                            $categories = \App\Models\AgentCategory::pluck('name', 'id')->toArray();
                        @endphp
                        {!! Form::select('agent_category_id', $categories, null, ['class'=>'form-control','placeholder'=>'Select Package*']) !!}
                    </div>
                    @error('agent_category_id')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-wallet"></i></span>
                        </div>
                        {!! Form::number('deposit_amount', null, ['class'=>'form-control','placeholder'=>'Enter Deposit Amount*','step'=>'0.01','min'=>'0']) !!}
                    </div>
                    @error('deposit_amount')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="text-white-50 small mb-1 d-block" style="padding-left: 2px;">Upload Deposit Receipt / Screenshot (Screenshots/Images only)*</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-camera"></i></span>
                        </div>
                        {!! Form::file('receipt', ['class'=>'form-control','accept'=>'image/*','required'=>true]) !!}
                    </div>
                    @error('receipt')
                        <span class="text-danger text-info">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <div class="form-check">
                <label class="form-check-label text-muted">
                <input type="checkbox" class="form-check-input" required> I agree to all Terms & Conditions </label>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn d-grid btn-main btn-lg font-weight-medium auth-form-btn text-white w-100">SIGN UP</button>
        </div>
        <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
        </div>
    {!! Form::close() !!}
</div>

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('input[name="sponsor"]').on('blur', function() {
                let sponsorId = $(this).val();
                let infoDiv = $('#sponsor_info');
                if(sponsorId) {
                    $.ajax({
                        url: '{{ route("sponsor.details") }}',
                        data: { sponsor: sponsorId },
                        success: function(res) {
                            if(res.error_code === 0) {
                                infoDiv.removeClass('text-danger').addClass('text-success').html('Sponsor found: ' + res.sponsor);
                                $('input[name="sponsor_name"]').val(res.sponsor);
                            } else {
                                infoDiv.removeClass('text-success').addClass('text-danger').html(res.error || 'Sponsor not found/active');
                                $('input[name="sponsor_name"]').val('');
                            }
                        },
                        error: function() {
                            infoDiv.removeClass('text-success').addClass('text-danger').html('Error verifying sponsor');
                            $('input[name="sponsor_name"]').val('');
                        }
                    });
                } else {
                    infoDiv.html('');
                    $('input[name="sponsor_name"]').val('');
                }
            });
            $('input[name="sponsor"]').trigger('blur');
        });
    </script>
@endsection
