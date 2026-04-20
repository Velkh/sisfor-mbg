@extends('layout.sppgLayout')

@section('title', 'Inspeksi IKL')

@section('content')
@php
    $initialStatus = old('status_ikl', $existingData->status_ikl ?? 'belum_mengajukan');
    $initialNilai = old('nilai_ikl', $existingData->nilai_ikl ?? null);
    $initialHasil = old('hasil_ikl', $existingData->hasil_ikl ?? null);
@endphp

<div class="page-header">
    <h1><i class="fas fa-stethoscope"></i> Inspeksi IKL</h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
            <div class="card-body">
                <small class="text-muted d-block mb-1">Status IKL</small>
                <h5 class="mb-0" id="summaryStatus">
                    @if ($initialStatus === 'belum_mengajukan')
                        Belum Mengajukan IKL
                    @elseif ($initialStatus === 'sudah_mengajukan')
                        Sudah Mengajukan IKL (Menunggu proses)
                    @elseif ($initialStatus === 'selesai')
                        {{ $initialHasil === 'memenuhi' ? 'Memenuhi' : ($initialHasil === 'tidak_memenuhi' ? 'Tidak Memenuhi' : 'Selesai') }}
                    @else
                        -
                    @endif
                </h5>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 border-success">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Nilai IKL</small>
                <h5 class="mb-0" id="summaryNilai">{{ $initialNilai ?? '-' }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Data Inspeksi Tersimpan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>Status IKL</th>
                        <th>Nilai IKL</th>
                        <th>Hasil IKL</th>
                        <th>Tanggal IKL</th>
                    </tr>
                </thead>
                <tbody>
                    @if (($existingData->status_ikl ?? null) || ($existingData->nilai_ikl ?? null) || ($existingData->tanggal_ikl ?? null))
                        <tr>
                            <td>
                                @if (($existingData->status_ikl ?? null) === 'belum_mengajukan')
                                    Belum Mengajukan IKL
                                @elseif (($existingData->status_ikl ?? null) === 'sudah_mengajukan')
                                    Sudah Mengajukan IKL (Menunggu proses)
                                @elseif (($existingData->status_ikl ?? null) === 'selesai')
                                    Selesai
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $existingData->nilai_ikl ?? '-' }}</td>
                            <td>
                                @if (($existingData->hasil_ikl ?? null) === 'memenuhi')
                                    Memenuhi
                                @elseif (($existingData->hasil_ikl ?? null) === 'tidak_memenuhi')
                                    Tidak Memenuhi
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ !empty($existingData->tanggal_ikl) ? \Carbon\Carbon::parse($existingData->tanggal_ikl)->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data inspeksi tersimpan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('sppg.ikl.store') }}" id="iklForm">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Status Awal IKL</h5>
        </div>
        <div class="card-body">
            <label class="form-label d-block mb-2">Sudah IKL?</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sudah_ikl" id="sudahIklTidak" value="tidak"
                    @checked(in_array($initialStatus, ['belum_mengajukan', 'sudah_mengajukan'], true))>
                <label class="form-check-label" for="sudahIklTidak">Belum</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="sudah_ikl" id="sudahIklYa" value="ya"
                    @checked($initialStatus === 'selesai')>
                <label class="form-check-label" for="sudahIklYa">Sudah</label>
            </div>

            <div id="statusBelumBlock">
                <label class="form-label">Status Saat Ini</label>
                <select id="statusBelumSelect" class="form-select">
                    <option value="belum_mengajukan" @selected($initialStatus === 'belum_mengajukan')>Belum Mengajukan IKL</option>
                    <option value="sudah_mengajukan" @selected($initialStatus === 'sudah_mengajukan')>Sudah Mengajukan IKL (Menunggu proses)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card mb-4 d-none" id="searchBlock">
        <div class="card-header">
            <h5 class="mb-0">Pencarian Data IKL</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-10">
                    <label class="form-label">Nama SPPG</label>
                    <input type="text" id="namaInput" class="form-control" placeholder="Contoh: SPPG BEJI">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="searchBtn" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>

            <small id="searchInfo" class="text-muted mt-2 d-block">Isi nama SPPG, lalu klik Cari.</small>

            <div class="table-responsive mt-3" id="searchResultWrapper">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama SPPG</th>
                            <th>Nilai IKL</th>
                            <th>Tanggal IKL</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="resultBody">
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada hasil.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4 d-none" id="selectedFormBlock">
        <div class="card-header">
            <h5 class="mb-0">3. Data IKL Terpilih</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama SPPG</label>
                    <input type="text" id="previewNama" class="form-control" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nilai IKL</label>
                    <input type="number" id="previewNilai" class="form-control" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasil IKL</label>
                    <input type="text" id="previewHasil" class="form-control" readonly>
                </div>
                <div class="col-12">
                    <label class="form-label">Tanggal IKL</label>
                    <input type="text" id="previewTanggal" class="form-control" readonly>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="status_ikl" id="statusFinal" value="{{ $initialStatus }}">
    <input type="hidden" name="selected_api_data" id="selectedApiData">

    <button type="submit" class="btn btn-primary w-100">Simpan</button>
</form>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hasil IKL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><strong>Nama SPPG:</strong> <span id="modalNama">-</span></p>
                <p class="mb-1"><strong>Nilai IKL:</strong> <span id="modalNilai">-</span></p>
                <p class="mb-0"><strong>Tanggal IKL:</strong> <span id="modalTanggal">-</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmPickBtn" class="btn btn-primary" data-bs-dismiss="modal">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const initialStatus = @json($initialStatus);
    const initialNilai = @json($initialNilai);
    const initialHasil = @json($initialHasil);

    const iklForm = document.getElementById('iklForm');

    const sudahIklTidak = document.getElementById('sudahIklTidak');
    const sudahIklYa = document.getElementById('sudahIklYa');
    const statusBelumBlock = document.getElementById('statusBelumBlock');
    const statusBelumSelect = document.getElementById('statusBelumSelect');
    const statusFinal = document.getElementById('statusFinal');

    const searchBlock = document.getElementById('searchBlock');
    const searchBtn = document.getElementById('searchBtn');
    const namaInput = document.getElementById('namaInput');
    const searchInfo = document.getElementById('searchInfo');
    const resultBody = document.getElementById('resultBody');
    const searchResultWrapper = document.getElementById('searchResultWrapper');

    const selectedFormBlock = document.getElementById('selectedFormBlock');
    const previewNama = document.getElementById('previewNama');
    const previewNilai = document.getElementById('previewNilai');
    const previewTanggal = document.getElementById('previewTanggal');
    const previewHasil = document.getElementById('previewHasil');

    const selectedApiData = document.getElementById('selectedApiData');

    const modalNama = document.getElementById('modalNama');
    const modalNilai = document.getElementById('modalNilai');
    const modalTanggal = document.getElementById('modalTanggal');
    const confirmPickBtn = document.getElementById('confirmPickBtn');

    const summaryStatus = document.getElementById('summaryStatus');
    const summaryNilai = document.getElementById('summaryNilai');

    let pendingPick = null;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    function statusLabel(value) {
        if (value === 'belum_mengajukan') {
            return 'Belum Mengajukan IKL';
        }
        if (value === 'sudah_mengajukan') {
            return 'Sudah Mengajukan IKL (Menunggu proses)';
        }
        if (value === 'selesai') {
            return 'Selesai';
        }
        return '-';
    }

    function hasilLabel(value) {
        if (value === 'memenuhi') {
            return 'Memenuhi';
        }
        if (value === 'tidak_memenuhi') {
            return 'Tidak Memenuhi';
        }
        return 'Selesai';
    }

    function clearSelectedForm() {
        selectedFormBlock.classList.add('d-none');
        previewNama.value = '';
        previewNilai.value = '';
        previewTanggal.value = '';
        previewHasil.value = '';
    }

    function syncMode() {
        if (sudahIklYa.checked) {
            statusBelumBlock.classList.add('d-none');
            searchBlock.classList.remove('d-none');
            statusFinal.value = 'selesai';

            if (selectedApiData.value) {
                // Sudah memilih data baru, status ditentukan saat konfirmasi.
            } else if (initialStatus === 'selesai') {
                summaryStatus.textContent = hasilLabel(initialHasil);
                summaryNilai.textContent = initialNilai !== null ? String(initialNilai) : '-';
            } else {
                summaryStatus.textContent = 'Menunggu Konfirmasi';
                summaryNilai.textContent = '-';
            }
        } else {
            statusBelumBlock.classList.remove('d-none');
            searchBlock.classList.add('d-none');
            statusFinal.value = statusBelumSelect.value;
            summaryStatus.textContent = statusLabel(statusFinal.value);
            summaryNilai.textContent = '-';

            selectedApiData.value = '';
            pendingPick = null;
            clearSelectedForm();
            searchResultWrapper.classList.remove('d-none');
        }
    }

    statusBelumSelect.addEventListener('change', function () {
        if (sudahIklTidak.checked) {
            statusFinal.value = this.value;
            summaryStatus.textContent = statusLabel(this.value);
            summaryNilai.textContent = '-';
        }
    });

    if (initialStatus === 'selesai') {
        sudahIklYa.checked = true;
        statusFinal.value = 'selesai';
        summaryStatus.textContent = hasilLabel(initialHasil);
        summaryNilai.textContent = initialNilai !== null ? String(initialNilai) : '-';
    } else {
        sudahIklTidak.checked = true;
        statusBelumSelect.value = initialStatus;
        statusFinal.value = initialStatus;
        summaryStatus.textContent = statusLabel(initialStatus);
        summaryNilai.textContent = '-';
    }

    sudahIklTidak.addEventListener('change', syncMode);
    sudahIklYa.addEventListener('change', syncMode);
    syncMode();

    searchBtn.addEventListener('click', async function () {
        const nama = namaInput.value.trim();

        if (!nama) {
            searchInfo.textContent = 'Nama SPPG wajib diisi.';
            return;
        }

        clearSelectedForm();
        pendingPick = null;
        searchResultWrapper.classList.remove('d-none');

        searchInfo.textContent = 'Mencari data IKL...';
        resultBody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';

        try {
            const url = '{{ route('sppg.ikl.search') }}?nama_sppg=' + encodeURIComponent(nama);
            const res = await fetch(url, { headers: { Accept: 'application/json' } });
            const json = await res.json();

            if (!res.ok || !json.success) {
                searchInfo.textContent = json.message || 'Pencarian gagal.';
                resultBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal mengambil data.</td></tr>';
                return;
            }

            const rows = Array.isArray(json.data) ? json.data : [];
            if (!rows.length) {
                searchInfo.textContent = 'Data tidak ditemukan.';
                resultBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada hasil.</td></tr>';
                return;
            }

            searchInfo.textContent = 'Data ditemukan. Pilih salah satu untuk konfirmasi.';
            resultBody.innerHTML = rows.map((item, index) => {
                return `
                    <tr>
                        <td>${item.nama_sppg ?? item.nama ?? nama}</td>
                        <td>${item.nilai_ikl ?? '-'}</td>
                        <td>${item.tanggal_ikl ?? '-'}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary pick-btn" data-index="${index}">
                                Pilih
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            document.querySelectorAll('.pick-btn').forEach((btn) => {
                btn.addEventListener('click', function () {
                    const index = Number(this.dataset.index);
                    pendingPick = rows[index] ?? null;
                    if (!pendingPick) {
                        return;
                    }

                    const fallbackNama = namaInput.value.trim() || '-';
                    modalNama.textContent = pendingPick.nama_sppg ?? pendingPick.nama ?? fallbackNama;
                    modalNilai.textContent = pendingPick.nilai_ikl ?? '-';
                    modalTanggal.textContent = pendingPick.tanggal_ikl ?? '-';
                    confirmModal.show();
                });
            });
        } catch (error) {
            searchInfo.textContent = 'Terjadi error jaringan.';
            resultBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Network error.</td></tr>';
        }
    });

    confirmPickBtn.addEventListener('click', function () {
        if (!pendingPick) {
            return;
        }

        const selectedNama = pendingPick.nama_sppg ?? pendingPick.nama ?? namaInput.value.trim();
        const nilai = Number(pendingPick.nilai_ikl ?? 0);
        const tanggal = pendingPick.tanggal_ikl ?? '';
        const hasil = nilai >= 80 ? 'memenuhi' : 'tidak_memenuhi';

        selectedApiData.value = JSON.stringify({
            nama_sppg: selectedNama,
            nilai_ikl: nilai,
            tanggal_ikl: tanggal,
        });

        statusFinal.value = 'selesai';
        summaryStatus.textContent = hasilLabel(hasil);
        summaryNilai.textContent = String(nilai);

        searchResultWrapper.classList.add('d-none');

        previewNama.value = selectedNama;
        previewNilai.value = String(nilai);
        previewTanggal.value = tanggal;
        previewHasil.value = hasilLabel(hasil);
        selectedFormBlock.classList.remove('d-none');

        searchInfo.textContent = 'Data IKL terkonfirmasi. Klik Simpan untuk menyelesaikan.';
    });

    iklForm.addEventListener('submit', function (event) {
        if (sudahIklYa.checked && !selectedApiData.value) {
            event.preventDefault();
            searchInfo.textContent = 'Pilih dan konfirmasi hasil API terlebih dahulu.';
        }
    });
});
</script>
@endsection