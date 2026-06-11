@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='My Downline' sub-menu='Network' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="card-title">All Levels</h5>
                    </div>
                    @for ($i=1; $i <= 3 ; $i++)
                        @if ($i <= 10)
                            @php
                                if($i > 1){
                                    $loopChilds = getSponsoredChilds($loopChilds->pluck('member_id')->toArray());
                                }else{
                                    $loopChilds = $childs;
                                }
                            @endphp
                            @if ($loopChilds->count() > 0)
                                <div class="col-md-12 mt-3">
                                    <h6 class="card-title">Team {{ chr(64 + $i) }}</h6>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>UserID</th>
                                                <th>Is Paid</th>
                                                {{-- <th>Position</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($loopChilds as $user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->member_id }}</td>
                                                    <td>
                                                        @if ($user->is_paid)
                                                            <span class="bg-success text-white px-3 py-1 rounded">Yes</span>
                                                        @else
                                                            <span class="bg-danger text-white px-3 py-1 rounded">No</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td>{{ ucfirst($user->parent_leg) }}</td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    @endfor
                </div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
@endsection
