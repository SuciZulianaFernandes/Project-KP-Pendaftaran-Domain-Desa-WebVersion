<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANDA - Sistem Pelayanan Domain Desa Kabupaten Bengkalis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal-1: #109696;
            --teal-2: #1A85A5;
            --blue-1: #1760C5;
            --bg-main: #FFFFFF;
            --bg-soft: #F0FDFA;
            --bg-card: #FFFFFF;
            --text-dark: #1E293B;
            --text-body: #475569;
            --text-muted: #94A3B8;
            --border-light: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow-md: 0 8px 24px rgba(16, 150, 150, 0.08);
            --shadow-xl: 0 20px 60px rgba(16, 150, 150, 0.12);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-body);
            overflow-x: hidden;
        }

        /* ===== PARTICLES CANVAS ===== */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border-light);
            padding: 16px 0;
            transition: all 0.4s ease;
            z-index: 1000;
        }

        .navbar.scrolled {
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 42px;
            width: auto;
            object-fit: contain;
            transition: all 0.4s ease;
        }
        
        .navbar-brand span {
            font-weight: 900;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }

        .nav-link {
            color: var(--text-body) !important;
            font-weight: 500;
            font-size: 0.88rem;
            margin: 0 4px;
            padding: 8px 18px !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .nav-link:hover {
            color: var(--teal-1) !important;
            background: rgba(16, 150, 150, 0.06);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--teal-1), var(--blue-1));
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after { width: 24px; }

        .nav-link.active {
            color: var(--teal-1) !important;
            background: rgba(16, 150, 150, 0.08);
        }
        
        .auth-buttons { margin-left: 20px; }

        .auth-buttons .btn-outline-primary {
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            font-weight: 700;
            font-size: 0.88rem;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1));
        }

        .auth-buttons .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 150, 150, 0.35);
            background: linear-gradient(135deg, var(--teal-2), var(--blue-1));
            color: white;
        }

        .navbar-toggler {
            border: 1px solid var(--border-light);
            padding: 6px 10px;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(16, 150, 150, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: linear-gradient(180deg, var(--bg-soft) 0%, var(--bg-main) 100%);
            padding: 140px 0 120px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(16, 150, 150, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 150, 150, 0.04) 1px, transparent 1px);
            background-size: 80px 80px;
            mask-image: radial-gradient(ellipse at 50% 50%, black 20%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 50%, black 20%, transparent 70%);
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(80px, 80px); }
        }
        
        .hero-content { position: relative; z-index: 2; }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            border: 1px solid rgba(16, 150, 150, 0.15);
            padding: 10px 24px;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--teal-1);
            margin-bottom: 32px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            box-shadow: var(--shadow-sm);
        }

        .hero-badge .pulse-dot {
            width: 8px; height: 8px; background: var(--teal-1); border-radius: 50%; position: relative;
        }
        .hero-badge .pulse-dot::before {
            content: ''; position: absolute; inset: -4px; border: 2px solid var(--teal-1); border-radius: 50%;
            animation: pingPulse 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        @keyframes pingPulse { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(2); opacity: 0; } }

        .hero-title {
            font-size: 4rem; font-weight: 900; line-height: 1.1; margin-bottom: 28px; letter-spacing: -2px; color: var(--text-dark);
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--teal-1) 0%, var(--teal-2) 50%, var(--blue-1) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero-subtitle { font-size: 1.2rem; color: var(--text-body); line-height: 1.8; max-width: 550px; margin: 0 auto 48px; font-weight: 400; }
        .hero-illustration { position: relative; display: flex; align-items: center; justify-content: center; }
        .hero-illustration svg { width: 100%; max-width: 500px; height: auto; filter: drop-shadow(0 10px 40px rgba(16, 150, 150, 0.15)); }
        .illustration-glow {
            position: absolute; width: 350px; height: 350px; background: radial-gradient(circle, rgba(16, 150, 150, 0.1) 0%, transparent 70%);
            border-radius: 50%; animation: glowPulse 4s ease-in-out infinite alternate;
        }
        @keyframes glowPulse { 0% { transform: scale(0.9); opacity: 0.5; } 100% { transform: scale(1.1); opacity: 1; } }

        .floating-shape { position: absolute; border-radius: 50%; opacity: 0.5; animation: floatShape 20s ease-in-out infinite; }
        @keyframes floatShape {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -30px) rotate(90deg); }
            50% { transform: translate(-10px, -50px) rotate(180deg); }
            75% { transform: translate(30px, -20px) rotate(270deg); }
        }

        /* ===== FEATURE SECTION ===== */
        .feature-section { padding: 120px 0; background: var(--bg-main); position: relative; }
        .feature-section::before {
            content: ""; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-light), transparent);
        }
        .section-label {
            display: inline-flex; align-items: center; gap: 12px; color: var(--teal-1); font-size: 0.75rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 3px; margin-bottom: 20px;
        }
        .section-label .line { width: 32px; height: 2px; background: linear-gradient(90deg, var(--teal-1), var(--blue-1)); border-radius: 2px; }
        .section-title { font-size: 2.8rem; font-weight: 900; color: var(--text-dark); margin-bottom: 20px; letter-spacing: -1px; }
        .section-desc { color: var(--text-body); font-size: 1.05rem; max-width: 580px; margin: 0 auto 72px; line-height: 1.8; }
        
        .feature-card {
            background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 24px; padding: 44px 36px; text-align: center;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); height: 100%; position: relative; overflow: hidden; box-shadow: var(--shadow-sm);
        }
        .feature-card::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--teal-1), var(--teal-2), var(--blue-1));
            opacity: 0; transition: opacity 0.4s ease;
        }
        .feature-card::after {
            content: ""; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 50% 0%, rgba(16, 150, 150, 0.04) 0%, transparent 50%);
            opacity: 0; transition: opacity 0.5s ease;
        }
        .feature-card:hover { transform: translateY(-12px); border-color: rgba(16, 150, 150, 0.2); box-shadow: var(--shadow-xl); }
        .feature-card:hover::before, .feature-card:hover::after { opacity: 1; }
        
        .feature-icon {
            width: 76px; height: 76px; background: linear-gradient(135deg, rgba(16, 150, 150, 0.08), rgba(23, 96, 197, 0.08));
            border: 1px solid rgba(16, 150, 150, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px; font-size: 1.5rem; color: var(--teal-1); transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); position: relative; z-index: 1;
        }
        .feature-card:hover .feature-icon {
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1)); color: white; border-color: transparent;
            transform: scale(1.08) rotate(-5deg); box-shadow: 0 12px 32px rgba(16, 150, 150, 0.35);
        }
        .feature-title { font-weight: 800; font-size: 1.05rem; margin-bottom: 10px; color: var(--text-dark); letter-spacing: 1px; position: relative; z-index: 1; }
        .feature-subtitle { color: var(--teal-2); font-size: 0.78rem; font-weight: 600; margin-bottom: 20px; letter-spacing: 0.3px; position: relative; z-index: 1; }
        .feature-card p:last-child { color: var(--text-body); font-size: 0.88rem; line-height: 1.8; margin: 0; position: relative; z-index: 1; }

        /* ===== STATS BAR ===== */
        .stats-bar { padding: 60px 0; background: linear-gradient(135deg, var(--teal-1) 0%, var(--teal-2) 50%, var(--blue-1) 100%); position: relative; overflow: hidden; }
        .stat-item { text-align: center; padding: 20px; position: relative; z-index: 1; }
        .stat-number { font-size: 2.8rem; font-weight: 900; color: #FFFFFF; line-height: 1.2; }
        .stat-label { color: rgba(255, 255, 255, 0.8); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 8px; }

        /* ===== KEBIJAKAN CARDS ===== */
        .policy-doc-card {
            background: white; border: 1px solid var(--border-light); border-radius: 24px; padding: 40px 30px; text-align: center;
            transition: all 0.4s ease; box-shadow: var(--shadow-sm); height: 100%; display: flex; flex-direction: column; justify-content: space-between;
        }
        .policy-doc-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); border-color: rgba(16, 150, 150, 0.2); }
        .policy-doc-icon { width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: white; font-size: 1.5rem; }
        .bg-red { background: linear-gradient(135deg, #ef4444, #b91c1c); }
        .bg-green { background: linear-gradient(135deg, #10b981, #047857); }
        .bg-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .policy-doc-title { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0 0 20px 0; }
        .btn-policy {
            display: inline-block; margin-top: auto; padding: 8px 20px; background: rgba(16, 150, 150, 0.08); color: var(--teal-1);
            border-radius: 10px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease;
        }
        .btn-policy:hover { background: var(--teal-1); color: white; }

        /* ===== PERSYARATAN LIST ===== */
        .policy-list { list-style: none; padding: 0; max-width: 850px; margin: 0 auto; }
        .policy-list li {
            background: white; border: 1px solid var(--border-light); border-radius: 18px; padding: 28px 32px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 22px; transition: all 0.35s ease; box-shadow: var(--shadow-sm);
        }
        .policy-list li:hover { border-color: rgba(16, 150, 150, 0.3); transform: translateX(8px); box-shadow: var(--shadow-md); }
        .policy-icon {
            flex-shrink: 0; width: 54px; height: 54px; background: linear-gradient(135deg, rgba(16, 150, 150, 0.1), rgba(23, 96, 197, 0.1));
            border: 1px solid rgba(16, 150, 150, 0.12); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--teal-1); font-size: 1.2rem;
        }
        .policy-list h5 { font-weight: 800; color: var(--text-dark); font-size: 1.05rem; margin-bottom: 6px; }
        .policy-list p { color: var(--text-body); font-size: 0.9rem; line-height: 1.7; margin: 0; }

        /* ===== FOOTER ===== */
        .footer { background: #F8FAFC; color: var(--text-body); padding: 80px 0 30px; position: relative; border-top: 1px solid var(--border-light); }
        
        .footer-logo { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
        .footer-logo img { height: 50px; width: auto; }
        .footer-logo span {
            font-size: 1.8rem; font-weight: 900;
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .footer p { color: var(--text-body); font-size: 0.88rem; line-height: 1.8; }
        .social-icons a {
            display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background: white;
            border: 1px solid var(--border-light); color: var(--text-body); border-radius: 14px; margin-right: 10px; font-size: 1rem;
            transition: all 0.3s ease; text-decoration: none;
        }
        .social-icons a:hover {
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1)); color: white; border-color: transparent;
            transform: translateY(-4px); box-shadow: 0 8px 20px rgba(16, 150, 150, 0.25);
        }

        .footer h5 { color: var(--text-dark); font-weight: 800; font-size: 0.95rem; margin-bottom: 24px; position: relative; padding-bottom: 14px; letter-spacing: 0.5px; }
        .footer h5::after { content: ""; position: absolute; bottom: 0; left: 0; width: 32px; height: 3px; background: linear-gradient(90deg, var(--teal-1), transparent); border-radius: 2px; }
        .footer-contact p { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
        .footer-contact i { color: var(--teal-1); margin-top: 4px; width: 16px; text-align: center; }
        
        .map-container { height: 220px; background: white; border: 1px solid var(--border-light); border-radius: 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); transition: all 0.3s ease; position: relative; overflow: hidden; padding: 0; }
        .map-container iframe { width: 100%; height: 100%; border: 0; filter: grayscale(0.1) contrast(1.1); }
        .map-container:hover { border-color: var(--teal-1); box-shadow: 0 8px 24px rgba(16, 150, 150, 0.1); }
        
        .copyright { margin-top: 60px; padding-top: 28px; border-top: 1px solid var(--border-light); text-align: center; font-size: 0.82rem; color: var(--text-muted); }

        /* ===== SCROLL ANIMATIONS ===== */
        .fade-up { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
        .fade-right { opacity: 0; transform: translateX(40px); transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); }
        .fade-right.visible { opacity: 1; transform: translateX(0); }
        .scale-in { opacity: 0; transform: scale(0.9); transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1); }
        .scale-in.visible { opacity: 1; transform: scale(1); }
        .delay-1 { transition-delay: 0.1s; } .delay-2 { transition-delay: 0.2s; } .delay-3 { transition-delay: 0.3s; } .delay-4 { transition-delay: 0.4s; }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .hero-title { font-size: 2.8rem; letter-spacing: -1px; }
            .hero-section { padding: 120px 0 80px; min-height: auto; }
            .hero-illustration { margin-top: 60px; }
            .section-title { font-size: 2.2rem; }
            .stat-number { font-size: 2.2rem; }
            .navbar-collapse {
                background: white; border-radius: 20px; padding: 24px; margin-top: 12px; border: 1px solid var(--border-light);
                box-shadow: 0 16px 48px rgba(0,0,0,0.08); max-height: 70vh; overflow-y: auto;
            }
            .nav-link::after { display: none; }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1rem; }
            .feature-section { padding: 80px 0; }
            .feature-card { padding: 32px 24px; }
            .section-title { font-size: 1.8rem; }
            .policy-list li { padding: 22px 20px; gap: 16px; }
            .navbar-brand span { font-size: 1.2rem; }
            .navbar-brand img { height: 36px; }
        }
    </style>
</head>
<body>
    <!-- Particles Background -->
    <canvas id="particles-canvas"></canvas>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#beranda">
                <img src="{{ asset('storage/images/logo1.png') }}" alt="Logo Pemkab Bengkalis">
                <span>SIMANDA</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kebijakan">Kebijakan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#persyaratan">Persyaratan & Biaya</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Hubungi Kami</a></li>
                </ul>
                <div class="auth-buttons d-flex align-items-center ms-3">
                    <a href="{{ url('/login') }}" class="btn btn-outline-primary">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Beranda -->
    <section class="hero-section" id="beranda">
        <div class="hero-grid"></div>
        
        <!-- Floating Decorative Shapes -->
        <div class="floating-shape" style="width:8px;height:8px;background:var(--teal-1);top:20%;left:10%;animation-delay:0s;opacity:0.2;"></div>
        <div class="floating-shape" style="width:6px;height:6px;background:var(--teal-2);top:30%;right:15%;animation-delay:-5s;opacity:0.25;"></div>
        <div class="floating-shape" style="width:10px;height:10px;background:var(--blue-1);bottom:25%;left:20%;animation-delay:-10s;opacity:0.15;"></div>
        <div class="floating-shape" style="width:7px;height:7px;background:var(--teal-1);top:60%;right:25%;animation-delay:-15s;opacity:0.2;"></div>
        <div class="floating-shape" style="width:5px;height:5px;background:var(--blue-1);top:15%;left:45%;animation-delay:-8s;opacity:0.3;"></div>
        <div class="floating-shape" style="width:9px;height:9px;background:var(--teal-2);bottom:15%;right:10%;animation-delay:-12s;opacity:0.15;"></div>

        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-badge fade-up">
                        <span class="pulse-dot"></span>
                        SISTEM PELAYANAN DOMAIN DESA (SIMANDA)
                    </div>
                    <h1 class="hero-title fade-up delay-1">
                        Pendaftaran & Perpanjangan <span class="gradient-text">Domain Desa</span> Kabupaten Bengkalis
                    </h1>
                    <p class="hero-subtitle fade-up delay-2">
                        Portal resmi SIMANDA untuk pendaftaran, perpanjangan, dan pengelolaan nama domain khusus untuk desa di wilayah Kabupaten Bengkalis.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration fade-right delay-3">
                        <div class="illustration-glow"></div>
                        <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg" style="position:relative;z-index:1;">
                            <g stroke="url(#lineGrad)" stroke-width="2" fill="none" opacity="0.3">
                                <line x1="250" y1="250" x2="120" y2="140" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="2s" repeatCount="indefinite"/></line>
                                <line x1="250" y1="250" x2="380" y2="130" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="2.5s" repeatCount="indefinite"/></line>
                                <line x1="250" y1="250" x2="100" y2="300" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="1.8s" repeatCount="indefinite"/></line>
                                <line x1="250" y1="250" x2="400" y2="280" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="2.2s" repeatCount="indefinite"/></line>
                                <line x1="250" y1="250" x2="180" y2="400" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="2.8s" repeatCount="indefinite"/></line>
                                <line x1="250" y1="250" x2="340" y2="390" stroke-dasharray="6 4"><animate attributeName="stroke-dashoffset" from="0" to="-20" dur="2.1s" repeatCount="indefinite"/></line>
                                <line x1="120" y1="140" x2="60" y2="80" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.5s" repeatCount="indefinite"/></line>
                                <line x1="120" y1="140" x2="50" y2="190" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.7s" repeatCount="indefinite"/></line>
                                <line x1="380" y1="130" x2="440" y2="70" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.6s" repeatCount="indefinite"/></line>
                                <line x1="380" y1="130" x2="450" y2="180" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.9s" repeatCount="indefinite"/></line>
                                <line x1="100" y1="300" x2="40" y2="350" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="2s" repeatCount="indefinite"/></line>
                                <line x1="400" y1="280" x2="460" y2="340" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.4s" repeatCount="indefinite"/></line>
                                <line x1="180" y1="400" x2="120" y2="450" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="1.8s" repeatCount="indefinite"/></line>
                                <line x1="340" y1="390" x2="400" y2="440" stroke-dasharray="4 4"><animate attributeName="stroke-dashoffset" from="0" to="-16" dur="2.3s" repeatCount="indefinite"/></line>
                            </g>

                            <defs>
                                <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#109696"/><stop offset="100%" stop-color="#1760C5"/></linearGradient>
                                <radialGradient id="nodeGlow1" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#109696" stop-opacity="0.2"/><stop offset="100%" stop-color="#109696" stop-opacity="0"/></radialGradient>
                                <radialGradient id="nodeGlow2" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#1A85A5" stop-opacity="0.2"/><stop offset="100%" stop-color="#1A85A5" stop-opacity="0"/></radialGradient>
                                <radialGradient id="nodeGlow3" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#1760C5" stop-opacity="0.2"/><stop offset="100%" stop-color="#1760C5" stop-opacity="0"/></radialGradient>
                                <filter id="softGlow"><feGaussianBlur stdDeviation="3" result="blur"/><feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
                            </defs>

                            <g filter="url(#softGlow)">
                                <circle cx="60" cy="80" r="6" fill="#109696" opacity="0.6"><animate attributeName="r" values="6;8;6" dur="3s" repeatCount="indefinite"/></circle>
                                <circle cx="50" cy="190" r="5" fill="#1A85A5" opacity="0.5"><animate attributeName="r" values="5;7;5" dur="2.5s" repeatCount="indefinite" begin="0.5s"/></circle>
                                <circle cx="440" cy="70" r="6" fill="#1760C5" opacity="0.6"><animate attributeName="r" values="6;8;6" dur="3.5s" repeatCount="indefinite" begin="1s"/></circle>
                                <circle cx="450" cy="180" r="5" fill="#109696" opacity="0.5"><animate attributeName="r" values="5;7;5" dur="2.8s" repeatCount="indefinite" begin="0.3s"/></circle>
                                <circle cx="40" cy="350" r="5" fill="#1760C5" opacity="0.5"><animate attributeName="r" values="5;7;5" dur="3.2s" repeatCount="indefinite" begin="0.7s"/></circle>
                                <circle cx="460" cy="340" r="6" fill="#1A85A5" opacity="0.6"><animate attributeName="r" values="6;8;6" dur="2.6s" repeatCount="indefinite" begin="1.2s"/></circle>
                                <circle cx="120" cy="450" r="5" fill="#109696" opacity="0.4"><animate attributeName="r" values="5;7;5" dur="3.1s" repeatCount="indefinite" begin="0.9s"/></circle>
                                <circle cx="400" cy="440" r="6" fill="#1760C5" opacity="0.5"><animate attributeName="r" values="6;8;6" dur="2.4s" repeatCount="indefinite" begin="0.4s"/></circle>
                            </g>

                            <g filter="url(#softGlow)">
                                <circle cx="120" cy="140" r="22" fill="url(#nodeGlow1)"/><circle cx="120" cy="140" r="12" fill="#109696" opacity="0.85"><animate attributeName="r" values="12;14;12" dur="4s" repeatCount="indefinite"/></circle>
                                <circle cx="380" cy="130" r="22" fill="url(#nodeGlow3)"/><circle cx="380" cy="130" r="12" fill="#1760C5" opacity="0.85"><animate attributeName="r" values="12;14;12" dur="4.5s" repeatCount="indefinite" begin="1s"/></circle>
                                <circle cx="100" cy="300" r="20" fill="url(#nodeGlow2)"/><circle cx="100" cy="300" r="10" fill="#1A85A5" opacity="0.85"><animate attributeName="r" values="10;12;10" dur="3.8s" repeatCount="indefinite" begin="0.5s"/></circle>
                                <circle cx="400" cy="280" r="20" fill="url(#nodeGlow1)"/><circle cx="400" cy="280" r="10" fill="#109696" opacity="0.85"><animate attributeName="r" values="10;12;10" dur="4.2s" repeatCount="indefinite" begin="1.5s"/></circle>
                                <circle cx="180" cy="400" r="18" fill="url(#nodeGlow3)"/><circle cx="180" cy="400" r="9" fill="#1760C5" opacity="0.8"><animate attributeName="r" values="9;11;9" dur="3.5s" repeatCount="indefinite" begin="0.8s"/></circle>
                                <circle cx="340" cy="390" r="18" fill="url(#nodeGlow2)"/><circle cx="340" cy="390" r="9" fill="#1A85A5" opacity="0.8"><animate attributeName="r" values="9;11;9" dur="3.9s" repeatCount="indefinite" begin="1.2s"/></circle>
                            </g>

                            <circle cx="250" cy="250" r="60" fill="url(#nodeGlow1)"><animate attributeName="r" values="60;70;60" dur="5s" repeatCount="indefinite"/></circle>
                            <circle cx="250" cy="250" r="40" fill="none" stroke="url(#lineGrad)" stroke-width="1.5" opacity="0.3" stroke-dasharray="6 6"><animateTransform attributeName="transform" type="rotate" from="0 250 250" to="360 250 250" dur="20s" repeatCount="indefinite"/></circle>
                            <circle cx="250" cy="250" r="32" fill="none" stroke="url(#lineGrad)" stroke-width="1" opacity="0.2" stroke-dasharray="3 8"><animateTransform attributeName="transform" type="rotate" from="360 250 250" to="0 250 250" dur="15s" repeatCount="indefinite"/></circle>
                            <circle cx="250" cy="250" r="24" fill="#109696" opacity="0.9" filter="url(#softGlow)"><animate attributeName="r" values="24;26;24" dur="3s" repeatCount="indefinite"/></circle>
                            
                            <g transform="translate(250,250)" fill="white" opacity="0.95">
                                <rect x="-9" y="-12" width="18" height="7" rx="2" fill="white"/><rect x="-9" y="-3" width="18" height="7" rx="2" fill="white"/><rect x="-9" y="6" width="18" height="7" rx="2" fill="white"/>
                                <circle cx="5" cy="-8.5" r="1.2" fill="#109696"/><circle cx="5" cy="0.5" r="1.2" fill="#109696"/><circle cx="5" cy="9.5" r="1.2" fill="#109696"/>
                            </g>

                            <circle r="3" fill="#109696" opacity="0.7"><animateMotion dur="2s" repeatCount="indefinite" path="M250,250 L120,140"/><animate attributeName="opacity" values="0;0.7;0" dur="2s" repeatCount="indefinite"/></circle>
                            <circle r="3" fill="#1760C5" opacity="0.7"><animateMotion dur="2.5s" repeatCount="indefinite" path="M250,250 L380,130" begin="0.5s"/><animate attributeName="opacity" values="0;0.7;0" dur="2.5s" repeatCount="indefinite" begin="0.5s"/></circle>
                            <circle r="2.5" fill="#1A85A5" opacity="0.6"><animateMotion dur="1.8s" repeatCount="indefinite" path="M250,250 L100,300" begin="1s"/><animate attributeName="opacity" values="0;0.6;0" dur="1.8s" repeatCount="indefinite" begin="1s"/></circle>
                            <circle r="2.5" fill="#109696" opacity="0.6"><animateMotion dur="2.2s" repeatCount="indefinite" path="M250,250 L400,280" begin="0.3s"/><animate attributeName="opacity" values="0;0.6;0" dur="2.2s" repeatCount="indefinite" begin="0.3s"/></circle>
                            <circle r="2" fill="#1760C5" opacity="0.5"><animateMotion dur="2.8s" repeatCount="indefinite" path="M250,250 L180,400" begin="0.7s"/><animate attributeName="opacity" values="0;0.5;0" dur="2.8s" repeatCount="indefinite" begin="0.7s"/></circle>
                            <circle r="2" fill="#1A85A5" opacity="0.5"><animateMotion dur="2.1s" repeatCount="indefinite" path="M250,250 L340,390" begin="1.2s"/><animate attributeName="opacity" values="0;0.5;0" dur="2.1s" repeatCount="indefinite" begin="1.2s"/></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Tentang Section -->
    <section class="feature-section" id="tentang">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label fade-up"><span class="line"></span>TENTANG KAMI<span class="line"></span></div>
                <h2 class="section-title fade-up delay-1">SIMANDA Kabupaten Bengkalis</h2>
                <p class="section-desc fade-up delay-2">SIMANDA (Sistem Pelayanan Domain Desa) adalah portal resmi yang dikelola oleh Pemerintah Kabupaten Bengkalis khusus untuk pendaftaran dan perpanjangan nama domain desa. Kami berkomitmen mendukung transformasi digital desa melalui layanan domain yang aman, cepat, dan terintegrasi.</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-1">
                        <div class="feature-icon"><i class="fas fa-shield-halved"></i></div>
                        <h3 class="feature-title">KEAMANAN TERPERCAYA</h3>
                        <p class="feature-subtitle">Perlindungan Data Terjamin</p>
                        <p>Sistem keamanan berlapis dengan enkripsi tingkat tinggi untuk melindungi data dan domain desa dari ancaman cyber.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-2">
                        <div class="feature-icon"><i class="fas fa-handshake-angle"></i></div>
                        <h3 class="feature-title">DUKUNGAN RESMI</h3>
                        <p class="feature-subtitle">Tim Profesional Berpengalaman</p>
                        <p>Didukung oleh tim teknis Diskominfotik yang berpengalaman siap membantu proses pengelolaan domain desa.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-3">
                        <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                        <h3 class="feature-title">PROSES CEPAT</h3>
                        <p class="feature-subtitle">Pendaftaran Real-Time</p>
                        <p>Pendaftaran dan aktivasi domain desa dilakukan secara real-time sehingga dapat segera digunakan oleh pemerintah desa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="feature-section" id="layanan" style="background: var(--bg-soft);">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label fade-up"><span class="line"></span>LAYANAN KAMI<span class="line"></span></div>
                <h2 class="section-title fade-up delay-1">Layanan Unggulan</h2>
                <p class="section-desc fade-up delay-2">Berbagai layanan terintegrasi untuk mendukung kebutuhan domain dan kehadiran digital desa secara menyeluruh.</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-1">
                        <div class="feature-icon"><i class="fas fa-globe"></i></div>
                        <h3 class="feature-title">PENDAFTARAN DOMAIN</h3>
                        <p class="feature-subtitle">Daftar Domain Baru</p>
                        <p>Layanan pendaftaran domain desa baru dengan ekstensi resmi sesuai kebutuhan pemerintah desa di Kabupaten Bengkalis.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-2">
                        <div class="feature-icon"><i class="fas fa-arrows-rotate"></i></div>
                        <h3 class="feature-title">PERPANJANGAN DOMAIN</h3>
                        <p class="feature-subtitle">Perpanjang Masa Aktif</p>
                        <p>Perpanjang masa aktif domain desa dengan mudah melalui sistem terintegrasi sebelum masa aktif berakhir.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card scale-in delay-3">
                        <div class="feature-icon"><i class="fas fa-file-invoice"></i></div>
                        <h3 class="feature-title">FAKTUR & PEMBAYARAN</h3>
                        <p class="feature-subtitle">Manajemen Tagihan</p>
                        <p>Sistem faktur dan pembayaran terintegrasi yang memudahkan desa dalam mengelola seluruh transaksi domain.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kebijakan Section -->
    <section class="feature-section" id="kebijakan">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label fade-up"><span class="line"></span>KEBIJAKAN<span class="line"></span></div>
                <h2 class="section-title fade-up delay-1">Dokumen Kebijakan</h2>
                <p class="section-desc fade-up delay-2">Regulasi dan kebijakan resmi yang mengatur pengelolaan nama domain instansi pemerintah dan desa.</p>
            </div>
            
            <div class="row g-5 justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="policy-doc-card scale-in delay-1">
                        <div>
                            <div class="policy-doc-icon bg-red"><i class="fas fa-file-pdf"></i></div>
                            <p class="policy-doc-title">PERATURAN MENTERI KOMDIGI NOMOR 5 TAHUN 2025 TENTANG PSE LINGKUP PUBLIK</p>
                        </div>
                        <a href="https://domain.go.id/file-landing/PM-Komdigi-No-5-Tahun-2025.pdf" class="btn-policy"><i class="fas fa-download me-2"></i>Lihat Dokumen</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="policy-doc-card scale-in delay-2">
                        <div>
                            <div class="policy-doc-icon bg-green"><i class="fas fa-file-pdf"></i></div>
                            <p class="policy-doc-title">KEPUTUSAN DIREKTUR APLIKASI PEMERINTAH DIGITAL NO. 25 / 2025 TENTANG STANDAR PELAYANAN NAMA DOMAIN INSTANSI PEMERINTAH</p>
                        </div>
                        <a href="https://domain.go.id/file-landing/Standar%20Pelayanan%20Nama%20Domain%20Pemerintah%202025.pdf" class="btn-policy"><i class="fas fa-download me-2"></i>Lihat Dokumen</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="policy-doc-card scale-in delay-3">
                        <div>
                            <div class="policy-doc-icon bg-blue"><i class="fas fa-file-pdf"></i></div>
                            <p class="policy-doc-title">PEMBERITAHUAN PENGELOLAAN NAMA DOMAIN INSTANSI DARI DIREKTUR APLIKASI PEMERINTAH DIGITAL PER 22 APRIL 2025</p>
                        </div>
                        <a href="https://domain.go.id/file-landing/Surat%20Pemberitahuan%20Pengelolaan%20Nama%20Domain%20Instansi.pdf" class="btn-policy"><i class="fas fa-download me-2"></i>Lihat Dokumen</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Persyaratan & Biaya Section -->
    <section class="feature-section" id="persyaratan" style="background: var(--bg-soft);">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label fade-up"><span class="line"></span>PERSYARATAN & BIAYA<span class="line"></span></div>
                <h2 class="section-title fade-up delay-1">Ketentuan & Biaya Layanan</h2>
                <p class="section-desc fade-up delay-2">Informasi mengenai persyaratan pendaftaran dan biaya pengelolaan nama domain.</p>
            </div>

            <ul class="policy-list">
                <li class="fade-up">
                    <div class="policy-icon"><i class="fas fa-laptop-file"></i></div>
                    <div><p>Seluruh proses pendaftaran, perpanjangan, dan pengelolaan nama domain (termasuk upload dokumen pendukung) dilakukan melalui website ini. Silakan mendaftarkan user atau mengakses aplikasi melalui menu <a href="{{ url('/login') }}" style="color: var(--teal-1); font-weight: 700;">&gt;&gt;Login&lt;&lt;</a>.</p></div>
                </li>
                <li class="fade-up delay-1">
                    <div class="policy-icon"><i class="fas fa-text-width"></i></div>
                    <div><p>Nama domain terdiri dari minimal 3 (tiga) karakter dan maksimal 63 (enam puluh tiga) karakter (huruf, angka, tanda minus/penghubung).</p></div>
                </li>
                <li class="fade-up delay-2">
                    <div class="policy-icon"><i class="fas fa-copyright"></i></div>
                    <div><p>Nama domain harus sesuai dengan kriteria penamaan serta harus menghormati dan tidak bertentangan dengan HAKI, IPR, Hak Paten/Merk.</p></div>
                </li>
                <li class="fade-up delay-3">
                    <div class="policy-icon"><i class="fas fa-gavel"></i></div>
                    <div><p>Jika dianggap perlu Direktorat Aplikasi Pemerintah Digital Kementerian Komdigi dapat meminta klarifikasi berupa Surat Pernyataan/Keterangan/Penjelasan, vide Pasal 23 ayat (2) UU Nomor 11/2008 tentang ITE.</p></div>
                </li>
                <li class="fade-up delay-4">
                    <div class="policy-icon"><i class="fas fa-money-check-dollar"></i></div>
                    <div><p>Biaya per tahun sebesar Rp 50.000,- (ditambah PPN 11%). Khusus nama domain desa.id dibebaskan untuk tahun pertama.</p></div>
                </li>
            </ul>
        </div>
    </section>

    <!-- Footer / Hubungi Kami -->
    <footer class="footer" id="kontak">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <img src="{{ asset('storage/images/logo1.png') }}" alt="Logo Pemkab Bengkalis">
                        <span>SIMANDA</span>
                    </div>
                    <p>Sistem Pelayanan Domain Desa Kabupaten Bengkalis. Dikelola oleh Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Kabupaten Bengkalis.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i> Kantor Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Kabupaten Bengkalis terletak di Jalan Kartini No. 012, Kode Pos 28712, Bengkalis, Riau.</p>
                        <p><i class="fas fa-phone"></i> +62 123 456 789</p>
                        <p><i class="fas fa-envelope"></i> info@bengkalis.go.id</p>
                        <p><i class="fas fa-clock"></i> Senin - Jumat, 08.00 - 17.00 WIB</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Lokasi Kami</h5>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps?q=Jalan+Kartini+No.+012,+Bengkalis,+Riau&output=embed" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} Diskominfotik Kabupaten Bengkalis. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== PARTICLE SYSTEM (Light Mode) =====
        const canvas = document.getElementById('particles-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        class Particle {
            constructor() { this.reset(); }
            reset() {
                this.x = Math.random() * canvas.width; this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2.5 + 1; this.speedX = (Math.random() - 0.5) * 0.4; this.speedY = (Math.random() - 0.5) * 0.4;
                this.opacity = Math.random() * 0.15 + 0.05;
                const colors = ['16, 150, 150', '26, 133, 165', '23, 96, 197'];
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            update() {
                this.x += this.speedX; this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${this.color}, ${this.opacity})`; ctx.fill();
            }
        }

        for (let i = 0; i < 50; i++) { particles.push(new Particle()); }

        function connectParticles() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x; const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 180) {
                        ctx.beginPath(); ctx.strokeStyle = `rgba(16, 150, 150, ${0.06 * (1 - distance / 180)})`;
                        ctx.lineWidth = 1; ctx.moveTo(particles[i].x, particles[i].y); ctx.lineTo(particles[j].x, particles[j].y); ctx.stroke();
                    }
                }
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            connectParticles();
            requestAnimationFrame(animateParticles);
        }
        animateParticles();

        // ===== NAVBAR SCROLL EFFECT =====
        const mainNav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) { mainNav.classList.add('scrolled'); } 
            else { mainNav.classList.remove('scrolled'); }
        });

        // ===== SCROLL REVEAL ANIMATIONS =====
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up, .fade-right, .scale-in').forEach(el => {
            observer.observe(el);
        });

        // ===== COUNTER ANIMATION =====
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseFloat(counter.getAttribute('data-target'));
                    const isDecimal = target % 1 !== 0;
                    const duration = 2000;
                    const startTime = performance.now();

                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const easeOut = 1 - Math.pow(1 - progress, 3);
                        const current = easeOut * target;

                        if (isDecimal) {
                            counter.textContent = current.toFixed(1);
                        } else {
                            counter.textContent = Math.floor(current);
                        }

                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            if (isDecimal) {
                                counter.textContent = target.toFixed(1);
                            } else {
                                counter.textContent = target;
                            }
                        }
                    }
                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.stat-number').forEach(el => {
            counterObserver.observe(el);
        });

        // ===== SMOOTH SCROLL FOR NAV LINKS =====
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                    // Close mobile menu if open
                    const navbarCollapse = document.getElementById('navbarNav');
                    if (navbarCollapse.classList.contains('show')) {
                        new bootstrap.Collapse(navbarCollapse).hide();
                    }
                }
            });
        });
    </script>
</body>
</html>