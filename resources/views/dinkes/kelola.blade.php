@extends('layout.dinkes')

@section('title', 'Kelola SPPG')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar SPPG</h5>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah SPPG Baru
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari SPPG...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="">Semua Status</option>
                            <option value="layak">Layak</option>
                            <option value="proses">Proses</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="">Semua Puskesmas</option>
                            <option value="1">Puskesmas A</option>
                            <option value="2">Puskesmas B</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID SPPG</th>
                                <th>Puskesmas</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Terakhir Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>SPPG-001-2026</strong></td>
                                <td>Puskesmas Kota Depok</td>
                                <td><span class="badge bg-success">Layak</span></td>
                                <td>12 Apr 2026</td>
                                <td>14 Apr 2026</td>
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
                                <td><strong>SPPG-002-2026</strong></td>
                                <td>Puskesmas Bojong</td>
                                <td><span class="badge bg-warning text-dark">Proses</span></td>
                                <td>11 Apr 2026</td>
                                <td>13 Apr 2026</td>
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
                                <td><strong>SPPG-003-2026</strong></td>
                                <td>Puskesmas Sukatani</td>
                                <td><span class="badge bg-danger">Ditolak</span></td>
                                <td>10 Apr 2026</td>
                                <td>12 Apr 2026</td>
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
                                <td><strong>SPPG-004-2026</strong></td>
                                <td>Puskesmas Cilodong</td>
                                <td><span class="badge bg-success">Layak</span></td>
                                <td>09 Apr 2026</td>
                                <td>11 Apr 2026</td>
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

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
