@extends('layout.sppgLayout')

@section('title', 'Dashboard Admin Kecamatan')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="{{ asset('css/kecamatan/kelayakan.css') }}">


<style>
    .card-summary {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        border: none;
        border-radius: 12px;
    }
    .card-summary:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>

<div class="container-fluid py-4 civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Dashboard Kecamatan</h3>
            <p class="text-muted mb-0">Ringkasan status kelayakan IKL dan penerima manfaat.</p>
        </div>
        <div>
            <span class="badge bg-light text-dark border p-2">
                <i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-summary shadow-sm h-100 bg-white border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">Total Unit Usaha</p>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalUnitUsaha) }}</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-summary shadow-sm h-100 bg-white border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">IKL Memenuhi</p>
                            <h2 class="mb-0 fw-bold text-success">{{ number_format($totalLulusIkl) }}</h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-summary shadow-sm h-100 bg-white border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">Memiliki SLHS</p>
                            <h2 class="mb-0 fw-bold text-warning">{{ number_format($totalMemilikiSlhs) }}</h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-summary shadow-sm h-100 bg-white border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">Penerima Manfaat</p>
                            <h2 class="mb-0 fw-bold text-info">{{ number_format($totalPenerima) }}</h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart + SLHS -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100" style="min-height: 320px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i>Rasio Kelayakan IKL</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="width: 65%;">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100" style="min-height: 320px;">
                <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>SLHS Hampir Kadaluarsa</h5>
                    <span class="badge bg-danger">{{ $slhsJatuhTempo->count() }} Terdekat</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Unit Usaha</th>
                                    <th>Tgl Berakhir</th>
                                    <th class="text-end pe-4">Sisa Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slhsJatuhTempo as $item)
                                    @php
                                        $tglBerakhir = \Carbon\Carbon::parse($item->laporanSlhs->tgl_berakhir_slhs);
                                        $sisaHari = \Carbon\Carbon::now()->diffInDays($tglBerakhir, false);
                                    @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->nama_unit_usaha }}</td>
                                    <td>{{ $tglBerakhir->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        @if($sisaHari <= 30)
                                            <span class="badge bg-danger">{{ floor($sisaHari) }} Hari Lagi</span>
                                        @else
                                            <span class="badge bg-secondary">{{ floor($sisaHari) }} Hari</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tidak ada data SLHS yang hampir kadaluarsa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Unit Usaha -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-clock text-primary me-2"></i>Data Unit Usaha Terbaru</h5>
                    <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Unit Usaha</th>
                                    <th>Pemilik</th>
                                    <th>Jenis Usaha</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUnitUsaha as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->nama_unit_usaha }}</td>
                                    <td>{{ $item->nama_pemilik ?? '-' }}</td>
                                    <td><span class="badge bg-primary">{{ strtoupper($item->jenis_usaha ?? '-') }}</span></td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data unit usaha.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const totalSemua = {{ $totalUnitUsaha }};
    const totalMemenuhi = {{ $totalLulusIkl }};
    const totalTidakMemenuhi = totalSemua - totalMemenuhi;

    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Memenuhi (IKL ≥ 80)', 'Tidak Memenuhi (IKL < 80)'],
            datasets: [{
                data: [totalMemenuhi, totalTidakMemenuhi],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
});
</script>
@endsection