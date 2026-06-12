@extends('admin.layouts.admin')
@section('title','MLM Software - Receipt')
@section('content')
    <div class="row">
        <div class="col-md-12 my-3">
            <h4 class="card-title">User Profile Management</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <!-- <div class="col-md-">
                            <h4 class="card-title mb-0">User Profile Management</h4>
                        </div> -->
                        <div class="col-md-6">
                            {!! Form::open(['method'=>'get', 'class' => 'form-inline float-md-right']) !!}
                                <div class="input-group input-group-sm">
                                    {!! Form::text('member_id',request()->member_id,['class'=>'form-control','placeholder'=>'Enter Member ID']) !!}
                                    <div class="input-group-append">
                                        {!! Form::submit('Search User',['class'=>'btn btn-main btn-sm']) !!}
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    
                    <div class="divider mb-4"></div>

                    @if(!empty($userModel))
                        {!! Form::model($userModel,['method'=>'post','route'=>'admin.profile.update','files'=>true]) !!}
                            {!! Form::hidden('id',$userModel['user_id']) !!}
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('name','Name*',['class'=>'col-sm-3 col-form-label col-form-label-sm']) !!}
                                        <div class="col-sm-9">
                                            {!! Form::text('name',null,['class'=>'form-control form-control-sm','placeholder'=>'Enter name']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('email','Email*',['class'=>'col-sm-3 col-form-label col-form-label-sm']) !!}
                                        <div class="col-sm-9">
                                            {!! Form::email('email',null,['class'=>'form-control form-control-sm','placeholder'=>'Enter email']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('deposit_amount','Deposit Balance ($)',['class'=>'col-sm-3 col-form-label col-form-label-sm']) !!}
                                        <div class="col-sm-9">
                                            {!! Form::number('deposit_amount',null,['class'=>'form-control form-control-sm','placeholder'=>'Enter Deposit Balance','step'=>'any']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('mobile','Mobile',['class'=>'col-sm-3 col-form-label col-form-label-sm']) !!}
                                        <div class="col-sm-9">
                                            {!! Form::number('mobile',null,['class'=>'form-control form-control-sm','placeholder'=>'Enter mobile']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('password','Password',['class'=>'col-sm-3 col-form-label col-form-label-sm']) !!}
                                        <div class="col-sm-9">
                                            {!! Form::password('password',['class'=>'form-control form-control-sm','placeholder'=>'Enter new password']) !!}
                                            <small class="text-muted" style="font-size: 0.75rem;">Leave blank to keep current password</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 mb-3">
                                <div class="col-12">
                                    <div class="card border border-light">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0 text-dark">KYC Documents</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="table-responsive mb-4">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>Card No</th>
                                                            <th class="text-center">Front</th>
                                                            <th class="text-center">Back</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($userModel['kyc_docs'] as $kyc)
                                                            <tr>
                                                                <td>{{ \App\Models\KycDoc::$kycTypes[$kyc->kyc_type] ?? 'N/A' }}</td>
                                                                <td class="font-weight-bold">{{ $kyc->card_no }}</td>
                                                                <td class="text-center">
                                                                    @if($kyc->card_front)
                                                                        <a href="{{ asset('images/kyc_docs/'.$kyc->card_front) }}" target="_blank">
                                                                            <img src="{{ asset('images/kyc_docs/'.$kyc->card_front) }}" style="width: 40px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted small">No image</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($kyc->card_back)
                                                                        <a href="{{ asset('images/kyc_docs/'.$kyc->card_back) }}" target="_blank">
                                                                            <img src="{{ asset('images/kyc_docs/'.$kyc->card_back) }}" style="width: 40px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted small">No image</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted">No KYC documents uploaded yet</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="bg-light p-3 rounded border">
                                                <h6 class="mb-3 font-weight-bold" style="font-size: 0.9rem;">Add / Update KYC Document</h6>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group small mb-2">
                                                            {!! Form::label('kyc_type','KYC Type') !!}
                                                            {!! Form::select('kyc_type',\App\Models\KycDoc::$kycTypes,null,['class'=>'form-control form-control-sm py-1','placeholder'=>'Select Type', 'style' => 'min-height: 30px;']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group small mb-2">
                                                            {!! Form::label('card_no','Card Number') !!}
                                                            {!! Form::text('card_no',null,['class'=>'form-control form-control-sm py-1','placeholder'=>'Enter Card No','style'=>'min-height: 30px;']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group small mb-2">
                                                            {!! Form::label('card_front','Front Image') !!}
                                                            {!! Form::file('card_front',['class'=>'form-control small p-2 d-block']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group small mb-2">
                                                            {!! Form::label('card_back','Back Image') !!}
                                                            {!! Form::file('card_back',['class'=>'form-control small p-2 d-block']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12 text-right">
                                    {!! Form::submit('Update User Profile',['class'=>'btn btn-main btn-sm px-4']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    @else
                        <div class="text-center py-5">
                            <i class="icon-magnifier text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">Enter a Member ID above to view and edit user profile details.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/treeview.js?ref='.rand(1111,9999)) }}" type="text/javascript"></script>
@endsection
