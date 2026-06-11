@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Transfer Pins' sub-menu='My Topup' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transfer Pins</h5>
                {!! Form::open(['route'=>'transfer-pins-now']) !!}
                    <div class="row mb-3 mt-3">
                        {!! Form::label('joining_kit','Joining Kit*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::select('joining_kit',\App\Models\JoiningKit::pluck('kit_name','id'),null,['class'=>'form-control','placeholder'=>'Select Kit']) !!}
                            @error('joining_kit')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        {!! Form::label('transfer_to','Transfer To*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::text('transfer_to',null,['class'=>'form-control','placeholder'=>'Enter member id']) !!}
                            <span class="help-block text-success member-name" style="display: none;">
                                <b class="text-dark">Member Name:</b> <span class="user-name"></span>
                            </span>
                            @error('transfer_to')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        {!! Form::label('number_of_pins','Number of pins*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::text('number_of_pins',null,['class'=>'form-control','placeholder'=>'Enter no of pins']) !!}
                            @error('number_of_pins')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Available Pins: <span class="text-danger avail-pins">0</span></h5>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            {!! Form::submit('Transfer Now',['class'=>'btn btn-primary btn-md transfer-now','style'=>'display: none;']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
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
