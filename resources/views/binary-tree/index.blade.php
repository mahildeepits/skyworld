@extends('layout.main')
@section('content')
    <style>
        .add-member-form{
            width: 50%;
            margin: 0 auto;
        }
        .custom-input{
            height: 30px;
            font-size: 13px;
        }
        .btn-green{
            background-color: #0f761cc7;
            color: white;
        }
    </style>
    <!--breadcrumb-->
    <x-page-breadcrumb current-page='View Tree' sub-menu='Network' />
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="position-relative" style="width: 280px;">
                                <div class="input-group shadow-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-magnifier"></i></span>
                                    </div>
                                    {!! Form::text('search',null,['class'=>'form-control userId','placeholder'=>'Search Member ID...']) !!}
                                </div>
                            </div>
                            <button type="button" class="btn btn-main btn-sm mx-2 view-id shadow-sm" style="border-radius: 10px; height: 35px;">
                                View Tree
                            </button>
                        </div>
                        
                        @if (auth()->guard('member')->user()->userRole->for_admin)
                            <a href="{{route('register')}}" class="btn btn-outline-primary btn-sm px-4 py-2" style="border-radius: 10px; border-width: 2px; font-weight: 700;">
                                <i class="icon-user-follow mr-1"></i> Add New Member
                            </a>
                        @endif
                    </div>
                    
                    {!! Form::hidden('tree_number',request()->number) !!}
                    
                    <div id="tree" class="mt-2 border rounded p-3 bg-light-subtle" style="min-height: 400px; background: #fcfdfe;">
                        <!-- Tree will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('js/treeview.js?ref='.rand(1111,9999)) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            getTree(`{{auth()->guard('member')->user()->member_id }}`);
            $('.view-id').click(function(){
                var userid = $('.userId').val().trim();
                if(userid === ''){
                    toasterMessage('error', 'Please enter a valid User ID');
                }else{
                    $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i> Loading...').prop('disabled', true);
                    getTree(userid);
                    setTimeout(() => {
                        $(this).html('View Tree').prop('disabled', false);
                    }, 1000);
                }
            });
        });
    </script>
@endsection
