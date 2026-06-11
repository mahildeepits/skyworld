@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='My Purchases' sub-menu='Portal' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-header">
                <h6>Purchases</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-striped" id="datatable-sale-entries">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date Purchase</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myPurchases as $key => $singlePurchase)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $singlePurchase->created_on }}</td>
                                        <td>₹{{ $singlePurchase->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
    <script>

        $(document).ready(function() {
            $('#datatable-sale-entries').DataTable({
                bSort: false,
            });
        });

    </script>

@endsection
