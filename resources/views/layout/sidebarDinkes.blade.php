<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- ================= TOP BAR ================= -->
<div class="top-bar">
    <div class="top-bar-left">
        <button id="sidebar-toggle" class="mobile-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="top-bar-right">
        <div class="user-info-top">
            <div class="user-details-top">
                <p class="user-name-top">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                <!-- Menampilkan Role secara dinamis (Dinkes / Korwil / Korcam) -->
                <span class="user-role-top">
                    @if(isset(Auth::user()->role))
                        {{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}
                    @else
                        Admin Dinkes
                    @endif
                </span>
            </div>
            <div class="user-avatar-top">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- ================= SIDEBAR ================= -->
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
            <!-- Menu Dashboard: Bisa diakses Dinkes & Korwil -->
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Menu Kelola Admin: HANYA untuk Admin Dinkes -->
            @if(Auth::user() && Auth::user()->role === 'admin_dinkes')
            <li class="nav-item">
                <a href="{{ route('admin.manage.index') }}" class="nav-link {{ Route::is('admin.manage*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>
            @endif

            <!-- Menu Input Data: Dinkes & Korwil (Korwil hanya akses SPPG di logic controllernya) -->
            <li class="nav-item">
                <a href="{{ route('admin.reporting.index') }}" class="nav-link {{ Route::is('admin.reporting*') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Input Data Unit Usaha</span>
                </a>
            </li>

            <!-- Menu Data Kelayakan: Dinkes & Korwil -->
            <li class="nav-item">
                <a href="{{ route('admin.data') }}" class="nav-link {{ Route::is('admin.data*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Data Kelayakan</span>
                </a>
            </li>

            <!-- Menu Rekap Laporan: Dinkes & Korwil -->
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

<!-- ================= CSS STYLES ================= -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        margin-left: 260px; 
        padding-top: 70px;  
        background-color: #f8fafc; 
        margin-top: 0;
    }

    /* --- TOP BAR STYLES (BIRU MUDA) --- */
    .top-bar {
        position: fixed;
        top: 0;
        left: 260px; 
        right: 0;
        height: 70px;
        background: #009DE0; /* Biru Muda */
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between; 
        align-items: center;
        padding: 0 30px;
        z-index: 999;
        transition: all 0.3s ease;
    }

    .user-info-top {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 6px 12px;
        border-radius: 12px;
        transition: background 0.3s ease;
        cursor: pointer;
    }

    .user-info-top:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .user-details-top {
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .user-name-top {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
    }

    .user-role-top {
        font-size: 12px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }

    .user-avatar-top i {
        font-size: 38px;
        color: #ffffff; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-toggle-btn {
        display: none;
        background: transparent;
        border: none;
        color: #ffffff; 
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
        transition: color 0.3s ease;
    }

    .mobile-toggle-btn:hover {
        color: rgba(255, 255, 255, 0.7);
    }

    /* --- SIDEBAR STYLES (BIRU MUDA) --- */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        background: #009DE0; /* Biru Muda, sama dengan top-bar */
        display: flex;
        flex-direction: column;
        color: white;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-img {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: white;
        padding: 6px;
        object-fit: contain;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .app-title h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    .app-title p {
        margin: 4px 0 0 0;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    .sidebar-nav {
        flex: 1;
        padding: 20px 0;
    }

    .nav-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-item {
        margin: 4px 16px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        color: #ffffff;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: #ffffff; 
        color: #009DE0; /* Teks menu aktif menyesuaikan warna biru muda */
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .nav-link i {
        font-size: 18px;
        min-width: 24px;
        text-align: center;
        transition: color 0.3s ease;
    }

    .nav-link.active i {
        color: #009DE0; /* Icon aktif menyesuaikan warna biru muda */
    }

    .nav-link span {
        flex: 1;
    }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(0, 0, 0, 0.05);
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
        font-family: inherit;
        color: #ffe4e6; 
    }

    .logout-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        transform: translateX(5px);
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar { width: 5px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 10px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }

    /* --- Responsive Design --- */
    @media (max-width: 992px) {
        .sidebar { width: 220px; }
        .top-bar { left: 220px; }
        body { margin-left: 220px; }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            z-index: 1001;
        }
        .sidebar.show-sidebar {
            transform: translateX(0);
        }
        .top-bar {
            left: 0;
            padding: 0 15px;
        }
        body {
            margin-left: 0;
            padding-top: 70px;
        }
        .mobile-toggle-btn {
            display: block; 
        }
    }
</style>

<!-- ================= JAVASCRIPT ================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');

        if(toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('show-sidebar');
            });
        }

        // Tutup sidebar jika klik di luar area sidebar pada mode mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('show-sidebar');
                }
            }
        });
    });
</script>