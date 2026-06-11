@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">Pending Pins</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open() !!}
                            <h5 class="card-title">Pending Pins Status</h5>
                            <div class="row mt-4">
                                <div class="col-md-3 @error('member_id') has-error @enderror">
                                    {!! Form::label('member_id','Member ID') !!}
                                    {!! Form::text('member_id',request()->member_id,['class'=>'form-control','autocomplete'=>'off']) !!}
                                    @error('member_id')
                                        <span class="help-block text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3 pt-1">
                                    {!! Form::submit('View Pending Pins',['class'=>'btn btn-main btn-lg mt-4']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                        <div class="divider"></div>
                        @if($userModel != null)
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>User Name</th>
                                                <th>Transferred Pins</th>
                                                <th>Pending Pins</th>
                                                <th>Used Pins</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $userModel->member_id }}</td>
                                                <td>{{ $userModel->name }}</td>
                                                <td>{{ (!$userModel->transfer_pin_rel->isEmpty()) ? $userModel->transfer_pin_rel->count() : '--' }}</td>
                                                <td>{{ (!$userModel->transfer_pin_rel->isEmpty()) ? $userModel->transfer_pin_rel->whereNull('used_by')->count() : '--' }}</td>
                                                <td>{{ (!$userModel->transfer_pin_rel->isEmpty()) ? $userModel->transfer_pin_rel->whereNotNull('used_by')->count() : '--' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif($userModel == null && request()->has('member_id'))
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h6 class="text-info font-italic">Invalid member id</h6>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection
