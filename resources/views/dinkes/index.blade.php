@extends('layout.dinkes')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Selamat Datang!</strong> Berikut adalah ringkasan data SPPG terbaru.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <div class="stat-card-value">24</div>
                    <div class="stat-card-label">Total SPPG Terdaftar</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #FF9800;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-card-value">5</div>
                    <div class="stat-card-label">SPPG Layak IKL</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #2196F3;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">12</div>
                    <div class="stat-card-label">SPPG Ber SLHS</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #F44336;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-card-value">7</div>
                    <div class="stat-card-label">SPPG Lulus IKL</div>
                </div>
            </div>
        </div>

        <!-- Main Content Cards -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Data SPPG Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SPPG</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SPPG - 001</td>
                                        <td><span class="badge bg-success">Layak</span></td>
                                        <td>15 Apr 2026</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>SPPG - 002</td>
                                        <td><span class="badge bg-warning">Proses</span></td>
                                        <td>14 Apr 2026</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>SPPG - 003</td>
                                        <td><span class="badge bg-danger">Ditolak</span></td>
                                        <td>13 Apr 2026</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.kelola') }}" class="btn btn-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-calendar me-2"></i>Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item mb-3">
                                <div class="d-flex">
                                    <div class="activity-icon">
                                        <i class="fas fa-plus-circle text-success"></i>
                                    </div>
                                    <div class="activity-content ms-3">
                                        <p class="mb-1"><strong>SPPG Baru Dibuat</strong></p>
                                        <small class="text-muted">Admin Dinkes - 2 jam lalu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="activity-item mb-3">
                                <div class="d-flex">
                                    <div class="activity-icon">
                                        <i class="fas fa-edit text-warning"></i>
                                    </div>
                                    <div class="activity-content ms-3">
                                        <p class="mb-1"><strong>Data SPPG Diubah</strong></p>
                                        <small class="text-muted">Admin Dinkes - 5 jam lalu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="d-flex">
                                    <div class="activity-icon">
                                        <i class="fas fa-check-circle text-info"></i>
                                    </div>
                                    <div class="activity-content ms-3">
                                        <p class="mb-1"><strong>Status Diperbarui</strong></p>
                                        <small class="text-muted">Operator SPPG - 1 hari lalu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .activity-icon {
            font-size: 20px;
            min-width: 30px;
        }

        .activity-item {
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-content p {
            font-size: 14px;
        }
    </style>
@endsection
