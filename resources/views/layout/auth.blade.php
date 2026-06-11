<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login:: {{ config('app.name') }}</title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/css/vendor.bundle.base.css') }}">
    <link href="{{ asset('plugins/toast/jquery.toast.min.css') }}" rel="stylesheet">
    
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/css/vertical-light-layout/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('icons/icon-192x192.png') }}" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        :root {
            --blue:       #1565C0;
            --blue-lt:    #1976D2;
            --orange:     #E84E0F;
            --orange-lt:  #FF6B35;
        }
        *, body { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body, html { height: 100%; margin: 0; }

        /* ── BACKGROUND: white with animated SVG shapes ── */
        .content-wrapper.auth {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: #f5f8ff;
        }

        /* Animated SVG background canvas */
        .auth-bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .auth-bg-canvas svg {
            width: 100%;
            height: 100%;
        }

        /* Floating shape animations */
        @keyframes floatUp {
            0%   { transform: translateY(0px) rotate(0deg); opacity: 0.07; }
            50%  { transform: translateY(-30px) rotate(5deg); opacity: 0.13; }
            100% { transform: translateY(0px) rotate(0deg); opacity: 0.07; }
        }
        @keyframes floatSide {
            0%   { transform: translateX(0px) rotate(0deg); }
            50%  { transform: translateX(20px) rotate(-5deg); }
            100% { transform: translateX(0px) rotate(0deg); }
        }
        @keyframes pulse-ring {
            0%   { r: 40; opacity: 0.08; }
            50%  { r: 60; opacity: 0.04; }
            100% { r: 40; opacity: 0.08; }
        }
        .shape-float  { animation: floatUp 6s ease-in-out infinite; }
        .shape-float2 { animation: floatUp 8s ease-in-out infinite 1s; }
        .shape-float3 { animation: floatSide 7s ease-in-out infinite 0.5s; }
        .pulse-circle { animation: pulse-ring 4s ease-in-out infinite; }

        /* ── FORM CARD: clean white card ── */
        .auth-form-glass {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid rgba(21, 101, 192, 0.1);
            box-shadow: 0 20px 60px rgba(21, 101, 192, 0.12), 0 4px 20px rgba(0, 0, 0, 0.06);
            padding: 2.2rem 1.8rem;
            width: 100%;
            margin: auto;
            color: #0f172a !important;
            position: relative;
            z-index: 2;
        }

        /* Top colored accent bar */
        .auth-form-glass::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--blue) 0%, var(--orange) 100%);
            border-radius: 24px 24px 0 0;
        }

        .auth-form-glass h4 {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-size: 1.4rem !important;
        }
        .auth-form-glass h6 { color: #64748b !important; font-weight: 400 !important; }
        .auth-form-glass label { color: #374151 !important; font-weight: 500 !important; }
        .auth-form-glass .text-muted { color: #64748b !important; }
        .auth-form-glass .text-center { color: #374151 !important; }

        .auth-form-glass .text-primary {
            color: var(--orange-lt) !important;
            font-weight: 700;
        }
        .auth-form-glass .text-success {
            color: #16a34a !important;
            font-weight: 500;
        }

        /* ── INPUT GROUPS (light theme) ── */
        .auth-form-glass .form-control {
            background: #f8faff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #0f172a;
            transition: all 0.25s ease;
            height: auto;
            padding: 0.62rem 1rem;
            font-size: 0.9rem;
        }
        .auth-form-glass .form-control:focus {
            background: #fff;
            border-color: var(--blue);
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.12);
        }
        .auth-form-glass .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group-text {
            background: #f0f5ff !important;
            border: 1px solid #e2e8f0 !important;
            border-right: none !important;
            border-radius: 12px 0 0 12px !important;
            color: var(--blue) !important;
        }
        .auth-form-glass .input-group .form-control {
            border-radius: 0 12px 12px 0 !important;
        }
        .auth-form-glass .input-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #f8faff;
            transition: all 0.25s;
        }
        .auth-form-glass .input-group:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.12);
        }
        .auth-form-glass .input-group .input-group-text {
            border: none !important;
            background: transparent !important;
            border-right: 1px solid #e2e8f0 !important;
            border-radius: 0 !important;
        }
        .auth-form-glass .input-group .form-control {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        /* ── SELECT dropdown ── */
        .auth-form-glass select.form-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231565C0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            outline: none;
        }
        .auth-form-glass select.form-control option {
            background-color: #fff;
            color: #0f172a;
        }

        /* ── BUTTON ── */
        .btn-main {
            background: var(--blue) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 0.8rem 2rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            letter-spacing: 0.03em;
        }
        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232, 78, 15, 0.35) !important;
        }

        /* ── CHECKBOX ── */
        .auth-form-glass .form-check-label { color: #374151 !important; font-size: 0.88rem; }

        .container-scroller, .page-body-wrapper {
            background: transparent !important;
        }
    </style>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1565C0">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>
<body>
    <!-- Animated SVG Background -->
    <div class="auth-bg-canvas">
        <svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="blueGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#1565C0;stop-opacity:0.08"/>
                    <stop offset="100%" style="stop-color:#1976D2;stop-opacity:0.04"/>
                </linearGradient>
                <linearGradient id="orangeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#E84E0F;stop-opacity:0.07"/>
                    <stop offset="100%" style="stop-color:#FF6B35;stop-opacity:0.03"/>
                </linearGradient>
                <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#f5f8ff;stop-opacity:1"/>
                    <stop offset="50%" style="stop-color:#fef8f5;stop-opacity:1"/>
                    <stop offset="100%" style="stop-color:#f0f5ff;stop-opacity:1"/>
                </linearGradient>
            </defs>
            <!-- Background fill -->
            <rect width="1440" height="900" fill="url(#bgGrad)"/>

            <!-- Large soft circle top-left -->
            <circle cx="120" cy="120" r="220" fill="url(#blueGrad)" class="shape-float"/>

            <!-- Large soft circle bottom-right -->
            <circle cx="1350" cy="800" r="180" fill="url(#orangeGrad)" class="shape-float2"/>

            <!-- Hexagon top-right -->
            <polygon points="1280,50 1340,84 1340,152 1280,186 1220,152 1220,84" fill="none" stroke="#1565C0" stroke-width="1.5" stroke-opacity="0.12" class="shape-float3"/>
            <polygon points="1310,90 1355,116 1355,168 1310,194 1265,168 1265,116" fill="none" stroke="#1565C0" stroke-width="1" stroke-opacity="0.07" class="shape-float"/>

            <!-- Hexagon bottom-left -->
            <polygon points="80,700 140,734 140,802 80,836 20,802 20,734" fill="none" stroke="#E84E0F" stroke-width="1.5" stroke-opacity="0.12" class="shape-float2"/>

            <!-- Rising arrows (right side) -->
            <g class="shape-float3" opacity="0.1">
                <line x1="1380" y1="600" x2="1380" y2="400" stroke="#1565C0" stroke-width="2" stroke-linecap="round"/>
                <polyline points="1370,420 1380,400 1390,420" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            <g class="shape-float2" opacity="0.08">
                <line x1="1410" y1="650" x2="1410" y2="500" stroke="#E84E0F" stroke-width="2" stroke-linecap="round"/>
                <polyline points="1400,520 1410,500 1420,520" fill="none" stroke="#E84E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>

            <!-- Rising arrows (left side) -->
            <g class="shape-float" opacity="0.09">
                <line x1="50" y1="500" x2="50" y2="320" stroke="#1565C0" stroke-width="2" stroke-linecap="round"/>
                <polyline points="40,340 50,320 60,340" fill="none" stroke="#1565C0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>

            <!-- Diamond shapes -->
            <rect x="200" y="780" width="20" height="20" rx="2" transform="rotate(45 210 790)" fill="#1565C0" fill-opacity="0.1" class="shape-float3"/>
            <rect x="1200" y="100" width="16" height="16" rx="2" transform="rotate(45 1208 108)" fill="#E84E0F" fill-opacity="0.1" class="shape-float"/>
            <rect x="700" y="820" width="12" height="12" rx="2" transform="rotate(45 706 826)" fill="#1565C0" fill-opacity="0.08" class="shape-float2"/>
            <rect x="100" y="400" width="14" height="14" rx="2" transform="rotate(45 107 407)" fill="#E84E0F" fill-opacity="0.09" class="shape-float3"/>

            <!-- Network dots -->
            <circle cx="300" cy="200" r="3" fill="#1565C0" fill-opacity="0.15" class="shape-float"/>
            <circle cx="1100" cy="300" r="3" fill="#1565C0" fill-opacity="0.12" class="shape-float2"/>
            <circle cx="900" cy="750" r="4" fill="#E84E0F" fill-opacity="0.12" class="shape-float3"/>
            <circle cx="500" cy="100" r="3" fill="#E84E0F" fill-opacity="0.1" class="shape-float"/>

            <!-- Connecting lines (network effect) -->
            <line x1="300" y1="200" x2="1100" y2="300" stroke="#1565C0" stroke-width="0.5" stroke-opacity="0.06"/>
            <line x1="120" y1="120" x2="300" y2="200" stroke="#1565C0" stroke-width="0.5" stroke-opacity="0.08"/>
            <line x1="1100" y1="300" x2="1350" y2="800" stroke="#E84E0F" stroke-width="0.5" stroke-opacity="0.06"/>
            <line x1="500" y1="100" x2="900" y2="750" stroke="#1565C0" stroke-width="0.4" stroke-opacity="0.05"/>

            <!-- Soft glow blobs -->
            <ellipse cx="720" cy="450" rx="350" ry="200" fill="#1565C0" fill-opacity="0.025"/>
            <ellipse cx="200" cy="600" rx="180" ry="130" fill="#E84E0F" fill-opacity="0.03"/>
            <ellipse cx="1200" cy="200" rx="200" ry="140" fill="#1565C0" fill-opacity="0.03"/>
        </svg>
    </div>

    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow w-100 mx-0">
            <div class="@yield('auth-col', 'col-lg-4') mx-auto px-1">
              @yield('content')
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('stellar_assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('plugins/toast/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/misc.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/settings.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/todolist.js') }}"></script>
    
    <!--Password show & hide js -->
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    
    @section('scripts')
        <script type="text/javascript">
            function route(){
                return '{{ url('/') }}';
            }
            $(document).ready(function(){
                @if(session()->has('success'))
                    @php
                        $sessionData = explode('|',session('success'));
                    @endphp
                    $.toast({
                        heading: '{{ $sessionData[0] }}',
                        text: '{{ $sessionData[1] ?? session('success') }}',
                        icon: 'info',
                        showHideTransition: 'slide',
                        loader: true,        
                        loaderBg: '#9EC600'  
                    });
                @endif
                @if(session()->has('error'))
                    @php
                        $sessionData = explode('|',session('error'));
                    @endphp
                    $.toast({
                        heading: '{{ $sessionData[0] }}',
                        text: '{{ $sessionData[1] ?? session('error') }}',
                        icon: 'error',
                        showHideTransition: 'slide',
                        loader: true,        
                        loaderBg: '#c6001e'  
                    });
                @endif
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        $.toast({
                            heading: 'Error',
                            text: '{{ $error }}',
                            icon: 'error',
                            showHideTransition: 'slide',
                            loader: true,        
                            loaderBg: '#c6001e'  
                        });
                    @endforeach
                @endif
            })
        </script>
    @show

    <script>
        if (!navigator.serviceWorker.controller) {
            navigator.serviceWorker.register("/sw.js").then(function (reg) {
                console.log("Service worker registered for Auth: " + reg.scope);
            });
        }

        // Prevent the browser's native PWA installation prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
        });
    </script>
</body>
</html>
