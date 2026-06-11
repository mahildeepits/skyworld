@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">Website Settings</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="card-title">Settings</h4>
                                {!! Form::model($details) !!}
                                    <div class="row">

                                        <div class="col-md-4">
                                            {!! Form::label('email','Email') !!}
                                            {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Enter email id']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('mobile','Mobile') !!}
                                            {!! Form::text('mobile',null,['class'=>'form-control','placeholder'=>'Enter mobile number']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('whats_app_number','Whatsapp Mobile') !!}
                                            {!! Form::text('whats_app_number',null,['class'=>'form-control','placeholder'=>'Enter mobile number']) !!}
                                        </div>
                                        <div class="col-md-4 form-group mt-5">
                                            {!! Form::label('office_address','Office Address') !!}
                                            {!! Form::textarea('office_address',null,['class'=>'form-control','placeholder'=>'Enter address','rows'=>3]) !!}
                                        </div>
                                        <div class="col-md-12">
                                            {!! Form::submit('Save Settings',['class'=>'btn btn-main']) !!}
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection
