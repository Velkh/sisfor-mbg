@extends('layout.sppgLayout')

@section('title', 'Berkas SLHS')

@section('content')
@php
    $statusMap = [
        'belum_mengajukan' => 'Belum Mengajukan',
        'sudah_mengajukan' => 'Sudah Mengajukan (Menunggu Proses)',
        'selesai' => 'Selesai',
    ];

    $statusValue = old('status_slhs', $sppg?->status_slhs ?? 'belum_mengajukan');
    $statusLabel = $statusMap[$statusValue] ?? '-';

    $tglBerlaku = old('tgl_berlaku', $sppg?->tgl_berlaku?->format('Y-m-d'));
    $tglBerakhir = old('tgl_berakhir', $sppg?->tgl_berakhir?->format('Y-m-d'));

    $sisaHariLabel = '-';
    $sisaHari = null;
    $perluPerbarui = false;
    $warningMerah = false;

    if (!empty($sppg?->tgl_berakhir)) {
        $today = \Carbon\Carbon::today();
        $endDate = \Carbon\Carbon::parse($sppg->tgl_berakhir)->startOfDay();
        $sisaHari = $today->diffInDays($endDate, false);

        $interval = $sisaHari < 0 ? $endDate->diff($today) : $today->diff($endDate);

        $parts = [];
        if ($interval->y > 0) {
            $parts[] = $interval->y . ' tahun';
        }
        if ($interval->m > 0) {
            $parts[] = $interval->m . ' bulan';
        }
        if ($interval->d > 0 || empty($parts)) {
            $parts[] = $interval->d . ' hari';
        }

        $durasi = implode(' ', $parts);

        if ($sisaHari < 0) {
            $sisaHariLabel = 'Kedaluwarsa ' . $durasi . ' lalu';
            $warningMerah = true;
        } else {
            $sisaHariLabel = $durasi . ' lagi';
            $perluPerbarui = $sisaHari <= 90;
            $warningMerah = $sisaHari <= 30;
        }
    }
@endphp


<div class="page-header">
    <h1>
        <i class="fas fa-file-signature"></i>
        Berkas SLHS
    </h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100 border-primary">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Status SLHS</small>
                <h5 class="mb-0">{{ $statusLabel }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 border-success">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Sisa Masa Berlaku</small>
                <h5 class="mb-0 {{ $warningMerah ? 'text-danger' : ($perluPerbarui ? 'text-warning' : '') }}">
                    {{ $sisaHariLabel }}
                </h5>
            </div>
        </div>
    </div>
</div>

@if ($warningMerah)
    <div class="alert alert-danger mb-4">
        Sisa masa berlaku SLHS tinggal {{ max($sisaHari, 0) }} hari. Segera lakukan pembaruan.
    </div>
@elseif ($perluPerbarui)
    <div class="alert alert-warning mb-4">
        Sisa masa berlaku SLHS {{ $sisaHari }} hari. Sudah masuk periode wajib diperbarui (<= 3 bulan).
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form SLHS</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sppg.suratlaik.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Status Pengajuan</label>
                <select name="status_slhs" id="statusSlhsSelect" class="form-select" required>
                    <option value="belum_mengajukan" @selected($statusValue === 'belum_mengajukan')>Belum Mengajukan</option>
                    <option value="sudah_mengajukan" @selected($statusValue === 'sudah_mengajukan')>Sudah Mengajukan (Menunggu proses)</option>
                    <option value="selesai" @selected($statusValue === 'selesai')>Selesai</option>
                </select>
            </div>

            <div id="slhsDetailFields" class="{{ $statusValue === 'selesai' ? '' : 'd-none' }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tgl_berlaku" id="tglBerlakuInput" class="form-control" value="{{ $tglBerlaku }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="tgl_berakhir" id="tglBerakhirInput" class="form-control" value="{{ $tglBerakhir }}">
                        <small id="masaBerlakuInfo" class="text-muted d-block mt-2"></small>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Input File</label>
                    <input type="file" name="foto_slhs" id="fotoSlhsInput" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                    @if (!empty($sppg?->foto_slhs))
                        <small class="text-muted d-block mt-1">
                            File saat ini:
                            <a href="{{ asset('storage/' . $sppg->foto_slhs) }}" target="_blank">Lihat Berkas</a>
                        </small>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-dark px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('statusSlhsSelect');
    const detailFields = document.getElementById('slhsDetailFields');
    const tglBerlakuInput = document.getElementById('tglBerlakuInput');
    const tglBerakhirInput = document.getElementById('tglBerakhirInput');
    const fotoSlhsInput = document.getElementById('fotoSlhsInput');
    const masaInfo = document.getElementById('masaBerlakuInfo');

    function formatYmd(startDate, endDate) {
        let y = endDate.getFullYear() - startDate.getFullYear();
        let m = endDate.getMonth() - startDate.getMonth();
        let d = endDate.getDate() - startDate.getDate();

        if (d < 0) {
            m -= 1;
            const daysInPrevMonth = new Date(endDate.getFullYear(), endDate.getMonth(), 0).getDate();
            d += daysInPrevMonth;
        }

        if (m < 0) {
            y -= 1;
            m += 12;
        }

        const parts = [];
        if (y > 0) {
            parts.push(y + ' tahun');
        }
        if (m > 0) {
            parts.push(m + ' bulan');
        }
        if (d > 0 || parts.length === 0) {
            parts.push(d + ' hari');
        }

        return parts.join(' ');
    }

    function syncSlhsFields() {
        const isSelesai = statusSelect.value === 'selesai';

        detailFields.classList.toggle('d-none', !isSelesai);
        tglBerlakuInput.required = isSelesai;
        tglBerakhirInput.required = isSelesai;
        fotoSlhsInput.required = isSelesai;

        if (!isSelesai) {
            masaInfo.textContent = '';
            masaInfo.className = 'text-muted d-block mt-2';
        }
    }

    function syncMasaInfo() {
        const start = tglBerlakuInput.value;
        const end = tglBerakhirInput.value;

        if (!start || !end) {
            masaInfo.textContent = '';
            masaInfo.className = 'text-muted d-block mt-2';
            return;
        }

        const startDate = new Date(start + 'T00:00:00');
        const endDate = new Date(end + 'T00:00:00');
        const diffMs = endDate - startDate;
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffDays < 0) {
            masaInfo.textContent = 'Tanggal berakhir tidak boleh sebelum tanggal berlaku.';
            masaInfo.className = 'text-danger d-block mt-2';
            return;
        }

        masaInfo.textContent = 'Durasi masa berlaku: ' + formatYmd(startDate, endDate);

        if (diffDays <= 30) {
            masaInfo.className = 'text-danger d-block mt-2';
        } else if (diffDays <= 90) {
            masaInfo.className = 'text-warning d-block mt-2';
        } else {
            masaInfo.className = 'text-muted d-block mt-2';
        }
    }

    statusSelect.addEventListener('change', syncSlhsFields);
    tglBerlakuInput.addEventListener('change', syncMasaInfo);
    tglBerakhirInput.addEventListener('change', syncMasaInfo);

    syncSlhsFields();
    syncMasaInfo();
});
</script>
@endsection