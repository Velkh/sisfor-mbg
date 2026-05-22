@extends('layout.sppgLayout')

@section('title', 'Rekap Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/kecamatan/kelayakan.css') }}">

    <div class="container-fluid civic civic-fade">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('kecamatan.sasaran.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Cari Unit Usaha / Puskesmas</label>
                            <input type="text" name="q" class="form-control" value="{{ $filters['q'] }}" placeholder="Cari...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori Sasaran</label>
                            <select name="kategori" class="form-select">
                                <option value="all" @selected($filters['kategori'] === 'all')>Semua</option>
                                <option value="Sekolah" @selected($filters['kategori'] === 'Sekolah')>Sekolah</option>
                                <option value="B3" @selected($filters['kategori'] === 'B3')>B3</option>
                                <option value="Umum" @selected($filters['kategori'] === 'Umum')>Umum</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search me-2"></i>Tampilkan
                            </button>
                            <a href="{{ route('kecamatan.sasaran.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Rekap Per Unit Usaha</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit Usaha</th>
                                <th>Jenis Usaha</th>
                                <th>Jumlah Penerima</th>
                                <th>Kelompok Penerima</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $index => $item)
                                @php
                                    $details = $item->sasaranManfaat->map(function ($row) use ($item) {
                                        $jumlah = (int) $row->jumlah_siswa
                                            + (int) $row->jumlah_bumil
                                            + (int) $row->jumlah_busui
                                            + (int) $row->jumlah_balita
                                            + (int) $row->jumlah_jiwa;

                                        return [
                                            'nama_kelompok_penerima' => $row->nama_instansi ?? '-',
                                            'kategori' => $row->kategori ?? '-',
                                            'tipe' => $row->tipe_instansi ?? '-',
                                            'status' => $row->status ? ucfirst((string) $row->status) : '-',
                                            'jumlah_penerima' => $jumlah,
                                            'kelurahan' => $item->kelurahan?->nama_kelurahan ?? '-',
                                            'kecamatan' => $item->kecamatan?->nama_kecamatan ?? '-',
                                            'puskesmas' => $item->puskesmas?->nama_puskesmas ?? '-',
                                        ];
                                    })->values();
                                @endphp
                                <tr>
                                    <td>{{ $reports->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $item->nama_unit_usaha }}</strong><br>
                                        <small class="text-muted">Kecamatan {{ $item->kecamatan?->nama_kecamatan ?? '-' }}</small>
                                    </td>
                                    <td>{{ strtoupper((string) $item->jenis_usaha) }}</td>
                                    <td>{{ number_format((int) $item->total_penerima) }}</td>
                                    <td>{{ $item->kelompok_penerima !== '' ? $item->kelompok_penerima : '-' }}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailDistribusiModal"
                                            data-unit="{{ $item->nama_unit_usaha }}"
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
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-list-alt me-2"></i>Detail Distribusi - <span id="modalUnitName">Unit Usaha</span>
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
                                    <th>Kategori</th>
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
                                    <td colspan="9" class="text-center text-muted">Belum ada detail.</td>
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
                const unitName = this.getAttribute('data-unit') || 'Unit Usaha';
                const details = JSON.parse(this.getAttribute('data-details') || '[]');

                document.getElementById('modalUnitName').textContent = unitName;

                const body = document.getElementById('detailDistribusiBody');
                body.innerHTML = '';

                if (!Array.isArray(details) || details.length === 0) {
                    body.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Belum ada detail distribusi.</td></tr>';
                    return;
                }

                details.forEach(function (row, index) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${row.nama_kelompok_penerima ?? '-'}</td>
                        <td>${row.kategori ?? '-'}</td>
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