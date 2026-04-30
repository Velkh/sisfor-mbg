@extends('layout.dinkes')

@section('title', 'Rekap Laporan')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari SPPG / Puskesmas</label>
                            <input type="text" name="q" class="form-control" value="{{ $filters['q'] }}" placeholder="Cari...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Periode Awal</label>
                            <input type="date" name="dari" class="form-control" value="{{ $filters['dari'] }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Periode Akhir</label>
                            <input type="date" name="sampai" class="form-control" value="{{ $filters['sampai'] }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kelompok Penerima</label>
                            <select name="kategori" class="form-select">
                                <option value="all" @selected($filters['kategori'] === 'all')>Semua</option>
                                <option value="Satuan Pendidikan" @selected($filters['kategori'] === 'Satuan Pendidikan')>Satuan Pendidikan</option>
                                <option value="Kelompok B3" @selected($filters['kategori'] === 'Kelompok B3')>Kelompok B3</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search me-2"></i>Tampilkan
                            </button>
                            <a href="{{ route('admin.laporan') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Rekap Per SPPG</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama SPPG</th>
                                <th>Total Kapasitas</th>
                                <th>Jumlah Penerima</th>
                                <th>Kelompok Penerima</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $index => $item)
                                @php
                                    $details = $item->laporanPenerimas->map(function ($laporan) use ($item) {
                                        $jumlah = (int) $laporan->jml_siswa
                                            + (int) $laporan->jml_bumil
                                            + (int) $laporan->jml_busui
                                            + (int) $laporan->jml_balita;

                                        return [
                                            'nama_kelompok_penerima' => $laporan->nama_instansi,
                                            'kategori' => $laporan->kategori,
                                            'tipe' => $laporan->tipe_instansi,
                                            'status' => ucfirst((string) $laporan->status),
                                            'jumlah_penerima' => $jumlah,
                                            'kelurahan' => $laporan->kelurahan?->nama_kelurahan ?? '-',
                                            'kecamatan' => $laporan->kecamatan?->nama_kecamatan ?? '-',
                                            'puskesmas' => $laporan->puskesmas?->nama_puskesmas ?? ($item->puskesmas?->nama_puskesmas ?? '-'),
                                        ];
                                    })->values();
                                @endphp
                                <tr>
                                    <td>{{ $reports->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $item->nama_sppg }}</strong><br>
                                        <small class="text-muted">{{ $item->puskesmas?->nama_puskesmas ?? '-' }}</small>
                                    </td>
                                    <td>{{ number_format((int) $item->total_kapasitas) }}</td>
                                    <td>{{ number_format((int) $item->total_penerima) }}</td>
                                    <td>{{ $item->kelompok_penerima !== '' ? $item->kelompok_penerima : '-' }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailDistribusiModal"
                                            data-sppg="{{ $item->nama_sppg }}"
                                            data-details='@json($details)'
                                        >
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Data laporan belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailDistribusiModal" tabindex="-1" aria-labelledby="detailDistribusiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-list-alt me-2"></i>Detail Distribusi - <span id="modalSppgName">SPPG</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok Penerima</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Jumlah Penerima</th>
                                    <th>Kelurahan</th>
                                    <th>Kecamatan</th>
                                    <th>Puskesmas</th>
                                </tr>
                            </thead>
                            <tbody id="detailDistribusiBody">
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada detail.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-detail').forEach(function (button) {
            button.addEventListener('click', function () {
                const sppgName = this.getAttribute('data-sppg') || 'SPPG';
                const details = JSON.parse(this.getAttribute('data-details') || '[]');

                document.getElementById('modalSppgName').textContent = sppgName;

                const body = document.getElementById('detailDistribusiBody');
                body.innerHTML = '';

                if (!Array.isArray(details) || details.length === 0) {
                    body.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Belum ada detail distribusi.</td></tr>';
                    return;
                }

                details.forEach(function (row, index) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${row.nama_kelompok_penerima ?? '-'}</td>
                        <td>${row.tipe ?? '-'}</td>
                        <td>${row.status ?? '-'}</td>
                        <td>${row.jumlah_penerima ?? 0}</td>
                        <td>${row.kelurahan ?? '-'}</td>
                        <td>${row.kecamatan ?? '-'}</td>
                        <td>${row.puskesmas ?? '-'}</td>
                    `;
                    body.appendChild(tr);
                });
            });
        });
    </script>
@endsection