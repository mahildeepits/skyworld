@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 mb-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title">Tasks</h4>
            <a href="javascript:void(0)" onclick="commanModel(`{{route('tasks.create')}}`,'Add Task')" class="btn btn-main btn-sm"> + Add </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body px-2">
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
    {{-- <script>
        const tasksDataTable = $('#tasks-table');
        $(document).ready(function(){
            tasksDataTable.parent().addClass('over-flow-scroll');
        });
    </script> --}}
@endsection
