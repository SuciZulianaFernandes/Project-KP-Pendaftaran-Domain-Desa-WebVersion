<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain - Web Hosting Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #9C272B;
            --dark-red: #9C272B;
            --light-gray: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--primary-red) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-red), var(--dark-red));
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .domain-search {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
        }
        
        .domain-search input {
            border: none;
            border-radius: 4px 0 0 4px;
            padding: 12px 15px;
            font-size: 1rem;
        }
        
        .domain-search button {
            background: #333;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            padding: 12px 25px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .domain-search button:hover {
            background: #222;
        }
        
        .feature-section {
            padding: 80px 0;
            background-color: white;
        }
        
        .feature-card {
            text-align: center;
            padding: 30px;
            border-radius: 8px;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-red);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-subtitle {
            color: #777;
            font-style: italic;
            margin-bottom: 15px;
        }
        
        .footer {
            background-color: var(--dark-red);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: #ddd;
        }
        
        .copyright {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
            text-align: center;
            font-size: 0.9rem;
        }
        
        .map-container {
            height: 200px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
        }
        
        .auth-buttons .btn {
            margin-left: 10px;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .feature-section {
                padding: 60px 0;
            }
            
            .domain-search {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Domain</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Harga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                </ul>
                <div class="auth-buttons d-flex align-items-center ms-3">
                    <span class="me-3"><i class="fas fa-phone"></i> +62 123 456 789</span>
                    <a href="{{ url('/login') }}" class="btn btn-outline-primary">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="display-4 fw-bold mb-4">The best web hosting service for your website.</h1>
                    <p class="lead mb-5">Dapatkan hosting terbaik dengan performa tinggi dan keamanan terjamin</p>
                    
                    <div class="domain-search">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari domain Anda...">
                                    <button class="btn" type="button">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="feature-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="feature-title">SMART DEPLOYMENT</h3>
                        <p class="feature-subtitle">Curabitur egestas consequat</p>
                        <p>Platform kami memungkinkan deployment aplikasi Anda dengan cepat dan mudah. Teknologi terkini untuk memastikan website Anda berjalan optimal.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">EASY INTEGRATIONS</h3>
                        <p class="feature-subtitle">Interdum Et Malesuada</p>
                        <p>Integrasikan website Anda dengan berbagai platform dan layanan pihak ketiga dengan mudah. API yang lengkap untuk pengembangan lebih lanjut.</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">REALTIME MONITORING</h3>
                        <p class="feature-subtitle">Aenean sit amet magna</p>
                        <p>Monitor performa website Anda secara real-time. Dapatkan laporan detail tentang traffic, resource usage, dan performa server.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">Domain</div>
                    <p>Penyedia layanan hosting terpercaya di Indonesia dengan dukungan teknis 24/7.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Hubungi Kami</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Bengkalis, Riau, Indonesia</p>
                    <p><i class="fas fa-phone me-2"></i> +62 123 456 789</p>
                    <p><i class="fas fa-envelope me-2"></i> info@domain.com</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">Lokasi Kami</h5>
                    <div class="map-container">
                        <i class="fas fa-map-marked-alt fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; {{ date('Y') }} Domain Hosting. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>