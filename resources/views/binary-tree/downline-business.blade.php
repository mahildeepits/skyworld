@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Downline Business Report' sub-menu='Network' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">Downline Business Report</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group" role="group" aria-label="Business Period Filter">
                                <a href="{{ route('member.downline.business', ['filter' => 'till_date']) }}" class="btn btn-outline-primary {{ $filter == 'till_date' ? 'active' : '' }}">Till Date</a>
                                <a href="{{ route('member.downline.business', ['filter' => 'this_month']) }}" class="btn btn-outline-primary {{ $filter == 'this_month' ? 'active' : '' }}">This Month</a>
                                <a href="{{ route('member.downline.business', ['filter' => 'today']) }}" class="btn btn-outline-primary {{ $filter == 'today' ? 'active' : '' }}">Today</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap" id="downlineBusinessTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Level</th>
                                    <th>Name</th>
                                    <th>User ID</th>
                                    <th>Package</th>
                                    <th>Deposit Amount</th>
                                    <th>Profit Income</th>
                                    <th>IB Income</th>
                                    <th>Total Business</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>Level {{ $item->level }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->member_id }}</td>
                                        <td>
                                            @if ($item->user->is_paid)
                                                <span class="bg-success text-white px-3 py-1 rounded">{{ $item->user->agentCategory()?->name ?? 'Paid' }}</span>
                                            @else
                                                <span class="bg-danger text-white px-3 py-1 rounded">No Package</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>${{ number_format($item->user->getTotalDeposits(), 2) }}</strong>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($item->roi_income, 2) }}</strong>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($item->ib_income, 2) }}</strong>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($item->total_income, 2) }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No downline found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($report->isNotEmpty())
                                <tfoot>
                                    <tr class="table-dark">
                                        <th colspan="6" class="text-end">Total</th>
                                        <th>${{ number_format($report->sum('roi_income'), 2) }}</th>
                                        <th>${{ number_format($report->sum('ib_income'), 2) }}</th>
                                        <th>${{ number_format($report->sum('total_income'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    @parent
    <style type="text/css">
        .btn-group .btn.active {
            background-color: #b66dff;
            border-color: #b66dff;
            color: #fff;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#downlineBusinessTable tbody tr').length > 1) {
                $('#downlineBusinessTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    order: [[1, 'asc']]
                });
            }
        });
    </script>
@endsection
