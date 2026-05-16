@extends('layout.sppgLayout')

@section('title', 'Detail Unit Usaha Kecamatan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0"><i class="fas fa-eye me-2"></i>Detail Unit Usaha</h4>
            <small class="text-muted">Informasi unit usaha pada kecamatan Anda</small>
        </div>
        <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Data Unit Usaha</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <strong>Nama Unit</strong><br>
                    {{ $unit->nama_unit_usaha }}
                </div>
                <div class="col-md-4">
                    <strong>Jenis Usaha</strong><br>
                    {{ strtoupper($unit->jenis_usaha) }}
                </div>
                <div class="col-md-4">
                    <strong>Nama Pemilik</strong><br>
                    {{ $unit->nama_pemilik }}
                </div>
                <div class="col-md-12">
                    <strong>Alamat</strong><br>
                    {{ $unit->alamat }}
                </div>
                <div class="col-md-4">
                    <strong>Kecamatan</strong><br>
                    {{ $unit->kecamatan?->nama_kecamatan ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Kelurahan</strong><br>
                    {{ $unit->kelurahan?->nama_kelurahan ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Puskesmas</strong><br>
                    {{ $unit->puskesmas?->nama_puskesmas ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Jumlah Pegawai</strong><br>
                    {{ number_format((int) ($unit->jumlah_pegawai ?? 0), 0, ',', '.') }}
                </div>
                <div class="col-md-4">
                    <strong>Jumlah Penjamah Terlatih</strong><br>
                    {{ number_format((int) ($unit->jumlah_penjamah_terlatih ?? 0), 0, ',', '.') }}
                </div>
                <div class="col-md-4">
                    <strong>Status Aktif</strong><br>
                    @if ($unit->status_aktif)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Foto Unit Usaha</h5>
        </div>
        <div class="card-body">
            {{-- Pastikan nama relasinya sesuai dengan yang ada di Model UnitUsaha kamu (misal: fotos atau fotoUnits) --}}
            @if ($unit->fotos && $unit->fotos->count() > 0)
                <div class="row g-3">
                    @foreach ($unit->fotos as $foto)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="{{ asset('storage/' . $foto->foto_unit_usaha) }}" target="_blank" class="d-block text-center text-decoration-none">
                                <img 
                                    src="{{ asset('storage/' . $foto->foto_unit_usaha) }}" 
                                    alt="Foto Unit" 
                                    class="img-thumbnail shadow-sm rounded" 
                                    style="width: 100%; height: 150px; object-fit: cover;"
                                >
                                <small class="text-muted mt-1 d-block">Klik untuk perbesar</small>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">Belum ada foto unit usaha yang diunggah.</p>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Informasi SLHS</h5>
        </div>
        <div class="card-body">
            @if ($unit->laporanSlhs)
                <div class="row g-3">
                    <div class="col-md-4">
                        <strong>Status IKL</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_ikl ?? '-')) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Status SLHS</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->status_slhs ?? '-')) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Nilai IKL</strong><br>
                        {{ $unit->laporanSlhs->nilai_ikl ?? '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Hasil IKL</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->hasil_ikl ?? '-')) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Tgl Terbit SLHS</strong><br>
                        {{ $unit->laporanSlhs->tgl_terbit_slhs?->format('d-m-Y') ?? '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Tgl Berakhir SLHS</strong><br>
                        {{ $unit->laporanSlhs->tgl_berakhir_slhs?->format('d-m-Y') ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Link SLHS</strong><br>
                        {{ $unit->laporanSlhs->link_slhs ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Ketersediaan IPAL</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->ketersediaan_ipal ?? '-')) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Jenis IPAL</strong><br>
                        {{ $unit->laporanSlhs->jenis_ipal ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Pengelolaan Sampah</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $unit->laporanSlhs->pengelolaan_sampah ?? '-')) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Jenis Pengelolaan</strong><br>
                        {{ $unit->laporanSlhs->jenis_pengelolaan ?? '-' }}
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">Belum ada data laporan SLHS. Pengisian data ini dilakukan oleh admin dinkes.</p>
            @endif
        </div>
    </div>
</div>
@endsection