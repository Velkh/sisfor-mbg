@extends('layout.sppgLayout')

@section('title', 'Dashboard SPPG')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>
        <i class="fas fa-home"></i>
        Dashboard SPPG
    </h1>
</div>

@if (!$sppg)
    <div class="alert alert-warning">
        Data SPPG belum tersedia. Lengkapi profil terlebih dahulu.
        <a href="{{ route('sppg.profile') }}" class="alert-link">Klik di sini</a>.
    </div>
@else
    <div class="alert alert-info">
        <strong>{{ $sppg->nama_sppg }}</strong> -
        Selamat datang kembali. Berikut ringkasan aktivitas distribusi Anda.
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border-primary">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Total Laporan</small>
                <h4 class="mb-0">{{ $totalLaporan }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-success">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Status Negeri</small>
                <h4 class="mb-0 text-success">{{ $totalNegeri }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-warning">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Status Swasta</small>
                <h4 class="mb-0 text-warning">{{ $totalSwasta }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-info">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Total Penerima</small>
                <h4 class="mb-0 text-info">{{ number_format($totalPenerima) }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan Distribusi Terbaru</h5>
                <a href="{{ route('sppg.pelaporan') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Instansi</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporansTerbaru as $laporan)
                                @php
                                    $total = (int) $laporan->jml_siswa + (int) $laporan->jml_bumil + (int) $laporan->jml_busui + (int) $laporan->jml_balita;
                                @endphp
                                <tr>
                                    <td>{{ $laporan->nama_instansi }}</td>
                                    <td>{{ $laporan->tipe_instansi }}</td>
                                    <td>
                                        @if ($laporan->status === 'negeri')
                                            <span class="badge bg-success">Negeri</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Swasta</span>
                                        @endif
                                    </td>
                                    <td>{{ $total }}</td>
                                    <td>{{ optional($laporan->created_at)->format('d-m-Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data laporan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Status Kepatuhan SPPG</h5>
            </div>
            <div class="card-body">
                @php
                    $statusIkl = $sppg->status_ikl ?? null;
                    $statusSlhs = $sppg->status_slhs ?? null;
                    $nilaiIkl = (int) ($sppg->nilai_ikl ?? 0);

                    $punyaDokumenSlhs = !empty($sppg->foto_slhs);
                    $slhsSelesai = $statusSlhs === 'selesai';
                    $slhsMasihBerlaku = !empty($sppg->tgl_berakhir)
                        ? now()->lte(\Illuminate\Support\Carbon::parse($sppg->tgl_berakhir))
                        : false;

                    $punyaSlhsValid = $punyaDokumenSlhs && $slhsSelesai && $slhsMasihBerlaku;

                    // Hardcoded evaluasi sesuai aturan
                    if (($sppg->nilai_ikl ?? null) === null) {
                        $evaluasiSppg = null;
                    } elseif ($nilaiIkl < 80) {
                        $evaluasiSppg = 'Tidak Laik';
                    } elseif (! $punyaSlhsValid) {
                        $evaluasiSppg = 'Bersyarat';
                    } else {
                        $evaluasiSppg = 'Laik Higiene';
                    }
                @endphp

                <div class="mb-3">
                    <small class="text-muted d-block">Status IKL</small>
                    @if ($statusIkl === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($statusIkl === 'sudah_mengajukan')
                        <span class="badge bg-warning text-dark">Proses</span>
                    @elseif ($statusIkl)
                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $statusIkl)) }}</span>
                    @else
                        <span class="badge bg-light text-dark">Belum diisi</span>
                    @endif
                    <div class="small text-muted mt-1">Nilai IKL: {{ $sppg->nilai_ikl ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Status SLHS</small>
                    @if ($statusSlhs === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @elseif ($statusSlhs === 'sudah_mengajukan')
                        <span class="badge bg-warning text-dark">Proses</span>
                    @elseif ($statusSlhs)
                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $statusSlhs)) }}</span>
                    @else
                        <span class="badge bg-light text-dark">Belum diisi</span>
                    @endif
                </div>

                <hr>

                <div>
                    <small class="text-muted d-block">Evaluasi SPPG</small>
                    @if ($evaluasiSppg === 'Laik Higiene')
                        <span class="badge bg-success">Laik Higiene</span>
                    @elseif ($evaluasiSppg === 'Bersyarat')
                        <span class="badge bg-warning text-dark">Bersyarat</span>
                    @elseif ($evaluasiSppg === 'Tidak Laik')
                        <span class="badge bg-danger">Tidak Laik</span>
                    @else
                        <span class="badge bg-light text-dark">Belum dievaluasi</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection