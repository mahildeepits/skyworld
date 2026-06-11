<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME', 'NEXO ') }}</title>

    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/chartist/chartist.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/css/vertical-light-layout/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('plugins/dataTables/css/datatables.min.css') }}">
    <link href="{{ asset('plugins/toast/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('icons/icon-192x192.png') }}" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    @section('css')
    @show
    <style>
      /* ════ GLOBAL ════════════════════════════════════════════ */
      :root {
        --blue-dark:  #0A1929;
        --blue:       #04c;
        --orange:     #ff2e17;
        --orange-lt:  #FF6B35;
        --grad:       #04c;
        --grad-dark:  #04c;
        --sidebar-w:  215px;
        --nav-h:      58px;
        --radius:     14px;
        --shadow:     0 4px 20px rgba(0,0,0,0.07);
        --shadow-h:   0 10px 32px rgba(21,101,192,0.18);
        --focus:      0 0 0 3px rgba(232,78,15,0.22);
      }
      * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
      body { background: #eef2f7; }

      /* ════ NAVBAR ════════════════════════════════════════════ */
      .default-layout-navbar {
        height: var(--nav-h) !important;
        background: white !important;
        /* box-shadow: 0 3px 22px rgba(21,101,192,0.45) !important; */
        border-bottom: 1px solid rgba(232,78,15,0.2) !important;
      }

      /* Brand wrapper: ONLY on mobile */
      .default-layout-navbar .navbar-brand-wrapper {
        display: none !important;           /* hidden on desktop */
        height: var(--nav-h) !important;
        background: white !important;
        border-right: 1px solid rgba(255,255,255,0.07) !important;
        width: 100% !important;
      }

      /* Menu wrapper fills full width on desktop */
      .default-layout-navbar .navbar-menu-wrapper {
        height: var(--nav-h) !important;
        background: transparent !important;
        padding: 0 20px 0 16px !important;
        width: 100% !important;
      }

      /* Welcome text */
      .nav-welcome-msg {
        color: rgba(255,255,255,0.85) !important;
        font-size: 0.86rem !important;
        font-weight: 400 !important;
        white-space: nowrap;
      }
      .nav-welcome-msg strong { color: #fff !important; font-weight: 700; }

      /* Wallet Pill */
      .nav-wallet-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(232,78,15,0.13);
        border: 1px solid rgba(232,78,15,0.4);
        color: var(--orange-lt) !important;
        padding: 6px 16px; border-radius: 50px;
        font-size: 0.8rem; font-weight: 600;
        text-decoration: none !important; transition: all 0.3s;
      }
      .nav-wallet-pill:hover {
        background: rgba(232,78,15,0.24); color: #fff !important;
        transform: translateY(-1px); box-shadow: 0 4px 14px rgba(232,78,15,0.3);
      }

      /* User Button */
      .nav-user-btn {
        display: inline-flex !important; align-items: center; gap: 8px;
        padding: 5px 12px 5px 5px !important;
        background: rgba(255,255,255,0.09) !important;
        border: 1px solid rgba(255,255,255,0.16) !important;
        border-radius: 50px !important;
        color: #fff !important; transition: all 0.3s !important;
      }
      .nav-user-btn::after { display: none !important; }
      .nav-user-btn:hover { background: rgba(255,255,255,0.16) !important; border-color: rgba(232,78,15,0.5) !important; }
      .nav-user-btn .img-xs { width: 30px !important; height: 30px !important; border: 2px solid rgba(232,78,15,0.55); object-fit: cover; }
      .nav-user-btn span { font-size: 0.82rem; font-weight: 500; }

      /* User Dropdown */
      .nav-user-dropdown {
        border: none !important; border-radius: 14px !important;
        box-shadow: 0 16px 50px rgba(0,0,0,0.18) !important;
        overflow: hidden !important; min-width: 240px !important;
        margin-top: 6px !important; padding: 0 !important;
      }
      .nav-user-dropdown-header { background: var(--grad) !important; color: #fff; padding: 22px 18px 18px; text-align: center; }
      .nav-user-dropdown-header img { width: 58px; height: 58px; border: 2px solid rgba(255,255,255,0.4); object-fit: cover; border-radius: 50%; }
      .nav-user-dropdown-header p { color: #fff !important; font-size: 0.88rem; font-weight: 600; margin: 0; }
      .nav-user-dropdown-header small { color: rgba(255,255,255,0.65); font-size: 0.74rem; }
      .nav-balance-chip { display: inline-block; background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 3px 12px; border-radius: 50px; font-size: 0.76rem; font-weight: 600; }
      .nav-user-dropdown .dropdown-item { padding: 9px 18px !important; font-size: 0.83rem !important; color: #374151 !important; transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
      .nav-user-dropdown .dropdown-item:hover { background: rgba(3,75,179,0.06) !important; padding-left: 22px !important; color: var(--blue) !important; }
      .nav-user-dropdown .dropdown-item.text-danger:hover { color: #dc3545 !important; background: rgba(220,53,69,0.06) !important; }
      .nav-user-dropdown .dropdown-divider { margin: 2px 0 !important; }

      /* ════ SIDEBAR ════════════════════════════════════════════ */
      .sidebar {
        position: fixed !important;
        top: 0 !important; left: 0 !important;
        height: 100vh !important;
        width: var(--sidebar-w) !important;
        background: #ffffff !important;
        overflow: hidden !important;
        z-index: 1100 !important;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: 4px 0 24px rgba(21,101,192,0.1) !important;
        border-right: 1px solid rgba(21,101,192,0.1) !important;
      }
      /* White sidebar — no background image or dark overlay */
      .sidebar::before { display: none; }
      .sidebar::after  { display: none; }
      .sidebar .nav,
      .sidebar-profile-card,
      .sidebar-logo-area { position: relative; z-index: 1; }

      /* ── Sidebar Logo (desktop, top of sidebar) */
      .sidebar-logo-area {
        padding: 14px 16px 10px;
        border-bottom: 1px solid rgba(21,101,192,0.1);
        background: #fff;
        flex-shrink: 0;
      }
      .sidebar-brand-link { display: block; }
      .sidebar-logo-full { height: 50px; width: auto; object-fit: contain; display: block; }

      /* ── Profile Card (centered) */
      .sidebar-profile-card {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        padding: 20px 14px 16px !important;
        border-bottom: 1px solid rgba(21,101,192,0.1) !important;
        background: linear-gradient(135deg, #f0f5ff 0%, #fff8f5 100%) !important;
        flex-shrink: 0;
      }
      .sidebar-profile-avatar-wrap { position: relative; margin-bottom: 10px; }
      .sidebar-profile-avatar-wrap img {
        width: 64px; height: 64px;
        object-fit: cover; border-radius: 50%;
        border: 3px solid rgba(232,78,15,0.7);
        box-shadow: 0 0 0 6px rgba(232,78,15,0.12), 0 0 20px rgba(232,78,15,0.45);
        display: block;
      }
      .sidebar-online-dot {
        position: absolute; bottom: 2px; right: 2px;
        width: 12px; height: 12px; background: #22c55e;
        border-radius: 50%; border: 2px solid #ffffff;
        box-shadow: 0 0 6px rgba(34,197,94,0.8);
      }
      .sidebar-profile-info { width: 100%; }
      .sidebar-profile-name {
        color: #0f172a !important; font-size: 0.88rem !important;
        font-weight: 700 !important; margin: 0 0 6px !important;
      }
      .sidebar-profile-role {
        display: inline-block;
        font-size: 0.62rem !important; color: var(--orange-lt) !important;
        text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;
        background: rgba(232,78,15,0.13);
        border: 1px solid rgba(232,78,15,0.35);
        padding: 2px 10px; border-radius: 50px;
      }

      /* ── Nav wrapper scrollable */
      .sidebar .nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-bottom: 20px;
        /* Hide scrollbar but allow scroll */
        scrollbar-width: none;
      }
      .sidebar .nav::-webkit-scrollbar { display: none; }

      /* ── Nav Items */
      .sidebar .nav .nav-item { padding: 0 6px !important; transition: none !important; }
      .sidebar .nav .nav-item .nav-link {
        display: flex !important; align-items: center !important;
        gap: 9px !important; padding: 8px 10px !important;
        color: rgba(15,23,42,0.65) !important;
        border-radius: 8px !important; margin: 1px 0 !important;
        transition: all 0.22s ease !important; border-top: none !important;
        white-space: nowrap; text-decoration: none;
      }
      .sidebar .nav .nav-item .nav-link:hover {
        /* color: var(--blue) !important; */
        background: rgba(21,101,192,0.08) !important;
        padding-left: 22px !important;
        box-shadow: none;
      }
      /* Icon on LEFT */
      .sidebar .nav .nav-item .nav-link i.menu-icon {
        font-size: 1rem !important; line-height: 1 !important;
        color: #ff2e17 !important;
        margin-left: 0 !important; margin-right: 0 !important;
        flex-shrink: 0; order: -1 !important;
        transition: color 0.2s; width: 18px; text-align: center;
      }
      .sidebar .nav .nav-item .nav-link:hover i.menu-icon { color: var(--orange-lt) !important; }
      .sidebar .nav .nav-item .nav-link .menu-title { font-size: 0.91rem !important; font-weight: 600; color: inherit !important; line-height: 1; flex: 1; }
            .sidebar .nav .nav-item .nav-link .menu-arrow { margin-left: auto !important; font-size: 0.7rem; opacity: 0.8; padding-right: 4px !important; }
      .sidebar .nav .nav-item .nav-link .menu-arrow::before {
        content: "\e606";
        font-family: 'simple-line-icons';
        display: block;
        transition: transform 0.2s ease;
      }
      .sidebar .nav .nav-item .nav-link[aria-expanded="true"] .menu-arrow::before {
        transform: rotate(90deg);
      }


      /* Active */
      .sidebar .nav .nav-item.active:not(.navbar-brand-mini-wrapper) { background: transparent !important; }
      .sidebar .nav .nav-item.active:not(.navbar-brand-mini-wrapper) > .nav-link {
        background: var(--blue) !important;
        /* border-left: 3px solid var(--orange) !important; */
        color: #fff !important; padding-left: 11px !important;
        box-shadow: 0 4px 14px rgba(21,101,192,0.28) !important;
      }
      .sidebar .nav .nav-item.active > .nav-link i.menu-icon { color: #ffffff !important; }
      .sidebar .nav .nav-item.active > .nav-link .menu-title { color: #fff !important; font-weight: 600 !important; }
      .sidebar .nav .nav-item.active + .nav-item .nav-link { border-top: none !important; }

      /* Category */
      .sidebar .nav .nav-item.nav-category { padding: 8px 8px 0 !important; }
      .sidebar .nav .nav-item.nav-category .nav-link {
        color: var(--orange) !important; font-size: 0.65rem !important; font-weight: 700 !important;
        letter-spacing: 0.1em !important; text-transform: uppercase !important;
        padding: 4px 10px !important; background: transparent !important;
        border-bottom: 1px solid rgba(21,101,192,0.1) !important;
        border-radius: 0 !important; margin-bottom: 4px !important;
      }
      .sidebar .nav .nav-item.nav-category .nav-link:hover { background: transparent !important; padding-left: 10px !important; }

      /* Sub-menu */
      .sidebar .nav .sub-menu { padding: 2px 0 4px 0 !important; }
      .sidebar .nav .sub-menu .nav-item { padding: 0 0px 0 26px !important; }
      .sidebar .nav .sub-menu .nav-item .nav-link { font-size: 0.79rem !important; color: rgba(15,23,42,0.55) !important; padding: 6px 0px 6px 20px !important; border-radius: 7px !important; margin: 1px 0 !important; gap: 0 !important; }
      /* Sub-menu arrow: give right margin so it's separated from text */
      .sidebar .nav .sub-menu .nav-item .nav-link::before { padding-left: 5px !important; color: #04c!important }
      .sidebar .nav .sub-menu .nav-item .nav-link:hover {
        color: var(--blue) !important;
        background: rgba(21,101,192,0.07) !important;
        padding-left: 24px !important;
      }
      .sidebar .nav .sub-menu .nav-item .nav-link.active { color: var(--blue) !important; background: rgba(21,101,192,0.1) !important; font-weight: 700; }

      /* Logout */
      .sidebar-logout-nav-item { border-top: 1px solid rgba(0,0,0,0.07) !important; margin-top: 6px !important; }
      .sidebar-logout-nav-item .nav-link { color: #dc3545 !important; }
      .sidebar-logout-nav-item .nav-link:hover { color: #c0392b !important; background: rgba(220,53,69,0.08) !important; }
      .sidebar-logout-nav-item .nav-link i.menu-icon { color: #dc3545 !important; }
      .sidebar-logout-nav-item .nav-link:hover i.menu-icon { color: #c0392b !important; }

      /* mini wrapper hidden on desktop */
      .sidebar .nav .navbar-brand-mini-wrapper { display: none !important; }

      /* ════ PAGE BODY WRAPPER ══════════════════════════════════ */
      .page-body-wrapper {
        min-height: 100vh !important;
        padding-left: var(--sidebar-w) !important;
      }

      /* ════ CONTENT ════════════════════════════════════════════ */
      .main-panel { position: relative; background-color: #ffefec; min-height: calc(100vh - var(--nav-h)); }
      .main-panel::before {
        content: ''; position: fixed; inset: 0;
        background-image: url('{{ asset("images/content_bg.png") }}');
        background-size: cover; background-position: center;
        opacity: 0.1; pointer-events: none; z-index: 0;
      }
      .content-wrapper { position: relative; z-index: 1; background: transparent !important; padding: 1.5rem !important; }

      /* ════ SHARED COMPONENTS ══════════════════════════════════ */
      .card { border: none !important; border-radius: var(--radius) !important; box-shadow: var(--shadow) !important; background: rgba(255,255,255,0.96) !important; transition: transform 0.28s ease, box-shadow 0.28s ease; overflow: hidden; }
      .card:hover { transform: translateY(-3px); box-shadow: var(--shadow-h) !important; }
      .card-title { font-weight: 700 !important; color: #0f172a !important; font-size: 0.95rem !important; margin-bottom: 1.1rem !important; position: relative; padding-bottom: 8px; }
      .card-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 34px; height: 3px; background: var(--grad); border-radius: 3px; }
      .bg-main { background: var(--grad) !important; }
      .text-main { color: var(--orange) !important; }
      .btn-main { background: var(--grad) !important; color: #fff !important; border: none !important; font-weight: 600; transition: all 0.3s !important; box-shadow: 0 4px 14px rgba(21,101,192,0.3) !important; }
      .btn-main:hover { transform: translateY(-2px) !important; box-shadow: 0 7px 22px rgba(232,78,15,0.38) !important; color: #fff !important; }
      .form-control { border-radius: 9px; border: 1px solid #e2e8f0; background: #fafcff; transition: all 0.2s; }
      .form-control:focus { border-color: var(--orange); box-shadow: var(--focus); background: #fff; }
      .input-group { border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; background: #fafcff; transition: all 0.2s; }
      .input-group:focus-within { border-color: var(--orange); box-shadow: var(--focus); }
      .input-group .form-control { border: none !important; border-radius: 0 !important; background: transparent; box-shadow: none !important; }
      .input-group-text { background: transparent !important; border: none !important; color: #94a3b8; }
      .input-group-append .btn, .input-group-prepend .btn { border-radius: 0 !important; margin: 0 !important; }
      .table thead th { border-top: none; background: #f8fafc; text-transform: uppercase; font-size: 0.71rem; letter-spacing: 0.06em; font-weight: 700; color: #64748b; }
      .nav-pills .nav-link.active, .nav-pills .show > .nav-link { background: var(--grad); box-shadow: 0 4px 10px rgba(21,101,192,0.22); }
      .nav-pills .nav-link { color: #64748b; border-radius: 50rem !important; transition: all 0.25s; }
      .badge-primary-light { background: rgba(21,101,192,0.1); color: #1565C0; }
      .badge-success-light { background: rgba(40,167,69,0.1); color: #28a745; }
      .badge-warning-light { background: rgba(255,193,7,0.1); color: #b45309; }
      .badge-danger-light { background: rgba(220,53,69,0.1); color: #dc3545; }
      #loading { display: none; position: fixed; inset: 0; background: rgba(10,25,41,0.55); backdrop-filter: blur(4px); z-index: 9999; }

      /* ════ MOBILE (≤991px) ════════════════════════════════════ */
      @media (max-width: 991px) {
        /* Show navbar brand wrapper on mobile */
        .default-layout-navbar .navbar-brand-wrapper {
          display: flex !important;
          width: 100% !important;
          justify-content: space-between !important;
        }
        /* Navbar menu wrapper hidden on mobile */
        .default-layout-navbar .navbar-menu-wrapper {
          display: none !important;
        }

        /* Sidebar: slide in from right as overlay */
        .sidebar {
          left: auto !important;
          right: -100% !important;
          transition: all 0.3s ease !important;
          z-index: 1200 !important;
          width: 250px !important;
          height: 100vh !important;
          top: 0 !important;
        }
        /* When sidebar is active (toggled by JS) */
        .sidebar.active {
          right: 0 !important;
          left: auto !important;
        }

        /* Page body: full width on mobile, no sidebar offset */
        .page-body-wrapper {
          padding-left: 0 !important;
          padding-top: var(--nav-h) !important;
        }

        /* Dimmed overlay when sidebar is open */
        .sidebar-overlay {
          display: none;
          position: fixed; inset: 0;
          background: rgba(0,0,0,0.5);
          z-index: 1199;
        }
        .sidebar.active ~ .sidebar-overlay,
        body.sidebar-open .sidebar-overlay {
          display: block;
        }
        body.sidebar-open {
          overflow: hidden !important;
        }
      }

      /* ════ TABLET (992px – 1199px) ═══════════════════════════ */
      @media (min-width: 992px) {
        /* Show the menu wrapper, hide brand wrapper */
        .default-layout-navbar .navbar-brand-wrapper { display: none !important; }
        .default-layout-navbar .navbar-menu-wrapper { display: flex !important; }
      }

      /* ════ SMALL MOBILE (≤575px) ═════════════════════════════ */
      @media (max-width: 575px) {
        .content-wrapper { padding: 0.9rem 0.75rem !important; }
        .card-body { padding: 0.9rem !important; }
        .card { border-radius: 12px !important; }
      }
      button, input[type=submit]{
        background-color: #04c !important;
        color: white !important;
        border-color: #04c;
      }
    </style>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1565C0">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>
<body>
    <div class="container-scroller">
        @include('components._navbar')

        <div class="container-fluid page-body-wrapper">
            @include('components._sidebar')

            {{-- Mobile overlay (click to close sidebar) --}}
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);">
            <div class="spinner-border text-white" role="status"><span class="sr-only"></span></div>
        </div>
    </div>

    <script src="{{ asset('stellar_assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/chartist/chartist.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/misc.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/settings.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/todolist.js') }}"></script>
    <script src="{{ asset('plugins/toast/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/dataTables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/dataTables/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        /* ── Mobile sidebar toggle ── */
        $(document).ready(function () {
            // Hamburger toggles sidebar on mobile
            $('[data-toggle="offcanvas"]').off('click').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('body').toggleClass('sidebar-open');
            });
            // Click overlay → close sidebar
            $('#sidebarOverlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('body').removeClass('sidebar-open');
            });
            // Desktop minimize toggle
            $('[data-toggle="minimize"]').on('click', function () {
                $('body').toggleClass('sidebar-icon-only');
            });
        });

        /* ── Toast config ── */
        const toasterMessanger = toastr;
        toasterMessanger.options = {
            closeButton: true, debug: false, progressBar: true,
            preventDuplicates: true, hideDuration: 800, showDuration: 300,
            extendedTimeOut: 4000, positionClass: 'toast-top-right',
        };
        function route() { return '{{ url('/') }}'; }
        function toasterMessage(type, message) {
            $.toast({ heading: type, text: message, icon: type, showHideTransition: 'fade', loader: false, loaderBg: '#9EC600', hideAfter: 5000, stack: 2, position: 'top-right', allowToastClose: true, className: 'custom-toast' });
        }

        /* ── Ajax form submit ── */
        function ajaxFormSubmit(form) {
            event.preventDefault();
            $('.invalid-feedback').removeClass('d-block text-danger').text('');
            var formData = new FormData(form[0]);
            var route = form.attr('action');
            var method = form.attr('method');
            var button = form.find('[type="submit"]');
            button.prop('disabled', true);
            $.ajax({
                url: route, type: method, data: formData,
                processData: false, contentType: false, cache: false,
                success: function(res) {
                    if (res.status) {
                        toasterMessage('success', res.message);
                        if (res.modal) { form.trigger('reset'); setTimeout(() => { $(document).find('.closeModel').trigger('click'); $(document).find('table').DataTable().ajax.reload(); }, 700); }
                        if (res.refresh) { setTimeout(() => { window.location.reload(); }, 1000); }
                        if (res.redirect) { setTimeout(() => { window.location.href = res.redirect; }, 1000); }
                    } else { toasterMessage('error', res.message); }
                    setTimeout(() => { button.prop('disabled', false); }, 1000);
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(key, message) {
                            if ($('input[name="'+key+'"]').length > 0) { $('input[name="'+key+'"]').parents('.form-group').find('.invalid-feedback').text(message[0]).addClass('text-danger d-block'); }
                            else { $('#'+key).parents('.form-group').find('.invalid-feedback').text(message[0]).addClass('text-danger d-block'); }
                        });
                    } else { toasterMessage('error', "Something went wrong."); }
                    setTimeout(() => { button.prop('disabled', false); }, 1000);
                }
            });
        }
        function ajaxOnClick(url, method='GET', data={}) {
            $.ajax({ url: url, type: method, data: data,
                success: function(res) {
                    if (res.status) { toasterMessage('success', res.message); if (res.refresh) { setTimeout(() => { window.location.reload(); }, 1000); } if (res.redirect) { setTimeout(() => { window.location.href = res.redirect; }, 1000); } }
                    else { toasterMessage('error', res.message); }
                },
                error: function() { toasterMessage('error', "Something went wrong."); }
            });
        }

        /* ── Loading overlay ── */
        $(document).ajaxStart(function() { $('#loading').show(); });
        $(document).ajaxStop(function() { $('#loading').hide(); });
        $(document).on('click', 'a', function(e) {
            var href = $(this).attr('href') || '';
            if ($(this).attr('target')==='_blank' || $(this).attr('download') || href.includes('javascript') || href==='#' || $(this).data('bs-toggle') || $(this).data('toggle')) return;
            $('#loading').show();
        });
        window.onpageshow = function(event) { if (event.persisted) { $('#loading').hide(); } else { $('#loading').hide(); } };

        /* ── Session toasts ── */
        $(document).ready(function() {
            @if(session()->has('success'))
                @php $s = explode('|', session('success')); @endphp
                $.toast({ heading: '{{ $s[0] ?? "Success" }}', text: '{{ $s[1] ?? "" }}', icon: 'info', showHideTransition: 'slide', loader: true, loaderBg: '#9EC600' });
            @endif
            @if(session()->has('error'))
                @php $e = explode('|', session('error')); @endphp
                $.toast({ heading: '{{ $e[0] ?? "Error" }}', text: '{{ $e[1] ?? "" }}', icon: 'error', showHideTransition: 'slide', loader: true, loaderBg: '#c6001e' });
            @endif
            try { $('.static-datatable').dataTable({ bSort: false }); } catch(e) {}
        });
    </script>
    @yield('scripts')

    <!-- Floating APK Download Button -->
    <!-- <a href="{{ asset('NEXO.apk') }}" download="NEXO.apk" id="apk-download-btn" style="position:fixed; bottom:30px; right:30px; z-index:9999; cursor:pointer; background:linear-gradient(135deg, #00e2fb 0%, #034bb3 100%); color:#fff; width:55px; height:55px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 25px rgba(3,75,179,0.5); border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s ease; text-decoration: none;">
        <i class="icon-cloud-download" style="font-size: 22px;"></i>
    </a> -->

    <script>
        if (!navigator.serviceWorker.controller) {
            navigator.serviceWorker.register("/sw.js").then(function (reg) {
                console.log("Service worker registered for Main: " + reg.scope);
            });
        }

        // Prevent the browser's native PWA installation prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
        });
    </script>
</body>
</html>
