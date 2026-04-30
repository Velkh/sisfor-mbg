@extends('layout.dinkes')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Selamat Datang!</strong> Berikut adalah ringkasan data SPPG terbaru.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="stat-card h-100">
                    <div class="stat-card-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalSppg ?? 0 }}</div>
                    <div class="stat-card-label">Total SPPG</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="stat-card h-100">
                    <div class="stat-card-icon" style="color: #4CAF50;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalLulusIkl ?? 0 }}</div>
                    <div class="stat-card-label">SPPG Lulus IKL</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="stat-card h-100">
                    <div class="stat-card-icon" style="color: #2196F3;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalBerslhs ?? 0 }}</div>
                    <div class="stat-card-label">SPPG Bers SLHS</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="stat-card h-100">
                    <div class="stat-card-icon" style="color: #FF9800;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalLaikHigiene ?? 0 }}</div>
                    <div class="stat-card-label">SPPG Laik Higiene</div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Sedang Proses IKL</div>
                            <div class="fs-3 fw-bold">{{ $totalProsesIkl ?? 0 }}</div>
                        </div>
                        <div class="fs-1 text-warning">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Sedang Proses SLHS</div>
                            <div class="fs-3 fw-bold">{{ $totalProsesSlhs ?? 0 }}</div>
                        </div>
                        <div class="fs-1 text-info">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Sebaran SPPG per Kecamatan
                        </h5>
                        <small class="text-muted">Sumber data: relasi SPPG - Kecamatan</small>
                    </div>
                    <div class="card-body">
                        <div style="height: 320px;">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Data SPPG Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th>Nama SPPG</th>
                                        <th>Kecamatan</th>
                                        <th>Status IKL</th>
                                        <th>Status SLHS</th>
                                        <th>Tanggal IKL</th>
                                        <th style="width: 90px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentSppg ?? [] as $index => $sppg)
                                        @php
                                            $isIklLulus = $sppg->status_ikl === 'selesai'
                                                && $sppg->hasil_ikl === 'memenuhi'
                                                && (int) ($sppg->nilai_ikl ?? 0) >= 80;

                                            $isLaikHigiene = $isIklLulus && $sppg->status_slhs === 'selesai';

                                            if ($isLaikHigiene) {
                                                $evaluasiLabel = 'Laik Higiene';
                                                $evaluasiClass = 'bg-success';
                                            } elseif ($isIklLulus) {
                                                $evaluasiLabel = 'Lulus IKL';
                                                $evaluasiClass = 'bg-primary';
                                            } else {
                                                $evaluasiLabel = 'Belum Layak';
                                                $evaluasiClass = 'bg-secondary';
                                            }

                                            $statusIklLabel = $sppg->status_ikl
                                                ? ucfirst(str_replace('_', ' ', $sppg->status_ikl))
                                                : '-';

                                            $statusSlhsLabel = $sppg->status_slhs
                                                ? ucfirst(str_replace('_', ' ', $sppg->status_slhs))
                                                : '-';
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $sppg->nama_sppg ?? '-' }}</div>
                                                <small class="text-muted">{{ $sppg->nama_kepala ?? '-' }}</small>
                                            </td>
                                            <td>{{ $sppg->kecamatan?->nama_kecamatan ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $isIklLulus ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $statusIklLabel }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $sppg->status_slhs === 'selesai' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $statusSlhsLabel }}
                                                </span>
                                            </td>
                                            <td>{{ $sppg->tanggal_ikl?->format('d M Y') ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $evaluasiClass }}">{{ $evaluasiLabel }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada data SPPG terbaru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.kelola') }}" class="btn btn-primary">
                                Lihat Semua <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Ringkasan Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Total SPPG</div>
                                    <div class="fs-4 fw-bold">{{ $totalSppg ?? 0 }}</div>
                                </div>
                                <i class="fas fa-building fs-2 text-primary"></i>
                            </div>
                        </div>

                        <div class="mb-3 p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Lulus IKL</div>
                                    <div class="fs-4 fw-bold">{{ $totalLulusIkl ?? 0 }}</div>
                                </div>
                                <i class="fas fa-check-circle fs-2 text-success"></i>
                            </div>
                        </div>

                        <div class="mb-3 p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Bers SLHS</div>
                                    <div class="fs-4 fw-bold">{{ $totalBerslhs ?? 0 }}</div>
                                </div>
                                <i class="fas fa-certificate fs-2 text-info"></i>
                            </div>
                        </div>

                        <div class="p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Laik Higiene</div>
                                    <div class="fs-4 fw-bold">{{ $totalLaikHigiene ?? 0 }}</div>
                                </div>
                                <i class="fas fa-shield-alt fs-2 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .stat-card-icon {
            font-size: 28px;
            color: #1976d2;
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .stat-card-label {
            font-size: 14px;
            color: #6c757d;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartElement = document.getElementById('kecamatanChart');
            if (!chartElement) {
                return;
            }

            const labels = @json(($sebaranKecamatan ?? collect())->pluck('nama_kecamatan')->values());
            const totals = @json(($sebaranKecamatan ?? collect())->pluck('sppg_count')->map(fn ($item) => (int) $item)->values());

            const hasData = totals.some(function (value) {
                return value > 0;
            });

            if (!hasData) {
                chartElement.parentElement.innerHTML = '<div class="text-muted text-center py-5">Belum ada data SPPG per kecamatan.</div>';
                return;
            }

            new Chart(chartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah SPPG',
                        data: totals,
                        backgroundColor: '#1976d2',
                        borderRadius: 8,
                        barThickness: 26,
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Jumlah SPPG: ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#495057'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#495057'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection