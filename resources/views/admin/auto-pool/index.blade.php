@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <style>
        .over-flow-scroll{
            overflow-x: scroll;
        }
    </style>
    <div class="row">
        <div class="col-md-12 mb-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title">Autopool List</h4>
            <a href="{{route('auto-pool.create')}}" class="btn btn-main btn-sm"> Create Autopool</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-nowrap">
                        {!! $dataTable->table() !!}
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
        const autopoolDataTable = $('#autopool-table');
        $(document).ready(function(){
            autopoolDataTable.parent().addClass('over-flow-scroll');
        });
    </script>
@endsection
