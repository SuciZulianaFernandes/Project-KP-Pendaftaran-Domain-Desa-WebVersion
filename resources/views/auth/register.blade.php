<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Nama Aplikasi</title>
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

        .register-container {
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

        /* Kolom Tengah - Form Registrasi */
        .middle-section {
            background-color: white;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative; /* Untuk posisikan link Sign In */
        }

        .register-form {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        
        .sign-in-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.9rem;
        }

        .sign-in-link a {
            color: #555;
            text-decoration: none;
        }

        .sign-in-link a:hover {
            color: var(--primary-red);
            text-decoration: underline;
        }

        .register-form h2 {
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

        .btn-register {
            background-color: var(--primary-red);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 5px;
            width: 100%;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-register:hover {
            background-color: var(--dark-red);
            color: white;
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
            position: relative;
        }

        .illustration i.laptop {
            font-size: 10rem;
            color: #ccc;
        }

        .illustration i.magnifier {
            font-size: 5rem;
            color: #aaa;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -30%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left-section, .right-section {
                display: none; /* Sembunyikan kolom samping di mobile */
            }
            .middle-section {
                width: 100%;
            }
            .sign-in-link {
                position: static;
                text-align: center;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
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

                <!-- Kolom Tengah (Form Registrasi) -->
                <div class="col-md-4 middle-section">
                    <div class="register-form">
                        <!-- Link Sign In -->
                        <div class="sign-in-link">
                            Have an Account? <a href="{{ url('/login') }}">Sign In</a>
                        </div>

                        <h2>Registrasi</h2>
                        
                        <!-- Form Registrasi -->
                        <form method="POST" action="{{ route('register') }}">                            @csrf
                            
                            <div class="mb-3">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" 
                                       value="{{ old('nama') }}" 
                                       placeholder="Nama" required autofocus>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

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
                                <input type="tel" class="form-control @error('no_handphone') is-invalid @enderror" 
                                       id="no_handphone" name="no_handphone" 
                                       value="{{ old('no_handphone') }}" 
                                       placeholder="No. Handphone" required>
                                @error('no_handphone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Email" required>
                                @error('email')
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

                            <div class="mb-3">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Konfirmasi Password" required>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-register">
                                Daftar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-4 right-section d-none d-md-flex">
                    <div class="illustration">
                        <!-- Ikon Laptop dengan Kaca Pembesar -->
                        <i class="fas fa-laptop laptop"></i>
                        <i class="fas fa-search magnifier"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>