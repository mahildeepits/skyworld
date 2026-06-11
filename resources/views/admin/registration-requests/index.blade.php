@extends('admin.layouts.admin')
@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pending Registration Requests</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Member ID </th>
                                <th> Name </th>
                                <th> Package / Amount </th>
                                <th> Sponsor </th>
                                <th> Status </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $req->user->member_id ?? 'N/A' }}</td>
                                <td>{{ $req->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $req->agentCategory->name ?? 'None' }} 
                                    @if($req->deposit_amount > 0)
                                        <br><small class="text-muted">Deposit: ${{ $req->deposit_amount }}</small>
                                    @endif
                                </td>
                                <td>{{ $req->user->sponsor_id ?? 'N/A' }}</td>
                                <td>
                                    @if($req->status == 'pending')
                                        <label class="badge badge-warning">Pending</label>
                                    @elseif($req->status == 'approved')
                                        <label class="badge badge-success">Approved</label>
                                    @else
                                        <label class="badge badge-danger">Rejected</label>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status == 'pending')
                                        <a href="{{ route('admin.registration.requests.approve', $req->id) }}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this registration?');">Approve</a>
                                        <a href="{{ route('admin.registration.requests.reject', $req->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this registration?');">Reject</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($requests->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">No registration requests found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
