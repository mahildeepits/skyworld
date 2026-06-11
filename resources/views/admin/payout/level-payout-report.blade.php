@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">Level Payout Report</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Transfer To</th>
                                    <th>Payout Of User</th>
                                    <th>Amount</th>
                                    <th>TDS</th>
                                    <th>Admin Charges</th>
                                    <th>Net Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payouts as $key => $payout)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $payout->user_id }}</td>
                                        <td>{{ $payout->payout_of_user }}</td>
                                        <td>{{ $payout->amount }}</td>
                                        <td>{{ $payout->tds }}</td>
                                        <td>{{ $payout->admin_charges }}</td>
                                        <td>{{ $payout->net_amount }}</td>
                                        <td>{{ $payout->created_at->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="4" class="text-right">
                                        Total Amount
                                    </th>
                                    <th>₹{{ number_format($payouts->sum('net_amount'),2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
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
