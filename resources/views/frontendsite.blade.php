<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexo | Unlock the Power of Your Assets</title>
    <meta name="description" content="Maximize your wealth with Nexo. Get instant credit lines, earn high interest on your crypto, and enjoy seamless financial freedom.">
    
    <link rel="icon" href="{{ asset('images/54-mini.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.5);
            --secondary: #8b5cf6;
            --accent: #10b981;
            --bg-dark: #020617;
            --bg-light: #ffffff;
            --bg-card: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #94a3b8;
            --text-muted-dark: #64748b;
            --glass-border: rgba(255, 255, 255, 0.1);
            --highlighted-text: linear-gradient(135deg, #00e5ff 0%, #014abe 100%);
            --gradient-bg: linear-gradient(135deg, #020617 0%, #002259 55%, #020617 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, .logo {
            font-family: 'Outfit', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1.5rem 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid transparent; /* Removed default border */
        }

        nav.scrolled {
            padding: 1rem 10%;
            background: rgba(2, 6, 23, 0.9);
            border-bottom: 1px solid var(--glass-border); /* Added border on scroll */
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            z-index: 1001;
        }

        .nav-btns {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--glass-border);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--primary-glow);
            filter: brightness(1.1);
        }

        .btn-glow {
            background: var(--primary);
            color: white !important;
            box-shadow: 0 0 15px var(--primary-glow);
            animation: pulse-glow 2s infinite;
            border: none;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 10% 0;
            position: relative;
            background: linear-gradient(rgba(2, 6, 23, 0.7), rgba(2, 6, 23, 0.7)), url('https://miro.medium.com/v2/1*czcdGNhz6jvyxSRvmuxlSQ.gif');
            background-size: cover;
            background-position: center;
            overflow: hidden;
            text-align: center;
        }

        .highlight {
            background: var(--highlighted-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .hero-content {
            max-width: 900px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 2rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }

        .blueprint-box {
            background: rgba(15, 23, 42, 0.8);
            border-left: 4px solid var(--primary);
            border-right: 4px solid var(--primary);
            padding: 1.5rem 3rem;
            margin-top: 2rem;
            max-width: 800px;
            backdrop-filter: blur(5px);
        }

        .blueprint-box p {
            color: #fff !important;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 1rem !important;
            margin-bottom: 0 !important;
            text-transform: uppercase;
        }

        .business-badge {
            font-size: 1rem;
            color: #00e5ff;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .badge {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: inline-block;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .hero p {
            font-size: 1.05rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            max-width: 500px;
        }

        .hero-image {
            position: absolute;
            right: -5%;
            top: 50%;
            transform: translateY(-50%);
            width: 50%;
            z-index: 5;
            animation: float 6s ease-in-out infinite;
        }

        .hero-image img {
            width: 100%;
            filter: drop-shadow(0 0 50px rgba(59, 130, 246, 0.3));
            border-radius: 20px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(-50%) translateX(0); }
            50% { transform: translateY(-55%) translateX(-10px); }
        }

        /* Stats Section */
        .stats {
            padding: 5rem 10%;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            background: rgba(15, 23, 42, 0.5);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        .stat-item h3 {
            font-size: 2rem;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Features Section */
        .features {
            padding: 8rem 10%;
            text-align: center;
        }

        .section-header {
            margin-bottom: 5rem;
        }

        .section-header h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .section-header p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: var(--bg-card);
            padding: 3rem 2rem;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-align: left;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.9);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 2rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* CTA Section */
        .cta {
            padding: 8rem 10%;
            text-align: center;
        }

        .cta-container {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 5rem;
            border-radius: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.1;
        }

        .cta h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            padding: 5rem 10% 2rem;
            background: #010409;
            border-top: 1px solid var(--glass-border);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }

        .footer-about p {
            color: var(--text-muted);
            margin: 1.5rem 0;
            max-width: 300px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-links h4 {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .hero h1 { font-size: 3rem; }
            .feature-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-image { width: 40%; right: 0; }
            .income-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }

        @media (max-width: 768px) {
            nav { padding: 1rem 5%; }
            .mobile-menu-btn { display: block; }
            
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background: rgba(2, 6, 23, 0.98);
                flex-direction: column;
                align-items: center;
                justify-content: center;
                transition: 0.4s;
                backdrop-filter: blur(20px);
                border-left: 1px solid var(--glass-border);
                display: flex; /* Overriding display:none if any */
            }

            .nav-links.active { right: 0; }
            .nav-btns { display: none; }
            
            .hero { padding: 0 5%; flex-direction: column; justify-content: center; text-align: center; height: auto; min-height: 100vh; padding-top: 8rem; }
            .hero-content { max-width: 100%; margin-top: 0; align-items: center; text-align: center; }
            .hero h1 { font-size: 2.5rem; }
            .hero-btns { flex-direction: column; width: 100%; }
            .hero-btns .btn { width: 100%; }
            
            .hero-image { position: relative; width: 90%; transform: none; top: 2rem; margin: 0 auto; display: block !important; right: auto; }
            .stats { grid-template-columns: repeat(2, 1fr); padding: 3rem 5%; }
            .feature-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
            
            .income-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .who-we-are, .problem-section, .process-section, .tools-section, .journey-section { padding: 5rem 5% !important; }
            
            .bento-main h2, .solution-panel h2 { font-size: 2rem !important; }
            .ready-pill { font-size: 0.65rem; padding: 0.5rem 1.5rem; }
            .journey-step { min-width: 100%; padding: 1.5rem; }
        }

        @media (max-width: 480px) {
            .income-grid { grid-template-columns: 1fr !important; }
            .stats { grid-template-columns: 1fr; text-align: center; }
            .hero h1 { font-size: 2.2rem; }
            .footer-nav-minimal { flex-direction: column; gap: 1rem; }
        }
        /* Bento Grid About Section */
        .who-we-are {
            padding: 8rem 10%;
            background: #020617;
            position: relative;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mix Theme Switch Logic */
        body.mixtheme .who-we-are {
            background: var(--bg-light) !important;
        }

        body.mixtheme .who-we-are .bento-item {
            background: var(--gradient-bg) !important;
        }

        body.mixtheme .problem-section {
            background: var(--bg-light);
        }

        body.mixtheme .problem-section .solution-panel {
            background: var(--gradient-bg);
        }

        body.mixtheme .problem-section .problem-list h2 {
            color: var(--text-dark) !important;
        }

        body.mixtheme .problem-section .problem-item {
            background: linear-gradient(135deg, #07122e 0%, #094d7f 55%, #00132f 100%);
            color: #ffffff;
        }

        body.mixtheme .tools-section {
            background: var(--bg-light);
        }

        body.mixtheme .tools-section h2 {
            color: var(--text-dark) !important;
        }

        body.mixtheme .tools-section .tool-card {
            background: linear-gradient(135deg, #07122e 0%, #0c2260 55%, #034bb3 100%);
            border-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        body.mixtheme .tools-section .tool-card h3 {
            color: #ffffff !important;
        }

        body.mixtheme .tools-section .tool-card p {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        body.mixtheme .tools-section .tool-icon {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        body.mixtheme .journey-section {
            background: var(--bg-light);
            border-top-color: #f1f5f9;
        }

        body.mixtheme .journey-section h2 {
            color: var(--text-dark) !important;
        }

        body.mixtheme .journey-section .journey-step {
            background: linear-gradient(135deg, #07122e 0%, #0c2260 55%, #034bb3 100%);
            border-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        body.mixtheme .journey-section .journey-step span:not(.s-num) {
            color: #ffffff;
        }

        body.mixtheme .journey-section .join-now-btn {
            background: #020617;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        body.mixtheme .journey-section .join-now-btn:hover {
            background: #0f172a;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 300px);
            gap: 1.5rem;
            margin-top: 4rem;
        }

        .bento-item {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 32px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .bento-item:hover {
            transform: scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.15);
        }

        .bento-item h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            z-index: 2;
        }

        .bento-item p {
            font-size: 0.95rem;
            color: var(--text-muted);
            z-index: 2;
            margin-bottom: 0;
        }

        .bento-main {
            grid-column: span 2;
            grid-row: span 2;
            justify-content: center;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.2), transparent);
        }

        .bento-main h2 {
            font-size: 2.5rem;
            margin-bottom: 1.2rem;
            line-height: 1.2;
        }

        .bento-main p {
            font-size: 1.05rem;
            max-width: 90%;
        }

        .bento-accent {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        }

        .bento-icon {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 2.5rem;
            opacity: 0.3;
            color: var(--primary);
        }

        .glow-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), rgba(255,255,255,0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        @media (max-width: 1024px) {
            .bento-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: auto;
            }
            .bento-main { grid-column: span 2; grid-row: auto; }
        }

        @media (max-width: 768px) {
            .bento-grid { grid-template-columns: 1fr; }
            .bento-main { grid-column: span 1; }
            .bento-main h2 { font-size: 2.5rem; }
        }

        /* Redesigned Problem Statement */
        .problem-section {
            padding: 10rem 10%;
            background: #020617;
            position: relative;
            overflow: hidden;
        }

        /* Redesigned Broken Section */
        .broken-section {
            padding: 10rem 10%;
            background: #000;
        }

        .broken-container {
            display: flex;
            align-items: center;
            gap: 6rem;
            margin-bottom: 8rem;
        }

        .broken-left { flex: 1; text-align: left; }
        .broken-right { flex: 1.4; position: relative; }

        .broken-left h2 { font-size: 2.8rem; color: #fff; line-height: 1.1; margin-bottom: 1.5rem; font-weight: 800; }
        .broken-left p { color: var(--text-muted); margin-bottom: 2.5rem; font-size: 1rem; max-width: 500px; }

        .broken-list { list-style: none; padding: 0; }
        .broken-list li { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            color: #fff; 
            margin-bottom: 1.5rem; 
            font-size: 1.1rem; 
            font-weight: 500;
            opacity: 0.9;
        }
        .broken-list i { color: var(--primary); font-size: 1.3rem; }

        .broken-right img { 
            width: 100%; 
            border-radius: 24px; 
            box-shadow: 0 30px 100px rgba(0,0,0,0.8), 0 0 50px rgba(59, 130, 246, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .broken-right::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .banner-gold {
            background: #111827;
            border-radius: 30px;
            padding: 4rem 5rem;
            display: flex;
            align-items: center;
            gap: 4rem;
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .banner-icon {
            flex-shrink: 0;
            background: rgba(30, 41, 59, 0.5);
            width: 140px;
            height: 140px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .banner-icon img { height: 90px; filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.4)); }

        .banner-text { 
            font-size: 2.3rem; 
            font-weight: 900; 
            color: #fff; 
            letter-spacing: 3px;
            text-align: left;
            line-height: 1.2;
            text-transform: uppercase;
        }

        @media (max-width: 1200px) {
            .broken-container { flex-direction: column; text-align: center; gap: 4rem; }
            .broken-left { text-align: center; }
            .broken-left p { margin: 0 auto 3rem; }
            .broken-list { display: inline-block; text-align: left; }
            .banner-gold { flex-direction: column; text-align: center; padding: 3rem 2rem; gap: 2rem; }
            .banner-text { font-size: 2rem; text-align: center; }
        }
        /* Improved How It Works */
        .process-section {
            padding: 10rem 10%;
            background: #020617;
            position: relative;
        }

        .process-header {
            text-align: center;
            margin-bottom: 6rem;
        }

        .process-header h2 {
            font-size: 2.8rem;
            background: linear-gradient(135deg, #fff 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            position: relative;
        }

        .process-step-v2 {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 3rem 2rem;
            border-radius: 30px;
            position: relative;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .process-step-v2:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.1);
        }

        .process-step-v2 .step-number {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
            position: absolute;
            top: -5px;
            right: -5px;
            opacity: 0.05;
            color: #fff;
            transition: all 0.5s ease;
        }

        .process-step-v2:hover .step-number {
            opacity: 0.15;
            transform: scale(1.1);
        }

        .step-icon-v2 {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .process-step-v2 h3 {
            font-size: 1.25rem;
            color: #fff;
            margin: 0;
        }

        .process-step-v2 p {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        .process-visual-full {
            grid-column: span 3;
            margin-top: 4rem;
            position: relative;
            border-radius: 40px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .process-visual-full img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            opacity: 0.6;
            transition: all 0.8s ease;
        }

        .process-visual-full:hover img {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .visual-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, #020617, transparent);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
            text-align: center;
        }

        .visual-overlay h2 {
            font-size: 2.5rem;
            letter-spacing: 5px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 300;
        }

        @media (max-width: 1024px) {
            .process-grid { grid-template-columns: repeat(2, 1fr); }
            .process-visual-full { grid-column: span 2; }
        }

        @media (max-width: 768px) {
            .process-grid { grid-template-columns: 1fr; }
            .process-visual-full { grid-column: span 1; padding: 2rem; }
            .visual-overlay h2 { font-size: 1.5rem; letter-spacing: 2px; }
        }
        /* Financial Tools Section */
        .tools-section {
            padding: 7rem 10%;
            background: #010409;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 3.5rem;
            margin-bottom: 5rem;
        }

        .tool-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 1.8rem;
            border-radius: 24px;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            text-align: left;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .tool-card:hover {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--primary);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .tool-icon {
            width: 65px;
            height: 65px;
            background: rgba(59, 130, 246, 0.08);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary);
            flex-shrink: 0;
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        .tool-content h3 { font-family: 'Outfit', sans-serif; font-size: 1.25rem; margin-bottom: 0.5rem; color: #fff; font-weight: 700; }
        .tool-content p { font-size: 0.95rem; color: var(--text-muted); line-height: 1.7; }

        .tools-visual {
            background: #020617;
            border-radius: 32px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: 0 30px 70px rgba(0,0,0,0.5);
            cursor: pointer;
            min-height: 750px;
        }

        .bg-video-preview {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            z-index: 1;
            opacity: 0.6;
        }

        .video-overlay-tint {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(2, 6, 23, 0.4) 0%, rgba(2, 6, 23, 0.8) 100%);
            z-index: 2;
        }



        .tools-visual-bottom {
            display: flex;
            align-items: center;
            gap: 4rem;
            max-width: 1000px;
            position: relative;
            z-index: 10;
        }

        .tools-visual-bottom .b-icon { height: 90px; filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3)); }
        .tools-visual-bottom h2 { 
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem; 
            font-weight: 900; 
            color: #fff; 
            letter-spacing: 1px;
            line-height: 1.2;
            text-align: left;
            text-transform: uppercase;
        }

        /* Video Modal Styles */
        .video-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .modal-content {
            position: relative;
            width: 90%;
            max-width: 1000px;
            background: #000;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 100px rgba(59, 130, 246, 0.3);
        }

        .modal-content video { width: 100%; display: block; border-radius: 20px; }

        .close-modal {
            position: absolute;
            top: 15px; right: 25px;
            font-size: 2.5rem;
            color: #fff;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s;
            text-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .close-modal:hover { color: var(--primary); transform: scale(1.1); }

        .play-icon-overlay {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 90px; height: 90px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #fff;
            box-shadow: 0 0 50px rgba(59, 130, 246, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 10;
        }

        .tools-visual:hover .play-icon-overlay { 
            transform: translate(-50%, -50%) scale(1.15);
            background: #2563eb;
            box-shadow: 0 0 70px rgba(59, 130, 246, 0.8);
        }

        .tools-visual:hover .play-icon-overlay { 
            opacity: 1; 
            transform: translate(-50%, -50%) scale(1);
        }

        @media (max-width: 1024px) {
            .tools-grid { grid-template-columns: 1fr; }
            .tools-visual-bottom { flex-direction: column; text-align: center; gap: 2rem; }
            .tools-visual-bottom h2 { text-align: center; font-size: 2rem; }
            .tools-visual { padding: 4rem 2rem; }
            .play-icon-overlay { width: 80px; height: 80px; font-size: 2rem; opacity: 1; }
        }

        /* Redesigned Type of Incomes (v3) */
        .income-v3-card {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }

        .income-v3-top {
            background: #1e293b;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: left;
            height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .income-v3-top.green { background: #86efac; color: #064e3b; border: none; }
        .income-v3-top.blue { background: #93c5fd; color: #1e3a8a; border: none; }
        .income-v3-top.purple { background: #c4b5fd; color: #4c1d95; border: none; }
        .income-v3-top.pink { background: #f9a8d4; color: #831843; border: none; }
        .income-v3-top.orange { background: #fdba74; color: #7c2d12; border: none; }
        .income-v3-top.cyan { background: #67e8f9; color: #083344; border: none; }

        .income-v3-top.highlight-roi {
            box-shadow: 0 0 30px rgba(134, 239, 172, 0.4);
            animation: roi-pulse 3s infinite ease-in-out;
            z-index: 5;
        }

        @keyframes roi-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }

        .income-v3-top h4 { 
            font-size: 3rem; 
            font-weight: 900; 
            margin-bottom: 0.5rem; 
            opacity: 0.2; 
            position: absolute;
            top: 10px;
            right: 15px;
        }

        .income-v3-top h3 { 
            font-size: 1.3rem; 
            margin-bottom: 0.8rem; 
            font-weight: 800; 
            position: relative;
            z-index: 2;
        }

        .income-v3-top p { 
            font-size: 0.85rem; 
            opacity: 0.8; 
            line-height: 1.4; 
            position: relative;
            z-index: 2;
        }

        .income-v3-pill {
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .income-v3-pill.green { background: #001610; color: #86efac; border-color: rgba(134, 239, 172, 0.3); }
        .income-v3-pill.blue { background: #060d21; color: #93c5fd; border-color: rgba(147, 197, 253, 0.3); }
        .income-v3-pill.purple { background: #130924; color: #c4b5fd; border-color: rgba(196, 181, 253, 0.3); }
        .income-v3-pill.pink { background: #2a0916; color: #f9a8d4; border-color: rgba(249, 168, 212, 0.3); }
        .income-v3-pill.orange { background: #260f08; color: #fdba74; border-color: rgba(253, 186, 116, 0.3); }
        .income-v3-pill.cyan { background: #031a24; color: #67e8f9; border-color: rgba(103, 232, 249, 0.3); }

        .income-v3-img {
            height: 180px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .income-v3-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .income-v3-card:hover .income-v3-img img {
            transform: scale(1.1);
        }

        .staking-icon-box {
            background: #1e293b;
            border-radius: 20px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .staking-icon-box img {
            max-width: 100%;
            max-height: 100%;
            filter: drop-shadow(0 10px 20px rgba(59, 130, 246, 0.3));
        }

        @media (max-width: 1200px) {
            .income-grid { grid-template-columns: repeat(3, 1fr); gap: 3rem; }
        }

        @media (max-width: 768px) {
            .income-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem; }
        }

        @media (max-width: 480px) {
            .income-grid { grid-template-columns: 1fr; }
        }
        /* Start Your Journey Section */
        .journey-section {
            padding: 10rem 10%;
            background: #010409;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .ready-pill {
            display: inline-block;
            padding: 0.6rem 2rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--primary);
            font-size: 0.75rem;
            letter-spacing: 2px;
            margin-bottom: 2rem;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 50px;
        }

        .journey-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 3rem;
            margin-bottom: 4rem;
            flex-wrap: wrap;
        }

        .journey-step {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 3rem;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 20px;
            min-width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .journey-step:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.6);
        }

        .journey-step .s-num {
            font-size: 0.8rem;
            color: var(--primary);
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .join-now-cta {
            margin-top: 1rem;
        }

        .join-now-btn {
            display: inline-block;
            padding: 1.2rem 4rem;
            background: #ffffff;
            color: #020617;
            font-weight: 800;
            font-size: 1.1rem;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 0 30px rgba(255,255,255,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            font-family: 'Outfit', sans-serif;
        }

        .join-now-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 50px rgba(255,255,255,0.3);
            background: #fff;
        }

        @media (max-width: 768px) {
            .journey-step { min-width: 100%; padding: 1.5rem; }
            .join-now-btn { padding: 1rem 3rem; font-size: 1rem; }
        }
        /* Modern Compact Glow Footer */
        footer {
            padding: 4rem 5% 3rem;
            background: radial-gradient(circle at center, #0d1a3a 0%, #000 100%);
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            position: relative;
        }

        .footer-logo-v2 {
            margin-bottom: 2rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer-logo-v2:hover {
            opacity: 1;
        }

        .footer-logo-v2 img {
            height: 50px;
        }

        .footer-nav-minimal {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-nav-minimal a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .footer-nav-minimal a:hover {
            color: var(--primary);
        }

        .footer-social-v2 {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .footer-social-v2 a {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
        }

        .footer-social-v2 a:hover {
            transform: translateY(-8px) rotate(8deg);
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.2);
        }

        .footer-copyright-v2 {
            color: rgba(255, 255, 255, 0.2);
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            display: inline-block;
            padding-top: 1.5rem;
        }
    </style>
</head>
<body class="mixtheme">

    <!-- Navigation -->
    <nav id="navbar">
        <a href="#" class="logo">
            <img src="{{ asset('images/54.png') }}" alt="Nexo" srcset="" style="width:120px;">
        </a>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>

            <li><a href="#process">Earning</a></li>
            <li><a href="#incomes">Incomes</a></li>
        </ul>
        <div class="nav-btns">
            <a href="/member/login" class="btn btn-glow">Sign In</a>
        </div>
        <div class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fa-solid fa-bars"></i>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="business-badge">NEXO BUSINESS</div>
            <h1>Unleash the Power of <br> Digital Wealth</h1>
            
            <div class="blueprint-box">
                <p>A STRATEGIC BLUEPRINT FOR PASSIVE INCOME, DAILY ROI, AND GLOBAL NETWORK EXCELLENCE.</p>
            </div>

            <div class="hero-btns" style="display: flex; gap: 1rem; margin-top: 3rem;">
                <a href="/member/login" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem;">Get Started Now</a>
                <a href="#process" class="btn btn-outline" style="padding: 1rem 2.5rem; font-size: 1rem;">View Plan</a>
            </div>
        </div>
        
        <!-- Background Decorative Shapes (Optional) -->
        <div class="hero-image" style="display: none;">
            {{-- <img src="{{ asset('images/crypto.gif') }}" alt="Nexo Trading App" id="heroImg"> --}}
        </div>
    </section>

    <!-- Redesigned Who We Are Section -->
    <section class="who-we-are" id="about">
        <div class="bento-grid">
            <div class="bento-item bento-main">
                <h2>Pioneering the <br> <span class="highlight">Future</span> of Finance</h2>
                <p>Nexo is a next-generation Fintech & Asset Management platform designed to help everyday investors grow their wealth through a secure, transparent, and automated ecosystem.</p>
            </div>
            
            <div class="bento-item bento-accent">
                <div class="bento-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>Security First</h3>
                <p>Institutional-grade security with multi-layer encryption.</p>
            </div>
            
            <div class="bento-item">
                <div class="bento-icon"><i class="fa-solid fa-globe"></i></div>
                <h3>Global Reach</h3>
                <p>Borderless access to wealth management tools.</p>
            </div>
            
            <div class="bento-item">
                <div class="bento-icon"><i class="fa-solid fa-users"></i></div>
                <h3>Community</h3>
                <p>Powered by trust and collective growth.</p>
            </div>
            
            <div class="bento-item bento-accent">
                <div class="bento-icon"><i class="fa-solid fa-bolt"></i></div>
                <h3>Smart ROI</h3>
                <p>Automated algorithms for consistent daily returns.</p>
            </div>
        </div>
        
        <div class="about-bottom-text" style="margin-top: 4rem; text-align: center; color: var(--text-muted); font-style: italic;">
            "To bridge the gap between traditional finance and digital wealth, providing everyone—from beginners to leaders—an equal opportunity to earn passive income."
        </div>
    </section>



    <!-- Stats -->
    <section class="stats">
        <div class="stat-item">
            <h3>$10B+</h3>
            <p>Assets Under Management</p>
        </div>
        <div class="stat-item">
            <h3>5M+</h3>
            <p>Global Active Users</p>
        </div>
        <div class="stat-item">
            <h3>16%</h3>
            <p>Max Annual Interest</p>
        </div>
        <div class="stat-item">
            <h3>200+</h3>
            <p>Jurisdictions Covered</p>
        </div>
    </section>

    <!-- Redesigned Broken vs Future Section -->
    <!-- Problem Statement Section -->
    <style>
        .problem-section {
            padding: 7rem 10%;
            background: #020617;
            position: relative;
            overflow: hidden;
        }

        .problem-container {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .problem-list {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .problem-item {
            background: rgba(30, 41, 59, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.03);
            padding: 1.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .problem-item:hover {
            background: rgba(239, 68, 68, 0.03);
            border-color: rgba(239, 68, 68, 0.2);
            transform: translateX(8px);
        }

        .problem-item i {
            font-size: 1.6rem;
            color: #f87171;
            background: rgba(248, 113, 113, 0.1);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .problem-item h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            margin-bottom: 0.2rem;
            color: #fff;
            font-weight: 600;
        }

        .problem-item p {
            font-size: 0.85rem;
            color: #94a3b8;
            margin: 0;
            line-height: 1.5;
        }

        .solution-panel {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent),
                        rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 3.5rem 2.5rem;
            border-radius: 32px;
            text-align: center;
            position: relative;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        .solution-panel img {
            width: 120px;
            margin-bottom: 1.5rem;
            animation: floatLogo 4s infinite ease-in-out;
            filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3));
        }

        .solution-panel h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            line-height: 1.1;
            margin-bottom: 1.2rem;
            background: linear-gradient(135deg, #fff 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .solution-panel p {
            color: #94a3b8;
            font-size: 1rem;
            max-width: 90%;
            margin: 0 auto;
            line-height: 1.6;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @media (max-width: 1024px) {
            .problem-container { grid-template-columns: 1fr; gap: 3rem; }
            .solution-panel { padding: 3rem 1.5rem; }
            .problem-section { padding: 5rem 5%; }
        }
    </style>
    <section class="problem-section" id="problem">
        <div class="problem-container">
            <div class="problem-list">
                <div style="color: #f87171; font-size: 0.85rem; letter-spacing: 3px; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase;">THE CHALLENGE</div>
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 3rem; color: #fff; margin-bottom: 2.5rem; line-height: 1.1; font-weight: 800;">Traditional Finance <br> is <span style="color: #f87171;">Broken.</span></h2>
                
                <div class="problem-item">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <div>
                        <h3>Stagnant Growth</h3>
                        <p>Traditional assets offer outdated returns that barely keep up with inflation.</p>
                    </div>
                </div>

                <div class="problem-item">
                    <i class="fa-solid fa-lock"></i>
                    <div>
                        <h3>Complex Barriers</h3>
                        <p>High entry costs and gatekeepers make it impossible for beginners to start.</p>
                    </div>
                </div>

                <div class="problem-item">
                    <i class="fa-solid fa-ban"></i>
                    <div>
                        <h3>Zero Flexibility</h3>
                        <p>Locked funds and hidden fees drain your wealth before you can use it.</p>
                    </div>
                </div>
            </div>

            <div class="solution-panel">
                <img src="{{ asset('images/54.png') }}" alt="Nexo Logo">
                <h2>The Smart Way <br> to Grow Wealth</h2>
                <p>Experience an automated, high-yield ecosystem designed to put your capital to work 24/7 with total transparency.</p>
                <div style="margin-top: 2rem;">
                    <a href="/member/register" class="btn btn-primary" style="padding: 1.2rem 3rem; border-radius: 12px; font-weight: 700;">Get Started Now <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Improved How It Works Section -->
    <section class="process-section" id="process">
        <div class="process-header">
            <h2 style="font-size: 2.8rem; color: #fff;">The Path to Prosperity</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 1rem auto 0;">A simplified 6-step blueprint to unlock your financial freedom with Nexo.</p>
        </div>
        
        <div class="process-grid">
            <div class="process-step-v2">
                <div class="step-number">01</div>
                <div class="step-icon-v2"><i class="fa-solid fa-user-plus"></i></div>
                <h3>Join Nexo</h3>
                <p>Register your account and become part of our global investment community.</p>
            </div>
            
            <div class="process-step-v2">
                <div class="step-number">02</div>
                <div class="step-icon-v2"><i class="fa-solid fa-wallet"></i></div>
                <h3>Deposit USD</h3>
                <p>Securely fund your wallet with USD or stablecoins to start earning.</p>
            </div>

            <div class="process-step-v2">
                <div class="step-number">03</div>
                <div class="step-icon-v2"><i class="fa-solid fa-layer-group"></i></div>
                <h3>Activate Level</h3>
                <p>Select your desired investment tier and activate your income stream.</p>
            </div>

            <div class="process-step-v2">
                <div class="step-number">04</div>
                <div class="step-icon-v2"><i class="fa-solid fa-users-gear"></i></div>
                <h3>Build Team</h3>
                <p>Invite others and build a powerful team to leverage collective growth.</p>
            </div>

            <div class="process-step-v2">
                <div class="step-number">05</div>
                <div class="step-icon-v2"><i class="fa-solid fa-chart-line"></i></div>
                <h3>Earn ROI Daily</h3>
                <p>Sit back and watch your wealth grow with consistent daily payouts.</p>
            </div>

            <div class="process-step-v2">
                <div class="step-number">06</div>
                <div class="step-icon-v2"><i class="fa-solid fa-gem"></i></div>
                <h3>Earn Bonuses</h3>
                <p>Unlock extra rewards and exclusive bonuses as your network thrives.</p>
            </div>
        </div>
    </section>

    <!-- Financial Tools Section -->
    <section class="tools-section" id="tools">
        <div class="section-header">
            <h2 style="font-size: 2.8rem; color: var(--text-dark);">Explore <span class="highlight">Nexo</span> Financial Tools</h2>
            <p style="color: var(--text-muted); max-width: 700px; margin: 1.2rem auto 0;">Powerful digital assets management tools designed to scale your wealth with precision and security.</p>
        </div>

        <div class="tools-grid">
            <div class="tool-card">
                <div class="tool-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="tool-content">
                    <h3>Platform Security</h3>
                    <p>Advanced security protocols and insurance to protect your digital assets.</p>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon"><i class="fa-solid fa-chart-line"></i></div>
                <div class="tool-content">
                    <h3>Flexible Savings</h3>
                    <p>Earn high interest on your idle crypto balances with daily payouts.</p>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon"><i class="fa-solid fa-credit-card"></i></div>
                <div class="tool-content">
                    <h3>Crypto Credit Line</h3>
                    <p>Unlock cash without selling your crypto using our instant loans.</p>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
                <div class="tool-content">
                    <h3>Fiat Exchange</h3>
                    <p>Instantly swap between cryptocurrencies and fiat currencies at competitive rates.</p>
                </div>
            </div>
        </div>

        <div class="tools-visual" onclick="openVideo()">
            <video class="bg-video-preview" autoplay muted loop playsinline>
                <source src="{{ asset('images/nexo-plan.mp4') }}" type="video/mp4">
            </video>
            <div class="video-overlay-tint"></div>
    
            <div class="play-icon-overlay"><i class="fa-solid fa-play"></i></div>            
        </div>
    </section>
    

    <!-- Redesigned Type of Incomes Section -->
    <section class="income-section" id="incomes" style="background: radial-gradient(circle at center, #0d1a3a 0%, #000 100%); padding: 10rem 5%;">
        <div class="section-header" style="text-align: center; margin-bottom: 6rem;">
            <h2 style="font-size: 2.8rem; margin-bottom: 1rem; color: #fff;">Type of Incomes</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Find real-time spaces transparency in all incomes.</p>
        </div>
        
        <div class="income-grid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.5rem;">
            <!-- Column 1: ROI -->
            <div class="income-v3-card">
                <div class="income-v3-top green highlight-roi">
                    <h4>01</h4>
                    <h3>ROI Income</h3>
                    <p>Daily automated rewards on your stablecoin assets.</p>
                </div>
                <div class="income-v3-pill green">Auto ROI <i class="fa-solid fa-fire"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_roi.png') }}" alt="ROI Growth">
                </div>
            </div>

            <!-- Column 2: Level -->
            <div class="income-v3-card">
                <div class="income-v3-top blue">
                    <h4>02</h4>
                    <h3>Level Reward</h3>
                    <p>Unlock deeper network rewards as you scale up.</p>
                </div>
                <div class="income-v3-pill blue">Network Level <i class="fa-solid fa-layer-group"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_level.png') }}" alt="Level Handshake">
                </div>
            </div>

            <!-- Column 3: Referral -->
            <div class="income-v3-card">
                <div class="income-v3-top purple">
                    <h4>03</h4>
                    <h3>Referral Bonus</h3>
                    <p>Get rewarded for every direct invitation you make.</p>
                </div>
                <div class="income-v3-pill purple">Direct Invite <i class="fa-solid fa-user-plus"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_referral.png') }}" alt="Referral Team">
                </div>
            </div>

            <!-- Column 4: Team Profit -->
            <div class="income-v3-card">
                <div class="income-v3-top pink">
                    <h4>04</h4>
                    <h3>Team Profit</h3>
                    <p>Earn from the collective success of your entire network.</p>
                </div>
                <div class="income-v3-pill pink">Group Yield <i class="fa-solid fa-users"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_team.png') }}" alt="Team Growth">
                </div>
            </div>

            <!-- Column 5: Future Fund -->
            <div class="income-v3-card">
                <div class="income-v3-top orange">
                    <h4>05</h4>
                    <h3>Future Fund</h3>
                    <p>Build your long-term wealth with dedicated future savings.</p>
                </div>
                <div class="income-v3-pill orange">Future Savings <i class="fa-solid fa-vault"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_future.png') }}" alt="Future Fund">
                </div>
            </div>

            <!-- Column 6: Cashback -->
            <div class="income-v3-card">
                <div class="income-v3-top cyan">
                    <h4>06</h4>
                    <h3>Cashback</h3>
                    <p>Exclusive rebates on your transactions and swaps.</p>
                </div>
                <div class="income-v3-pill cyan">Daily Rebates <i class="fa-solid fa-hand-holding-dollar"></i></div>
                <div class="income-v3-img">
                    <img src="{{ asset('images/income_cashback.jpg') }}" alt="Cashback Savings">
                </div>
            </div>
        </div>
    </section>

    <!-- Start Your Journey Section -->
    <section class="journey-section">
        <div class="ready-pill">READY TO EARN?</div>
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 3.5rem; margin-bottom: 1.5rem; color: var(--text-dark); font-weight: 800;">Start Your <span class="highlight">Journey</span></h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 3rem;">A simple, secure path to financial growth. Join thousands of users earning daily rewards.</p>
        
        <div class="journey-grid">
            <div class="journey-step">
                <span class="s-num">Step 01</span>
                <span>Register</span>
            </div>
            <div class="journey-step">
                <span class="s-num">Step 02</span>
                <span>Deposit</span>
            </div>
            <div class="journey-step">
                <span class="s-num">Step 03</span>
                <span>Earn Daily</span>
            </div>
        </div>

        <div class="join-now-cta">
            <a href="/member/register" class="join-now-btn">Join Nexo Now <i class="fa-solid fa-arrow-right" style="margin-left: 8px; font-size: 0.9rem;"></i></a>
        </div>
    </section>

    <!-- Modern Glow Footer -->
    <footer>
        <div class="footer-logo-v2">
            <img src="{{ asset('images/54.png') }}" alt="Nexo Logo">
        </div>

        <div class="footer-nav-minimal">
            <a href="#about">About</a>

            <a href="#process">Process</a>
            <a href="#incomes">Incomes</a>
        </div>
        
        <div class="footer-social-v2">
            <a href="https://t.me/Nexobizz" target="_blank" title="Telegram"><i class="fa-brands fa-telegram"></i></a>
            <a href="https://x.com/NexoBizz" target="_blank" title="X (Twitter)"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.youtube.com/@NexoBizzofficial" target="_blank" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
            <a href="https://www.facebook.com/nexobizz" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/nexobizz.official/" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
        </div>

        <div class="footer-copyright-v2">
            DESIGNED FOR THE FUTURE &copy; 2026 NEXO.
        </div>
    </footer>

    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (nav) {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            }
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    // Close mobile menu if open
                    const navLinks = document.querySelector('.nav-links');
                    if(navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        document.getElementById('mobileMenuBtn').innerHTML = '<i class="fa-solid fa-bars"></i>';
                    }
                }
            });
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.querySelector('.nav-links');
        
        if(mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                if(navLinks.classList.contains('active')) {
                    mobileMenuBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                } else {
                    mobileMenuBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
                }
            });
        }
    </script>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal" onclick="closeVideo()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="close-modal" onclick="closeVideo()">&times;</span>
            <video id="planVideo" controls>
                <source src="{{ asset('images/nexo-plan.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <script>
        function openVideo() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('planVideo');
            if (modal && video) {
                modal.style.display = 'flex';
                video.play();
            }
        }
        
        function closeVideo() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('planVideo');
            if (modal && video) {
                modal.style.display = 'none';
                video.pause();
            }
        }

        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .stat-item').forEach(el => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "all 0.6s ease-out";
            observer.observe(el);
        });
    </script>
</body>
</html>
