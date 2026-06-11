@extends('layout.auth')
@section('content')
<div class="auth-form-glass text-left">
<div class="brand-logo text-center">
    <img src="{{ asset('images/54.png') }}" style="width:180px;" alt="logo">
</div>
<h4>Hello! let's get started</h4>
<h6 class="font-weight-light">Sign in to continue.</h6>
{!! Form::open(['class'=>'pt-3','method'=>'post','route'=>'login']) !!}
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
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="icon-lock"></i></span>
            </div>
            {!! Form::password('password',['class'=>'form-control','placeholder'=>'Password']) !!}
        </div>
        @error('password')
            <span class="text-danger text-info">{{ $message }}</span>
        @enderror
    </div>
    <div class="mt-3">
    <button type="submit" class="btn d-grid btn-main btn-lg font-weight-medium auth-form-btn text-white w-100">SIGN IN</button>
    </div>
    <div class="my-2 d-flex justify-content-between align-items-center">
    <div class="form-check">
        <label class="form-check-label text-muted">
        <input type="checkbox" class="form-check-input"> Keep me signed in <i class="input-helper"></i></label>
    </div>
    <a href="{{ route('forget.password') }}" class="auth-link text-black">Forgot password?</a>
    </div>
    <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
    </div>
{!! Form::close() !!}
</div>
@endsection
