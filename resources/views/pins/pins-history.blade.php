@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Pin History' sub-menu='My Topup' />
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Available Pins</p>
                            <h1 class="mb-0">{{ $availablePins->count() }}</h1>
                        </div>
                        <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Transferred Pins</p>
                            <h1 class="mb-0">{{ $transferredPin->count() }}</h1>
                        </div>
                        <i class="typcn typcn-chart-pie icon-xl text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Epin Report</h4>
                {!! Form::open(['route' => 'member.pins.history', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="col-md-3">
                            {!! Form::label('from_date', 'From Date') !!}
                            {!! Form::date('from_date', request()->from_date, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('to_date', 'To Date') !!}
                            {!! Form::date('to_date', request()->to_date, ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('joining_kit_id', 'Joining Kit') !!}
                            {!! Form::select('joining_kit_id',\App\Models\JoiningKit::pluck('kit_name','id'), request()->joining_kit_id, ['class' => 'form-control']) !!}
                        </div>
                        @if(auth()->guard('admin')->check())
                            <div class="col-md-3">
                                {!! Form::label('to_user_id', 'To User Id') !!}
                                {!! Form::text('to_user_id', null, ['class' => 'form-control']) !!}
                            </div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            {!! Form::submit('Search Records',['class'=>'btn btn-primary']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
                <div class="row">
                    <div class="col-md-12">
                        {!! $dataTable->table() !!}
                    </div>
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
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
    </script>
@endsection
