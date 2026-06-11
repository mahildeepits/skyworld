@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Edit Profile' sub-menu='My Information' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h6>Edit Profile</h6>
                @php
                    $userDetails = auth()->guard('member')->user();
                @endphp
                {!! Form::open(['method'=>'post','route'=>'account.save-profile']) !!}
                    <div class="row mt-3">
                        {!! Form::label('name','Name*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('name',auth()->guard('member')->user()->name,['class'=>'form-control','placeholder'=>'Enter name']) !!}
                            @error('name')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- <div class="row mb-3">
                        {!! Form::label('father_name','Father Name*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::text('father_name',auth()->guard('member')->user()->father_name,['class'=>'form-control','placeholder'=>'Enter father name']) !!}
                            @error('father_name')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div> -->
                    <div class="row">
                        {!! Form::label('address','Address*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('address',auth()->guard('member')->user()->profile_rel?->address,['class'=>'form-control','placeholder'=>'Enter address']) !!}
                            @error('address')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        {!! Form::label('city','City*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('city',auth()->guard('member')->user()->profile_rel?->city,['class'=>'form-control','placeholder'=>'Enter city']) !!}
                        </div>
                    </div>

                    <div class="row">
                        {!! Form::label('country','Country*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('country',auth()->guard('member')->user()->profile_rel?->country,['class'=>'form-control','placeholder'=>'Enter country']) !!}
                            @error('country')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        {!! Form::label('state','State*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('state',auth()->guard('member')->user()->profile_rel?->state,['class'=>'form-control','placeholder'=>'Enter state']) !!}
                            @error('state')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="row mb-3">
                        {!! Form::label('pin_code','Pincode*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6">
                            {!! Form::text('pin_code',auth()->guard('member')->user()->profile_rel?->pin_code,['class'=>'form-control','placeholder'=>'Pincode']) !!}
                        </div>
                    </div> -->
                    <div class="row">
                        {!! Form::label('mobile','Mobile*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('mobile',auth()->guard('member')->user()->mobile,['class'=>'form-control','placeholder'=>'Mobile']) !!}
                        </div>
                    </div>
                    <!-- <div class="row mb-3">
                        {!! Form::label('dob','DOB*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::date('dob',auth()->guard('member')->user()->dob,['class'=>'form-control','placeholder'=>'Date of Birth']) !!}
                        </div>
                    </div>

                    <div class="row mb-3">
                        {!! Form::label('gender','Gender*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::select('gender',['male'=>'Male','female'=>'Female'],auth()->guard('member')->user()->gender,['class'=>'form-control','placeholder'=>'Gender']) !!}
                            @error('gender')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div> -->

                    <div class="row mb-3">
                        {!! Form::label('email','Email Id*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group mb-1">
                            {!! Form::text('email',auth()->guard('member')->user()->email,['class'=>'form-control','placeholder'=>'Enter email']) !!}
                            @error('email')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="row mb-3">
                        {!! Form::label('nominee_name','Nominee Name*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::text('nominee_name',auth()->guard('member')->user()->profile_rel?->nominee_name,['class'=>'form-control','placeholder'=>'Enter nominee name']) !!}
                            @error('nominee_name')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        {!! Form::label('nominee_relation','Nominee Relation*',['class'=>'col-md-2 col-form-label font-16']) !!}
                        <div class="col-md-6 form-group">
                            {!! Form::text('nominee_relation',auth()->guard('member')->user()->profile_rel?->nominee_relation,['class'=>'form-control','placeholder'=>'Enter nominee relation']) !!}
                            @error('nominee_relation')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div> -->

                    <div class="row mt-3">
                        <div class="col-md-12">
                            {!! Form::submit('Save Profile',['class'=>'btn btn-primary']) !!}
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
        $(document).ready(function(){
            @if($userDetails->kyc_rel !== null)
                $('input').each(function(){
                    if($(this).attr('name') !== 'mobile' && $(this).attr('name') !== 'password'){
                        $(this).prop('readonly',true);
                    }
                })
            @endif
        });
    </script>
@endsection
