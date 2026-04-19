@extends('layout.sppgLayout')

@section('title', 'Inspeksi')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-stethoscope"></i>
            Data Inspeksi
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahInspeksi">
            <i class="fas fa-plus me-2"></i> Tambah Inspeksi
        </button>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Lokasi</label>
                    <input type="text" class="form-control" placeholder="Cari lokasi...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option selected>Semua Status</option>
                        <option>Selesai</option>
                        <option>Proses</option>
                        <option>Belum Dimulai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list me-2"></i>Daftar Inspeksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Inspeksi</th>
                            <th>Lokasi</th>
                            <th>Inspektur</th>
                            <th>Status</th>
                            <th>Hasil</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>15 Apr 2026</td>
                            <td>SPPG Pusat - Depok</td>
                            <td>Budi Santoso</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td>
                                <span class="badge bg-success">Layak</span>
                            </td>
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
                            <td>2</td>
                            <td>14 Apr 2026</td>
                            <td>SPPG Cabang - Cileungsi</td>
                            <td>Siti Nurhaliza</td>
                            <td><span class="badge bg-warning">Proses</span></td>
                            <td>
                                <span class="badge bg-secondary">Belum Selesai</span>
                            </td>
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
                            <td>3</td>
                            <td>12 Apr 2026</td>
                            <td>SPPG Satelit - Gunung Putri</td>
                            <td>Ahmad Wijaya</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td>
                                <span class="badge bg-success">Layak</span>
                            </td>
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
                            <td>10 Apr 2026</td>
                            <td>SPPG Satelit 2 - Limusnunggal</td>
                            <td>Rina Hermawan</td>
                            <td><span class="badge bg-danger">Ditolak</span></td>
                            <td>
                                <span class="badge bg-danger">Tidak Layak</span>
                            </td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Inspeksi -->
    <div class="modal fade" id="tambahInspeksi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Inspeksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Lokasi SPPG</label>
                            <input type="text" class="form-control" placeholder="Masukkan lokasi SPPG">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Inspeksi</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Inspektur</label>
                            <input type="text" class="form-control" placeholder="Masukkan nama inspektur">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hasil Inspeksi</label>
                            <select class="form-select">
                                <option selected>Pilih hasil inspeksi</option>
                                <option>Layak</option>
                                <option>Tidak Layak</option>
                                <option>Perlu Perbaikan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" rows="3" placeholder="Masukkan catatan inspeksi"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
