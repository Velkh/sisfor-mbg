{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard MBG Kota Depok</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --g900: #0d3b1e;
            --g800: #155c30;
            --g700: #1a7a3f;
            --g600: #2e9e57;
            --g400: #52c77a;
            --cream: #faf8f3;
            --text: #1a1a1a;
            --muted: #5e5e5e;
            --border: #ddd7cb;
            --white: #ffffff;
            --error: #dc2626;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, var(--g900) 0%, var(--g800) 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--white);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 40px rgba(0,0,0,.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-pill {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .logo-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--g700);
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-header h1 {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            color: var(--g900);
            margin-bottom: 6px;
        }

        .login-header p {
            color: var(--muted);
            font-size: .9rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: .8rem;
            font-weight: 700;
            color: var(--g900);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--g600);
            box-shadow: 0 0 0 3px rgba(46, 158, 87, .1);
        }

        .form-input.error {
            border-color: var(--error);
        }

        .error-message {
            color: var(--error);
            font-size: .8rem;
            margin-top: 6px;
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--g700);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            margin-top: 24px;
        }

        .btn-submit:hover {
            background: var(--g800);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(13, 59, 30, .2);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: .85rem;
            color: var(--muted);
        }

        .login-footer a {
            color: var(--g700);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px;
            }

            .login-header h1 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-pill">
                <div class="logo-circle">
                    <img src="{{ asset('images/images.jpeg') }}" alt="BGN" style="object-fit: contain; padding: 4px;" onerror="this.style.display='none';">
                </div>
            </div>
            <h1>Dashboard SLHS</h1>
            <p>Kota Depok</p>
        </div>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 20px; color: var(--error); font-size: .9rem;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input @error('username') error @enderror" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input @error('password') error @enderror" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="login-footer">
            Kembali ke <a href="{{ route('guest.index') }}">Beranda</a>
        </div>
    </div>
</div>

</body>
</html>