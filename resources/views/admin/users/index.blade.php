@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    @if(request()->has('kyc_details'))
        <div class="modal fade" id="kyc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Manage KYC Documents</h5>
                        <button type="button" onclick="window.location.href='{{ route('admin.users') }}'" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($kycDetails->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>KYC Type</th>
                                            <th>Card No</th>
                                            <th>Front</th>
                                            <th>Back</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kycDetails as $kyc)
                                            <tr>
                                                <td>{{ \App\Models\KycDoc::$kycTypes[$kyc->kyc_type] ?? 'N/A' }}</td>
                                                <td>{{ $kyc->card_no }}</td>
                                                <td>
                                                    @if($kyc->card_front)
                                                        <a href="{{ asset('images/kyc_docs/'.$kyc->card_front) }}" target="_blank">
                                                            <img src="{{ asset('images/kyc_docs/'.$kyc->card_front) }}" width="50" />
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kyc->card_back)
                                                        <a href="{{ asset('images/kyc_docs/'.$kyc->card_back) }}" target="_blank">
                                                            <img src="{{ asset('images/kyc_docs/'.$kyc->card_back) }}" width="50" />
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kyc->status == 1)
                                                        <span class="badge badge-success">Approved</span>
                                                    @elseif($kyc->status == 2)
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kyc->status == 0)
                                                        <a href="{{ route('admin.approve.kyc', $kyc->id) }}" class="btn btn-success btn-xs">Approve</a>
                                                        <a href="{{ route('admin.reject.kyc', $kyc->id) }}" class="btn btn-danger btn-xs">Reject</a>
                                                    @else
                                                        <span class="text-muted small">No action required</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center"><i>No record uploaded</i></div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Close</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 my-3">
            <h4 class="card-title">Users</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped static-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Sponsor</th>
                                    <th>Is Paid</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->member_id }}</td>
                                        <td title="{{ \Illuminate\Support\Facades\Crypt::decrypt($user->enc_password) }}">{{ \Illuminate\Support\Facades\Crypt::decrypt($user->enc_password) }}</td>
                                        <td>{{ $user->sponsor_id }}</td>
                                        <td>
                                            @if ($user->is_paid == 1)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                            <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->mobile }}</td>

                                        <td>
                                            <a target="_blank" href="{{ route('admin.edit.user',['member_id'=>$user->member_id]) }}" class="btn btn-info btn-sm">Edit</a>
                                            <!-- <a href="javascript:void(0)" onclick="commanModel(`{{route('admin.setpaid.form',$user->id)}}`,'SET USER PAID')" class="btn btn-main btn-sm">SET PAID</a> -->
                                            <a href="{{ route('admin.users',['kyc_details'=>$user->id]) }}" class="btn btn-warning btn-sm" title="View KYC">KYC</a>
                                            {{-- 
                                            @if($user->is_blocked)
                                                <a href="{{ route('admin.users',['unblock_user'=>$user->id]) }}" class="btn btn-danger btn-xs">Un-Block</a>
                                            @else
                                                <a href="{{ route('admin.users',['block_user'=>$user->id]) }}" class="btn btn-warning btn-xs">Block</a>
                                            @endif
                                            @if($user->is_paid == 0 && $user->user_icon == 'golden.png')
                                                <a href="{{ route('set-user-to-paid',$user->id) }}" onclick="return confirm('Are you sure to set as paid user ?')" class="btn btn-dark btn-xs">Paid</a>
                                            @endif --}}

{{--                                            @if($user->is_franchise)--}}
{{--                                                <a href="{{ route('admin.franchise.user',['user_id' => $user->id, 'type' => 'remove']) }}" class="btn btn-danger btn-xs mt-1s" title="Remove as a Franchise user"><i class="fa fa-user-times" aria-hidden="true"></i></a>--}}
{{--                                            @else--}}
{{--                                                <a href="{{ route('admin.franchise.user',['user_id' => $user->id, 'type' => 'make']) }}" class="btn btn-info btn-xs mt-1s" title="Make as a Franchise user"><i class="fa fa-user-plus" aria-hidden="true"></i></a>--}}
{{--                                            @endif--}}

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
{{--                        <div class="float-right">--}}
{{--                            {!! $users->render('vendor.pagination.default') !!}--}}
{{--                        </div>--}}
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
            @if(request()->q)
                setTimeout(function(){
                    $('input[type=search]').val('{{ request()->q }}').trigger('keyup');
                },500);
            @endif
            @if(request()->has('kyc_details'))
                $('#kyc-modal').modal('show');
            @endif
        });
    </script>
@endsection
