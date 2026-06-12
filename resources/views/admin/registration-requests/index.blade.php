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
                                <td>
                                    {{ $loop->iteration }}
                                    @if($req->status == 'pending')
                                        <form id="approve-form-{{ $req->id }}" action="{{ route('admin.registration.requests.approve', $req->id) }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    @endif
                                </td>
                                <td>{{ $req->user->member_id ?? 'N/A' }}</td>
                                <td>{{ $req->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if($req->status == 'pending')
                                        <select name="agent_category_id" form="approve-form-{{ $req->id }}" class="form-control form-control-sm text-dark font-weight-bold" style="min-width: 150px; background-color: #fff; border: 1px solid #ccc;">
                                            <option value="">None (No Package)</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $req->agent_category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group input-group-sm mt-1" style="min-width: 150px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="deposit_amount" form="approve-form-{{ $req->id }}" class="form-control form-control-sm text-dark" value="{{ $req->deposit_amount }}" step="any" placeholder="Deposit Amount">
                                        </div>
                                    @else
                                        {{ $req->agentCategory->name ?? 'None' }} 
                                        @if($req->deposit_amount > 0)
                                            <br><small class="text-muted">Deposit: ${{ $req->deposit_amount }}</small>
                                        @endif
                                    @endif
                                    @if($req->receipt)
                                        <div class="mt-2">
                                            <a href="{{ asset('images/receipts/' . $req->receipt) }}" target="_blank" class="btn btn-outline-info btn-xs font-weight-bold">
                                                View Receipt
                                            </a>
                                        </div>
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
                                        <button type="submit" form="approve-form-{{ $req->id }}" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this registration?');">Approve</button>
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
