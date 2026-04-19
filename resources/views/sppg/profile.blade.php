@extends('layout.sppgLayout')

@section('title', 'Profile')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-user"></i>
            Profile SPPG
        </h1>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div style="font-size: 80px; color: #0066cc; margin: 20px 0;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4>SPPG Pusat Depok</h4>
                    <p class="text-muted mb-3">Satuan Pelayanan Pemenuhan Gizi</p>
                    <div class="mb-3">
                        <span class="badge bg-success" style="font-size: 12px; padding: 8px 12px;">Status: Aktif</span>
                    </div>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2"><strong>ID SPPG:</strong> SPPG-2026-001</p>
                        <p class="mb-2"><strong>Terdaftar:</strong> 15 Januari 2025</p>
                        <p class="mb-0"><strong>Wilayah:</strong> Kota Depok</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-phone me-2"></i>Informasi Kontak</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Telepon:</strong><br>
                        <a href="tel:021123456">(021) 123456</a>
                    </p>
                    <p class="mb-2">
                        <strong>Email:</strong><br>
                        <a href="mailto:sppg@depok.go.id">sppg@depok.go.id</a>
                    </p>
                    <p class="mb-0">
                        <strong>Alamat:</strong><br>
                        Jl. Margonda Raya No. 100, Depok
                    </p>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfile">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Nama SPPG</p>
                            <p style="font-weight: 600;">SPPG Pusat Depok</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Tipe SPPG</p>
                            <p style="font-weight: 600;">Pusat</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Desa/Kelurahan</p>
                            <p style="font-weight: 600;">Depok Lama</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Kecamatan</p>
                            <p style="font-weight: 600;">Depok</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Kapasitas Harian</p>
                            <p style="font-weight: 600;">1.500 porsi</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Operator SPPG</p>
                            <p style="font-weight: 600;">{{ auth()->user()->username ?? 'Operator' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operation Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-cogs me-2"></i>Informasi Operasional</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Status Operasional</p>
                            <p style="font-weight: 600;">
                                <span class="badge bg-success">Beroperasi</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Sertifikasi</p>
                            <p style="font-weight: 600;">
                                <span class="badge bg-success">Tersertifikasi</span>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Izin Usaha</p>
                            <p style="font-weight: 600;">IUP-SPPG-2025-001</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">NPWP</p>
                            <p style="font-weight: 600;">12.345.678.9-123.000</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted mb-1">Catatan Operasional</p>
                            <p style="font-weight: 600;">SPPG berjalan optimal dengan kapasitas produksi mencapai 80% dari kapasitas maksimal.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-card-value">12.450</div>
                        <div class="stat-card-label">Porsi Terlayani</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="color: #FF9800;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-card-value">830</div>
                        <div class="stat-card-label">Penerima Manfaat</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="color: #4CAF50;">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-card-value">125</div>
                        <div class="stat-card-label">Hari Operasional</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profile -->
    <div class="modal fade" id="editProfile" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile SPPG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama SPPG</label>
                            <input type="text" class="form-control" value="SPPG Pusat Depok">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="tel" class="form-control" value="(021) 123456">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="sppg@depok.go.id">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" rows="3">Jl. Margonda Raya No. 100, Depok</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kapasitas Harian</label>
                                <input type="number" class="form-control" value="1500" placeholder="Jumlah porsi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option selected>Beroperasi</option>
                                    <option>Tidak Beroperasi</option>
                                    <option>Dalam Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
