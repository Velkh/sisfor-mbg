@extends('layout.dinkes')

@section('title', 'Data Kelayakan')

@section('content')
    <div class="container-fluid">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-card-value">8</div>
                    <div class="stat-card-label">Total Kelayakan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #4CAF50;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-card-value">5</div>
                    <div class="stat-card-label">SPPG Layak IKL</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #2196F3;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-card-value">3</div>
                    <div class="stat-card-label">SPPG Ber SLHS</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #FF9800;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-card-value">0</div>
                    <div class="stat-card-label">Pending</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari SPPG...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option value="">Semua Kelayakan</option>
                            <option value="layak_ikl">Layak IKL</option>
                            <option value="ber_slhs">Ber SLHS</option>
                            <option value="lulus_ikl">Lulus IKL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Kelayakan -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-check-double me-2"></i>Daftar Kelayakan SPPG</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>SPPG</th>
                                <th>Puskesmas</th>
                                <th>Status Kelayakan</th>
                                <th>Tanggal Verifikasi</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>SPPG-001-2026</td>
                                <td>Puskesmas Kota Depok</td>
                                <td>
                                    <span class="badge bg-success">Layak IKL</span>
                                </td>
                                <td>14 Apr 2026</td>
                                <td><small>Semua persyaratan lengkap</small></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>SPPG-002-2026</td>
                                <td>Puskesmas Bojong</td>
                                <td>
                                    <span class="badge bg-info">Ber SLHS</span>
                                </td>
                                <td>13 Apr 2026</td>
                                <td><small>Memiliki Surat Layak Haji</small></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>SPPG-003-2026</td>
                                <td>Puskesmas Sukatani</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Proses</span>
                                </td>
                                <td>-</td>
                                <td><small>Verifikasi masih berlangsung</small></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>SPPG-004-2026</td>
                                <td>Puskesmas Cilodong</td>
                                <td>
                                    <span class="badge bg-success">Layak IKL</span>
                                </td>
                                <td>12 Apr 2026</td>
                                <td><small>Kelayakan ditingkatkan ke taraf nasional</small></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>SPPG-005-2026</td>
                                <td>Puskesmas Cimanggis</td>
                                <td>
                                    <span class="badge bg-danger">Tidak Layak</span>
                                </td>
                                <td>11 Apr 2026</td>
                                <td><small>Tidak memenuhi standar peraturan</small></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
