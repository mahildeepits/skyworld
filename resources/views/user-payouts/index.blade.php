@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='My Payouts' sub-menu='Payout' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">My Payouts</h4>
                <div class="row mb-4 mx-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">From date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control filter" id="from_date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">To date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control filter" id="to_date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Income Type</label>
                            {!! Form::select('income_type',['direct' => 'Direct','level' => 'Level','autopool' => 'Autopool'],null,['class' => 'form-control filter','placeholder' => 'All','id' => 'income_type']) !!}
                        </div>
                    </div>
                    @if ($type == null)
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">List type</label>
                                {!! Form::select('status',['requested' => 'Requested','not_requested' => 'Not Requested'],null,['class' => 'form-control filter','placeholder' => 'All','id' => 'list_type']) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Payment Status</label>
                            {!! Form::select('status',['paid' => 'Paid','not_paid' => 'Not paid'],null,['class' => 'form-control filter','placeholder' => 'All','id' => 'payment_status']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-nowrap">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('scripts')
    @parent
    {!! $dataTable->scripts() !!}
    <script>
        const userpayoutsDataTable = $('#userpayouts-table');
        $(document).ready(function(){
            userpayoutsDataTable.parent().addClass('over-flow-scroll');
        });
        userpayoutsDataTable.on('preXhr.dt',function(e, settings,data){
            data.from = $('#from_date').val();
            data.to = $('#to_date').val();
            data.type = $('#list_type').val();
            data.status = $('#payment_status').val();
            data.income_type = $('#income_type').val();
        });
        $(function(){
            $('.filter').on('change',function(){
                userpayoutsDataTable.DataTable().ajax.reload();
            });
        });
    </script>
@endsection
