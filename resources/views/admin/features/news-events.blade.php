@extends('admin.layouts.admin')
@section('title','MLM Software - Admin Panel')
@section('content')
    <div class="row">
        <div class="col-md-12 my-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title">News & Events</h4>
            <a href="javascript:void(0)" class="btn btn-main btn-sm" onclick="commanModel(`{{route('news.events.create')}}`,'Add News/Event')">Add</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
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
    {{ $dataTable->scripts() }}
@endsection