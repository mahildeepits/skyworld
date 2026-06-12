<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyWorld | Beyond The Sky, Beyond Success</title>
    <meta name="description" content="Access next-generation algorithmic Forex trading and high-yield investment options with SkyWorld. Explore our Basic and Golden tiers and unlock the 10-level IB Income Plan.">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        skyworldBlue: {
                            light: '#38bdf8',
                            DEFAULT: '#2563eb',
                            dark: '#1d4ed8',
                        },
                        skyworldOrange: {
                            light: '#f97316',
                            DEFAULT: '#ea580c',
                            dark: '#dc2626',
                        },
                        slateDark: '#0f172a',
                        slateMuted: '#475569',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Grid pattern background */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(15, 23, 42, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
        }

        /* Glassmorphism custom classes */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Ticker ticker animation */
        @keyframes ticker-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-ticker {
            display: flex;
            width: max-content;
            animation: ticker-scroll 35s linear infinite;
        }
        .animate-ticker:hover {
            animation-play-state: paused;
        }

        /* Custom Range Slider Styling */
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8 0%, #1d4ed8 100%);
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.3);
            border: 2px solid #ffffff;
            transition: transform 0.1s ease;
        }
        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.15);
        }
        input[type="range"]::-moz-range-thumb {
            width: 22px;
            height: 22px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8 0%, #1d4ed8 100%);
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.3);
            transition: transform 0.1s ease;
        }
        input[type="range"]::-moz-range-thumb:hover {
            transform: scale(1.15);
        }

        /* Glowing floating shapes */
        .glow-shape-blue {
            filter: blur(120px);
            background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(29, 78, 216, 0.05) 70%);
        }
        .glow-shape-orange {
            filter: blur(120px);
            background: radial-gradient(circle, rgba(249, 115, 22, 0.2) 0%, rgba(234, 88, 12, 0.05) 70%);
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-blue-600 selection:text-white overflow-x-hidden">

    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-[120vh] bg-grid-pattern -z-10 pointer-events-none"></div>
    <div class="absolute top-[10%] left-[-10%] w-[500px] h-[500px] rounded-full glow-shape-blue -z-10 pointer-events-none"></div>
    <div class="absolute top-[30%] right-[-10%] w-[600px] h-[600px] rounded-full glow-shape-orange -z-10 pointer-events-none"></div>
    <div class="absolute bottom-[20%] left-[-5%] w-[500px] h-[500px] rounded-full glow-shape-blue -z-10 pointer-events-none"></div>

    <!-- Navigation Header -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 px-6 py-4 lg:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo Section -->
            <a href="#" class="flex items-center gap-3 group">
                <!-- <div class="relative w-10 h-10 flex items-center justify-center bg-gradient-to-tr from-blue-600 to-sky-400 rounded-xl shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300"> -->
                    <!-- If logo exists, render it. Fallback is styled icon. -->
                    @if(file_exists(public_path('images/54.png')))
                        <img src="{{ asset('images/54.png') }}" class=" object-contain" width="200px" height="auto" alt="SkyWorld logo">
                    @else
                        <i class="fa-solid fa-chart-line-up text-white text-lg"></i>
                    @endif
                <!-- </div> -->
                <!-- <span class="text-2xl font-bold font-outfit tracking-tight text-slateDark group-hover:text-blue-600 transition-colors">
                    Sky<span class="bg-gradient-to-r from-orange-500 to-red-600 bg-clip-text text-transparent">World</span>
                </span> -->
            </a>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center gap-8 font-medium text-slate-600">
                <a href="#about" class="hover:text-blue-600 transition-colors">About Us</a>
                <a href="#packages" class="hover:text-blue-600 transition-colors">Packages</a>
                <a href="#calculator" class="hover:text-blue-600 transition-colors">Profit Calculator</a>
                <a href="#referral" class="hover:text-blue-600 transition-colors">IB Income</a>
                <a href="#contact" class="hover:text-blue-600 transition-colors">Contact</a>
            </div>

            <!-- Header Action CTAs -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="/member/login" id="loginBtnDesktop" class="px-5 py-2 text-sm font-semibold rounded-full border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white transition duration-300">
                    <i class="fa-solid fa-right-to-bracket mr-1.5"></i>Login
                </a>
                <a href="/member/register" id="registerBtnDesktop" class="px-6 py-2.5 text-sm font-semibold rounded-full bg-gradient-to-r from-orange-500 to-red-600 text-white hover:opacity-90 shadow-md shadow-orange-500/20 hover:shadow-orange-500/35 transition duration-300">
                    Create Account
                </a>
            </div>

            <!-- Hamburger Button for Mobile -->
            <button id="mobile-menu-btn" class="lg:hidden flex flex-col justify-between w-6 h-4.5 text-slateDark focus:outline-none" aria-label="Toggle Navigation Menu">
                <span class="w-6 h-0.5 bg-slateDark rounded transition-all duration-300" id="line1"></span>
                <span class="w-6 h-0.5 bg-slateDark rounded transition-all duration-300" id="line2"></span>
                <span class="w-6 h-0.5 bg-slateDark rounded transition-all duration-300" id="line3"></span>
            </button>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl border-b border-slate-100 shadow-xl py-6 px-8 flex flex-col gap-6 lg:hidden animate-fade-in transition-all">
            <div class="flex flex-col gap-4 text-lg font-medium text-slate-700">
                <a href="#about" class="mobile-link hover:text-blue-600 transition-colors py-1">About Us</a>
                <a href="#packages" class="mobile-link hover:text-blue-600 transition-colors py-1">Packages</a>
                <a href="#calculator" class="mobile-link hover:text-blue-600 transition-colors py-1">Profit Calculator</a>
                <a href="#referral" class="mobile-link hover:text-blue-600 transition-colors py-1">IB Income</a>
                <a href="#contact" class="mobile-link hover:text-blue-600 transition-colors py-1">Contact</a>
            </div>
            <hr class="border-slate-100">
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/member/login" class="flex-1 text-center py-2.5 text-sm font-semibold rounded-full border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white transition duration-300">
                    Login
                </a>
                <a href="/member/register" class="flex-1 text-center py-2.5 text-sm font-semibold rounded-full bg-gradient-to-r from-orange-500 to-red-600 text-white hover:opacity-90 transition duration-300">
                    Register
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative pt-32 pb-20 md:pt-40 md:pb-28 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Info Panel -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left z-10">
                <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-full px-4 py-1.5 text-blue-600 font-semibold text-xs tracking-wider uppercase animate-bounce">
                    <span class="flex h-2 w-2 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Next-Generation Algorithmic Trading
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6.5xl font-black font-outfit text-slateDark leading-[1.1] tracking-tight">
                    SkyWorld — <br>
                    <span class="bg-gradient-to-r from-blue-600 to-sky-400 bg-clip-text text-transparent">Beyond The Sky</span>,<br>
                    <span class="bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">Beyond Success</span>
                </h1>
                
                <p class="text-base sm:text-lg text-slateMuted max-w-xl mx-auto lg:mx-0">
                    Empowering your financial future through institutional Forex market strategies. Gain up to 10% daily yield commissions backed by liquid multi-pool operations and custom compounding options.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="/member/register" class="px-8 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold rounded-full shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:-translate-y-0.5 transition duration-300 text-center">
                        Get Started Now <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                    </a>
                    <a href="#packages" class="px-8 py-4 bg-white text-blue-600 hover:text-blue-700 font-semibold rounded-full border border-slate-200 hover:border-blue-600 shadow-sm hover:shadow transition duration-300 text-center">
                        View Packages
                    </a>
                </div>

                <!-- Small Trust Indicator -->
                <div class="flex items-center justify-center lg:justify-start gap-6 pt-6 text-slate-500 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-blue-600"></i>
                        <span>Secure Liquidity Pool</span>
                    </div>
                    <div class="w-1.5 h-1.5 bg-slate-300 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-orange-500"></i>
                        <span>Instantly Settled</span>
                    </div>
                </div>
            </div>

            <!-- Right Trading Visuals Panel -->
            <div class="lg:col-span-5 relative z-10 flex justify-center">
                <div class="relative w-full max-w-[420px] aspect-square rounded-[36px] bg-gradient-to-tr from-blue-50/50 to-white p-4 shadow-2xl shadow-blue-900/5 border border-white/80">
                    <!-- Grid background overlay -->
                    <div class="absolute inset-0 bg-grid-pattern opacity-40 rounded-[36px]"></div>
                    
                    <!-- Floating Widget 1: Live Trades Ticker -->
                    <div class="absolute -top-14 -right-10 glass-card rounded-2xl p-4 w-60 shadow-lg scale-90 sm:scale-100 animate-pulse duration-2000">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-400">LIVE BOT ACTIVITY</span>
                            <span class="text-[10px] bg-green-500/15 text-green-600 font-bold px-2 py-0.5 rounded-full uppercase">Active</span>
                        </div>
                        <div id="live-trades-box" class="space-y-2 text-xs font-mono">
                            <!-- Injected dynamically via JS -->
                            <div class="flex justify-between items-center text-green-600">
                                <span>EUR/USD BUY</span>
                                <strong>+0.32%</strong>
                            </div>
                            <div class="flex justify-between items-center text-green-600">
                                <span>GBP/JPY SELL</span>
                                <strong>+0.58%</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Widget 2: Wallet Income -->
                    <div class="absolute -bottom-12 -left-6 glass-card rounded-2xl p-4 w-52 shadow-lg flex items-center gap-3.5 scale-90 sm:scale-100">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500">
                            <i class="fa-solid fa-wallet text-lg"></i>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Daily ROI Settled</span>
                            <span class="text-lg font-extrabold text-slateDark font-outfit" id="live-roi-earnings">$472.90</span>
                        </div>
                    </div>

                    <!-- Centered SVG Forex Graph Graphic -->
                    <div class="w-full h-full flex flex-col justify-between p-4 relative z-10">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-slate-400 text-xs font-semibold block">SKY FOREX ALGO</span>
                                <h3 class="text-xl font-bold font-outfit text-slateDark">XAU/USD (Gold)</h3>
                            </div>
                            <div class="text-right">
                                <span class="text-green-600 font-bold text-sm bg-green-50 px-2 py-0.5 rounded-full"><i class="fa-solid fa-caret-up mr-1"></i>2.48%</span>
                            </div>
                        </div>

                        <!-- Sparkline SVG -->
                        <div class="w-full h-40 my-4">
                            <svg viewBox="0 0 300 120" class="w-full h-full">
                                <defs>
                                    <linearGradient id="chart-glow" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.4" />
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                                <!-- Chart Shading -->
                                <path d="M 0 100 Q 40 40 80 80 T 160 50 T 240 20 T 300 10 L 300 120 L 0 120 Z" fill="url(#chart-glow)" />
                                <!-- Chart Line -->
                                <path d="M 0 100 Q 40 40 80 80 T 160 50 T 240 20 T 300 10" fill="none" stroke="url(#line-grad)" stroke-width="4" stroke-linecap="round" />
                                <linearGradient id="line-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#2563eb" />
                                    <stop offset="50%" stop-color="#38bdf8" />
                                    <stop offset="100%" stop-color="#f97316" />
                                </linearGradient>
                                <!-- Floating Pulse Node -->
                                <circle cx="300" cy="10" r="5" fill="#f97316" class="animate-ping" style="transform-origin: 300px 10px;" />
                                <circle cx="300" cy="10" r="4" fill="#ea580c" />
                            </svg>
                        </div>

                        <div class="flex justify-between items-center text-xs text-slate-400 font-medium">
                            <span>08:00 AM</span>
                            <span>12:00 PM</span>
                            <span>04:00 PM</span>
                            <span>Active Trading Session</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Market pairs infinite marquee ticker -->
    <div class="w-full bg-slateDark border-y border-slate-800 py-3 overflow-hidden select-none">
        <div class="animate-ticker text-white text-xs font-mono font-semibold gap-12 tracking-wide uppercase">
            <!-- Pair items -->
            <div class="flex items-center gap-2">
                <span class="text-slate-400">EUR/USD</span>
                <span id="ticker-eurusd">1.0845</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.12%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">GBP/USD</span>
                <span id="ticker-gbpusd">1.2718</span>
                <span class="text-red-500 font-bold"><i class="fa-solid fa-caret-down mr-1"></i>-0.08%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">USD/JPY</span>
                <span id="ticker-usdjpy">156.42</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.25%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">AUD/USD</span>
                <span id="ticker-audusd">0.6654</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.04%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">Gold (XAU/USD)</span>
                <span id="ticker-xauusd">2342.15</span>
                <span class="text-red-500 font-bold"><i class="fa-solid fa-caret-down mr-1"></i>-0.34%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">Bitcoin (BTC/USD)</span>
                <span id="ticker-btcusd">67842.50</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+1.85%</span>
            </div>
            <!-- Duplicate for infinite seamless scroll -->
            <div class="flex items-center gap-2">
                <span class="text-slate-400">EUR/USD</span>
                <span id="ticker-eurusd-dup">1.0845</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.12%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">GBP/USD</span>
                <span id="ticker-gbpusd-dup">1.2718</span>
                <span class="text-red-500 font-bold"><i class="fa-solid fa-caret-down mr-1"></i>-0.08%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">USD/JPY</span>
                <span id="ticker-usdjpy-dup">156.42</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.25%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">AUD/USD</span>
                <span id="ticker-audusd-dup">0.6654</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+0.04%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">Gold (XAU/USD)</span>
                <span id="ticker-xauusd-dup">2342.15</span>
                <span class="text-red-500 font-bold"><i class="fa-solid fa-caret-down mr-1"></i>-0.34%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400">Bitcoin (BTC/USD)</span>
                <span id="ticker-btcusd-dup">67842.50</span>
                <span class="text-green-500 font-bold"><i class="fa-solid fa-caret-up mr-1"></i>+1.85%</span>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <section id="about" class="py-24 px-6 relative bg-slate-50/50 scroll-mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                
                <!-- Left Visual Graph Cards -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/10 to-transparent rounded-3xl filter blur-2xl"></div>
                    <div class="relative glass-card p-6 rounded-3xl space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="ml-2 font-mono text-xs text-slate-400">skyworld-ecosystem.cfg</span>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white/80 p-4 rounded-xl border border-slate-100 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slateDark font-outfit">Diversified Liquidity pools</h4>
                                    <p class="text-xs text-slateMuted mt-1">Spreading deposit volumes across highly liquid currency buffers, shielding your assets from market turbulence.</p>
                                </div>
                            </div>
                            <div class="bg-white/80 p-4 rounded-xl border border-slate-100 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500 shrink-0">
                                    <i class="fa-solid fa-network-wired"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slateDark font-outfit">Direct Network Connectivity</h4>
                                    <p class="text-xs text-slateMuted mt-1">Expanding your reach instantly via our 10-level reward mechanism and tracking earnings transparently in real-time.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right About Copy -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="text-xs font-extrabold uppercase tracking-widest text-blue-600">ABOUT SKYWORLD</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slateDark leading-tight tracking-tight">
                        A High-Performance Forex Platform Built to Unlock Financial Freedom
                    </h2>
                    <p class="text-slateMuted leading-relaxed">
                        SkyWorld is a cutting-edge Forex trading and investment platform designed to help users achieve absolute financial freedom. By merging the volatility-hedging advantages of automated algorithmic forex trading with a robust Multi-Level Marketing referral plan, we offer a steady, sustainable architecture for collective wealth accumulation.
                    </p>
                    <p class="text-slateMuted leading-relaxed">
                        Whether you are a retail investor looking to grow your wallet capitals via passive trade commissions or an active team leader building an affiliate channel, SkyWorld provides the tools, transparent rules, and deep liquidity resources to propel you beyond success.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-check"></i></span>
                            <span class="font-semibold text-slateDark">Compounding Payouts</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-check"></i></span>
                            <span class="font-semibold text-slateDark">10-Level Profit-Sharing</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-check"></i></span>
                            <span class="font-semibold text-slateDark">200% Payout Security Caps</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs"><i class="fa-solid fa-check"></i></span>
                            <span class="font-semibold text-slateDark">Secure Admin Approvals</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Investment Packages Section -->
    <section id="packages" class="py-24 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto space-y-16">
            
            <!-- Section Header -->
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <div class="text-xs font-extrabold uppercase tracking-widest text-orange-500">INVESTMENT OPTIONS</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slateDark tracking-tight">Premium Investment Packages</h2>
                <p class="text-slateMuted">
                    Maximize your capital returns with our tailored tiers. Select the plan that matches your investment goals to start earning daily commission ratios.
                </p>
            </div>

            <!-- Package Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Basic Package Card -->
                <div class="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-2.5 bg-gradient-to-r from-blue-500 to-sky-400"></div>
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-sm font-extrabold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Basic Tier</span>
                            <i class="fa-solid fa-piggy-bank text-2xl text-blue-500 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <h3 class="text-2xl font-bold font-outfit text-slateDark">Basic Package</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black font-outfit text-slateDark">$100 - $999</span>
                            <span class="text-slate-400 block text-sm mt-1">Investment Range</span>
                        </div>
                        
                        <!-- Details list -->
                        <ul class="space-y-4 border-t border-slate-100 pt-6 text-sm text-slateMuted">
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-simple text-blue-500"></i>
                                <span>Daily Commission: <strong class="text-slateDark">8%</strong> on wallet amount</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-shield-halved text-blue-500"></i>
                                <span>Monthly Capping: <strong class="text-slateDark">Max 200%</strong> of deposit</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-calendar-check text-blue-500"></i>
                                <span>Automated Wallet Accumulation</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-nodes text-blue-500"></i>
                                <span>Standard Referral Eligibility</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-8">
                        <a href="/member/register" class="block text-center w-full py-3.5 bg-slateDark hover:bg-blue-600 text-white font-bold rounded-xl transition duration-300 shadow-md">
                            Acquire Basic Package
                        </a>
                    </div>
                </div>

                <!-- Golden Package Card -->
                <div class="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-2.5 bg-gradient-to-r from-orange-500 to-red-500"></div>
                    <div class="absolute top-6 right-6">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-orange-600 bg-orange-100/80 px-2.5 py-1 rounded-full"><i class="fa-solid fa-crown mr-1 animate-pulse"></i>Best Value</span>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-sm font-extrabold uppercase tracking-wider text-orange-600 bg-orange-50 px-3 py-1 rounded-full">VIP Tier</span>
                            <i class="fa-solid fa-gem text-2xl text-orange-500 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <h3 class="text-2xl font-bold font-outfit text-slateDark">Golden Package</h3>
                        <div class="my-6">
                            <span class="text-4xl font-black font-outfit text-slateDark">$1,000+</span>
                            <span class="text-slate-400 block text-sm mt-1">Investment Range</span>
                        </div>
                        
                        <!-- Details list -->
                        <ul class="space-y-4 border-t border-slate-100 pt-6 text-sm text-slateMuted">
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-chart-simple text-orange-500"></i>
                                <span>Daily Commission: <strong class="text-slateDark">10%</strong> on wallet amount</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-shield-halved text-orange-500"></i>
                                <span>Monthly Capping: <strong class="text-slateDark">Max 200%</strong> of deposit</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-calendar-check text-orange-500"></i>
                                <span>Automated Wallet Accumulation</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-nodes text-orange-500"></i>
                                <span>Complete 10-Level IB Income unlocked</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-8">
                        <a href="/member/register" class="block text-center w-full py-3.5 bg-gradient-to-r from-orange-500 to-red-600 hover:opacity-90 text-white font-bold rounded-xl transition duration-300 shadow-md shadow-orange-500/15">
                            Acquire Golden Package
                        </a>
                    </div>
                </div>

            </div>

            <!-- Dynamic Profit Calculator Widget -->
            <div id="calculator" class="max-w-3xl mx-auto glass-card rounded-[32px] p-8 md:p-12 space-y-8 scroll-mt-20">
                <div class="text-center space-y-2">
                    <h3 class="text-2xl font-bold font-outfit text-slateDark">Trading Profit Calculator</h3>
                    <p class="text-sm text-slateMuted">Simulate your daily and monthly passive trade returns based on deposit capital amount.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    
                    <!-- Slider & Numeric Input Controls -->
                    <div class="md:col-span-7 space-y-6">
                        <div class="flex justify-between items-center">
                            <label for="deposit-number" class="text-sm font-semibold text-slateDark">Investment Amount ($)</label>
                            <input type="number" id="deposit-number" min="100" max="10000" step="50" value="1000" class="w-28 text-right bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 font-bold font-mono text-blue-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        
                        <!-- Slider input -->
                        <div class="space-y-2">
                            <input type="range" id="deposit-slider" min="100" max="10000" step="50" value="1000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600 focus:outline-none">
                            <div class="flex justify-between text-xs font-semibold font-mono text-slate-400">
                                <span>Min: $100</span>
                                <span>Mid: $5,000</span>
                                <span>Max: $10,000+</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Output Display Indicators -->
                    <div class="md:col-span-5 bg-gradient-to-tr from-slate-50 to-white rounded-2xl p-6 border border-slate-100 space-y-4 shadow-inner">
                        <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                            <span>Package Tier:</span>
                            <span id="calc-package-badge" class="px-2.5 py-0.5 rounded-full bg-orange-100 text-orange-600 font-extrabold uppercase">Golden</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                            <span>Daily ROI Ratio:</span>
                            <span id="calc-roi-percentage" class="text-slateDark font-bold">10%</span>
                        </div>
                        <hr class="border-slate-200/60">
                        
                        <!-- Profit Output breakdown values -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500 font-medium">Daily Earnings:</span>
                                <strong class="text-base font-bold text-slateDark font-mono" id="calc-daily-payout">$1000.00</strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500 font-medium">Monthly Earnings (30D):</span>
                                <strong class="text-base font-bold text-slateDark font-mono" id="calc-monthly-payout">$30,000.00</strong>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500 font-semibold text-orange-600">Max Withdrawal (200%):</span>
                                <strong class="text-lg font-black text-orange-600 font-mono" id="calc-capping-payout">$20,000.00</strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- IB Income (Referral Program) Section -->
    <section id="referral" class="py-24 px-6 bg-slate-50/50 scroll-mt-20">
        <div class="max-w-7xl mx-auto space-y-16">
            
            <!-- Section Header -->
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <div class="text-xs font-extrabold uppercase tracking-widest text-blue-600">REFERRAL PLAN</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slateDark tracking-tight">10-Level IB Income Program</h2>
                <p class="text-slateMuted">
                    Build a thriving global trading team. Earn a direct percentage share of the trading profit distributed across 10 network layers.
                </p>
            </div>

            <!-- IB Levels and Rule Container -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- 10 levels progress lists -->
                <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                    <h3 class="text-xl font-bold font-outfit text-slateDark mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-network-wired text-blue-600"></i> Level Profit Breakdown
                    </h3>
                    
                    <!-- Grid of levels -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Level 1 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 1 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">10% Profit</span>
                        </div>
                        <!-- Level 2 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 2 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">9% Profit</span>
                        </div>
                        <!-- Level 3 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 3 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">8% Profit</span>
                        </div>
                        <!-- Level 4 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 4 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">6% Profit</span>
                        </div>
                        <!-- Level 5 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 5 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">5% Profit</span>
                        </div>
                        <!-- Level 6 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 6 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">4% Profit</span>
                        </div>
                        <!-- Level 7 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 7 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">3% Profit</span>
                        </div>
                        <!-- Level 8 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 8 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">2% Profit</span>
                        </div>
                        <!-- Level 9 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 9 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">2% Profit</span>
                        </div>
                        <!-- Level 10 -->
                        <div class="flex items-center justify-between bg-slate-50 p-3.5 rounded-xl border border-slate-100 hover:border-blue-400 hover:bg-blue-50/20 transition-all">
                            <span class="font-bold text-slateDark text-sm">Level 10 Referral</span>
                            <span class="text-lg font-extrabold text-blue-600 font-outfit">1% Profit</span>
                        </div>
                    </div>
                </div>

                <!-- Info rule and visual graphics -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- CRITICAL Info warning banner -->
                    <div class="relative bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-orange-500 rounded-2xl p-6 md:p-8 shadow-md">
                        <div class="absolute -top-3.5 -left-3.5 w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        </div>
                        <h4 class="font-bold text-orange-800 text-lg font-outfit mb-3">Critical Referral Requirement</h4>
                        <p class="text-sm text-orange-950/80 leading-relaxed font-medium">
                            <strong>Important Rule:</strong> 1 Direct Referral opens 1 Level of IB Income. To unlock the complete 10-level IB Income Plan, a total of <strong>10 Direct Referrals</strong> are required. 
                        </p>
                        <hr class="border-orange-200 my-4">
                        <div class="flex gap-3 items-start text-xs text-orange-900/70">
                            <i class="fa-solid fa-info mt-0.5 font-bold"></i>
                            <span>If you maintain only 2 Direct Referrals, you will accumulate profit-sharing commission up to Level 2 only, even if your team and active volumes expand deeper into lower levels.</span>
                        </div>
                    </div>

                    <!-- Visual team graphic card -->
                    <div class="glass-card rounded-2xl p-6 flex items-center gap-4 border border-slate-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 shrink-0">
                            <i class="fa-solid fa-people-group text-lg"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Network Potential</span>
                            <span class="text-sm font-semibold text-slateDark block mt-0.5">Maximize network yield caps by registering 10 Direct Referrals today.</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto space-y-16">
            
            <!-- Section Header -->
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <div class="text-xs font-extrabold uppercase tracking-widest text-orange-500">GET IN TOUCH</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slateDark tracking-tight">Contact SkyWorld Support</h2>
                <p class="text-slateMuted">
                    Have questions about our Forex trading strategies, package commissions, or the 10-tier referral network? Our desk is standing by.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-5xl mx-auto">
                
                <!-- Contact info channels -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-start gap-4 hover:border-blue-400 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slateDark font-outfit">Official Email Support</h4>
                            <p class="text-sm text-slateMuted mt-1">support@skyworld.com</p>
                            <p class="text-xs text-slate-400 mt-0.5">All responses resolved within 24 hours</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-start gap-4 hover:border-blue-400 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slateDark font-outfit">Corporate Desk</h4>
                            <p class="text-sm text-slateMuted mt-1">Financial District, Suite 482B</p>
                            <p class="text-xs text-slate-400 mt-0.5">London, United Kingdom</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-start gap-4 hover:border-blue-400 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slateDark font-outfit">Trading Desk Hours</h4>
                            <p class="text-sm text-slateMuted mt-1">24 Hours, Monday — Friday</p>
                            <p class="text-xs text-slate-400 mt-0.5">Aligned with London & New York session times</p>
                        </div>
                    </div>
                </div>

                <!-- Interactive contact form -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
                        <form id="contact-form" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="contact-name" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Your Full Name*</label>
                                    <input type="text" id="contact-name" name="name" required placeholder="E.g., John Doe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-slateDark">
                                </div>
                                <div class="space-y-2">
                                    <label for="contact-email" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Your Email Address*</label>
                                    <input type="email" id="contact-email" name="email" required placeholder="john@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-slateDark">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="contact-phone" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Phone Number*</label>
                                <input type="text" id="contact-phone" name="phone" required placeholder="+1 (555) 000-0000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-slateDark">
                            </div>
                            <div class="space-y-2">
                                <label for="contact-message" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Message / Inquiry Details*</label>
                                <textarea id="contact-message" name="message" required rows="4" placeholder="How can our Forex team assist you today?" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 focus:bg-white transition-all text-slateDark"></textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-sky-500 hover:opacity-95 text-white font-bold rounded-xl transition-all shadow-md shadow-blue-500/15">
                                    Submit Inquiry Message <i class="fa-solid fa-paper-plane ml-2 text-sm"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slateDark text-white pt-20 pb-8 px-6 border-t border-slate-800">
        <div class="max-w-7xl mx-auto space-y-16">
            
            <!-- Grid list -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                
                <!-- Logo & Disclaimer -->
                <div class="space-y-6">
                    <a href="#" class="flex items-center gap-3">
                        <!-- <div class="w-9 h-9 flex items-center justify-center bg-gradient-to-tr from-blue-600 to-sky-400 rounded-lg shadow-lg"> -->
                            @if(file_exists(public_path('images/54.png')))
                                <img src="{{ asset('images/54.png') }}" class=" object-contain" width="150px" alt="SkyWorld logo text">
                            @else
                                <i class="fa-solid fa-chart-line-up text-white text-sm"></i>
                            @endif
                        <!-- </div> -->
                        <!-- <span class="text-xl font-bold font-heading tracking-tight">SkyWorld</span> -->
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Risk Warning: Trading Forex indices involve substantial volatility. The 200% capping policy is actively enforced automatically by algorithmic liquidity pool nodes to ensure payout stability.
                    </p>
                </div>

                <!-- Navigation Quick links -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-slate-300 font-heading">SkyWorld Portal</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#about" class="hover:text-white transition-colors">Our Vision & Mission</a></li>
                        <li><a href="#packages" class="hover:text-white transition-colors">Investment Plans</a></li>
                        <li><a href="#calculator" class="hover:text-white transition-colors">Yield Calculator</a></li>
                        <li><a href="#referral" class="hover:text-white transition-colors">10-Tier IB Income</a></li>
                    </ul>
                </div>

                <!-- Affiliate Links -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-slate-300 font-heading">Member Platform</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="/member/login" class="hover:text-white transition-colors">Login Portal</a></li>
                        <li><a href="/member/register" class="hover:text-white transition-colors">Register Account</a></li>
                        <!-- <li><a href="/admin/login" class="hover:text-white transition-colors">Corporate Administration</a></li> -->
                    </ul>
                </div>

                <!-- Office hours & Legal -->
                <div class="space-y-4">
                    <h4 class="font-semibold text-slate-300 font-heading">Security Compliance</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Anti-Money Laundering Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Liquidity Disclosures</a></li>
                    </ul>
                </div>

            </div>

            <!-- Footer Bottom panel -->
            <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-6 text-xs text-slate-500">
                <span>&copy; 2026 SkyWorld Forex Systems Ltd. All rights reserved.</span>
                
                <!-- Social accounts icons -->
                <div class="flex items-center gap-4 text-lg">
                    <a href="#" class="text-slate-500 hover:text-blue-500 transition-colors" aria-label="SkyWorld Telegram"><i class="fa-brands fa-telegram"></i></a>
                    <a href="#" class="text-slate-500 hover:text-sky-400 transition-colors" aria-label="SkyWorld Twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="text-slate-500 hover:text-red-500 transition-colors" aria-label="SkyWorld Youtube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="text-slate-500 hover:text-white transition-colors" aria-label="SkyWorld Discord"><i class="fa-brands fa-discord"></i></a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Visual Submission Success Toast Popups -->
    <div id="toast-message" class="fixed bottom-6 right-6 z-[100] transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-slateDark text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-800">
            <span class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white"><i class="fa-solid fa-check"></i></span>
            <div>
                <strong class="block text-sm">Message Submitted</strong>
                <span class="text-xs text-slate-400">We will review your inquiry shortly.</span>
            </div>
        </div>
    </div>

    <!-- Frontend Interactive Javascript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Navbar scroll styling toggle
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('glass-nav', 'py-3');
                    navbar.classList.remove('py-4');
                } else {
                    navbar.classList.remove('glass-nav', 'py-3');
                    navbar.classList.add('py-4');
                }
            });

            // Mobile menu drawer toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const line1 = document.getElementById('line1');
            const line2 = document.getElementById('line2');
            const line3 = document.getElementById('line3');

            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                line1.classList.toggle('rotate-45');
                line1.classList.toggle('translate-y-1.5');
                line2.classList.toggle('opacity-0');
                line3.classList.toggle('-rotate-45');
                line3.classList.toggle('-translate-y-1.5');
            });

            // Close mobile menu on clicking drawer link
            document.querySelectorAll('.mobile-link').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    line1.classList.remove('rotate-45', 'translate-y-1.5');
                    line2.classList.remove('opacity-0');
                    line3.classList.remove('-rotate-45', '-translate-y-1.5');
                });
            });

            // Live Simulator: Trading profits accumulated count
            const roiCounter = document.getElementById('live-roi-earnings');
            let initialEarnings = 472.90;
            setInterval(() => {
                initialEarnings += (Math.random() * 0.15);
                roiCounter.textContent = `$${initialEarnings.toFixed(2)}`;
            }, 3000);

            // Live Simulator: Trading console updates
            const tradesBox = document.getElementById('live-trades-box');
            const currencies = ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD', 'GBP/JPY', 'XAU/USD'];
            const actions = ['BUY', 'SELL'];
            setInterval(() => {
                const currency = currencies[Math.floor(Math.random() * currencies.length)];
                const action = actions[Math.floor(Math.random() * actions.length)];
                const profit = (Math.random() * 0.9 + 0.1).toFixed(2);
                
                tradesBox.innerHTML = `
                    <div class="flex justify-between items-center text-green-600 animate-fade-in">
                        <span>${currency} ${action}</span>
                        <strong>+${profit}%</strong>
                    </div>
                ` + tradesBox.innerHTML;
                
                // Keep only top 2 rows
                const rows = tradesBox.getElementsByTagName('div');
                if (rows.length > 2) {
                    tradesBox.removeChild(rows[rows.length - 1]);
                }
            }, 4500);

            // Live Simulator: Forex rates ticker fluctuations
            const tickerIds = ['eurusd', 'gbpusd', 'usdjpy', 'audusd', 'xauusd', 'btcusd'];
            setInterval(() => {
                const randomId = tickerIds[Math.floor(Math.random() * tickerIds.length)];
                const element = document.getElementById(`ticker-${randomId}`);
                const elementDup = document.getElementById(`ticker-${randomId}-dup`);
                if(element) {
                    let currentVal = parseFloat(element.textContent);
                    const change = (Math.random() * 0.02 - 0.01) * currentVal;
                    currentVal += change;
                    const decimals = randomId === 'eurusd' || randomId === 'gbpusd' || randomId === 'audusd' ? 4 : 2;
                    const formatted = currentVal.toFixed(decimals);
                    element.textContent = formatted;
                    if(elementDup) elementDup.textContent = formatted;
                }
            }, 2000);

            // Interactive Calculator logic
            const depositSlider = document.getElementById('deposit-slider');
            const depositNumber = document.getElementById('deposit-number');
            const calcPackageBadge = document.getElementById('calc-package-badge');
            const calcRoiPercentage = document.getElementById('calc-roi-percentage');
            const calcDailyPayout = document.getElementById('calc-daily-payout');
            const calcMonthlyPayout = document.getElementById('calc-monthly-payout');
            const calcCappingPayout = document.getElementById('calc-capping-payout');

            function updateCalculator() {
                let amount = parseFloat(depositNumber.value);
                if (isNaN(amount) || amount < 100) {
                    amount = 1000;
                }
                
                let rate = 0.08; // 8%
                let tier = 'Basic';
                
                if (amount >= 1000) {
                    rate = 0.10; // 10%
                    tier = 'Golden';
                    calcPackageBadge.textContent = 'Golden';
                    calcPackageBadge.className = 'px-2.5 py-0.5 rounded-full bg-orange-100 text-orange-600 font-extrabold uppercase';
                } else {
                    calcPackageBadge.textContent = 'Basic';
                    calcPackageBadge.className = 'px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-600 font-extrabold uppercase';
                }

                calcRoiPercentage.textContent = `${(rate * 100).toFixed(0)}%`;
                
                const dailyProfit = amount * rate;
                const monthlyProfit = dailyProfit * 30;
                const cappingLimit = amount * 2.00;

                calcDailyPayout.textContent = `$${dailyProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                calcMonthlyPayout.textContent = `$${monthlyProfit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                calcCappingPayout.textContent = `$${cappingLimit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            }

            // Sync Slider with Number Input
            depositSlider.addEventListener('input', (e) => {
                depositNumber.value = e.target.value;
                updateCalculator();
            });

            depositNumber.addEventListener('input', (e) => {
                let val = parseInt(e.target.value);
                if (val > 10000) {
                    // Update slider max dynamically if typing higher value
                    depositSlider.max = val;
                }
                depositSlider.value = val;
                updateCalculator();
            });

            depositNumber.addEventListener('blur', (e) => {
                let val = parseInt(e.target.value);
                if (isNaN(val) || val < 100) {
                    depositNumber.value = 100;
                    depositSlider.value = 100;
                }
                updateCalculator();
            });

            // Initialize calculator once on page load
            updateCalculator();

            // Contact Form Simulation toast popup
            const contactForm = document.getElementById('contact-form');
            const toast = document.getElementById('toast-message');

            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Show floating visual success notification
                toast.classList.remove('translate-y-24', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
                
                // Reset fields
                contactForm.reset();

                // Auto hide after 4 seconds
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-24', 'opacity-0');
                }, 4000);
            });

        });
    </script>
</body>
</html>
