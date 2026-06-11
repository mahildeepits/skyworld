@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">Pin Status</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open() !!}
                             <h4 class="card-title">Enter pin details</h4>
                            <div class="row mt-4">
                                <div class="col-md-3 @error('kit_name') has-error @enderror">
                                    {!! Form::label('pin_no','Pin No') !!}
                                    {!! Form::text('pin_no',null,['class'=>'form-control','autocomplete'=>'off']) !!}
                                    @error('kit_name')
                                        <span class="help-block text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3 pt-1">
                                     {!! Form::submit('View Pin Status',['class'=>'btn btn-main btn-md mt-4']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                        <div class="divider"></div>
                        @if($epinModel)
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Pin No</th>
                                    <td>{{ $epinModel->pin_no }}</td>
                                </tr>
                                <tr>
                                    <th>Pin Status</th>
                                    <td>
                                        @if($epinModel->used_by != null && $epinModel->transfer_to == null)
                                            @php($userName = \App\Models\User::find($epinModel->used_by)->name)
                                            <span>Pin is used by: <a href="{{ route('admin.users',$userName) }}" target="_blank"> <span class="badge badge-primary badge-md" style="font-size: 12px;">{{ $userName }}</span></a> at {{ $epinModel->created_at->diffForHumans() }}</span>
                                        @elseif($epinModel->transfer_to != null && $epinModel->used_by == null)
                                            @php($userName = \App\Models\User::find($epinModel->transfer_to)->name)
                                            <span>Pin transfer to : <a href="{{ route('admin.users',$userName) }}" target="_blank"><span class="badge badge-primary badge-md" style="font-size: 12px;">{{ $userName }}</span></span> at {{ \Carbon\Carbon::parse($epinModel->transferred_at)->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>
                                        <span class="badge badge-danger" style="font-size: 12px;">{{ $epinModel->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            </table>
                        @elseif($epinModel == null && request()->isMethod('post'))
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h5 class="font-italic text-info">Pin not availble!</h5>
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
    <script src="{{ asset('js/treeview.js?ref='.rand(1111,9999)) }}" type="text/javascript"></script>
@endsection
