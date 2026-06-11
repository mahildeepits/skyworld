@extends('layout.auth')
@section('content')
<div class="auth-form-glass text-left">
    <div class="brand-logo text-center">
        <img src="{{ asset('images/54.png') }}" style="width:180px;" alt="logo">
    </div>
    <h4>Forget Password</h4>
    <h6 class="font-weight-light">Enter your username to reset your password.</h6>
    
    {!! Form::open(['class'=>'pt-3','method'=>'post','route'=>'forget.password']) !!}
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-user"></i></span>
                </div>
                {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'Username']) !!}
            </div>
            @error('username')
                <span class="text-danger text-info">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn d-grid btn-main btn-lg font-weight-medium auth-form-btn text-white w-100">SEND PASSWORD</button>
        </div>
        <div class="text-center mt-4 font-weight-light"> Remember account details? <a href="{{ route('login') }}" class="text-primary">Login Now</a>
        </div>
    {!! Form::close() !!}
</div>
@endsection
