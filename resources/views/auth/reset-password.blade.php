<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Domain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal-1: #109696;
            --teal-2: #1A85A5;
            --blue-1: #1760C5;
            --text-dark: #1E293B;
            --text-muted: #94A3B8;
            --border-light: #E2E8F0;
            --bg-soft: #F8FAFC;
        }
        body, html { height: 100%; margin: 0; font-family: 'Inter', sans-serif; background-color: var(--bg-soft); }
        .center-section { height: 100vh; display: flex; justify-content: center; align-items: center; padding: 2rem; }
        .auth-card { width: 100%; max-width: 440px; padding: 3rem; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); }
        .auth-header .brand-text { font-size: 1.6rem; font-weight: 900; color: var(--text-dark); margin-bottom: 0.5rem; }
        .auth-header .brand-text span { background: linear-gradient(135deg, var(--teal-1), var(--blue-1)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .auth-header p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; }
        .input-group-custom { position: relative; margin-bottom: 1.5rem; }
        .input-group-custom i { position: absolute; left: 16px; top: 16px; color: var(--text-muted); font-size: 0.9rem; z-index: 2; }
        .form-control { padding: 14px 16px 14px 48px; border-radius: 12px; border: 1.5px solid var(--border-light); font-size: 0.95rem; background-color: var(--bg-soft); }
        .form-control:focus { border-color: var(--teal-1); box-shadow: 0 0 0 4px rgba(16,150,150,0.1); background-color: white; }
        .btn-primary-custom { background: linear-gradient(135deg, var(--teal-1) 0%, var(--blue-1) 100%); border: none; padding: 14px; font-weight: 700; border-radius: 12px; width: 100%; color: white; }
    </style>
</head>
<body>
    <div class="center-section">
        <div class="auth-card">
            <div class="auth-header">
                <div class="brand-text">Atur Ulang <span>Password</span></div>
                <p>Masukkan password baru Anda di bawah ini.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group-custom">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $email) }}"
                           placeholder="Email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           placeholder="Password Baru" required minlength="6">
                    @error('password')
                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation"
                           placeholder="Konfirmasi Password Baru" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary-custom">
                    <i class="fas fa-key me-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>
