@extends('layout.sppgLayout')

@section('title', 'Pelaporan Distribusi')

@section('content')
@php
    $laporans = $laporans ?? collect();

    $isPaginated = $laporans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
        || $laporans instanceof \Illuminate\Contracts\Pagination\Paginator;

    $laporanRows = $isPaginated ? collect($laporans->items()) : collect($laporans);

    $showForm = old('kategori') !== null || old('tipe_instansi') !== null;
@endphp

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="page-header d-flex justify-content-between align-items-center">
    <h1>
        <i class="fas fa-truck-loading"></i>
        Pelaporan Distribusi
    </h1>
    <button type="button" class="btn btn-primary" id="toggleFormBtn">
        <i class="fas fa-plus me-1"></i> Tambah Penerima Manfaat
    </button>
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
                <small class="text-muted d-block mb-1">Negeri</small>
                <h4 class="mb-0 text-success">{{ $totalNegeri }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-warning">
            <div class="card-body text-center">
                <small class="text-muted d-block mb-1">Swasta</small>
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

<div class="card mb-4 {{ $showForm ? '' : 'd-none' }}" id="formCard">
    <div class="card-header">
        <h5 class="mb-0">Tambah Penerima Manfaat</h5>
    </div>
    <div class="card-body">
        @if (!$sppg)
            <div class="alert alert-warning mb-0">
                Data SPPG belum tersedia. Lengkapi profil terlebih dahulu.
            </div>
        @else
            <form method="POST" action="{{ route('sppg.pelaporan.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama SPPG</label>
                        <input type="text" class="form-control" value="{{ $sppg->nama_sppg ?? '-' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Instansi</label>
                        <input type="text" name="nama_instansi" class="form-control" value="{{ old('nama_instansi') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" id="kategoriSelect" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            <option value="Satuan Pendidikan" @selected(old('kategori') === 'Satuan Pendidikan')>Satuan Pendidikan</option>
                            <option value="Kelompok B3" @selected(old('kategori') === 'Kelompok B3')>Kelompok B3</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipe Instansi</label>
                        <select name="tipe_instansi" id="tipeInstansiSelect" class="form-select" required>
                            <option value="">Pilih tipe</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kecamatan</label>
                        <select name="id_kecamatan" id="idKecamatanSelect" class="form-select select2-basic" data-placeholder="Pilih kecamatan" required>
                            <option value="">Pilih kecamatan</option>
                            @foreach ($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id_kecamatan }}" @selected((string) old('id_kecamatan') === (string) $kecamatan->id_kecamatan)>
                                    {{ $kecamatan->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelurahan</label>
                        <select name="id_kelurahan" id="idKelurahanSelect" class="form-select select2-basic" data-placeholder="Pilih kelurahan" required>
                            <option value="">Pilih kelurahan</option>
                            @foreach ($kelurahans as $kelurahan)
                                <option
                                    value="{{ $kelurahan->id_kelurahan }}"
                                    data-kecamatan="{{ $kelurahan->id_kecamatan }}"
                                    @selected((string) old('id_kelurahan') === (string) $kelurahan->id_kelurahan)>
                                    {{ $kelurahan->nama_kelurahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Puskesmas</label>
                        <select name="id_puskesmas" id="idPuskesmasSelect" class="form-select select2-basic" data-placeholder="Pilih puskesmas" required>
                            <option value="">Pilih puskesmas</option>
                            @foreach ($puskesmas as $item)
                                <option value="{{ $item->id_puskesmas }}" @selected((string) old('id_puskesmas') === (string) $item->id_puskesmas)>
                                    {{ $item->nama_puskesmas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih status</option>
                            <option value="negeri" @selected(old('status') === 'negeri')>Negeri</option>
                            <option value="swasta" @selected(old('status') === 'swasta')>Swasta</option>
                        </select>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Rincian Penerima</h6>

                <div class="row g-3" id="sekolahFields">
                    <div class="col-md-4">
                        <label class="form-label">Siswa</label>
                        <input type="number" name="jml_siswa" id="jmlSiswaInput" min="0" class="form-control" value="{{ old('jml_siswa', 0) }}">
                    </div>
                </div>

                <div class="row g-3 d-none" id="posyanduFields">
                    <div class="col-md-4">
                        <label class="form-label">Bumil</label>
                        <input type="number" name="jml_bumil" id="jmlBumilInput" min="0" class="form-control" value="{{ old('jml_bumil', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Busui</label>
                        <input type="number" name="jml_busui" id="jmlBusuiInput" min="0" class="form-control" value="{{ old('jml_busui', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Balita</label>
                        <input type="number" name="jml_balita" id="jmlBalitaInput" min="0" class="form-control" value="{{ old('jml_balita', 0) }}">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="cancelFormBtn">Batal</button>
                    <button type="submit" class="btn btn-dark">Simpan</button>
                </div>
            </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Pelaporan Distribusi</h5>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanRows as $laporan)
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
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailLaporan{{ $laporan->id_laporan }}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data distribusi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($isPaginated && $laporans->hasPages())
                <div class= "pagination-wrapper mt-4 mb-2">
                {{ $laporans->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}                
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

@foreach ($laporanRows as $laporan)
    @php
        $total = (int) $laporan->jml_siswa + (int) $laporan->jml_bumil + (int) $laporan->jml_busui + (int) $laporan->jml_balita;
    @endphp
    <div class="modal fade" id="detailLaporan{{ $laporan->id_laporan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Laporan - {{ $laporan->nama_instansi }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Kategori</small>
                            <div>{{ $laporan->kategori }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tipe Instansi</small>
                            <div>{{ $laporan->tipe_instansi }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Status</small>
                            <div>{{ ucfirst($laporan->status) }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Total Penerima</small>
                            <div>{{ $total }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Kecamatan</small>
                            <div>{{ $laporan->kecamatan->nama_kecamatan ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Kelurahan</small>
                            <div>{{ $laporan->kelurahan->nama_kelurahan ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Puskesmas</small>
                            <div>{{ $laporan->puskesmas->nama_puskesmas ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Dibuat Pada</small>
                            <div>{{ optional($laporan->created_at)->format('d-m-Y H:i') ?? '-' }}</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Rincian Penerima</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Siswa</small>
                            <div>{{ $laporan->jml_siswa }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Bumil</small>
                            <div>{{ $laporan->jml_bumil }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Busui</small>
                            <div>{{ $laporan->jml_busui }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Balita</small>
                            <div>{{ $laporan->jml_balita }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formCard = document.getElementById('formCard');
    const toggleFormBtn = document.getElementById('toggleFormBtn');
    const cancelFormBtn = document.getElementById('cancelFormBtn');

    const kategoriSelect = document.getElementById('kategoriSelect');
    const tipeInstansiSelect = document.getElementById('tipeInstansiSelect');

    const sekolahFields = document.getElementById('sekolahFields');
    const posyanduFields = document.getElementById('posyanduFields');

    const jmlSiswaInput = document.getElementById('jmlSiswaInput');
    const jmlBumilInput = document.getElementById('jmlBumilInput');
    const jmlBusuiInput = document.getElementById('jmlBusuiInput');
    const jmlBalitaInput = document.getElementById('jmlBalitaInput');

    const kecamatanSelect = document.getElementById('idKecamatanSelect');
    const kelurahanSelect = document.getElementById('idKelurahanSelect');

    const tipeByKategori = {
        'Satuan Pendidikan': ['TK Sederajat', 'SD Sederajat', 'SMP Sederajat', 'SMA Sederajat'],
        'Kelompok B3': ['Posyandu']
    };

    const oldKategori = @json(old('kategori'));
    const oldTipeInstansi = @json(old('tipe_instansi'));
    const oldKelurahan = @json(old('id_kelurahan'));

    const allKelurahan = Array.from(kelurahanSelect.options)
        .slice(1)
        .map(function (opt) {
            return {
                value: opt.value,
                text: opt.textContent.trim(),
                kecamatan: opt.dataset.kecamatan || ''
            };
        });

    function initSelect2() {
        $('.select2-basic').select2({
            width: '100%',
            allowClear: true
        });
    }

    function isTipeSekolah(tipe) {
        return ['TK Sederajat', 'SD Sederajat', 'SMP Sederajat', 'SMA Sederajat'].includes(tipe);
    }

    function syncFieldsByTipe() {
        const tipe = tipeInstansiSelect.value;
        const sekolah = isTipeSekolah(tipe);
        const posyandu = tipe === 'Posyandu';

        if (posyandu) {
            sekolahFields.classList.add('d-none');
            posyanduFields.classList.remove('d-none');
        } else {
            sekolahFields.classList.remove('d-none');
            posyanduFields.classList.add('d-none');
        }

        jmlSiswaInput.required = sekolah;
        jmlBumilInput.required = false;
        jmlBusuiInput.required = false;
        jmlBalitaInput.required = false;
    }

    function syncTipeInstansiByKategori() {
        const kategori = kategoriSelect.value;
        const options = tipeByKategori[kategori] || [];
        const currentValue = tipeInstansiSelect.value;

        tipeInstansiSelect.innerHTML = '<option value="">Pilih tipe</option>';

        options.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item;
            opt.textContent = item;
            tipeInstansiSelect.appendChild(opt);
        });

        if (options.includes(currentValue)) {
            tipeInstansiSelect.value = currentValue;
        } else if (oldTipeInstansi && options.includes(oldTipeInstansi)) {
            tipeInstansiSelect.value = oldTipeInstansi;
        } else {
            tipeInstansiSelect.value = '';
        }

        syncFieldsByTipe();
    }

    function syncKelurahanByKecamatan() {
        const kecamatanId = kecamatanSelect.value;
        const currentValue = kelurahanSelect.value;

        kelurahanSelect.innerHTML = '<option value="">Pilih kelurahan</option>';

        if (!kecamatanId) {
            kelurahanSelect.disabled = true;
            $('#idKelurahanSelect').val(null).trigger('change.select2');
            return;
        }

        const filtered = allKelurahan.filter(function (item) {
            return item.kecamatan === kecamatanId;
        });

        filtered.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.value;
            opt.textContent = item.text;
            kelurahanSelect.appendChild(opt);
        });

        kelurahanSelect.disabled = false;

        const nextValue = filtered.some(function (item) { return item.value === currentValue; })
            ? currentValue
            : (filtered.some(function (item) { return item.value === String(oldKelurahan || ''); }) ? String(oldKelurahan) : '');

        kelurahanSelect.value = nextValue;
        $('#idKelurahanSelect').trigger('change.select2');
    }

    if (toggleFormBtn && formCard) {
        toggleFormBtn.addEventListener('click', function () {
            formCard.classList.toggle('d-none');
        });
    }

    if (cancelFormBtn && formCard) {
        cancelFormBtn.addEventListener('click', function () {
            formCard.classList.add('d-none');
        });
    }

    if (oldKategori) {
        kategoriSelect.value = oldKategori;
    }

    initSelect2();

    kategoriSelect.addEventListener('change', syncTipeInstansiByKategori);
    tipeInstansiSelect.addEventListener('change', syncFieldsByTipe);

    $('#idKecamatanSelect').on('change', function () {
        syncKelurahanByKecamatan();
    });

    syncTipeInstansiByKategori();
    syncKelurahanByKecamatan();
});
</script>
@endsection