@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <style>
        .over-flow-scroll{
            overflow-x: scroll;
        }
    </style>
    <div class="row">
        <div class="col-md-12 my-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title">Withdrawal Requests</h4>
            <!-- <a class="btn btn-sm btn-main" href="{{ route('settle.bonuses') }}">Settle Bonuses</a> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="from_date">From Date</label>
                        {!! Form::date('from_date', request('from_date'), ['class' => 'form-control filters','id' => 'from_date']) !!}
                    </div>
                    <div class="col-md-3">
                        <label for="to_date">To Date</label>
                        {!! Form::date('to_date', request('to_date'), ['class' => 'form-control filters','id' => 'to_date']) !!}
                    </div>
                    <div class="col-md-3">
                        <label for="status">Status</label>
                        {!! Form::select('status',['success' => 'Success', 'pending' => 'Pending'], request('status'), ['class' => 'form-control filters','id' => 'status','placeholder' => 'All']) !!}
                    </div>
                    <div class="col-lg-12 text-nowrap mt-3 p-0">
                        {!! $dataTable->table() !!}
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
        const transactionDataTable = $('#transaction-table');
        $(document).ready(function(){
            transactionDataTable.parent().addClass('over-flow-scroll');
        });
        transactionDataTable.on('preXhr.dt',function(e, settings,data){
            data.from_date = $('#from_date').val();
            data.to_date = $('#to_date').val();
            data.status = $('#status').val();
        });
        $(function(){
            $('.filters').on('change',function(){
                transactionDataTable.DataTable().ajax.reload();
            });
        });
    </script>
@endsection