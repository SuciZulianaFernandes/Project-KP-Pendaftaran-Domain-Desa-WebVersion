<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Domain</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal-1: #109696;
            --teal-2: #1A85A5;
            --blue-1: #1760C5;
            --text-dark: #1E293B;
            --text-body: #475569;
            --text-muted: #94A3B8;
            --border-light: #E2E8F0;
            --bg-soft: #F8FAFC;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-soft);
        }

        .login-container {
            height: 100vh;
            overflow: hidden;
        }

        /* Kolom Kiri - Gradient Teal-Blue */
        .left-section {
            background: linear-gradient(135deg, var(--teal-1) 0%, var(--teal-2) 50%, var(--blue-1) 100%);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            padding: 3rem;
            overflow: hidden;
        }

        /* Grid Pattern Background */
        .left-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }
        .shape-1 { width: 300px; height: 300px; top: -50px; left: -50px; filter: blur(60px); }
        .shape-2 { width: 200px; height: 200px; bottom: -30px; right: -30px; filter: blur(40px); background: rgba(255,255,255,0.15); }
        .shape-3 { width: 100px; height: 100px; top: 20%; right: 20%; background: rgba(255,255,255,0.05); animation: float 6s ease-in-out infinite; }
        .shape-4 { width: 60px; height: 60px; bottom: 30%; left: 15%; background: rgba(255,255,255,0.08); animation: float 8s ease-in-out infinite reverse; }
        .shape-5 { width: 8px; height: 8px; top: 40%; left: 40%; background: rgba(255,255,255,0.4); animation: float 4s ease-in-out infinite; }
        .shape-6 { width: 6px; height: 6px; bottom: 20%; right: 30%; background: rgba(255,255,255,0.3); animation: float 5s ease-in-out infinite; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .brand-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .brand-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .left-content h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
            line-height: 1.3;
        }

        .left-content p {
            font-size: 1rem;
            opacity: 0.85;
            line-height: 1.7;
            max-width: 320px;
            margin: 0 auto;
        }

        /* Kolom Tengah - Form Login */
        .middle-section {
            background-color: white;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
        }

        /* Subtle decoration on right */
        .middle-section::before {
            content: "";
            position: absolute;
            top: 50px;
            right: 50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(16, 150, 150, 0.04) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .login-form {
            width: 100%;
            max-width: 420px;
            padding: 3rem;
            z-index: 1;
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-header .brand-text {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            line-height: 1.3;
        }

        .login-header .brand-text span {
            background: linear-gradient(135deg, var(--teal-1), var(--blue-1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin: 0;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 16px; /* Adjusted for padding */
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: color 0.3s;
            z-index: 2;
        }

        .form-control {
            padding: 14px 16px 14px 48px;
            border-radius: 12px;
            border: 1.5px solid var(--border-light);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-dark);
            background-color: var(--bg-soft);
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .form-control:focus {
            border-color: var(--teal-1);
            box-shadow: 0 0 0 4px rgba(16, 150, 150, 0.1);
            background-color: white;
        }

        .input-group-custom:focus-within i {
            color: var(--teal-1);
        }

        /* Fix bootstrap invalid state styling */
        .form-control.is-invalid {
            border-color: #EF4444;
            background-color: #FEF2F2;
        }
        
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            font-size: 0.82rem;
            margin-left: 48px;
            margin-top: 6px;
        }

        .form-check-input {
            background-color: var(--bg-soft);
            border: 1.5px solid var(--border-light);
            width: 18px;
            height: 18px;
            margin-top: 0.15em;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-check-input:checked {
            background-color: var(--teal-1);
            border-color: var(--teal-1);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: var(--text-body);
            cursor: pointer;
            margin-left: 6px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--teal-1) 0%, var(--blue-1) 100%);
            border: none;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 12px;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 150, 150, 0.2);
            letter-spacing: 0.5px;
            margin-top: 2rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 150, 150, 0.35);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .left-section {
                display: none; /* Sembunyikan kolom samping di mobile */
            }
            .middle-section {
                width: 100%;
            }
            .login-form {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Kolom Kiri -->
                <div class="col-md-5 left-section d-none d-md-flex">
                    <!-- Elemen Dekoratif -->
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                    <div class="shape shape-4"></div>
                    <div class="shape shape-5"></div>
                    <div class="shape shape-6"></div>
                    
                    <!-- Konten Kiri -->
                    <div class="left-content">
                        <div class="brand-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <h2>Kelola Domain<br>Dengan Mudah</h2>
                        <p>Platform management domain desa terintegrasi, cepat, dan aman untuk seluruh Indonesia.</p>
                    </div>
                </div>

                <!-- Kolom Tengah (Form Login) -->
                <div class="col-md-7 middle-section">
                    <div class="login-form">
                        <div class="login-header">
                            <div class="brand-text">Selamat Datang di <span>SIMANDA</span></div>
                            <p>Silakan masuk ke akun Anda untuk melanjutkan</p>
                        </div>
                        
                        <!-- Form Login -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="input-group-custom">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" 
                                       value="{{ old('username') }}" 
                                       placeholder="Masukkan Username" required>
                                @error('username')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="input-group-custom">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Masukkan Password" required>
                                @error('password')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Bagian Remember Me (Ingat Saya) -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--teal-1); font-size: 0.9rem; font-weight: 600;">Lupa Password?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </form>

                        <!-- Footer -->
                        <div class="form-footer">
                            &copy; {{ date('Y') }} Domain. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>