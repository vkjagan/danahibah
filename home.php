<?php
/**
 * DanaHibah™ - Official English Landing Page (Elite Edition with Flawless Continuous Curve & Custom Logo)
 * Secure. Transparent. Amanah.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DanaHibah™ helps mosques and surau manage donation collections more transparently, securely and easily through a combination of smart hardware and an integrated digital platform.">
    <meta name="keywords" content="DanaHibah, digital governance, mosque donation, surau, secure donation kiosk, Malaysia, elite mosque design">
    <title>DanaHibah™ — Trusted Digital Governance Infrastructure for Mosques & Surau</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Custom Elite Landing Page Styles -->
    <style>
        :root {
            --primary: #1A3C34;
            --primary-dark: #122B25;
            --primary-light: #2A5244;
            --gold: #C9A84C;
            --gold-light: #E8C96C;
            --gold-dark: #A8873A;
            --bg-light: #F8FAFB;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --font-sans: 'Outfit', sans-serif;
            --font-display: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-sans);
            color: var(--text-main);
            background-color: var(--bg-light);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Golden Divider Lines */
        .gold-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 0;
            opacity: 0.7;
        }
        .gold-line-thick {
            height: 4px;
            background: linear-gradient(90deg, var(--gold-dark), var(--gold-light), var(--gold-dark));
        }

        /* Navbar */
        .navbar-glass {
            background: rgba(18, 43, 37, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(201, 168, 76, 0.3);
            transition: var(--transition);
        }
        .navbar-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.85rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .navbar-brand .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary-dark);
            font-size: 1.6rem;
            box-shadow: 0 4px 15px rgba(201, 168, 76, 0.4);
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 1.2rem !important;
            transition: var(--transition);
        }
        .nav-link:hover, .nav-link.active {
            color: var(--gold-light) !important;
            transform: translateY(-1px);
        }
        .lang-switch {
            border: 1px solid rgba(201, 168, 76, 0.4);
            border-radius: 30px;
            padding: 4px 6px;
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
        }
        .lang-btn {
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            text-decoration: none;
            transition: var(--transition);
        }
        .lang-btn.active {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(201, 168, 76, 0.4);
        }
        .lang-btn:hover:not(.active) {
            color: var(--gold-light);
        }
        .btn-login {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary-dark) !important;
            font-weight: 800;
            padding: 0.65rem 1.8rem;
            border-radius: 30px;
            border: 1px solid var(--gold-light);
            box-shadow: 0 4px 20px rgba(201, 168, 76, 0.4);
            transition: var(--transition);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(201, 168, 76, 0.6);
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
        }

        /* Flawless Continuous Curve Hero Section */
        .hero-section-custom {
            position: relative;
            padding-top: 86px; /* space for navbar */
            min-height: 880px;
            overflow: hidden;
            border-bottom: 4px solid var(--gold);
            background-color: #F8FAFB;
            display: flex;
            align-items: center;
        }

        /* Hero Absolute Background Layers */
        .hero-bg-top {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; z-index: 1;
        }
        .bg-light-left { width: 50%; height: 100%; background-color: #F8FAFB; }
        .bg-mosque-right { width: 50%; height: 100%; position: relative; overflow: hidden; background-color: #0F172A; }
        .bg-mosque-right img { width: 100%; height: 100%; object-fit: cover; object-position: center 25%; }

        /* The Continuous Golden Curve & Dark Green Bottom Fill */
        .hero-curve-container {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 48%; z-index: 2; pointer-events: none;
        }

        /* Typography & Content inside Hero */
        .brand-text-large { font-family: var(--font-display); font-weight: 800; font-size: 3.5rem; color: var(--primary); letter-spacing: -1px; line-height: 1; }
        .hero-subheading { font-family: var(--font-display); font-weight: 700; font-size: 1.4rem; color: var(--gold-dark); margin-bottom: 0.8rem; }
        .hero-title-main { font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 4vw, 3.8rem); color: var(--primary); line-height: 1.15; letter-spacing: -1px; }
        .hero-desc-main { font-size: 1.15rem; color: var(--text-main); max-width: 600px; line-height: 1.6; font-weight: 500; }

        .kenapa-title { font-family: var(--font-display); font-weight: 800; color: var(--gold-light); font-size: 1.4rem; letter-spacing: 1px; margin-bottom: 1.8rem; }
        .kenapa-icon { width: 50px; height: 50px; background: rgba(201,168,76,0.15); color: var(--gold-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border: 1px solid rgba(201,168,76,0.3); flex-shrink: 0; }

        .malaysian-badge { background: rgba(255,255,255,0.92); backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid rgba(201,168,76,0.4); box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 14px 24px; z-index: 4; }

        /* Device Showcase */
        .devices-showcase-container { z-index: 3; padding-bottom: 10px; }
        .device-tall { width: 250px; height: 520px; background: linear-gradient(145deg, #2A3342, #0F172A); border-radius: 28px; padding: 22px; border: 4px solid #475569; display: flex; flex-direction: column; align-items: center; justify-content: space-between; box-shadow: 0 30px 70px rgba(0,0,0,0.8); position: relative; }
        .device-tall-header { font-family: var(--font-display); font-weight: 800; color: #ffffff; font-size: 1.2rem; letter-spacing: 1px; margin-bottom: 12px; }
        .device-led-green { width: 40px; height: 4px; background: #10B981; border-radius: 2px; box-shadow: 0 0 12px #10B981; margin-bottom: 15px; }
        .device-tall-screen { width: 100%; background: #0F172A; border: 2px solid #334155; border-radius: 14px; padding: 24px 16px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        .device-tall-slot { width: 80px; height: 8px; background: #000; border-radius: 4px; border-bottom: 2px solid #64748B; margin-top: 18px; }

        .device-small { width: 180px; height: 330px; background: #0F172A; border-radius: 20px; padding: 16px; border: 3px solid #334155; display: flex; flex-direction: column; align-items: center; box-shadow: 0 25px 60px rgba(0,0,0,0.8); position: relative; margin-bottom: 12px; }
        .device-small-printer { width: 120px; height: 12px; background: #1E293B; border-radius: 6px 6px 0 0; border: 1px solid #475569; margin-bottom: 16px; }
        .device-small-screen { width: 100%; background: #ffffff; border-radius: 12px; padding: 16px 10px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); }

        .gold-circle-badge { width: 140px; height: 140px; background: linear-gradient(135deg, var(--gold-light), var(--gold), var(--gold-dark)); border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; border: 4px solid #ffffff; box-shadow: 0 20px 45px rgba(0,0,0,0.6); position: absolute; right: 20px; bottom: 80px; z-index: 4; animation: pulseGold 3s infinite; padding: 10px; }
        @keyframes pulseGold { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); box-shadow: 0 25px 50px rgba(201,168,76,0.5); } }

        /* Section Styling */
        .section-padding { padding: 110px 0; }
        .section-title {
            font-size: clamp(2.2rem, 3.8vw, 3rem);
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 80px; height: 4px;
            background: var(--gold);
            border-radius: 2px;
        }
        .section-subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 700px;
            margin: 1.5rem auto 4rem;
        }

        /* Section Pattern Overlay */
        .section-pattern {
            background: linear-gradient(135deg, rgba(248, 250, 251, 0.96), rgba(255, 255, 255, 0.96)), url('assets/images/golden_islamic_pattern.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Elite Why DanaHibah Cards */
        .elite-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(201, 168, 76, 0.4);
            border-radius: 24px;
            padding: 40px 32px;
            height: 100%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05), inset 0 2px 4px rgba(255,255,255,0.8);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .elite-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--gold), var(--primary));
            opacity: 0;
            transition: var(--transition);
        }
        .elite-card:hover {
            transform: translateY(-8px);
            border-color: var(--gold);
            box-shadow: 0 25px 50px rgba(201,168,76,0.15);
        }
        .elite-card:hover::before { opacity: 1; }
        .why-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, rgba(201,168,76,0.2), rgba(201,168,76,0.1));
            color: var(--gold-dark);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin-bottom: 24px;
            border: 1px solid rgba(201,168,76,0.3);
            transition: var(--transition);
        }
        .elite-card:hover .why-icon {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--primary-dark);
            transform: scale(1.1);
            border-color: var(--gold-light);
        }
        .elite-card h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; color: var(--primary); }
        .elite-card p { color: var(--text-muted); margin: 0; line-height: 1.7; font-size: 0.98rem; }

        /* How It Works Steps */
        .steps-flex-container {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 15px;
        }
        .step-card-wrapper {
            flex: 1 1 0;
            min-width: 0;
        }
        .step-arrow-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--gold);
            padding: 0 5px;
            flex-shrink: 0;
        }
        .step-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 35px 20px;
            text-align: center;
            border: 1px solid rgba(201,168,76,0.3);
            position: relative;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            transition: var(--transition);
        }
        .step-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(201,168,76,0.12); border-color: var(--gold); }
        .step-number {
            width: 45px; height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--gold-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0 auto 20px;
            box-shadow: 0 4px 15px rgba(26,60,52,0.4);
            border: 2px solid var(--gold);
        }
        .step-icon {
            font-size: 2.8rem;
            color: var(--gold);
            margin-bottom: 20px;
        }
        .step-card h4 { font-size: 1.15rem; font-weight: 800; color: var(--primary); margin-bottom: 12px; }
        .step-card p { font-size: 0.9rem; color: var(--text-muted); margin: 0; line-height: 1.5; }

        /* Deep Dive Showcase */
        .deep-dive-box {
            background: #ffffff;
            border-radius: 35px;
            overflow: hidden;
            border: 1px solid rgba(201,168,76,0.4);
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            transition: var(--transition);
        }
        .deep-dive-box:hover { box-shadow: 0 30px 70px rgba(201,168,76,0.15); border-color: var(--gold); }
        .deep-dive-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            padding: 45px 40px;
            position: relative;
            border-bottom: 3px solid var(--gold);
        }
        .deep-dive-header h3 { font-size: 2.2rem; font-weight: 800; margin: 0; color: #ffffff; }
        .deep-dive-header p { color: var(--gold-light); font-size: 1.15rem; margin: 8px 0 0; font-weight: 600; }
        .feature-list-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 28px 32px;
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }
        .feature-list-item:hover { background: rgba(201,168,76,0.05); }
        .feature-list-item:last-child { border-bottom: none; }
        .feature-list-icon {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, rgba(201,168,76,0.2), rgba(201,168,76,0.1));
            color: var(--primary);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(201,168,76,0.4);
        }
        .feature-list-content h5 { font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 6px; }
        .feature-list-content p { color: var(--text-muted); font-size: 1rem; margin: 0; line-height: 1.6; }

        /* Benefits Accordion Override */
        .accordion-item { border: 1px solid rgba(201,168,76,0.3); border-radius: 20px !important; overflow: hidden; margin-bottom: 20px; box-shadow: 0 6px 20px rgba(0,0,0,0.03); }
        .accordion-button { font-family: var(--font-display); font-weight: 800; font-size: 1.25rem; color: var(--primary); padding: 22px 28px; background: #ffffff; }
        .accordion-button:not(.collapsed) { background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05)); color: var(--primary-dark); box-shadow: none; border-bottom: 2px solid var(--gold); }
        .accordion-button:focus { box-shadow: none; }
        .accordion-body { padding: 28px; background: #ffffff; color: var(--text-muted); line-height: 1.8; font-size: 1.02rem; }

        /* Built for Malaysia Grid */
        .pillar-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 38px 26px;
            text-align: center;
            border: 1px solid rgba(201,168,76,0.3);
            box-shadow: 0 10px 35px rgba(0,0,0,0.03);
            height: 100%;
            transition: var(--transition);
        }
        .pillar-card:hover { transform: translateY(-6px); border-color: var(--gold); box-shadow: 0 20px 45px rgba(201,168,76,0.15); }
        .pillar-icon { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; }
        .pillar-card h5 { font-size: 1.25rem; font-weight: 800; color: var(--primary); margin-bottom: 10px; }
        .pillar-card p { font-size: 0.95rem; color: var(--text-muted); margin: 0; }

        /* CTA Section with Golden Pattern Overlay */
        .cta-section {
            background: linear-gradient(135deg, rgba(26, 60, 52, 0.95) 0%, rgba(18, 43, 37, 0.98) 100%), url('assets/images/golden_islamic_pattern.png');
            background-size: cover;
            background-position: center;
            border-radius: 35px;
            padding: 80px 70px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(26,60,52,0.5);
            margin: 80px 0 100px;
            border: 2px solid var(--gold);
        }
        .cta-section::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201, 168, 76, 0.3) 0%, transparent 70%);
            bottom: -150px; right: -150px;
            border-radius: 50%;
        }

        /* Footer */
        .footer {
            background: #ffffff;
            border-top: 2px solid var(--gold);
            padding: 70px 0 50px;
            color: var(--text-muted);
            font-size: 1rem;
        }
        .footer-brand { font-family: var(--font-display); font-weight: 800; font-size: 1.8rem; color: var(--primary); margin-bottom: 18px; display: inline-block; }
        .footer-title { font-family: var(--font-display); font-weight: 800; color: var(--primary); font-size: 1.2rem; margin-bottom: 24px; position: relative; padding-bottom: 10px; }
        .footer-title::after { content:''; position:absolute; bottom:0; left:0; width:40px; height:3px; background:var(--gold); border-radius:2px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 14px; }
        .footer-links a { color: var(--text-muted); text-decoration: none; transition: var(--transition); font-weight: 500; }
        .footer-links a:hover { color: var(--gold-dark); padding-left: 5px; }
        .footer-contact-item { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; color: var(--text-main); font-weight: 600; }
        .footer-contact-icon { width: 42px; height: 42px; background: linear-gradient(135deg, rgba(201,168,76,0.2), rgba(201,168,76,0.1)); color: var(--gold-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid rgba(201,168,76,0.4); }

        @media (max-width: 991.98px) {
            .hero-bg-top { flex-direction: column; }
            .bg-light-left { width: 100% !important; height: 50% !important; }
            .bg-mosque-right { width: 100% !important; height: 50% !important; }
            .hero-curve-container { height: 60% !important; }
            .gold-circle-badge { position: relative !important; right: auto !important; bottom: auto !important; margin: 20px auto 0; }
            .devices-showcase-container { flex-direction: column; align-items: center !important; }
            .section-padding { padding: 80px 0; }
            .cta-section { padding: 50px 35px; }
            .steps-flex-container { flex-direction: column; align-items: center; gap: 20px; }
            .step-arrow-wrapper { transform: rotate(90deg); padding: 15px 0; }
            .step-card-wrapper { width: 100%; max-width: 380px; }
        }
    </style>
</head>
<body>

    <!-- Sticky Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top py-3">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="assets/images/logo.png" alt="DanaHibah Logo" style="width: 54px; height: 54px; object-fit: contain;">
                <span>DanaHibah™</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#why">Why DanaHibah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#solutions">Our Solution</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features & Benefits</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <!-- Language Switcher -->
                    <div class="lang-switch">
                        <a href="home.php" class="lang-btn active">EN</a>
                        <a href="home_ms.php" class="lang-btn">BM</a>
                    </div>
                    <!-- Login Button Top Right -->
                    <a href="login.php" class="btn btn-login">
                        <i class="bi bi-person-circle me-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flawless Continuous Curve Hero Section -->
    <section class="hero-section-custom" id="hero">
        <!-- Layer 1: Absolute Top Background (Left White, Right Mosque) -->
        <div class="hero-bg-top">
            <div class="bg-light-left"></div>
            <div class="bg-mosque-right">
                <img src="assets/images/elite_mosque_hero.png" alt="Mosque Masterpiece">
            </div>
        </div>

        <!-- Layer 2: Absolute Flawless Continuous Sweeping Golden Arch & Dark Green Bottom Fill (Height adjusted to 48% to ensure mosque is fully visible and buttons never overlap) -->
        <div class="hero-curve-container">
            <svg viewBox="0 0 1440 400" preserveAspectRatio="none" class="w-100 h-100 position-absolute bottom-0 start-0">
                <!-- Dark Green Fill below curve -->
                <path d="M0,80 C450,180 900,30 1440,120 L1440,400 L0,400 Z" fill="#122B25"></path>
                <!-- Golden Thick Stroke along the curve -->
                <path d="M0,80 C450,180 900,30 1440,120" fill="none" stroke="#C9A84C" stroke-width="12"></path>
            </svg>
        </div>

        <!-- Layer 3: Foreground Content Grid -->
        <div class="container position-relative z-3 py-5">
            <div class="row g-0 align-items-stretch" style="min-height: 780px;">
                <!-- Left Column: Content & Why DanaHibah -->
                <div class="col-lg-6 d-flex flex-column justify-content-between pe-lg-5 py-4">
                    <div class="hero-content-top mb-5 pe-lg-4 my-auto">
                        <!-- Custom DanaHibah Mosque Logo & Brand -->
                        <div class="d-flex align-items-end gap-3 mb-3">
                            <img src="assets/images/logo.png" alt="DanaHibah Elite Logo" style="width: 105px; height: 105px; object-fit: contain;">
                            <span class="brand-text-large" style="line-height: 0.85;">DanaHibah™</span>
                        </div>
                        <h4 class="hero-subheading">Governance & Donation System for Mosques & Surau</h4>
                        <h1 class="hero-title-main mb-4">MODERN. SECURE. AMANAH.</h1>
                        <p class="hero-desc-main mb-5">Empowering mosques with transparent, tamper-proof smart hardware and an integrated cloud governance platform.</p>
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <a href="#how" class="btn btn-login py-3 px-5 fs-5">Get Started Now</a>
                            <a href="#contact" class="btn btn-outline-dark py-3 px-4 fs-5" style="border-radius:30px;font-weight:700;border-width:2px;">Contact Support</a>
                        </div>
                    </div>
                    
                    <!-- Bottom Left Dark Green Box Content (perfectly positioned over the SVG dark green fill) -->
                    <div class="text-white pt-5 pe-lg-4 mt-auto">
                        <h3 class="kenapa-title">WHY DANAHIBAH?</h3>
                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-4">
                                    <div class="kenapa-icon"><i class="bi bi-shield-check"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-gold">SECURE</h5>
                                        <p class="mb-0 fs-7 text-light">Tamper-proof hardware with real-time monitoring.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-4">
                                    <div class="kenapa-icon"><i class="bi bi-eye"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-gold">TRANSPARENT</h5>
                                        <p class="mb-0 fs-7 text-light">Clear collection reports accessible anytime, anywhere.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-4">
                                    <div class="kenapa-icon"><i class="bi bi-people"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-gold">ACCOUNTABLE</h5>
                                        <p class="mb-0 fs-7 text-light">Complete audit trails and role-based access control.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-4">
                                    <div class="kenapa-icon"><i class="bi bi-shield-lock"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-gold">TRUSTED</h5>
                                        <p class="mb-0 fs-7 text-light">Designed for public trust and community confidence.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Devices Showcase & Badges -->
                <div class="col-lg-6 position-relative d-flex flex-column justify-content-between ps-lg-5 py-4">
                    <!-- Top Right Malaysian Innovation Badge -->
                    <div class="malaysian-badge align-self-end mb-5">
                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                            <i class="bi bi-flag-fill text-danger fs-3"></i>
                            <span class="fw-bold text-dark fs-5" style="line-height:1.1;">MALAYSIAN<br>INNOVATION</span>
                        </div>
                        <div class="text-muted fw-bold fs-7 tracking-wider mt-1" style="letter-spacing:1px;text-align:right;">BUILT FOR AMANAH</div>
                    </div>

                    <!-- The Two Hardware Devices Showcase -->
                    <div class="devices-showcase-container d-flex align-items-end justify-content-center gap-4 mt-auto w-100 pb-2">
                        <!-- Tall Kiosk -->
                        <div class="device-tall shadow-lg">
                            <div class="device-tall-header">DanaHibah™</div>
                            <div class="device-led-green"></div>
                            <div class="device-tall-screen">
                                <p class="mb-3 text-white fw-bold fs-6">Thank You<br>for your<br>donation</p>
                                <p class="text-gold fs-7 mb-4">Thank you for your wakaf</p>
                                <div class="bg-white p-2 rounded-3 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:110px;height:110px;">
                                    <i class="bi bi-qr-code text-dark" style="font-size:5.5rem;line-height:1;"></i>
                                </div>
                            </div>
                            <div class="device-tall-slot"></div>
                        </div>

                        <!-- Small Countertop Terminal -->
                        <div class="device-small shadow-lg">
                            <div class="device-small-printer"></div>
                            <div class="device-small-screen">
                                <div class="d-flex align-items-center justify-content-center gap-1 mb-3 text-primary fw-bold fs-7">
                                    <i class="bi bi-shield-check text-gold"></i> DanaHibah™
                                </div>
                                <div class="bg-light text-primary fw-bold py-2 rounded-3 mb-3 fs-4 border border-gold">RM 50.00</div>
                                <p class="fs-8 text-muted mb-0" style="font-size:0.75rem;">Thank you for your donation</p>
                            </div>
                            <div class="text-center mt-3 text-white fw-bold fs-7">
                                <i class="bi bi-moon-stars-fill text-gold me-1"></i> DanaHibah™
                            </div>
                        </div>

                        <!-- Golden Circular Badge -->
                        <div class="gold-circle-badge shadow-lg">
                            <i class="bi bi-people-fill fs-1 text-primary-dark mb-1"></i>
                            <span class="fw-bold text-primary-dark" style="font-size:0.75rem;line-height:1.1;">AMANAH<br>TRANSPARENCY<br>TECHNOLOGY</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Thick Golden Line Divider -->
    <div class="gold-line-thick"></div>

    <!-- Why DanaHibah Section (Elite Pattern Overlay) -->
    <section class="section-padding section-pattern" id="why">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Why DanaHibah?</h2>
                <p class="section-subtitle">Engineered to elevate public trust, streamline committee operations, and secure every contribution from kiosk to cloud.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="elite-card">
                        <div class="why-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <h3>Secure</h3>
                        <p>Tamper-proof hardware equipped with real-time monitoring and instant security alerts.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="elite-card">
                        <div class="why-icon"><i class="bi bi-eye-fill"></i></div>
                        <h3>Transparent</h3>
                        <p>Live reporting and clear collection visibility accessible anytime, anywhere.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="elite-card">
                        <div class="why-icon"><i class="bi bi-file-earmark-check-fill"></i></div>
                        <h3>Accountable</h3>
                        <p>Comprehensive audit trails, multi-level approvals, and strictly role-based access.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="elite-card">
                        <div class="why-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                        <h3>Trusted</h3>
                        <p>Built for amanah, designed specifically for the community and religious institutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gold-line"></div>

    <!-- How It Works Section -->
    <section class="section-padding bg-white" id="how">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">How DanaHibah™ Works</h2>
                <p class="section-subtitle">A seamless, fully automated donation and governance lifecycle.</p>
            </div>
            <div class="steps-flex-container">
                <div class="step-card-wrapper">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <div class="step-icon"><i class="bi bi-person-heart"></i></div>
                        <h4>Donor</h4>
                        <p>Donor makes cash donation or QR payment.</p>
                    </div>
                </div>
                <div class="step-arrow-wrapper">
                    <i class="bi bi-arrow-right"></i>
                </div>
                <div class="step-card-wrapper">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <div class="step-icon"><i class="bi bi-terminal"></i></div>
                        <h4>Smart Hardware</h4>
                        <p>DanaHibah device records the donation and issues receipt.</p>
                    </div>
                </div>
                <div class="step-arrow-wrapper">
                    <i class="bi bi-arrow-right"></i>
                </div>
                <div class="step-card-wrapper">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <div class="step-icon"><i class="bi bi-wifi"></i></div>
                        <h4>Secure Connection</h4>
                        <p>Data is encrypted and sent securely to the cloud.</p>
                    </div>
                </div>
                <div class="step-arrow-wrapper">
                    <i class="bi bi-arrow-right"></i>
                </div>
                <div class="step-card-wrapper">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <div class="step-icon"><i class="bi bi-cloud-check"></i></div>
                        <h4>DanaHibah Cloud™</h4>
                        <p>Data is stored and analysed in real-time on the cloud.</p>
                    </div>
                </div>
                <div class="step-arrow-wrapper">
                    <i class="bi bi-arrow-right"></i>
                </div>
                <div class="step-card-wrapper">
                    <div class="step-card">
                        <div class="step-number">5</div>
                        <div class="step-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <h4>Management & Report</h4>
                        <p>Committee views live dashboard and downloads reports.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gold-line"></div>

    <!-- Our Solution Section (Deep Dive) -->
    <section class="section-padding section-pattern" id="solutions">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Solution in One System</h2>
                <p class="section-subtitle">Combining state-of-the-art IoT donation hardware with an enterprise-grade cloud governance platform.</p>
            </div>
            <div class="row g-5">
                <!-- Hardware Box -->
                <div class="col-lg-6">
                    <div class="deep-dive-box h-100">
                        <div class="deep-dive-header">
                            <h3>Smart Donation Hardware</h3>
                            <p>Engineered for security, reliability, and real-time connectivity</p>
                        </div>
                        <div class="feature-list">
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-cash-stack"></i></div>
                                <div class="feature-list-content">
                                    <h5>Cash Acceptor & Counter</h5>
                                    <p>Accurate counting with built-in advanced counterfeit detection.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-qr-code"></i></div>
                                <div class="feature-list-content">
                                    <h5>QR Code Payment</h5>
                                    <p>Supports all major e-wallets, banking apps, and DuitNow QR.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-broadcast"></i></div>
                                <div class="feature-list-content">
                                    <h5>Real-Time Connectivity</h5>
                                    <p>4G/5G IoT connectivity for instant data transmission to the cloud.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-exclamation-triangle"></i></div>
                                <div class="feature-list-content">
                                    <h5>Tamper Alert & CCTV Ready</h5>
                                    <p>Instant alert for unauthorized access or tampering, with CCTV integration support.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cloud Box -->
                <div class="col-lg-6">
                    <div class="deep-dive-box h-100">
                        <div class="deep-dive-header" style="background:var(--primary-dark);">
                            <h3>DanaHibah Cloud™</h3>
                            <p>Centralised platform for full visibility, analytics, and control</p>
                        </div>
                        <div class="feature-list">
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-speedometer2"></i></div>
                                <div class="feature-list-content">
                                    <h5>Live Dashboard</h5>
                                    <p>Real-time collection tracking and monitoring at a glance.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-journal-check"></i></div>
                                <div class="feature-list-content">
                                    <h5>Audit Trail & Analytics</h5>
                                    <p>Complete activity logs and daily, weekly, monthly report trends.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-diagram-3"></i></div>
                                <div class="feature-list-content">
                                    <h5>Multi-Branch Management</h5>
                                    <p>Manage multiple mosque and surau locations seamlessly in one platform.</p>
                                </div>
                            </div>
                            <div class="feature-list-item">
                                <div class="feature-list-icon"><i class="bi bi-cpu"></i></div>
                                <div class="feature-list-content">
                                    <h5>AI Anomaly Detection</h5>
                                    <p>Smart automated alerts for unusual collection activities and irregularities.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gold-line"></div>

    <!-- Features & Benefits Section -->
    <section class="section-padding bg-white" id="features">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Key Features</h2>
                    <div class="row g-4 mt-2">
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-4 bg-light h-100 shadow-sm" style="border-color:rgba(201,168,76,0.3)!important;">
                                <i class="bi bi-qr-code-scan text-gold fs-2 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-2 fs-5">QR & Cash Collection</h5>
                                <p class="text-muted fs-7 mb-0">Accepts QR payments and cash donations securely.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-4 bg-light h-100 shadow-sm" style="border-color:rgba(201,168,76,0.3)!important;">
                                <i class="bi bi-display text-gold fs-2 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-2 fs-5">Real-Time Monitoring</h5>
                                <p class="text-muted fs-7 mb-0">View collections live from any device or browser.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-4 bg-light h-100 shadow-sm" style="border-color:rgba(201,168,76,0.3)!important;">
                                <i class="bi bi-file-bar-graph text-gold fs-2 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-2 fs-5">Automated Reports</h5>
                                <p class="text-muted fs-7 mb-0">Daily, weekly, monthly reports generated in one click.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-4 bg-light h-100 shadow-sm" style="border-color:rgba(201,168,76,0.3)!important;">
                                <i class="bi bi-shield-check text-gold fs-2 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-2 fs-5">Secure & Reliable</h5>
                                <p class="text-muted fs-7 mb-0">Enterprise-grade security and robust data protection.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">Benefits For You</h2>
                    <div class="accordion mt-4" id="benefitsAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="bi bi-building me-3 text-gold"></i> For Mosques & Surau
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#benefitsAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0 ps-3">
                                        <li class="mb-2"><strong>Better transparency:</strong> Build unshakeable public trust with clear collection visibility.</li>
                                        <li class="mb-2"><strong>Easier reporting:</strong> Eliminate administrative headaches with instant report generation.</li>
                                        <li class="mb-2"><strong>Reduced manual work:</strong> Automated counting and digital logs minimize human error.</li>
                                        <li><strong>Stronger public trust:</strong> Demonstrating absolute amanah in financial management.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="bi bi-people me-3 text-gold"></i> For Committees
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#benefitsAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0 ps-3">
                                        <li class="mb-2"><strong>Real-time visibility:</strong> Track live incoming donations across all kiosks.</li>
                                        <li class="mb-2"><strong>Secure audit trail:</strong> Every transaction is meticulously recorded and traceable.</li>
                                        <li class="mb-2"><strong>Faster reconciliation:</strong> Seamlessly verify bank deposits against cash collections.</li>
                                        <li><strong>Role-based access:</strong> Multi-tier secure access tailored for distinct administrative roles.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="bi bi-heart me-3 text-gold"></i> For Donors & Community
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#benefitsAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0 ps-3">
                                        <li class="mb-2"><strong>More trust and confidence:</strong> Peace of mind knowing contributions are securely managed.</li>
                                        <li class="mb-2"><strong>Clear accountability:</strong> Instant digital confirmation and transparent fund governance.</li>
                                        <li><strong>Modern donation experience:</strong> Quick, convenient, and intuitive payment options via QR or cash.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gold-line"></div>

    <!-- Built for Mosques & Built for Malaysia Section -->
    <section class="section-padding section-pattern">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Built for Mosques & Surau</h2>
                <p class="section-subtitle">Proudly developed to support local religious institutions and community needs across Malaysia.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-2-5 col-xl-2">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="bi bi-hand-index-thumb"></i></div>
                        <h5>Easy to Use</h5>
                        <p>Simple for anyone to operate.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2-5 col-xl-2">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="bi bi-tag"></i></div>
                        <h5>Affordable</h5>
                        <p>Cost-effective solution.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2-5 col-xl-2">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="bi bi-shield-check"></i></div>
                        <h5>Reliable</h5>
                        <p>Stable hardware & system.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2-5 col-xl-2">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="bi bi-headset"></i></div>
                        <h5>Support</h5>
                        <p>Local support, fast response.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2-5 col-xl-2">
                    <div class="pillar-card">
                        <div class="pillar-icon"><i class="bi bi-flag"></i></div>
                        <h5>Made for Malaysia</h5>
                        <p>Designed for local needs.</p>
                    </div>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="cta-section">
                <div class="row align-items-center justify-content-between g-4 position-relative z-2">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-3 text-white fs-1">Empower Your Mosque with Transparent Digital Governance</h2>
                        <p class="fs-5 text-light mb-0">Join leading mosques and surau across Malaysia in modernising donation collections and safeguarding community trust.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="login.php" class="btn btn-login py-3 px-5 fs-5">Get Started Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Thick Golden Line Divider -->
    <div class="gold-line-thick"></div>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-5">
                    <a href="home.php" class="footer-brand d-flex align-items-center gap-2 mb-3">
                        <img src="assets/images/logo.png" alt="DanaHibah Logo" style="width: 48px; height: 48px; object-fit: contain;">
                        <span>DanaHibah™</span>
                    </a>
                    <p class="mb-4 pe-lg-5">Trusted Digital Governance Infrastructure for Mosques & Surau. Helping religious institutions collect donations securely, manage them transparently, and report with absolute confidence.</p>
                    <div class="d-flex gap-3 fs-5 text-primary">
                        <a href="#" class="text-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#why">Why DanaHibah</a></li>
                        <li><a href="#how">How It Works</a></li>
                        <li><a href="#solutions">Our Solution</a></li>
                        <li><a href="#features">Features & Benefits</a></li>
                        <li><a href="login.php">System Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Contact Us</h5>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-globe"></i></div>
                        <span>www.danahibah.com</span>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-envelope"></i></div>
                        <span>hello@danahibah.com</span>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="bi bi-telephone"></i></div>
                        <span>+60 12-345 6789</span>
                    </div>
                    <div class="mt-4 p-3 bg-light rounded-4 border text-center" style="max-width:220px;border-color:rgba(201,168,76,0.3)!important;">
                        <i class="bi bi-qr-code fs-1 text-dark d-block mb-2"></i>
                        <span class="fs-7 fw-bold text-dark">SCAN TO KNOW MORE ABOUT DANAHIBAH™</span>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary-subtle">
            <div class="d-flex flex-wrap justify-content-between align-items-center fs-7 text-muted">
                <div>&copy; <?= date('Y') ?> DanaHibah™. All rights reserved. Secure · Transparent · Amanah.</div>
                <div class="d-flex gap-4 mt-2 mt-md-0">
                    <a href="#" class="text-muted">Privacy Policy</a>
                    <a href="#" class="text-muted">Terms of Service</a>
                    <a href="#" class="text-muted">Security</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-glass');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(18, 43, 37, 0.98)';
                navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.3)';
            } else {
                navbar.style.background = 'rgba(18, 43, 37, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>
