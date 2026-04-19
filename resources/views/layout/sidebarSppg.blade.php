<!-- Sidebar Navigation for SPPG Operator -->
<div class="sidebar-sppg">
    <!-- Sidebar Header with Logo -->
    <div class="sidebar-header">
        <div class="logo-section">
            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/2/29/Logo_Badan_Gizi_Nasional.svg/3840px-Logo_Badan_Gizi_Nasional.svg.png" alt="Logo BGN" class="logo-img">
            <div class="app-title">
                <h5>BGN</h5>
                <p>SPPG System</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
            <p class="user-name">{{ Auth::user()->username ?? 'Operator' }}</p>
            <span class="user-role">Operator SPPG</span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('sppg.index') }}" class="nav-link {{ Route::is('sppg.index') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('sppg.inspeksi') }}" class="nav-link {{ Route::is('sppg.inspeksi') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope"></i>
                    <span>Inspeksi</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('sppg.profile') }}" class="nav-link {{ Route::is('sppg.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('sppg.suratlaik') }}" class="nav-link {{ Route::is('sppg.suratlaik') ? 'active' : '' }}">
                    <i class="fas fa-file-pdf"></i>
                    <span>Surat Laik</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('sppg.pelaporan') }}" class="nav-link {{ Route::is('sppg.pelaporan') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Pelaporan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
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

<!-- Sidebar Styles -->
<style>
    .sidebar-sppg {
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        height: 100vh;
        background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
        display: flex;
        flex-direction: column;
        color: white;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
    }

    /* Sidebar Header */
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

    /* User Info */
    .user-info {
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-avatar {
        font-size: 32px;
        color: white;
        min-width: 40px;
        text-align: center;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: white;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.75);
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
    .sidebar-sppg::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-sppg::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-sppg::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .sidebar-sppg::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Main Content Adjustment */
    body.sppg-layout {
        margin-left: 250px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar-sppg {
            width: 200px;
        }

        body.sppg-layout {
            margin-left: 200px;
        }

        .app-title h5 {
            font-size: 13px;
        }

        .app-title p {
            font-size: 10px;
        }

        .nav-link {
            padding: 10px 15px;
            font-size: 13px;
        }
    }

    @media (max-width: 576px) {
        .sidebar-sppg {
            position: absolute;
            width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 0 0 10px 10px;
        }

        body.sppg-layout {
            margin-left: 0;
        }
    }
</style>