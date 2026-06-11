@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='Replace Tree' sub-menu='Network' />
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Replace Tree</h5>
                {!! Form::open(['route' => 'member.tree.replace','method' => 'post']) !!}
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">Parent User ID</label>
                            {!! Form::text('parent_id', null, ['class' => 'form-control']) !!}
                            @error('parent_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">User Tree For Replace</label>
                            {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
                            @error('user_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group  mt-4 pt-1">
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>@endsection

@section('scripts')
    @parent

@endsection
