@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <style>
        .over-flow-scroll{
            overflow-x: scroll;
        }
    </style>
    <div class="row">
        <div class="col-md-12 mb-3">
            <h4 class="card-title">All Payouts</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
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
                            <label for="member_id">Member ID</label>
                            {!! Form::select('member_id',[],null,['class'=>'form-control filter select2-ajax','placeholder'=>'Enter Member id','id' => 'member_id']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Income Type</label>
                            {!! Form::select('income_type',['direct' => 'Direct','level' => 'Level','autopool' => 'Autopool','charity' => 'Charity'],null,['class' => 'form-control filter','placeholder' => 'All','id' => 'income_type']) !!}
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
                    <div class="col-lg-12 text-nowrap">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    {!! $dataTable->scripts() !!}
    <script>
        const alluserspayoutsDataTable = $('#alluserspayouts-table');
        $(document).ready(function(){
            alluserspayoutsDataTable.parent().addClass('over-flow-scroll');
        });
        alluserspayoutsDataTable.on('preXhr.dt',function(e, settings,data){
            data.from = $('#from_date').val();
            data.to = $('#to_date').val();
            data.type = $('#list_type').val();
            data.status = $('#payment_status').val();
            data.user_id = $('#member_id').val();
            data.income_type = $('#income_type').val();
        });
        $(function(){
            $('.filter').on('change',function(){
                alluserspayoutsDataTable.DataTable().ajax.reload();
            });
        });
    </script>
@endsection
