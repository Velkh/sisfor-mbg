@extends('layout.dinkes')

@section('title', 'Dashboard Admin Dinkes')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

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
    .table-responsive {
        border-radius: 0 0 12px 12px;
    }
</style>

<div class="container-fluid py-4 civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold">Dashboard Kepatuhan IKL dan SLHS</h3>
            <p class="text-muted mb-0">Ringkasan status kelayakan IKL tingkat Kota.</p>
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
                            <p class="text-muted mb-1 fw-semibold">Total TPP</p>
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
                            <p class="text-muted mb-1 fw-semibold">Memenuhi Syarat (IKL ≥ 80)</p>
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
            <div class="card card-summary shadow-sm h-100 bg-white border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 fw-semibold">Tidak Memenuhi (IKL < 80)</p>
                            <h2 class="mb-0 fw-bold text-danger">{{ number_format($totalUnitUsaha - $totalLulusIkl) }}</h2>
                        </div>
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-times-circle"></i>
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
                            <p class="text-muted mb-1 fw-semibold">Memiliki SLHS / Label HSP</p>
                            <h2 class="mb-0 fw-bold text-info">{{ number_format($totalBerslhs) }}</h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="fas fa-users-shield"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="fw-bold"><i class="fas fa-map-marker-alt text-primary me-2"></i>Sebaran TPP per Kecamatan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="barChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="fw-bold"><i class="fas fa-shield-check text-success me-2"></i>Sebaran TPP BerSLHS per Kecamatan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="barChartSlhs" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i>Hasil IKL</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="width: 80%;">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-6 col-lg-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-warning"><i class="fas fa-bell me-2"></i>SLHS Hampir Kadaluarsa</h5>
                    <span class="badge bg-warning text-dark">{{ $slhsJatuhTempo->count() }} Terdekat</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Unit Usaha</th>
                                    <th>Kecamatan</th>
                                    <th>Tgl Berakhir</th>
                                    <th class="text-end pe-4">Sisa Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slhsJatuhTempo as $item)
                                    @php
                                        // Hitung sisa hari dari sekarang ke tanggal berakhir
                                        $tglBerakhir = \Carbon\Carbon::parse($item->laporanSlhs->tgl_berakhir_slhs);
                                        $sisaHari = \Carbon\Carbon::now()->diffInDays($tglBerakhir, false); // false agar bisa negatif jika terlewat
                                    @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->nama_unit_usaha }}</td>
                                    <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
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
                                    <td colspan="4" class="text-center text-muted py-4">Semua data SLHS masih dalam masa aktif yang aman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-clock text-primary me-2"></i>Data Unit Usaha Terbaru</h5>
                    <a href="{{ route('admin.reporting.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Data</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Unit Usaha</th>
                                    <th>Pemilik</th>
                                    <th>Jenis Usaha</th>
                                    <th>Kecamatan</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUnitUsaha as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->nama_unit_usaha }}</td>
                                    <td>{{ $item->nama_pemilik ?? '-' }}</td>
                                   @php
                                        $warnaBadge = match(strtolower($item->jenis_usaha ?? '')) {
                                            'sppg'     => 'bg-primary',         // Biru
                                            'restoran' => 'bg-success',         // Hijau
                                            'catering' => 'bg-info text-dark',  // Biru Muda
                                            'dam'      => 'bg-warning text-dark',// Kuning
                                            'kantin'   => 'bg-danger',          // Merah
                                            'tpp'      => 'bg-dark',            // Hitam
                                            default    => 'bg-secondary'        // Abu-abu (Default)
                                        };
                                    @endphp
                                    <td><span class="badge {{ $warnaBadge }}">{{ strtoupper($item->jenis_usaha ?? '-') }}</span></td>
                                    <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data unit usaha.</td>
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
    
    // 1. Data untuk Grafik Bar
    const kecamatanData = @json($sebaranKecamatan);
    const labelsBar = kecamatanData.map(item => item.nama_kecamatan || item.nama);
    const dataSppg = kecamatanData.map(item => item.sppg_count || 0);
    const dataCatering = kecamatanData.map(item => item.catering_count || 0);
    const dataRestoran = kecamatanData.map(item => item.restoran_count || 0);
    const dataDam = kecamatanData.map(item => item.dam_count || 0);
    const dataKantin = kecamatanData.map(item => item.kantin_count || 0);

    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labelsBar,
            datasets: [
                { label: 'SPPG', data: dataSppg, backgroundColor: 'rgba(13, 110, 253, 0.8)', borderRadius: 4 },
                { label: 'Catering', data: dataCatering, backgroundColor: 'rgba(25, 135, 84, 0.8)', borderRadius: 4 },
                { label: 'Restoran', data: dataRestoran, backgroundColor: 'rgba(255, 193, 7, 0.8)', borderRadius: 4 },
                { label: 'DAM', data: dataDam, backgroundColor: 'rgba(13, 202, 240, 0.8)', borderRadius: 4 },
                { label: 'Kantin', data: dataKantin, backgroundColor: 'rgba(220, 53, 69, 0.8)', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 1.5. Data untuk Grafik Bar (Sudah SLHS)
    const dataSppgSlhs = kecamatanData.map(item => item.sppg_slhs_count || 0);
    const dataCateringSlhs = kecamatanData.map(item => item.catering_slhs_count || 0);
    const dataRestoranSlhs = kecamatanData.map(item => item.restoran_slhs_count || 0);
    const dataDamSlhs = kecamatanData.map(item => item.dam_slhs_count || 0);
    const dataKantinSlhs = kecamatanData.map(item => item.kantin_slhs_count || 0);

    const ctxBarSlhs = document.getElementById('barChartSlhs').getContext('2d');
    new Chart(ctxBarSlhs, {
        type: 'bar',
        data: {
            labels: labelsBar,
            datasets: [
                { label: 'SPPG ', data: dataSppgSlhs, backgroundColor: 'rgba(13, 110, 253, 0.8)', borderRadius: 4 },
                { label: 'Catering ', data: dataCateringSlhs, backgroundColor: 'rgba(25, 135, 84, 0.8)', borderRadius: 4 },
                { label: 'Restoran ', data: dataRestoranSlhs, backgroundColor: 'rgba(255, 193, 7, 0.8)', borderRadius: 4 },
                { label: 'DAM', data: dataDamSlhs, backgroundColor: 'rgba(13, 202, 240, 0.8)', borderRadius: 4 },
                { label: 'Kantin', data: dataKantinSlhs, backgroundColor: 'rgba(220, 53, 69, 0.8)', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 2. Data untuk Grafik Donat
    const totalSemua = {{ $totalUnitUsaha }};
    const totalMemenuhi = {{ $totalLulusIkl }};
    const totalTidakMemenuhi = totalSemua - totalMemenuhi;

    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Memenuhi Syarat', 'Tidak Memenuhi Syarat'],
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

    // 3. Grafik Garis Tren Bulanan (Opsional - Jika ada canvasnya)
    const ctxLineElement = document.getElementById('lineChart');
    if (ctxLineElement) {
        const trenBulananData = @json($grafikBulanan);
        const ctxLine = ctxLineElement.getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Unit Usaha Baru',
                    data: trenBulananData,
                    borderColor: 'rgba(13, 110, 253, 1)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                interaction: { intersect: false, mode: 'index' },
            }
        });
    }
});
</script>
@endsection