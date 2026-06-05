<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="topbar-sppg">
    <div class="topbar-left">
        <button id="sidebarToggle" class="mobile-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
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

<div class="sidebar-sppg" id="sidebarMenu">
    <div class="sidebar-header">
        <div class="logo-section">
            <img src="{{ asset('images/images.jpeg') }}" alt="Logo BGN" class="logo-img">
            <div class="app-title">
                <h5>Dinkes Kota Depok</h5>
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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    /* ================= Topbar Styles ================= */
    .topbar-sppg {
        position: fixed;
        top: 0;
        left: 260px;
        right: 0;
        height: 70px;
        background: rgba(30, 41, 59, 0.98);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        z-index: 999;
        transition: all 0.3s ease;
    }

    /* Style untuk tombol mobile (disembunyikan di desktop) */
    .mobile-toggle-btn {
        display: none;
        background: none;
        border: none;
        color: #ffffff;
        font-size: 22px;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 8px;
        transition: background 0.3s;
    }

    .mobile-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .topbar-sppg .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 6px 12px;
        border-radius: 12px;
        transition: background 0.3s ease;
        cursor: pointer;
    }

    .topbar-sppg .user-info:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    .topbar-sppg .user-avatar {
        font-size: 38px;
        color: #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
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
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    /* ================= Sidebar Styles ================= */
    .sidebar-sppg {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        display: flex;
        flex-direction: column;
        color: white;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1001; /* Ditingkatkan agar di atas overlay */
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
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
        color: #94a3b8;
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
        color: #cbd5e1;
        text-decoration: none;
        border-radius: 10px; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.06);
        color: #ffffff;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: #4338ca;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4);
    }

    .nav-link i {
        font-size: 18px;
        min-width: 24px;
        text-align: center;
        transition: color 0.3s ease;
    }

    .nav-link.active i {
        color: #e0e7ff; 
    }

    .nav-link span {
        flex: 1;
    }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(15, 23, 42, 0.4);
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
        color: #f87171; 
    }

    .logout-btn:hover {
        background-color: rgba(248, 113, 113, 0.1);
        color: #ef4444;
        transform: translateX(5px);
    }

    .sidebar-sppg::-webkit-scrollbar { width: 5px; }
    .sidebar-sppg::-webkit-scrollbar-track { background: transparent; }
    .sidebar-sppg::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    .sidebar-sppg::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

    /* Overlay Styles */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    body.sppg-layout {
        margin-left: 260px;
        padding-top: 70px;
        background-color: #f8fafc;
    }

    /* ================= Responsive Design ================= */
    @media (max-width: 992px) {
        .sidebar-sppg {
            width: 220px;
        }
        .topbar-sppg {
            left: 220px;
        }
        body.sppg-layout {
            margin-left: 220px;
        }
    }

    @media (max-width: 768px) {
        .mobile-toggle-btn {
            display: block; /* Menampilkan tombol di mobile */
        }

        .sidebar-sppg {
            transform: translateX(-100%); 
        }

        /* Class ini ditambahkan oleh JavaScript */
        .sidebar-sppg.show-sidebar {
            transform: translateX(0);
        }

        /* Class ini ditambahkan oleh JavaScript */
        .sidebar-overlay.show-overlay {
            display: block;
            opacity: 1;
        }

        .topbar-sppg {
            left: 0;
            padding: 0 15px;
        }

        /* Sembunyikan detail nama dan role agar topbar tidak terlalu sempit di layar kecil */
        .topbar-sppg .user-details {
            display: none; 
        }

        body.sppg-layout {
            margin-left: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');

        // Fungsi untuk membuka/menutup sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('show-sidebar');
            overlay.classList.toggle('show-overlay');
        }

        // Event klik pada tombol hamburger
        toggleBtn.addEventListener('click', toggleSidebar);

        // Event klik pada overlay untuk menutup sidebar
        overlay.addEventListener('click', toggleSidebar);
    });
</script>