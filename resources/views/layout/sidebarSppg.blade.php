<div class="topbar-sppg">
    <div class="topbar-left"></div>
    
    <div class="topbar-right">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <p class="user-name">{{ Auth::user()->username ?? 'Operator' }}</p>
                <span class="user-role">Admin {{ Auth::user()->kecamatan?->nama_kecamatan ?? 'Kecamatan' }} 
                    | {{ strtoupper(Auth::user()->akses_tipe_usaha ?? 'Unit Usaha') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="sidebar-sppg">
    <div class="sidebar-header">
        <div class="logo-section">
            <img src="{{ asset('images/images.jpeg') }}" alt="Logo BGN" class="logo-img">
            <div class="app-title">
                <h5>Dinas Kesehatan Kota Depok</h5>
                <p>SLHS Monitoring System</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('kecamatan.dashboard') }}" class="nav-link {{ Route::is('kecamatan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('kecamatan.laporan-unit.index') }}" class="nav-link {{ Route::is('kecamatan.unit-usaha.index') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope"></i>
                    <span>Tambah Unit Usaha</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('kecamatan.kelayakan.index') }}" class="nav-link {{ Route::is('kecamatan.kelayakan.index') ? 'active' : '' }}">
                    <i class="fas fa-file-pdf"></i>
                    <span>Kelayakan</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('kecamatan.sasaran.index') }}" class="nav-link {{ Route::is('kecamatan.sasaran.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Pelaporan</span>
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
    /* ================= Topbar Styles ================= */
    .topbar-sppg {
        position: fixed;
        top: 0;
        left: 250px; /* Menyesuaikan lebar sidebar */
        right: 0;
        height: 65px;
        background: #0066cc;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 25px;
        z-index: 999;
    }

    .topbar-sppg .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .topbar-sppg .user-avatar {
        font-size: 32px;
        color: #ffffff; /* Menggunakan warna biru agar senada dengan sidebar */
    }

    .topbar-sppg .user-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .topbar-sppg .user-name {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
    }

    .topbar-sppg .user-role {
        font-size: 11px;
        color: #ffffff;
    }

    /* ================= Sidebar Styles ================= */
    .sidebar-sppg {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: #0066cc;
        display: flex;
        flex-direction: column;
        color: white;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
    }

    .sidebar-header {
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        padding: 4px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .app-title h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: white;
    }

    .app-title p {
        margin: 2px 0 0 0;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    /* Sidebar Navigation */
    .sidebar-nav {
        flex: 1;
        padding: 10px 0;
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
        border-left-color: #ffeb3b;
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

    /* Sidebar Footer */
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
    .sidebar-sppg::-webkit-scrollbar { width: 6px; }
    .sidebar-sppg::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .sidebar-sppg::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 3px; }
    .sidebar-sppg::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

    /* ================= Main Content Layout ================= */
    body.sppg-layout {
        margin-left: 250px;
        padding-top: 65px; /* Offset untuk menghindari konten tertutup topbar */
        background-color: #f4f6f9; /* Background abu-abu muda standar dashboard */
    }

    /* ================= Responsive Design ================= */
    @media (max-width: 768px) {
        .sidebar-sppg {
            width: 200px;
        }
        .topbar-sppg {
            left: 200px;
        }
        body.sppg-layout {
            margin-left: 200px;
        }
        .app-title h5 { font-size: 13px; }
        .app-title p { font-size: 10px; }
        .nav-link { padding: 10px 15px; font-size: 13px; }
    }

    @media (max-width: 576px) {
        .sidebar-sppg {
            position: absolute; /* Atau fixed tapi disembunyikan menggunakan hamburger menu */
            width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 0 0 10px 10px;
            z-index: 1001; /* Harus di atas topbar jika sedang terbuka */
        }
        .topbar-sppg {
            left: 0; /* Full width di mobile */
        }
        body.sppg-layout {
            margin-left: 0;
            padding-top: 65px;
        }
    }
</style>
