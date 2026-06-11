@extends('layout.main')
@section('content')
    <x-page-breadcrumb current-page='ID Card' />
    
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card bg-transparent border-0 shadow-none">
                <div class="card-body">
                    
                    <div class="print-btn-container mb-4">
                        <button onclick="window.print()" class="btn btn-print">
                            <i class="mdi mdi-printer"></i> Print ID Card
                        </button>
                    </div>

                    <div class="id-card-wrapper" id="printArea">
                        @php
                            $user = auth()->guard('member')->user();
                            $profile = $user->profile ?? null;
                            $address = $profile->address ?? 'N/A';
                            $city = $profile->city ?? 'N/A';
                            $state = $profile->state ?? 'N/A';
                        @endphp
                        
                        <div class="id-card">
                            <div class="id-card-inner">
                                <div class="id-card-header">
                                    <img src="{{ asset('images/54.png') }}" alt="Logo">
                                </div>
                                
                                <div class="id-card-body">
                                    <div class="id-card-photo-wrapper">
                                        <div class="id-card-photo">
                                            <img src="{{ $user->profile_image_url }}" alt="Profile Photo">
                                        </div>
                                    </div>
                                    
                                    <div class="id-card-details">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-role">Distributor</div>
                                        
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <span class="info-label">Distributor ID</span>
                                                <span class="info-value">{{ $user->member_id }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Mobile No</span>
                                                <span class="info-value">{{ $user->mobile }}</span>
                                            </div>
                                            <div class="info-item full-width">
                                                <span class="info-label">Address</span>
                                                <span class="info-value">{{ $address }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">City</span>
                                                <span class="info-value">{{ $city }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">State</span>
                                                <span class="info-value">{{ $state }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="id-card-footer">
                                    <span class="footer-text">{{ config('app.name') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    @parent
    <style type="text/css">
        .id-card-wrapper {
            margin: 0 auto;
            max-width: 480px;
            perspective: 1000px;
        }

        .id-card {
            position: relative;
            background: linear-gradient(135deg, #091c29, #1a365d, #0f2027); /* Sleek professional dark blue */
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            overflow: hidden;
            color: #fff;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .id-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        /* Abstract geometrical background */
        .id-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(0, 198, 255, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 0% 100%, rgba(0, 114, 255, 0.15) 0%, transparent 40%);
            z-index: 0;
        }

        .id-card-inner {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: linear-gradient(180deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0) 100%);
        }

        .id-card-header {
            padding: 1.25rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
        }

        .id-card-header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, #00c6ff, transparent);
            opacity: 0.6;
        }

        .id-card-header img {
            max-height: 40px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .id-card-body {
            padding: 2rem 1.75rem;
            display: flex;
            gap: 1.75rem;
            align-items: center;
            position: relative;
        }

        .id-card-photo-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .id-card-photo {
            width: 110px;
            height: 110px;
            border-radius: 12px;
            padding: 3px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .id-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 9px;
            background: #e2e8f0;
        }

        .id-card-details {
            flex-grow: 1;
            z-index: 2;
        }

        .user-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            text-transform: capitalize;
        }

        .user-role {
            font-size: 0.75rem;
            color: #00c6ff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-bottom: 18px;
            display: inline-block;
            padding: 3px 10px;
            background: rgba(0, 198, 255, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(0, 198, 255, 0.2);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-item.full-width {
            grid-column: span 2;
        }

        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .info-value {
            font-size: 0.85rem;
            color: #f8fafc;
            font-weight: 500;
            word-break: break-word;
            line-height: 1.3;
        }

        .id-card-footer {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-text {
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Shine overlay effect */
        .id-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.05) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg);
            animation: shine 6s infinite;
            z-index: 10;
            pointer-events: none;
        }

        @keyframes shine {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        .print-btn-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 114, 255, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 114, 255, 0.4);
            color: white;
        }
        
        .btn-print i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        @media (max-width: 576px) {
            .id-card-body {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
                gap: 1.25rem;
            }
            .user-role {
                margin-left: auto;
                margin-right: auto;
            }
            .info-grid {
                text-align: left;
            }
        }

        /* Print optimization */
        @media print {
            @page {
                margin: 0;
                size: auto;
            }
            
            /* Hide UI elements */
            .navbar, .sidebar, .print-btn-container, .page-breadcrumb, .footer, 
            #sidebarOverlay, #loading, .btn-print, .breadcrumb-container {
                display: none !important;
            }

            /* Reset layout for print */
            body, html {
                height: auto;
                margin: 0;
                padding: 0;
                background: white !important;
            }
            
            .container-scroller, .page-body-wrapper, .main-panel, .content-wrapper {
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
                width: 100% !important;
                display: block !important;
            }

            .row, .col-md-12, .grid-margin, .card, .card-body {
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            #printArea {
                position: relative;
                margin-top: 50px;
                display: flex !important;
                justify-content: center;
                width: 100%;
                visibility: visible !important;
            }

            .id-card-wrapper {
                max-width: 100% !important;
            }

            .id-card {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background: #091c29 !important; /* Solid fallback */
                background: linear-gradient(135deg, #091c29, #1a365d, #0f2027) !important;
                box-shadow: none !important;
                transform: none !important;
                width: 450px !important;
                border: 1px solid #1a365d !important;
                break-inside: avoid;
                color: white !important;
            }

            .id-card-inner {
                background: transparent !important;
            }

            .id-card-header {
                background: rgba(0, 0, 0, 0.4) !important;
                -webkit-print-color-adjust: exact !important;
            }

            .id-card-photo {
                background: #00c6ff !important;
                -webkit-print-color-adjust: exact !important;
            }

            .user-name, .info-value {
                color: white !important;
            }

            .user-role {
                background: rgba(0, 198, 255, 0.15) !important;
                -webkit-print-color-adjust: exact !important;
                border: 1px solid rgba(0, 198, 255, 0.3) !important;
            }

            .info-label {
                color: #94a3b8 !important;
            }

            .id-card-footer {
                background: rgba(0, 0, 0, 0.4) !important;
                -webkit-print-color-adjust: exact !important;
            }
        }
    </style>
@endsection
