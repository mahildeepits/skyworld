@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Top-Up' sub-menu='Home' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>Please Pay Your Joining Amount</h3>
                        <p>Or</p>
                        <h4>Top-up your account</h4>
                        <a href="javascript:void(0)" class="btn btn-primary topupNow">Top-up Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('css')
    @parent

@endsection
@section('scripts')
    @parent

@endsection
