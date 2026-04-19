@extends('layout.dinkes')

@section('title', 'Rekap Laporan')

@section('content')
    <div class="container-fluid">
        <!-- Report Controls -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Buat Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Periode Awal</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Periode Akhir</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipe Laporan</label>
                        <select class="form-select">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Tampilkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-card-value">156</div>
                    <div class="stat-card-label">Total Laporan</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #4CAF50;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">142</div>
                    <div class="stat-card-label">Laporan Selesai</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #FF9800;">
                        <i class="fas fa-hourglass"></i>
                    </div>
                    <div class="stat-card-value">12</div>
                    <div class="stat-card-label">Laporan Proses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #F44336;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-card-value">2</div>
                    <div class="stat-card-label">Laporan Bermasalah</div>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Rincian Laporan</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download me-2"></i>Excel
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Puskesmas</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Penerima Manfaat</th>
                                <th>Total SPPG</th>
                                <th>Tanggal Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Puskesmas Kota Depok</td>
                                <td>April 2026</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>45</td>
                                <td>8</td>
                                <td>14 Apr 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Puskesmas Bojong</td>
                                <td>April 2026</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>32</td>
                                <td>6</td>
                                <td>13 Apr 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Puskesmas Sukatani</td>
                                <td>April 2026</td>
                                <td><span class="badge bg-warning text-dark">Proses</span></td>
                                <td>28</td>
                                <td>5</td>
                                <td>12 Apr 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Puskesmas Cilodong</td>
                                <td>March 2026</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td>52</td>
                                <td>10</td>
                                <td>01 Apr 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Puskesmas Cimanggis</td>
                                <td>March 2026</td>
                                <td><span class="badge bg-danger">Bermasalah</span></td>
                                <td>18</td>
                                <td>3</td>
                                <td>31 Mar 2026</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Download">
                                        <i class="fas fa-download"></i>
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
