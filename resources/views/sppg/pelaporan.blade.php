@extends('layout.sppgLayout')

@section('title', 'Pelaporan')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-file-alt"></i>
            Pelaporan
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buatLaporan">
            <i class="fas fa-plus me-2"></i> Buat Laporan Baru
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-file-check"></i>
                </div>
                <div class="stat-card-value">12</div>
                <div class="stat-card-label">Laporan Selesai</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #FF9800;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-card-value">2</div>
                <div class="stat-card-label">Draft Laporan</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #2196F3;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-value">8</div>
                <div class="stat-card-label">Disetujui</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="color: #F44336;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-card-value">2</div>
                <div class="stat-card-label">Ditolak</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan/Tahun</label>
                    <input type="month" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Laporan</label>
                    <select class="form-select">
                        <option selected>Semua Status</option>
                        <option>Draft</option>
                        <option>Diajukan</option>
                        <option>Disetujui</option>
                        <option>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Laporan</label>
                    <select class="form-select">
                        <option selected>Semua Jenis</option>
                        <option>Bulanan</option>
                        <option>Triwulanan</option>
                        <option>Tahunan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search me-2"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan List -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list me-2"></i>Daftar Laporan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan/Tahun</th>
                            <th>Jenis Laporan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>April 2026</td>
                            <td>Bulanan</td>
                            <td>15 Apr 2026</td>
                            <td><span class="badge bg-success">Disetujui</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Maret 2026</td>
                            <td>Bulanan</td>
                            <td>10 Apr 2026</td>
                            <td><span class="badge bg-success">Disetujui</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Februari 2026</td>
                            <td>Bulanan</td>
                            <td>05 Apr 2026</td>
                            <td><span class="badge bg-warning">Draft</span></td>
                            <td>-</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Januari 2026</td>
                            <td>Bulanan</td>
                            <td>28 Mar 2026</td>
                            <td><span class="badge bg-danger">Ditolak</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Buat Laporan -->
    <div class="modal fade" id="buatLaporan" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Laporan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Bulan</label>
                                <select class="form-select">
                                    <option selected>Pilih bulan</option>
                                    <option>Januari</option>
                                    <option>Februari</option>
                                    <option>Maret</option>
                                    <option>April</option>
                                    <option>Mei</option>
                                    <option>Juni</option>
                                    <option>Juli</option>
                                    <option>Agustus</option>
                                    <option>September</option>
                                    <option>Oktober</option>
                                    <option>November</option>
                                    <option>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun</label>
                                <select class="form-select">
                                    <option>2026</option>
                                    <option selected>2025</option>
                                    <option>2024</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Laporan</label>
                            <select class="form-select">
                                <option selected>Pilih jenis laporan</option>
                                <option>Bulanan</option>
                                <option>Triwulanan</option>
                                <option>Tahunan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Porsi Disajikan</label>
                            <input type="number" class="form-control" placeholder="Masukkan jumlah porsi">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Penerima Manfaat</label>
                            <input type="number" class="form-control" placeholder="Masukkan jumlah penerima">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Pelaporan</label>
                            <textarea class="form-control" rows="4" placeholder="Masukkan catatan atau temuan khusus..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lampiran File</label>
                            <input type="file" class="form-control" multiple>
                            <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning me-2">
                        <i class="fas fa-save me-1"></i> Simpan Draft
                    </button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Ajukan Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
