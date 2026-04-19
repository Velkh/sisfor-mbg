@extends('layout.sppgLayout')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-home"></i>
            Dashboard SPPG
        </h1>
    </div>

    <!-- Welcome Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Selamat Datang!</strong> Berikut adalah ringkasan aktivitas SPPG Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-value">8</div>
                <div class="stat-card-label">Inspeksi Selesai</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #FF9800;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-card-value">3</div>
                <div class="stat-card-label">Inspeksi Berlangsung</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #2196F3;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-card-value">12</div>
                <div class="stat-card-label">Laporan Diajukan</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #4CAF50;">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-card-value">5</div>
                <div class="stat-card-label">Surat Laik Aktif</div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Recent Inspections -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-stethoscope me-2"></i>Inspeksi Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>15 Apr 2026</td>
                                    <td>SPPG Pusat - Depok</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                    <td>
                                        <a href="{{ route('sppg.inspeksi') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>14 Apr 2026</td>
                                    <td>SPPG Cabang - Cileungsi</td>
                                    <td><span class="badge bg-warning">Proses</span></td>
                                    <td>
                                        <a href="{{ route('sppg.inspeksi') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>12 Apr 2026</td>
                                    <td>SPPG Satelit - Gunung Putri</td>
                                    <td><span class="badge bg-success">Selesai</span></td>
                                    <td>
                                        <a href="{{ route('sppg.inspeksi') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('sppg.inspeksi') }}" class="btn btn-primary">
                            Lihat Semua Inspeksi <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Card -->
        <div class="col-lg-4">
            <!-- Recent Reports -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Laporan Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <div class="activity-item mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                            <div class="d-flex">
                                <div class="activity-icon" style="font-size: 20px; min-width: 30px;">
                                    <i class="fas fa-plus-circle text-success"></i>
                                </div>
                                <div class="ms-3" style="flex: 1; min-width: 0;">
                                    <p class="mb-1" style="font-weight: 600; font-size: 13px;">Laporan Baru Dibuat</p>
                                    <small class="text-muted">2 jam lalu</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                            <div class="d-flex">
                                <div class="activity-icon" style="font-size: 20px; min-width: 30px; color: #FF9800;">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="ms-3" style="flex: 1; min-width: 0;">
                                    <p class="mb-1" style="font-weight: 600; font-size: 13px;">Laporan Diubah</p>
                                    <small class="text-muted">5 jam lalu</small>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item mb-0">
                            <div class="d-flex">
                                <div class="activity-icon" style="font-size: 20px; min-width: 30px; color: #2196F3;">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div class="ms-3" style="flex: 1; min-width: 0;">
                                    <p class="mb-1" style="font-weight: 600; font-size: 13px;">Laporan Diajukan</p>
                                    <small class="text-muted">1 hari lalu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-lightning-bolt me-2"></i>Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('sppg.inspeksi') }}" class="btn btn-outline-primary btn-sm" style="justify-content: flex-start;">
                            <i class="fas fa-stethoscope me-2"></i> Input Inspeksi
                        </a>
                        <a href="{{ route('sppg.pelaporan') }}" class="btn btn-outline-primary btn-sm" style="justify-content: flex-start;">
                            <i class="fas fa-file-alt me-2"></i> Buat Laporan
                        </a>
                        <a href="{{ route('sppg.suratlaik') }}" class="btn btn-outline-primary btn-sm" style="justify-content: flex-start;">
                            <i class="fas fa-certificate me-2"></i> Kelola Surat Laik
                        </a>
                        <a href="{{ route('sppg.profile') }}" class="btn btn-outline-primary btn-sm" style="justify-content: flex-start;">
                            <i class="fas fa-user me-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
