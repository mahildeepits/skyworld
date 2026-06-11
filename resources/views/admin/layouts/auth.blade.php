<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login :: {{ env('APP_NAME')}} Panel</title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('stellar_assets/vendors/css/vendor.bundle.base.css') }}">
    
    <!-- Layout styles -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('stellar_assets/css/vertical-light-layout/style.css') }}">
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Rubik', sans-serif;
            background-color: #f4f7f9;
        }

        /* Split Screen Layout for Admin */
        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Dark Side - Matches Admin Sidebar */
        .auth-side-info {
            flex: 1;
            background: #ffffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Background Pattern for Admin Side */
        /* .auth-side-info::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(0, 226, 251, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
        } */

        .auth-side-content {
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .auth-side-content img {
            width: 200px;
            margin-bottom: 30px;
        }

        .auth-side-content h2 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 2.5rem;
            color: #ff2c18;
        }
        
        .auth-side-content p {
            color: #a7afb7;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Form Side */
        .auth-side-form {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #04c;
            padding: 40px;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 450px;
        }

        .auth-form-wrapper h3 {
            font-weight: 700;
            color: #ffffffff;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .auth-form-wrapper .subtitle {
            color: #ffffffff;
            margin-bottom: 35px;
            font-size: 1rem;
        }

        .form-group label {
            font-weight: 500;
            color: #ffffffff !important;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border: 1px solid #e8e5e5 !important;
            border-radius: 8px !important;
            height: 55px !important;
            padding: 10px 20px !important;
            background: #fbfbfb !important;
            color: #111111 !important;
            font-size: 1rem !important;
        }

        .form-control:focus {
            background: #ffffff !important;
            border-color: #ffffffff !important;
            box-shadow: 0 0 0 4px rgba(0, 226, 251, 0.05) !important;
        }

        .btn-main {
            background: #ff2c18!important;
            border: none;
            border-radius: 8px !important;
            height: 55px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff !important;
            box-shadow: 0 10px 20px rgba(3, 75, 179, 0.2);
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(3, 75, 179, 0.3);
        }

        .alert-danger {
            background: #fff5f5 !important;
            color: #e53e3e !important;
            border: 1px solid #feb2b2 !important;
            border-radius: 8px;
            padding: 15px;
            font-size: 0.95rem;
        }

        .login-footer {
            margin-top: 40px;
            color: #ffffffff;
            font-size: 0.9rem;
        }

        .position-relative i {
            font-size: 1.2rem;
            color: #626262;
        }

        /* Mobile Styles */
        @media (max-width: 991px) {
            .auth-container {
                flex-direction: column;
            }
            .auth-side-info {
                flex: none;
                padding: 60px 20px;
            }
            .auth-side-content h2 {
                font-size: 1.8rem;
            }
            .auth-side-form {
                flex: 1;
                padding: 60px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Brand Info Side -->
        <div class="auth-side-info">
            <div class="auth-side-content">
                <img src="{{ asset('images/54.png') }}" alt="NEXO Logo">
                <h2>Admin Console</h2>
                <p>Welcome back, Administrator. Please enter your credentials to manage the NEXO ecosystem.</p>
            </div>
        </div>

        <!-- Login Form Side -->
        <div class="auth-side-form">
            <div class="auth-form-wrapper">
                @yield('content')
                <div class="login-footer">
                    <p>Copyright © NEXO Panel {{ date('Y') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('stellar_assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('stellar_assets/js/misc.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
