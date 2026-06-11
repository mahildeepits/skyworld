@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Join From Pin' sub-menu='My Topup' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h6>Joining Pins</h6>
                <div class="row mb-3 mt-3">
                    {!! Form::label('joining_kit','Joining Kit*',['class'=>'col-md-2 col-form-label font-16']) !!}
                    <div class="col-md-6 form-group">
                        {!! Form::select('joining_kit',\App\Models\JoiningKit::pluck('kit_name','id'),null,['class'=>'form-control','placeholder'=>'Select Kit']) !!}
                        @error('current_password')
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
                        <a href="javascript:void(0)"  target="_blank" class="btn btn-primary btn-md join-link" style="display: none">Join Now</a>
                        @if(auth()->guard('member')->user()->is_paid == 0)
                            <a href="{{ route('member.topup.now',['from_existing_pins'=>true]) }}" onclick="return confirm('Are you sure to top up now? Your one paid pin will be deducted and not able to reverse.')" class="btn btn-danger topup-now" style="display: none;">Top Up Now</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">

    </script>
@endsection
