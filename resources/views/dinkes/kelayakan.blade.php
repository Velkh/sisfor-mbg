@extends('layout.dinkes')

@section('title', 'Data Kelayakan')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-card-value">{{ $stats['total'] }}</div>
                    <div class="stat-card-label">Total SPPG</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #F44336;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-card-value">{{ $stats['belum_layak'] ?? 0 }}</div>
                    <div class="stat-card-label">Belum Layak</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #FF9800;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-card-value">{{ $stats['bersyarat'] ?? 0 }}</div>
                    <div class="stat-card-label">Bersyarat</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-card-icon" style="color: #4CAF50;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">{{ $stats['laik_higiene'] ?? 0 }}</div>
                    <div class="stat-card-label">Laik Higiene</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.data') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input
                                type="text"
                                name="q"
                                class="form-control"
                                placeholder="Cari SPPG"
                                value="{{ $filters['q'] }}"
                            >
                        </div>

                        <div class="col-md-2">
                            <select name="evaluasi" class="form-select">
                                <option value="all" @selected(($filters['evaluasi'] ?? 'all') === 'all')>Semua Evaluasi</option>
                                <option value="belum_layak" @selected(($filters['evaluasi'] ?? '') === 'belum_layak')>Belum Layak</option>
                                <option value="bersyarat" @selected(($filters['evaluasi'] ?? '') === 'bersyarat')>Bersyarat</option>
                                <option value="laik_higiene" @selected(($filters['evaluasi'] ?? '') === 'laik_higiene')>Laik Higiene</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status_ikl" class="form-select">
                                <option value="">Semua Status IKL</option>
                                <option value="belum_mengajukan" @selected(($filters['status_ikl'] ?? '') === 'belum_mengajukan')>Belum Mengajukan</option>
                                <option value="sudah_mengajukan" @selected(($filters['status_ikl'] ?? '') === 'sudah_mengajukan')>Sudah Mengajukan</option>
                                <option value="selesai" @selected(($filters['status_ikl'] ?? '') === 'selesai')>Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status_slhs" class="form-select">
                                <option value="">Semua Status SLHS</option>
                                <option value="belum_mengajukan" @selected(($filters['status_slhs'] ?? '') === 'belum_mengajukan')>Belum Mengajukan</option>
                                <option value="sudah_mengajukan" @selected(($filters['status_slhs'] ?? '') === 'sudah_mengajukan')>Sudah Mengajukan</option>
                                <option value="selesai" @selected(($filters['status_slhs'] ?? '') === 'selesai')>Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <input type="date" name="dari" class="form-control" value="{{ $filters['dari'] }}">
                        </div>

                        <div class="col-md-1">
                            <input type="date" name="sampai" class="form-control" value="{{ $filters['sampai'] }}">
                        </div>

                        <div class="col-md-1 d-flex gap-2">
                            <button class="btn btn-outline-secondary w-100" type="submit">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('admin.data.export.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </a>
                        <a href="{{ route('admin.data') }}" class="btn btn-sm btn-outline-dark">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-check-double me-2"></i>Daftar Kelayakan SPPG</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>SPPG</th>
                                <th>Puskesmas</th>
                                <th>IKL</th>
                                <th>SLHS</th>
                                <th>Tanggal IKL</th>
                                <th>Evaluasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $index => $item)
                                @php
                                    $isIklLulus = $item->status_ikl === 'selesai'
                                        && $item->hasil_ikl === 'memenuhi'
                                        && (int) ($item->nilai_ikl ?? 0) >= 80;

                                    $isLaikHigiene = $isIklLulus && $item->status_slhs === 'selesai';
                                    $isBersyarat = $isIklLulus && $item->status_slhs !== 'selesai';

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
                                        'nama_sppg' => $item->nama_sppg,
                                        'nama_mitra' => $item->nama_mitra,
                                        'puskesmas' => $item->puskesmas?->nama_puskesmas ?? '-',
                                        'status_ikl' => $item->status_ikl ? ucfirst(str_replace('_', ' ', $item->status_ikl)) : '-',
                                        'nilai_ikl' => $item->nilai_ikl ?? '-',
                                        'hasil_ikl' => $item->hasil_ikl ? ucfirst(str_replace('_', ' ', $item->hasil_ikl)) : '-',
                                        'tanggal_ikl' => $item->tanggal_ikl?->format('d M Y') ?? '-',
                                        'status_slhs' => $item->status_slhs ? ucfirst(str_replace('_', ' ', $item->status_slhs)) : '-',
                                        'tgl_berlaku' => $item->tgl_berlaku?->format('d M Y') ?? '-',
                                        'tgl_berakhir' => $item->tgl_berakhir?->format('d M Y') ?? '-',
                                        'foto_slhs_path' => $item->foto_slhs ? asset('storage/' . $item->foto_slhs) : null,
                                        'foto_slhs_name' => $item->foto_slhs,
                                        'evaluasi_text' => $evaluasiText,
                                        'foto_sppg' => $item->fotoSppg
                                            ->map(fn ($foto) => asset('storage/' . $foto->foto_sppg))
                                            ->values()
                                            ->all(),
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $items->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $item->nama_sppg }}</strong>
                                        <br>
                                        <small class="text-muted">Mitra: {{ $item->nama_mitra }}</small>
                                    </td>
                                    <td>{{ $item->puskesmas?->nama_puskesmas ?? '-' }}</td>
                                    <td>
                                        <div>
                                            @if ($item->status_ikl === 'belum_mengajukan')
                                                <span class="badge bg-secondary">Belum Mengajukan</span>
                                            @elseif ($item->status_ikl === 'sudah_mengajukan')
                                                <span class="badge bg-warning text-dark">Sudah Mengajukan</span>
                                            @elseif ($item->status_ikl === 'selesai')
                                                <span class="badge bg-info">Selesai</span>
                                            @else
                                                <span class="badge bg-light text-dark">-</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            Nilai: {{ $item->nilai_ikl ?? '-' }} |
                                            Hasil:
                                            @if ($item->hasil_ikl === 'memenuhi')
                                                Memenuhi
                                            @elseif ($item->hasil_ikl === 'tidak_memenuhi')
                                                Tidak Memenuhi
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if ($item->status_slhs === 'belum_mengajukan')
                                            <span class="badge bg-secondary">Belum Mengajukan</span>
                                        @elseif ($item->status_slhs === 'sudah_mengajukan')
                                            <span class="badge bg-warning text-dark">Sudah Mengajukan</span>
                                        @elseif ($item->status_slhs === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-light text-dark">-</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            Berlaku: {{ $item->tgl_berlaku?->format('d M Y') ?? '-' }}
                                            s/d {{ $item->tgl_berakhir?->format('d M Y') ?? '-' }}
                                        </small>
                                    </td>
                                    <td>{{ $item->tanggal_ikl?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $evaluasiClass }}">{{ $evaluasiText }}</span>
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
                                    <td colspan="8" class="text-center text-muted">Data tidak ditemukan.</td>
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
                        <i class="fas fa-file-alt me-2"></i>Detail IKL & SLHS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Nama SPPG</small>
                            <strong id="detailNamaSppg">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Mitra</small>
                            <strong id="detailMitra">-</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Puskesmas</small>
                            <strong id="detailPuskesmas">-</strong>
                        </div>
                        <div class="col-md-12">
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
                                    <small class="text-muted d-block mt-2">
                                        Foto IKL khusus belum tersedia pada struktur tabel saat ini.
                                    </small>
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
                                        <tr><th>Tanggal Berlaku</th><td id="detailTglBerlaku">-</td></tr>
                                        <tr><th>Tanggal Berakhir</th><td id="detailTglBerakhir">-</td></tr>
                                    </table>

                                    <div id="detailSlhsContainer" class="text-muted">File SLHS belum tersedia.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-images me-2"></i>Galeri Foto SPPG</h6>
                                </div>
                                <div class="card-body">
                                    <div id="detailFotoSppgContainer" class="row g-3">
                                        <p class="text-muted mb-0">Belum ada foto SPPG.</p>
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

                setText('detailNamaSppg', detail.nama_sppg);
                setText('detailMitra', detail.nama_mitra);
                setText('detailPuskesmas', detail.puskesmas);
                setText('detailStatusIkl', detail.status_ikl);
                setText('detailNilaiIkl', detail.nilai_ikl);
                setText('detailHasilIkl', detail.hasil_ikl);
                setText('detailTanggalIkl', detail.tanggal_ikl);
                setText('detailStatusSlhs', detail.status_slhs);
                setText('detailTglBerlaku', detail.tgl_berlaku);
                setText('detailTglBerakhir', detail.tgl_berakhir);

                const evaluasiEl = document.getElementById('detailEvaluasi');
                evaluasiEl.textContent = detail.evaluasi_text ?? '-';
                evaluasiEl.className = 'badge bg-secondary';

                const ev = (detail.evaluasi_text || '').toLowerCase();
                if (ev === 'laik higiene') {
                    evaluasiEl.className = 'badge bg-success';
                } else if (ev === 'bersyarat') {
                    evaluasiEl.className = 'badge bg-warning text-dark';
                } else if (ev === 'belum layak') {
                    evaluasiEl.className = 'badge bg-danger';
                }

                const slhsContainer = document.getElementById('detailSlhsContainer');
                slhsContainer.innerHTML = '';
                if (detail.foto_slhs_path) {
                    const isPdf = (detail.foto_slhs_name || '').toLowerCase().endsWith('.pdf');
                    if (isPdf) {
                        slhsContainer.innerHTML = `
                            <a href="${detail.foto_slhs_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-pdf me-1"></i>Lihat File SLHS
                            </a>
                        `;
                    } else {
                        slhsContainer.innerHTML = `
                            <a href="${detail.foto_slhs_path}" target="_blank">
                                <img src="${detail.foto_slhs_path}" class="img-fluid rounded border" alt="Foto SLHS">
                            </a>
                        `;
                    }
                } else {
                    slhsContainer.innerHTML = '<span class="text-muted">File SLHS belum tersedia.</span>';
                }

                const fotoContainer = document.getElementById('detailFotoSppgContainer');
                fotoContainer.innerHTML = '';
                const fotoList = Array.isArray(detail.foto_sppg) ? detail.foto_sppg : [];
                if (fotoList.length === 0) {
                    fotoContainer.innerHTML = '<p class="text-muted mb-0">Belum ada foto SPPG.</p>';
                } else {
                    fotoList.forEach(function (url) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <a href="${url}" target="_blank">
                                <img src="${url}" class="img-fluid rounded border" alt="Foto SPPG" style="height:160px;width:100%;object-fit:cover;">
                            </a>
                        `;
                        fotoContainer.appendChild(col);
                    });
                }
            });
        });
    </script>
@endsection