@extends('admin.layouts.auth')
@section('title','Admin Sign In')

@section('content')
    <h3>Sign In</h3>
    <p class="subtitle">Access the administrative dashboard.</p>

    <div class="alert alert-danger text-left ajax-error" role="alert" style="display: none;"></div>

    @if(!$errors->isEmpty())
        <div class="alert alert-danger text-left mb-4" role="alert">
            <i class="icon-info mr-2"></i> {{ $errors->first() }}
        </div>
    @endif

    {!! Form::open(['route'=>'admin.login', 'class' => 'mt-4']) !!}
        <div class="form-group mb-4">
            <label>Username</label>
            {!! Form::text('username',null,['class'=>'form-control','placeholder'=>'Enter administrative username','autocomplete'=>'off', 'required']) !!}
        </div>
        <div class="form-group mb-4">
            <label>Password</label>
            <div class="position-relative">
                {!! Form::password('password',['class'=>'form-control','id'=>'password','placeholder'=>'Enter your secure password','autocomplete'=>'new-password', 'required']) !!}
                <i class="icon-eye position-absolute" id="togglePassword" style="right: 15px; top: 15px; cursor: pointer; color: #969696;"></i>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-main">Sign In to Dashboard</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('icon-eye icon-eye-off');
            });
        });
    </script>
@endsection
