<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Nama Aplikasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #9C272B;
            --dark-red: #9C272B;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            height: 100vh;
            overflow: hidden;
        }

        /* Kolom Kiri - Background Merah */
        .left-section {
            background-color: var(--primary-red);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            padding: 2rem;
        }

        /* Animasi gelembung domain */
        .domain-bubble {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 10px 20px;
            font-weight: bold;
            animation: float 5s infinite ease-in-out;
        }
        .bubble-1 { top: 20%; left: 10%; animation-delay: 0s; }
        .bubble-2 { top: 60%; left: 25%; animation-delay: 1s; }
        .bubble-3 { top: 35%; right: 15%; animation-delay: 2s; }
        .bubble-4 { bottom: 25%; right: 10%; animation-delay: 3s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .left-section .character-icon {
            font-size: 8rem; /* Ikon besar untuk tokoh kartun */
            color: rgba(255, 255, 255, 0.9);
            z-index: 2;
        }

        /* Kolom Tengah - Form Login */
        .middle-section {
            background-color: white;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .login-form {
            width: 100%;
            max-width: 380px;
            padding: 2rem;
            border-radius: 8px;
        }

        .login-form h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }

        .btn-login {
            background-color: var(--primary-red);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 5px;
            width: 100%;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: var(--dark-red);
            color: white;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .form-footer a {
            color: #777;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .form-footer a:hover {
            color: var(--primary-red);
            text-decoration: underline;
        }
        
        /* Kolom Kanan - Ilustrasi */
        .right-section {
            background-color: #f8f9fa; /* Background abu-abu sangat muda */
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .illustration {
            text-align: center;
        }

        .illustration i {
            font-size: 10rem;
            color: #ccc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left-section, .right-section {
                display: none; /* Sembunyikan kolom samping di mobile */
            }
            .middle-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Kolom Kiri -->
                <div class="col-md-4 left-section d-none d-md-flex">
                    <!-- Ikon Karakter Kartun -->
                    <i class="fas fa-user-astronaut character-icon"></i>
                    
                    <!-- Gelembung Domain -->
                    <span class="domain-bubble bubble-1">.com</span>
                    <span class="domain-bubble bubble-2">.net</span>
                    <span class="domain-bubble bubble-3">.org</span>
                    <span class="domain-bubble bubble-4">.id</span>
                </div>

                <!-- Kolom Tengah (Form Login) -->
                <div class="col-md-4 middle-section">
                    <div class="login-form">
                        <h2>Selamat Datang di<br>Nama Aplikasi</h2>
                        
                        <!-- Form Login -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" 
                                       value="{{ old('username') }}" 
                                       placeholder="Username" required>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                Masuk
                            </button>
                        </form>

                        <div class="form-footer">
                            <a href="#">Lupa Password?</a>
                            <p class="mt-3 mb-0">
                                Doesn't Have an Account? 
                            <a href="{{ url('/register') }}">Sign Up</a>                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-4 right-section d-none d-md-flex">
                    <div class="illustration">
                        <!-- Ikon Laptop dengan Kaca Pembesar -->
                        <i class="fas fa-laptop"></i>
                        <i class="fas fa-search" style="font-size: 5rem; color: #aaa; position: absolute; transform: translate(-50px, 30px);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>