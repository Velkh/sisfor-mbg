@extends('layout.dinkes')

@section('title', 'Detail Unit Usaha')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

<div class="container-fluid civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="fas fa-eye me-2 text-primary"></i>Detail Unit Usaha</h4>
            <p class="text-muted mb-0 small">Informasi lengkap terkait data unit usaha dan kelayakannya.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reporting.edit', $unit->id_unit_usaha) }}" class="btn btn-warning shadow-sm">
                <i class="fas fa-pen me-2"></i>Edit Data
            </a>
            <a href="{{ route('admin.reporting.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-building me-2"></i>Data Unit Usaha</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-muted small">Jenis Usaha</div>
                    <div class="fw-semibold text-uppercase">{{ $unit->jenis_usaha }}</div>
                </div>
                <div class="col-md-5">
                    <div class="text-muted small">Nama Unit</div>
                    <div class="fw-semibold">{{ $unit->nama_unit_usaha }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Nama Pemilik</div>
                    <div class="fw-semibold">{{ $unit->nama_pemilik }}</div>
                </div>

                <div class="col-md-12">
                    <div class="text-muted small">Alamat</div>
                    <div class="fw-semibold">{{ $unit->alamat }}</div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Jumlah Pegawai</div>
                    <div class="fw-semibold">{{ $unit->jumlah_pegawai }} Orang</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Penjamah Terlatih</div>
                    <div class="fw-semibold">{{ $unit->jumlah_penjamah_terlatih }} Orang</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Kecamatan</div>
                    <div class="fw-semibold">{{ $unit->kecamatan?->nama_kecamatan ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Kelurahan</div>
                    <div class="fw-semibold">{{ $unit->kelurahan?->nama_kelurahan ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-images me-2"></i>Foto Unit Usaha</h5>
        </div>
        <div class="card-body">
            @if ($unit->fotos && $unit->fotos->count() > 0)
                <div class="row g-3">
                    @foreach ($unit->fotos as $foto)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ asset('storage/' . $foto->foto_unit_usaha) }}" target="_blank" class="d-block text-center text-decoration-none">
                                <img
                                    src="{{ asset('storage/' . $foto->foto_unit_usaha) }}"
                                    alt="Foto Unit"
                                    class="img-thumbnail shadow-sm rounded-3 border-0"
                                    style="width: 100%; height: 150px; object-fit: cover;"
                                >
                                <small class="text-muted mt-2 d-block"><i class="fas fa-search-plus me-1"></i>Perbesar</small>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 bg-light rounded-3">
                    <i class="fas fa-image fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted mb-0">Belum ada foto unit usaha yang diunggah.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-clipboard-check me-2"></i>Laporan Kelayakan Unit</h5>
        </div>
        <div class="card-body">
            @if ($unit->laporanSlhs)
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-muted small">Status IKL</div>
                        <div class="fw-semibold">
                            @if($unit->laporanSlhs->status_ikl === 'selesai')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah IKL</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_ikl ?? '-')) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Nilai IKL</div>
                        <div class="fw-semibold fs-5">{{ $unit->laporanSlhs->nilai_ikl ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Hasil IKL</div>
                        <div class="fw-semibold">
                            @if(strtolower($unit->laporanSlhs->hasil_ikl) == 'memenuhi syarat' || strtolower($unit->laporanSlhs->hasil_ikl) == 'ms')
                                <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>MS</span>
                            @elseif(strtolower($unit->laporanSlhs->hasil_ikl) == 'tidak memenuhi syarat' || strtolower($unit->laporanSlhs->hasil_ikl) == 'tms')
                                <span class="text-danger fw-bold"><i class="fas fa-times-circle me-1"></i>TMS</span>
                            @else
                                {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->hasil_ikl ?? '-')) }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Status SLHS</div>
                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_slhs ?? '-')) }}</div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">Tgl Terbit SLHS</div>
                        <div class="fw-semibold">{{ optional($unit->laporanSlhs->tgl_terbit_slhs)->format('d F Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Tgl Berakhir SLHS</div>
                        <div class="fw-semibold">{{ optional($unit->laporanSlhs->tgl_berakhir_slhs)->format('d F Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Link SLHS</div>
                        <div class="fw-semibold">
                            @if($unit->laporanSlhs->link_slhs)
                                <a href="{{ $unit->laporanSlhs->link_slhs }}" target="_blank" class="text-decoration-none btn btn-sm btn-outline-primary mt-1">Buka Tautan <i class="fas fa-external-link-alt ms-1"></i></a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">Ketersediaan IPAL</div>
                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->ketersediaan_ipal ?? '-')) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Jenis IPAL</div>
                        <div class="fw-semibold">{{ $unit->laporanSlhs->jenis_ipal ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Pengelolaan Sampah</div>
                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->pengelolaan_sampah ?? '-')) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Jenis Pengelolaan</div>
                        <div class="fw-semibold">{{ $unit->laporanSlhs->jenis_pengelolaan ?? '-' }}</div>
                    </div>
                </div>
            @else
                <div class="text-center py-4 bg-light rounded-3">
                    <i class="fas fa-file-excel fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted mb-0">Belum ada data laporan SLHS.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-users me-2"></i>Sasaran Manfaat</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Kategori</th>
                            <th>Tipe Instansi</th>
                            <th>Nama Instansi</th>
                            <th>Status</th>
                            <th class="text-center">Siswa</th>
                            <th class="text-center">Bumil</th>
                            <th class="text-center">Busui</th>
                            <th class="text-center">Balita</th>
                            <th class="text-center">Jiwa</th>
                            <th class="pe-4">Detail Jangkauan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($unit->sasaranManfaat as $i => $s)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $s->kategori }}</span></td>
                                <td>{{ $s->tipe_instansi ?? '-' }}</td>
                                <td class="fw-semibold">{{ $s->nama_instansi ?? '-' }}</td>
                                <td>{{ $s->status ? ucfirst($s->status) : '-' }}</td>
                                <td class="text-center">{{ $s->jumlah_siswa ?? 0 }}</td>
                                <td class="text-center">{{ $s->jumlah_bumil ?? 0 }}</td>
                                <td class="text-center">{{ $s->jumlah_busui ?? 0 }}</td>
                                <td class="text-center">{{ $s->jumlah_balita ?? 0 }}</td>
                                <td class="text-center">{{ $s->jumlah_jiwa ?? 0 }}</td>
                                <td class="pe-4 text-muted small">{{ $s->detail_jangkauan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-2x mb-3 opacity-25 d-block"></i>
                                    Belum ada data sasaran manfaat yang terinput.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection