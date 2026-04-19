@extends('layout.sppgLayout')

@section('title', 'Surat Laik')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-certificate"></i>
            Surat Laik Usaha
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajukanPermohonan">
            <i class="fas fa-plus me-2"></i> Ajukan Permohonan
        </button>
    </div>

    <!-- Info Box -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Informasi:</strong> Surat Laik Usaha SPPG adalah sertifikat yang menunjukkan bahwa SPPG Anda telah memenuhi standar operasional yang ditetapkan.
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card text-center">
                <div class="stat-card-icon">
                    <i class="fas fa-file-check"></i>
                </div>
                <div class="stat-card-value">5</div>
                <div class="stat-card-label">Surat Aktif</div>
            </div>
        </div>
    </div>

    <!-- Surat Laik List -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list me-2"></i>Daftar Surat Laik</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat</th>
                            <th>Tanggal Terbit</th>
                            <th>Berlaku Hingga</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SLHS-SPPG-2026-001</td>
                            <td>15 Apr 2026</td>
                            <td>14 Apr 2027</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Perpanjang">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>SLHS-SPPG-2025-001</td>
                            <td>20 Mar 2025</td>
                            <td>19 Mar 2026</td>
                            <td><span class="badge bg-warning">Akan Kadaluarsa</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Perpanjang">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>SLHS-SPPG-2024-002</td>
                            <td>10 Feb 2024</td>
                            <td>09 Feb 2025</td>
                            <td><span class="badge bg-danger">Kadaluarsa</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Perpanjang">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>SLHS-SPPG-2024-001</td>
                            <td>15 Jan 2024</td>
                            <td>14 Jan 2025</td>
                            <td><span class="badge bg-danger">Kadaluarsa</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Perpanjang">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Permohonan Status -->
    <div class="card mt-4">
        <div class="card-header">
            <h5><i class="fas fa-hourglass-half me-2"></i>Status Permohonan Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Nomor Permohonan</p>
                    <p style="font-weight: 600;">PRM-SPPG-2026-005</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Status</p>
                    <p style="font-weight: 600;"><span class="badge bg-warning">Sedang Diproses</span></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Tanggal Pengajuan</p>
                    <p style="font-weight: 600;">10 Apr 2026</p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Target Selesai</p>
                    <p style="font-weight: 600;">20 Apr 2026</p>
                </div>
            </div>
            <hr>
            <p class="text-muted mb-1">Keterangan</p>
            <p>Permohonan Surat Laik Usaha sedang dalam tahap verifikasi dokumen. Silakan menunggu berita selanjutnya.</p>
        </div>
    </div>

    <!-- Modal Ajukan Permohonan -->
    <div class="modal fade" id="ajukanPermohonan" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Permohonan Surat Laik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Tujuan Permohonan</label>
                            <select class="form-select">
                                <option selected>Pilih tujuan</option>
                                <option>Permohonan Baru</option>
                                <option>Perpanjangan</option>
                                <option>Perubahan Data</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control" multiple>
                            <small class="text-muted">Format: PDF, DOC, DOCX (Maks 5MB per file)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan Permohonan</label>
                            <textarea class="form-control" rows="4" placeholder="Jelaskan alasan permohonan..."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="setujuShyarat">
                            <label class="form-check-label" for="setujuShyarat">
                                Saya telah membaca dan menyetujui syarat dan ketentuan
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Ajukan Permohonan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
