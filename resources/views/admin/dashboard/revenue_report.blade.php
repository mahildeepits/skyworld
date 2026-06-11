@extends('admin.layouts.admin')
@section('title', $title)
@section('content')
    <div class="row">
        <div class="col-md-12 my-3">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2">Total Deposit (Success)</p>
                            <h4>$ {{ number_format($total_deposit, 2) }}</h4>
                        </div>
                        <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2">Total Withdrawal (Success)</p>
                            <h4>$ {{ number_format($total_withdrawal_success, 2) }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2">Total Withdrawal (Pending)</p>
                            <h4>$ {{ number_format($total_withdrawal_pending, 2) }}</h4>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.company.revenue.report') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label for="from_date">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $from }}">
                        </div>
                        <div class="col-md-4">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $to }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter Report</button>
                            <a href="{{ route('admin.company.revenue.report') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th>#</th>
                                    <th>Member ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                                        <td>{{ $transaction->user->member_id ?? 'N/A' }}</td>
                                        <td>
                                            @if($transaction->type == 'deposit')
                                                <span class="badge badge-success">Deposit</span>
                                            @else
                                                <span class="badge badge-primary">Withdrawal</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->status == 'success' || $transaction->status == 'Completed')
                                                <span class="text-success font-weight-bold">Success</span>
                                            @elseif($transaction->status == 'pending')
                                                <span class="text-warning font-weight-bold">Pending</span>
                                            @else
                                                <span class="text-danger font-weight-bold">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                        <td>$ {{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
