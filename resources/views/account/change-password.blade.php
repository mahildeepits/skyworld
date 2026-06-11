@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Change Password' sub-menu='My Information' />
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8 col-12 grid-margin stretch-card">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle p-3 rounded-circle mr-3" style="background: rgba(3, 75, 179, 0.1); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                            <i class="icon-lock text-primary m-0" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bold mb-0" style="font-size: 1.1rem;">Security Settings</h4>
                            <p class="text-muted small mb-0">Update your account password</p>
                        </div>
                    </div>
                {!! Form::open(['route'=>'account.update-password', 'onsubmit' => 'ajaxFormSubmit($(this))']) !!}
                    <div class="form-group mb-4">
                        {!! Form::label('current_password','Current Password',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-lock-open"></i></span>
                            </div>
                            {!! Form::password('current_password',['class'=>'form-control','placeholder'=>'Enter current password']) !!}
                        </div>
                        <div class="invalid-feedback"></div>
                        @error('current_password')
                            <span class="help-block text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-4">
                        {!! Form::label('new_password','New Password',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-key"></i></span>
                            </div>
                            {!! Form::password('new_password',['class'=>'form-control','placeholder'=>'Enter new password']) !!}
                        </div>
                        <div class="invalid-feedback"></div>
                        @error('new_password')
                            <span class="help-block text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        {!! Form::label('conf_password','Confirm Password',['class'=>'font-weight-bold small text-uppercase text-muted']) !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-check"></i></span>
                            </div>
                            {!! Form::password('conf_password',['class'=>'form-control','placeholder'=>'Confirm your new password']) !!}
                        </div>
                        <div class="invalid-feedback"></div>
                        @error('conf_password')
                            <span class="help-block text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4">
                        {!! Form::submit('Update Password',['class'=>'btn btn-main btn-lg btn-block shadow-sm']) !!}
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        f
    </script>
@endsection
