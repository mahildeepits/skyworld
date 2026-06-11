@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">Pin History</h4>
        </div>
    </div>
    <div class="row">
            <div class="col-md-3">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="widgets-icons rounded-circle mx-auto bg-light-info text-info mb-3"><i class="icon-paper-clip menu-icon"></i>
                            </div>
                            <h4 class="my-1">{{ $availablePins->count() }}</h4>
                            <p class="mb-0 text-secondary">Available Pins</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="widgets-icons rounded-circle mx-auto bg-light-warning text-warning mb-3"><i class="icon-shuffle menu-icon"></i>
                            </div>
                            <h4 class="my-1">{{ $transferredPin->count() }}</h4>
                            <p class="mb-0 text-secondary">Transferred Pins</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
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
                             {!! Form::submit('Search Records',['class'=>'btn btn-main']) !!}
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
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
    </script>
@endsection
