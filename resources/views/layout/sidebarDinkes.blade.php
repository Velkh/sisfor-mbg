<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                <a href="{{ route('admin.manage.index') }}" class="nav-link {{ Route::is('admin.manage*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.reporting.index') }}" class="nav-link {{ Route::is('admin.reporting*') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Input Data Unit Usaha</span>
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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        margin-left: 260px; 
        padding-top: 70px;  
        background-color: #f8fafc; 
        margin-top: 0;
    }

    /* --- TOP BAR STYLES (HIJAU) --- */
    .top-bar {
        position: fixed;
        top: 0;
        left: 260px; 
        right: 0;
        height: 70px;
        /* Latar belakang hijau Dinkes dengan efek transparan tipis */
        background: rgba(0, 104, 3, 0.98);
        backdrop-filter: blur(10px);
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
        color: #ffffff; /* Teks nama putih */
    }

    .user-role-top {
        font-size: 12px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.8); /* Teks abu-abu terang */
    }

    .user-avatar-top i {
        font-size: 38px;
        color: #ffffff; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Tombol Hamburger Mobile (Putih) */
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

    /* --- SIDEBAR STYLES (HIJAU) --- */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        /* Gradien hijau gelap khas Dinkes */
        background: linear-gradient(180deg, #006803 0%, #004202 100%); 
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
        color: rgba(255, 255, 255, 0.8);
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
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        transform: translateX(5px);
    }

    .nav-link.active {
        /* Background putih dan teks hijau agar sangat kontras dengan background sidebar */
        background-color: #ffffff; 
        color: #006803;
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
        color: #006803;
    }

    .nav-link span {
        flex: 1;
    }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(0, 0, 0, 0.1);
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
        color: #fca5a5; /* Merah muda lembut agar tetap terlihat di bg hijau */
    }

    .logout-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fef2f2;
        transform: translateX(5px);
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar { width: 5px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

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