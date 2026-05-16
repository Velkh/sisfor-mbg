@extends('layout.dinkes')

@section('title', 'Data Kelayakan')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

    <div class="container-fluid civic civic-fade">
        @php
            $total = $stats['total'] ?? 0;
            $memenuhiCount = ($stats['laik_higiene'] ?? 0) + ($stats['bersyarat'] ?? 0);
            $tidakCount = $stats['belum_layak'] ?? 0;
        @endphp

        <div class="civic-header">
            <div>
                <div class="civic-title">Data Kelayakan</div>
                <div class="civic-sub">Ringkasan status kelayakan unit usaha di wilayah Anda</div>
            </div>
        </div>

        <div class="stat-grid mb-4">
            <div class="stat-tile">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total Unit Usaha</div>
                </div>
            </div>

            <div class="stat-tile">
                <div class="stat-icon" style="color: #2e7d32; background: rgba(46, 125, 50, 0.12);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $memenuhiCount }}</div>
                    <div class="stat-label">Memenuhi</div>
                </div>
            </div>

            <div class="stat-tile">
                <div class="stat-icon" style="color: #c62828; background: rgba(198, 40, 40, 0.12);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $tidakCount }}</div>
                    <div class="stat-label">Tidak Memenuhi</div>
                </div>
            </div>
        </div>

        <div class="filter-card mb-4">
            <form method="GET" action="{{ url()->current() }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Cari unit usaha / pemilik"
                            value="{{ $filters['q'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <select name="status_ikl" class="form-select">
                            <option value="">Semua Status IKL</option>
                            <option value="belum_mengajukan" @selected(($filters['status_ikl'] ?? '') === 'belum_mengajukan')>Belum Mengajukan</option>
                            <option value="sudah_mengajukan" @selected(($filters['status_ikl'] ?? '') === 'sudah_mengajukan')>Sudah Mengajukan</option>
                            <option value="selesai" @selected(($filters['status_ikl'] ?? '') === 'selesai')>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="status_slhs" class="form-select">
                            <option value="">Semua Status SLHS</option>
                            <option value="belum_mengajukan" @selected(($filters['status_slhs'] ?? '') === 'belum_mengajukan')>Belum Mengajukan</option>
                            <option value="sudah_mengajukan" @selected(($filters['status_slhs'] ?? '') === 'sudah_mengajukan')>Sudah Mengajukan</option>
                            <option value="selesai" @selected(($filters['status_slhs'] ?? '') === 'selesai')>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-civic w-100" type="submit">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.data.export.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a>
                    <a href="{{ route('admin.data.export.excel', request()->query()) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </a>
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-dark">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="card-header border-0 bg-transparent">
                <h5 class="mb-0"><i class="fas fa-check-double me-2"></i>Daftar Kelayakan Unit Usaha</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive border rounded-3 overflow-hidden">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit Usaha</th>
                                <th>Kecamatan</th>
                                <th>IKL</th>
                                <th>SLHS</th>
                                <th>IPAL</th>
                                <th>Pengelolaan Sampah</th>
                                <th>Evaluasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $index => $item)
                                @php
                                    $laporan = $item->laporanSlhs;
                                    $nilaiIkl = (int) ($laporan?->nilai_ikl ?? 0);

                                    if ($nilaiIkl >= 80) {
                                        $evaluasiShort = 'Memenuhi';
                                        $evaluasiClassShort = 'success';
                                    } else {
                                        $evaluasiShort = 'Tidak Memenuhi';
                                        $evaluasiClassShort = 'danger';
                                    }

                                    $isIklLulus = $laporan
                                        && $laporan->status_ikl === 'selesai'
                                        && $laporan->hasil_ikl === 'memenuhi'
                                        && $nilaiIkl >= 80;

                                    $isLaikHigiene = $isIklLulus && $laporan && $laporan->status_slhs === 'selesai';
                                    $isBersyarat = $isIklLulus && $laporan && $laporan->status_slhs !== 'selesai';

                                    if ($isLaikHigiene) {
                                        $evaluasiText = 'Laik Higiene';
                                        $evaluasiClass = 'success';
                                    } elseif ($isBersyarat) {
                                        $evaluasiText = 'Bersyarat';
                                        $evaluasiClass = 'warning text-dark';
                                    } else {
                                        $evaluasiText = 'Belum Layak';
                                        $evaluasiClass = 'danger';
                                    }

                                    $detailPayload = [
                                        'nama_unit_usaha' => $item->nama_unit_usaha,
                                        'nama_pemilik' => $item->nama_pemilik,
                                        'jenis_usaha' => strtoupper($item->jenis_usaha ?? '-'),
                                        'kecamatan' => $item->kecamatan?->nama_kecamatan ?? '-',
                                        'puskesmas' => $item->puskesmas?->nama_puskesmas ?? '-',
                                        'status_ikl' => $laporan?->status_ikl ? ucfirst(str_replace('_', ' ', $laporan->status_ikl)) : '-',
                                        'nilai_ikl' => $laporan?->nilai_ikl ?? '-',
                                        'hasil_ikl' => $laporan?->hasil_ikl ? ucfirst(str_replace('_', ' ', $laporan->hasil_ikl)) : '-',
                                        'tanggal_ikl' => optional($laporan?->updated_at)->format('d M Y') ?? '-',
                                        'status_slhs' => $laporan?->status_slhs ? ucfirst(str_replace('_', ' ', $laporan->status_slhs)) : '-',
                                        'tgl_terbit_slhs' => optional($laporan?->tgl_terbit_slhs)->format('d M Y') ?? '-',
                                        'tgl_berakhir_slhs' => optional($laporan?->tgl_berakhir_slhs)->format('d M Y') ?? '-',
                                        'link_slhs' => $laporan?->link_slhs ?? null,
                                        'ketersediaan_ipal' => $laporan?->ketersediaan_ipal ? ucfirst(str_replace('_', ' ', $laporan->ketersediaan_ipal)) : '-',
                                        'jenis_ipal' => $laporan?->jenis_ipal ?? '-',
                                        'pengelolaan_sampah' => $laporan?->pengelolaan_sampah ? ucfirst(str_replace('_', ' ', $laporan->pengelolaan_sampah)) : '-',
                                        'jenis_pengelolaan' => $laporan?->jenis_pengelolaan ?? '-',
                                        'evaluasi_text' => $evaluasiText,
                                        'evaluasi_short' => $evaluasiShort,
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $items->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $item->nama_unit_usaha }}</strong>
                                        <br>
                                        <small class="text-muted">Pemilik: {{ $item->nama_pemilik ?? '-' }}</small>
                                        <br>
                                        <small class="text-muted">Jenis: {{ strtoupper($item->jenis_usaha ?? '-') }}</small>
                                    </td>
                                    <td>{{ $item->kecamatan?->nama_kecamatan ?? '-' }}</td>
                                    <td>
                                        <div>
                                            @if (($laporan?->status_ikl ?? null) === 'belum_mengajukan')
                                                <span class="badge bg-secondary">Belum Mengajukan</span>
                                            @elseif (($laporan?->status_ikl ?? null) === 'sudah_mengajukan')
                                                <span class="badge bg-warning text-dark">Sudah Mengajukan</span>
                                            @elseif (($laporan?->status_ikl ?? null) === 'selesai')
                                                <span class="badge bg-info">Selesai</span>
                                            @else
                                                <span class="badge bg-light text-dark">-</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            Nilai: {{ $laporan?->nilai_ikl ?? '-' }} |
                                            Hasil:
                                            @if (($laporan?->hasil_ikl ?? null) === 'memenuhi')
                                                Memenuhi
                                            @elseif (($laporan?->hasil_ikl ?? null) === 'tidak_memenuhi')
                                                Tidak Memenuhi
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if (($laporan?->status_slhs ?? null) === 'belum_mengajukan')
                                            <span class="badge bg-secondary">Belum Mengajukan</span>
                                        @elseif (($laporan?->status_slhs ?? null) === 'sudah_mengajukan')
                                            <span class="badge bg-warning text-dark">Sudah Mengajukan</span>
                                        @elseif (($laporan?->status_slhs ?? null) === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-light text-dark">-</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            Terbit: {{ optional($laporan?->tgl_terbit_slhs)->format('d M Y') ?? '-' }}
                                            <br>
                                            Berakhir: {{ optional($laporan?->tgl_berakhir_slhs)->format('d M Y') ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        <small class="text-muted d-block">Ketersediaan:</small>
                                        {{ $laporan?->ketersediaan_ipal ? ucfirst(str_replace('_', ' ', $laporan->ketersediaan_ipal)) : '-' }}
                                        <br>
                                        <small class="text-muted d-block">Jenis:</small>
                                        {{ $laporan?->jenis_ipal ?? '-' }}
                                    </td>

                                    <td>
                                        <small class="text-muted d-block">Pengelolaan:</small>
                                        {{ $laporan?->pengelolaan_sampah ? ucfirst(str_replace('_', ' ', $laporan->pengelolaan_sampah)) : '-' }}
                                        <br>
                                        <small class="text-muted d-block">Jenis:</small>
                                        {{ $laporan?->jenis_pengelolaan ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $evaluasiClassShort }}">{{ $evaluasiShort }}</span>
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailKelayakanModal"
                                            data-detail='@json($detailPayload)'
                                        >
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailKelayakanModal" tabindex="-1" aria-labelledby="detailKelayakanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailKelayakanModalLabel">
                        <i class="fas fa-file-alt me-2"></i>Detail IKL, SLHS, dan Sanitasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Nama Unit Usaha</small>
                            <strong id="detailNamaUnitUsaha">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Nama Pemilik</small>
                            <strong id="detailNamaPemilik">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Puskesmas</small>
                            <strong id="detailPuskesmas">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Jenis Usaha</small>
                            <strong id="detailJenisUsaha">-</strong>
                        </div>
                        <div class="col-md-8">
                            <small class="text-muted d-block">Evaluasi</small>
                            <span id="detailEvaluasi" class="badge bg-secondary">-</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Detail IKL</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tr><th style="width:40%;">Status IKL</th><td id="detailStatusIkl">-</td></tr>
                                        <tr><th>Nilai IKL</th><td id="detailNilaiIkl">-</td></tr>
                                        <tr><th>Hasil IKL</th><td id="detailHasilIkl">-</td></tr>
                                        <tr><th>Tanggal IKL</th><td id="detailTanggalIkl">-</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>Detail SLHS</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-3">
                                        <tr><th style="width:40%;">Status SLHS</th><td id="detailStatusSlhs">-</td></tr>
                                        <tr><th>Tgl Terbit</th><td id="detailTglTerbitSlhs">-</td></tr>
                                        <tr><th>Tgl Berakhir</th><td id="detailTglBerakhirSlhs">-</td></tr>
                                    </table>
                                    <div id="detailSlhsContainer" class="text-muted">Link SLHS belum tersedia.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-recycle me-2"></i>IPAL dan Pengelolaan Sampah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Ketersediaan IPAL</small>
                                            <strong id="detailKetersediaanIpal">-</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Jenis IPAL</small>
                                            <strong id="detailJenisIpal">-</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Pengelolaan Sampah</small>
                                            <strong id="detailPengelolaanSampah">-</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Jenis Pengelolaan</small>
                                            <strong id="detailJenisPengelolaan">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-detail').forEach(function (button) {
            button.addEventListener('click', function () {
                const detail = JSON.parse(this.dataset.detail || '{}');

                const setText = (id, value) => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = value ?? '-';
                    }
                };

                setText('detailNamaUnitUsaha', detail.nama_unit_usaha);
                setText('detailNamaPemilik', detail.nama_pemilik);
                setText('detailJenisUsaha', detail.jenis_usaha);
                setText('detailPuskesmas', detail.puskesmas);

                setText('detailStatusIkl', detail.status_ikl);
                setText('detailNilaiIkl', detail.nilai_ikl);
                setText('detailHasilIkl', detail.hasil_ikl);
                setText('detailTanggalIkl', detail.tanggal_ikl);

                setText('detailStatusSlhs', detail.status_slhs);
                setText('detailTglTerbitSlhs', detail.tgl_terbit_slhs);
                setText('detailTglBerakhirSlhs', detail.tgl_berakhir_slhs);

                setText('detailKetersediaanIpal', detail.ketersediaan_ipal);
                setText('detailJenisIpal', detail.jenis_ipal);
                setText('detailPengelolaanSampah', detail.pengelolaan_sampah);
                setText('detailJenisPengelolaan', detail.jenis_pengelolaan);

                const evaluasiEl = document.getElementById('detailEvaluasi');
                evaluasiEl.textContent = detail.evaluasi_short ?? detail.evaluasi_text ?? '-';
                evaluasiEl.className = 'badge bg-secondary';

                const ev = (detail.evaluasi_short || detail.evaluasi_text || '').toLowerCase();
                if (ev.includes('memenuhi') || ev.includes('laik')) {
                    evaluasiEl.className = 'badge bg-success';
                } else if (ev.includes('bersyarat')) {
                    evaluasiEl.className = 'badge bg-warning text-dark';
                } else {
                    evaluasiEl.className = 'badge bg-danger';
                }

                const slhsContainer = document.getElementById('detailSlhsContainer');
                slhsContainer.innerHTML = '';
                if (detail.link_slhs) {
                    slhsContainer.innerHTML = `
                        <a href="${detail.link_slhs}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-link me-1"></i>Buka Link SLHS
                        </a>
                    `;
                } else {
                    slhsContainer.innerHTML = '<span class="text-muted">Link SLHS belum tersedia.</span>';
                }
            });
        });
    </script>
@endsection