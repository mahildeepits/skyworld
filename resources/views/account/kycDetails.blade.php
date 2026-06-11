@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='KYC Doc' sub-menu='Portal' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                {!! Form::model($model,['method'=>'post','route'=>'update.kyc-documents','files'=>true]) !!}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5 class="mb-0">Edit KYC Doc</h5>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        {!! Form::label('name', 'Name:', ['class' => 'font-weight-normal mb-1', 'style' => 'font-size: 0.9rem;']) !!}
                                        {!! Form::text('name', auth()->guard('member')->user()->name, ['class' => 'form-control', 'readonly']) !!}
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        {!! Form::label('kyc_type', 'KYC Type:', ['class' => 'font-weight-normal mb-1', 'style' => 'font-size: 0.9rem;']) !!}
                                        {!! Form::select('kyc_type', \App\Models\KycDoc::$kycTypes, null, ['class' => 'form-control', 'id' => 'kyc_type', 'placeholder' => 'Select KYC Type']) !!}
                                        <!-- <small class="text-danger mt-1 d-block" style="font-size: 0.70rem; line-height: 1.2;">Note: Once you select your KYC details, it will never change again!</small> -->
                                        @error('kyc_type')
                                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                @php
                                    $readonly = false;
                                @endphp
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        {!! Form::label('card_no', 'Card No:', ['class' => 'font-weight-normal mb-1', 'style' => 'font-size: 0.9rem;']) !!}
                                        {!! Form::text('card_no', null, ['class' => 'form-control', 'id' => 'card_no', 'placeholder' => 'Enter Card No', 'readonly' => $readonly]) !!}
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        {!! Form::label('card_front', 'Card Front Image:', ['class' => 'font-weight-normal mb-1', 'style' => 'font-size: 0.9rem;']) !!}
                                        {!! Form::file('card_front', ['class' => 'form-control p-2 d-block', 'accept'=>'image/*']) !!}
                                        @error('card_front')
                                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        {!! Form::label('card_back', 'Card Back Image:', ['class' => 'font-weight-normal mb-1', 'style' => 'font-size: 0.9rem;']) !!}
                                        {!! Form::file('card_back', ['class' => 'form-control p-2 d-block', 'accept'=>'image/*']) !!}
                                        @error('card_back')
                                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-4">
                                    @php
                                        $isAnyApproved = $model->where('status', 1)->first();
                                    @endphp
                                    @if($isAnyApproved)
                                        <div class="alert alert-success py-2">Your KYC is already approved. You cannot edit it anymore.</div>
                                    @else
                                        {!! Form::submit('Save KYC Details', ['class' => 'btn btn-info text-white px-4 py-2', 'style' => 'background-color: #1dd3d5; border-color: #1dd3d5;']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4 pt-3 border-top">
                            <h6 class="mb-3 font-weight-bold">Uploaded Documents</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>KYC Type</th>
                                            <th>Card No</th>
                                            <th class="text-center">Image Front</th>
                                            <th class="text-center">Image Back</th>
                                            <th>Status</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($model as $k => $details)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ \App\Models\KycDoc::$kycTypes[$details->kyc_type] ?? 'N/A' }}</td>
                                            <td class="font-weight-bold">{{ $details->card_no }}</td>
                                            <td class="text-center">
                                                @if($details->card_front)
                                                    <a href="{{ asset('images/kyc_docs/'.$details->card_front) }}" target="_blank">
                                                        <img src="{{ asset('images/kyc_docs/'.$details->card_front) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;"/>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No image</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($details->card_back)
                                                    <a href="{{ asset('images/kyc_docs/'.$details->card_back) }}" target="_blank">
                                                        <img src="{{ asset('images/kyc_docs/'.$details->card_back) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;"/>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No image</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($details->status == 1)
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($details->status == 2)
                                                    <span class="badge bg-danger">Rejected</span>
                                                    <br/>
                                                    <button type="button" class="btn btn-warning btn-sm py-1 mt-1 edit-kyc" 
                                                        data-type="{{ $details->kyc_type }}" 
                                                        data-no="{{ $details->card_no }}">Edit / Resubmit</button>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $details->admin_remark ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No KYC documents uploaded yet</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    @parent
    <style type="text/css">
        .form-group.mb-2 {
            margin-bottom: 0.6rem !important;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.edit-kyc', function() {
                var type = $(this).data('type');
                var no = $(this).data('no');
                $('#kyc_type').val(type);
                $('#card_no').val(no);
                // Highlight the form
                $('html, body').animate({
                    scrollTop: $(".card-body").offset().top - 20
                }, 500);
            });
        });
    </script>
@endsection
