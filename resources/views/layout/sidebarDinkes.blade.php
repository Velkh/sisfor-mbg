<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="top-bar">
    <div class="top-bar-right">
        <div class="user-info-top">
            <div class="user-details-top">
                <p class="user-name-top">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                <span class="user-role-top">Admin Dinkes</span>
            </div>
            <div class="user-avatar-top">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-section">
            <img src="{{ asset('images/images.jpeg') }}" alt="Logo Kota Depok" class="logo-img">
            <div class="app-title">
                <h5>Dinas Kesehatan</h5>
                <p>Kota Depok</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.reporting.index') }}" class="nav-link {{ Route::is('admin.reporting*') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Input Data Unit Usaha</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.manage.index') }}" class="nav-link {{ Route::is('admin.manage*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.data') }}" class="nav-link {{ Route::is('admin.data*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Data Kelayakan</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.laporan') }}" class="nav-link {{ Route::is('admin.laporan*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Rekap Laporan</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="nav-link logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Log Out</span>
            </button>
        </form>
    </div>
</div>

<style>
    /* Main Content Adjustment */
    body {
        margin-left: 250px; /* Offset untuk Sidebar */
        padding-top: 70px;  /* Offset untuk Top Bar (BARU) */
        background-color: #f4f7f6; /* Warna bg standar dashboard */
        margin-top: 0;
    }

    /* --- TOP BAR STYLES (BARU) --- */
    .top-bar {
        position: fixed;
        top: 0;
        left: 250px; /* Mulai setelah lebar sidebar */
        right: 0;
        height: 70px;
        background-color: #006803;
        box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: flex-end; /* Memindahkan konten ke kanan */
        align-items: center;
        padding: 0 30px;
        z-index: 999;
    }

    .user-info-top {
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
    }

    .user-details-top {
        text-align: right;
    }

    .user-name-top {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff; /* Teks Gelap karena bg putih */
    }

    .user-role-top {
        font-size: 12px;
        color: #ffffff;
    }

    .user-avatar-top i {
        font-size: 38px;
        color: #ffffff; /* Warna Hijau Dinkes */
    }

    /* --- SIDEBAR STYLES --- */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: linear-gradient(135deg, #006803 0%, #006803 100%);
        display: flex;
        flex-direction: column;
        color: white;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        padding: 5px;
        object-fit: contain;
    }

    .app-title h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: white;
    }

    .app-title p {
        margin: 2px 0 0 0;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
    }

    .sidebar-nav {
        flex: 1;
        padding: 20px 0; /* Padding ditambah karena user-info hilang */
    }

    .nav-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-item {
        margin: 5px 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        font-size: 14px;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border-left-color: white;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        border-left-color: white;
        font-weight: 600;
    }

    .nav-link i {
        font-size: 16px;
        min-width: 20px;
        text-align: center;
    }

    .nav-link span {
        flex: 1;
    }

    .sidebar-footer {
        padding: 10px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logout-form {
        width: 100%;
    }

    .logout-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        padding: 12px 20px;
        margin: 0;
        font-family: inherit;
        color: rgba(255, 255, 255, 0.85);
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar { width: 6px; }
    .sidebar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 3px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

    /* --- Responsive Design --- */
    @media (max-width: 768px) {
        .sidebar { width: 200px; }
        body { margin-left: 200px; }
        .top-bar { left: 200px; }
        
        .app-title h5 { font-size: 13px; }
        .app-title p { font-size: 10px; }
        .nav-link { padding: 10px 15px; font-size: 13px; }
    }

    @media (max-width: 576px) {
        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
            max-height: auto;
        }
        .top-bar {
            left: 0;
            position: relative;
            box-shadow: none;
            border-bottom: 1px solid #006803;
        }
        body {
            margin-left: 0;
            padding-top: 0;
        }
    }
</style>