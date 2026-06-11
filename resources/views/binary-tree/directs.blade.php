@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='My Directs' sub-menu='Network' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="card-title">My Directs</h5>
                    </div>
                    <div class="col-md-12 mt-3">
                        <table class="table " >
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>User ID</th>
                                    <th>Is Paid ?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($directs as $key => $user)
                                    <tr>
                                        <td>{{ $loop->index +1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->member_id }}</td>
                                        <td>
                                            @if ($user->is_paid)
                                                <span class="bg-success text-white px-3 py-1 rounded">Yes</span>
                                            @else
                                                <span class="bg-danger text-white px-3 py-1 rounded">No</span>
                                            @endif
                                        </td>
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
</div>@endsection
@section('css')
    @parent
    <style type="text/css">

    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        f
    </script>
@endsection
