<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/chartist/chartist.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/css/vertical-light-layout/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('stellar_assets/images/favicon.png') }}" />
    
    <link href="{{ asset('plugins/dataTables/css/datatables.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('plugins/toastr/css/toastr.min.css') }}" rel="stylesheet" >
    <link href="{{  asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    
    <style>
        .select2-container{
            border: 1px solid #ced4da;
            border-radius: 3px;
            width: 100% !important;
        }
        .bg-main {
            background-color: #35074a !important;
        }
        .text-main {
            color: #35074a !important;
        }
        .btn-main {
            background: linear-gradient(180deg, #00e2fb, #034bb3)!important;
            color: white!important;
            border: none!important;
        }
        /* Navbar Fixes */
        .navbar {
            z-index: 1030;
        }
        .navbar .navbar-brand-wrapper {
            background: #181824 !important; /* Match sidebar */
            height: 70px !important;
        }
        .navbar .navbar-menu-wrapper {
            height: 70px !important;
            background: #fff !important;
        }
        .sidebar {
            position: fixed !important;
            top: 70px !important;
            bottom: 0 !important;
            height: auto !important;
            min-width: 240px;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            z-index: 1020;
            background: #181824 !important; /* Ensure background stays dark */
            padding-bottom: 80px !important; /* Extra space at bottom */
        }
        .page-body-wrapper {
            padding-top: 70px !important;
            min-height: calc(100vh - 70px);
        }
        .main-panel {
            margin-left: 240px;
            min-height: calc(100vh - 70px);
            padding-top: 0 !important;
            width: calc(100% - 240px) !important;
        }
        /* Make scrollbar clearly visible */
        .sidebar::-webkit-scrollbar {
            width: 6px !important;
            display: block !important;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #2a2a3e !important;
            border-radius: 10px !important;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent !important;
        }
        @media (max-width: 991px) {
            .sidebar {
                position: fixed !important;
                margin-left: -241px;
                transition: margin-left 0.4s ease;
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
                z-index: 1050 !important;
            }
            .sidebar.active {
                margin-left: 0 !important;
            }
            .main-panel {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .navbar .navbar-menu-wrapper {
                padding-right: 15px;
            }
        }
        .user-dropdown img {
            width: 35px !important;
            height: 35px !important;
        }
        .navbar-nav-right {
            margin-left: auto;
        }
        .navbar-toggler {
            color: #333 !important;
            font-size: 1.5rem !important;
        }
        .navbar-toggler:focus {
            box-shadow: none !important;
            outline: none !important;
        }
        /* DataTables Styling */
        .dataTables_paginate {
            margin-top: 15px !important;
            float: right;
            display: flex;
            align-items: center;
        }
        .dataTables_paginate .paginate_button {
            padding: 6px 12px !important;
            margin-left: 5px !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 4px !important;
            cursor: pointer !important;
            background: #fff !important;
            color: #034bb3 !important;
            text-decoration: none !important;
            display: inline-block !important;
            font-size: 13px;
        }
        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(180deg, #00e2fb, #034bb3) !important;
            color: #fff !important;
            border-color: #034bb3 !important;
        }
        .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #e9ecef !important;
            color: #034bb3 !important;
        }
        .dataTables_paginate .paginate_button.disabled {
            color: #6c757d !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 6px 12px !important;
            margin-left: 10px !important;
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 4px 8px !important;
            margin: 0 5px !important;
            outline: none;
        }
        .dataTables_info {
            margin-top: 15px !important;
            font-size: 13px;
            color: #6c757d;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem !important;
            font-size: 0.75rem !important;
        }
        /* Custom Balanced Modal */
        .modal-md-custom {
            max-width: 750px !important;
        }
        @media (max-width: 768px) {
            .modal-md-custom {
                max-width: 96% !important;
                margin: 0.5rem auto !important;
            }
        }
        /* Tighten Spacing */
        .form-group {
            margin-bottom: 0.8rem !important;
        }
        .form-control{
            padding: 0.4375rem 0.75rem !important;
            font-size: 0.8125rem !important;
            line-height: 1.5 !important;
        }
    </style>
    @yield('styles')
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:admin.partial._navbar -->
      @include('admin.partial._navbar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:admin.partial._sidebar -->
        @include('admin.partial._sidebar')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <!-- <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © MLM Software {{ date('Y') }}</span>
            </div>
          </footer> -->
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    
    <div class="modal fade" id="comman-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title">Modal Title</h5>
                    <button type="button" class="close closeModel" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('stellar_assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('stellar_assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/chartist/chartist.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('stellar_assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/misc.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('stellar_assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page -->
    
    <script src="{{ asset('plugins/dataTables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    
    <script>
        function route(){
            return '{{ url('/') }}';
        }
        
        $(document).ready(function(){
            $('.static-datatable').dataTable({
                bSort: false,
            });
            
            toastr.options = {
                closeButton: true,
                debug: false,
                progressBar: true,
                preventDuplicates: true,
                hideDuration: 800,
                showDuration: 300,
                extendedTimeOut: 4000,
                positionClass: 'toast-top-right',
            };
            
            @if(session()->has('success'))
                @php $message = explode('|', session('success')); @endphp
                toastr.success('{{ $message[1] ?? $message[0] }}', '{{ $message[0] }}')
            @elseif(session()->has('error'))
                @php $message = explode('|', session('error')); @endphp
                toastr.error('{{ $message[1] ?? $message[0] }}', '{{ $message[0] }}')
            @endif
        });
        
        const toasterMessage = toastr;
        
        function ajaxOnClick(route, method, data = {}){
            $.ajax({
                url: route,
                type: method,
                data: data,
                success: function(res){
                    if(res.status){
                        toasterMessage.success('Success', res.message);
                        if($(document).find('table').length > 0 && $.fn.DataTable.isDataTable($(document).find('table')[0])) {
                            $(document).find('table').DataTable().ajax.reload();
                        } else {
                            setTimeout(() => { window.location.reload(); }, 2000);
                        }
                    } else {
                        toasterMessage.error('Error', res.message);
                    }
                },
                error: function(err){
                    toasterMessage.error('Error', 'Something went wrong');
                }
            });
        }
        
        function commanModel(route, title, size = ''){
            var commanModal = $('#comman-modal');
            commanModal.find('.modal-dialog').removeClass('modal-lg modal-sm').addClass(size);
            $.ajax({
                url: route,
                type: 'get',
                success: function(res){
                    if(res.status){
                        commanModal.modal('show');
                        commanModal.find('.modal-title').text(title);
                        commanModal.find('.modal-body').html(res.html);
                    }
                }
            });
        }
        
        function ajaxFormSubmit(form){
            event.preventDefault();
            $('.invalid-feedback').removeClass('d-block text-danger').text('');
            var formData = new FormData(form[0]);
            var route = form.attr('action');
            var method = form.attr('method');
            $.ajax({
                url: route,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(res){
                    if(res.status){
                        toasterMessage.success('Success', res.message);
                        if(res.modal){
                            form.trigger('reset');
                            setTimeout(() => {
                                $(document).find('.closeModel').trigger('click');
                                if($(document).find('table').DataTable()) {
                                    $(document).find('table').DataTable().ajax.reload();
                                }
                            }, 700);
                        } else if(res.redirect){
                            setTimeout(() => { window.location.href = res.redirect; }, 1000);
                        } else if (res.refresh || res.refresh == undefined) {
                            setTimeout(() => { window.location.reload(); }, 1000);
                        }
                    } else {
                        toasterMessage.error('Error', res.message);
                    }
                },
                error: function(error){
                    $.each(error.responseJSON.errors, function(key, message){
                        $('input[name="'+key+'"]').parents('.form-group').find('.invalid-feedback').text(message[0]).addClass('text-danger d-block');
                    });
                }
            })
        }
    </script>
    @yield('scripts')
  </body>
</html>
