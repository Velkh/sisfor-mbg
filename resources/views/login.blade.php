{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard MBG Kota Depok</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 20px; color: #dc2626; font-size: .9rem;">
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
                <input id="password" type="password" name="password" class="form-input @error('password') error @enderror" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <div style="margin-top:10px;">
                    <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.95rem; color:#555;">
                        <input id="showPasswordToggle" type="checkbox" style="width:16px;height:16px;">
                        <span>Tampilkan password</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="login-footer">
            Kembali ke <a href="{{ route('guest.index') }}">Beranda</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('showPasswordToggle');
    const pw = document.getElementById('password');
    if (!toggle || !pw) return;
    toggle.addEventListener('change', function () {
        pw.type = this.checked ? 'text' : 'password';
    });
});
</script>
</body>
</html>