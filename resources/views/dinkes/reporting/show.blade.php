@extends('layout.dinkes')

@section('title', 'Detail Unit Usaha')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-eye me-2"></i>Detail Unit Usaha</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reporting.edit', $unit->id_unit_usaha) }}" class="btn btn-warning">
                <i class="fas fa-pen me-2"></i>Edit
            </a>
            <a href="{{ route('admin.reporting.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Data Unit Usaha</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Jenis Usaha</strong><br>{{ strtoupper($unit->jenis_usaha) }}</div>
                <div class="col-md-5"><strong>Nama Unit</strong><br>{{ $unit->nama_unit_usaha }}</div>
                <div class="col-md-4"><strong>Nama Pemilik</strong><br>{{ $unit->nama_pemilik }}</div>
                <div class="col-md-12"><strong>Alamat</strong><br>{{ $unit->alamat }}</div>
                <div class="col-md-2"><strong>Jumlah Pegawai</strong><br>{{ $unit->jumlah_pegawai }}</div>
                <div class="col-md-2"><strong>ID Kecamatan</strong><br>{{ $unit->id_kecamatan }}</div>
                <div class="col-md-2"><strong>ID Kelurahan</strong><br>{{ $unit->id_kelurahan }}</div>
                <div class="col-md-2"><strong>ID Puskesmas</strong><br>{{ $unit->id_puskesmas }}</div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Laporan SLHS</h5></div>
        <div class="card-body">
            @if ($unit->laporanSlhs)
                <div class="row g-3">
                    <div class="col-md-3"><strong>Status IKL</strong><br>{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_ikl ?? '-')) }}</div>
                    <div class="col-md-2"><strong>Nilai IKL</strong><br>{{ $unit->laporanSlhs->nilai_ikl ?? '-' }}</div>
                    <div class="col-md-3"><strong>Hasil IKL</strong><br>{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->hasil_ikl ?? '-')) }}</div>
                    <div class="col-md-3"><strong>Status SLHS</strong><br>{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_slhs ?? '-')) }}</div>
                    <div class="col-md-3"><strong>Tgl Terbit SLHS</strong><br>{{ optional($unit->laporanSlhs->tgl_terbit_slhs)->format('d-m-Y') ?? '-' }}</div>
                    <div class="col-md-3"><strong>Tgl Berakhir SLHS</strong><br>{{ optional($unit->laporanSlhs->tgl_berakhir_slhs)->format('d-m-Y') ?? '-' }}</div>
                    <div class="col-md-6"><strong>Link SLHS</strong><br>{{ $unit->laporanSlhs->link_slhs ?? '-' }}</div>
                    <div class="col-md-3"><strong>Ketersediaan IPAL</strong><br>{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->ketersediaan_ipal ?? '-')) }}</div>
                    <div class="col-md-3"><strong>Jenis IPAL</strong><br>{{ $unit->laporanSlhs->jenis_ipal ?? '-' }}</div>
                    <div class="col-md-3"><strong>Pengelolaan Sampah</strong><br>{{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->pengelolaan_sampah ?? '-')) }}</div>
                    <div class="col-md-3"><strong>Jenis Pengelolaan</strong><br>{{ $unit->laporanSlhs->jenis_pengelolaan ?? '-' }}</div>
                </div>
            @else
                <p class="text-muted mb-0">Belum ada data laporan SLHS.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Sasaran Manfaat</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Tipe Instansi</th>
                            <th>Nama Instansi</th>
                            <th>Status</th>
                            <th>Jumlah Siswa</th>
                            <th>Bumil</th>
                            <th>Busui</th>
                            <th>Balita</th>
                            <th>Jiwa</th>
                            <th>Detail Jangkauan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($unit->sasaranManfaat as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $s->kategori }}</td>
                                <td>{{ $s->tipe_instansi ?? '-' }}</td>
                                <td>{{ $s->nama_instansi ?? '-' }}</td>
                                <td>{{ $s->status ? ucfirst($s->status) : '-' }}</td>
                                <td>{{ $s->jumlah_siswa ?? 0 }}</td>
                                <td>{{ $s->jumlah_bumil ?? 0 }}</td>
                                <td>{{ $s->jumlah_busui ?? 0 }}</td>
                                <td>{{ $s->jumlah_balita ?? 0 }}</td>
                                <td>{{ $s->jumlah_jiwa ?? 0 }}</td>
                                <td>{{ $s->detail_jangkauan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">Belum ada sasaran manfaat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection