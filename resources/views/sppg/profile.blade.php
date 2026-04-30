@extends('layout.sppgLayout')

@section('title', 'Daftar SPPG')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-building"></i>
        Data SPPG
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
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>
            {{ isset($sppg) && $sppg ? 'Update Data SPPG' : 'Tambah Data SPPG' }}
        </h5>
    </div>

    <div class="card-body">
        <form action="{{ route('sppg.daftar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama SPPG</label>
                    <input type="text" name="nama_sppg" class="form-control"
                        value="{{ old('nama_sppg', $sppg->nama_sppg ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Kepala</label>
                    <input type="text" name="nama_kepala" class="form-control"
                        value="{{ old('nama_kepala', $sppg->nama_kepala ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto Kepala</label>
                    <input type="file" name="foto_kepala" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    @if (!empty($sppg?->foto_kepala))
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $sppg->foto_kepala) }}" class="img-fluid rounded border" style="max-height:120px;" alt="Foto Kepala">
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Mitra</label>
                    <input type="text" name="nama_mitra" class="form-control"
                        value="{{ old('nama_mitra', $sppg->nama_mitra ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jumlah Pegawai</label>
                    <input type="number" name="jml_pegawai" min="1" class="form-control"
                        value="{{ old('jml_pegawai', $sppg->jml_pegawai ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kapasitas Porsi</label>
                    <input type="number" name="kapasitas_porsi" min="1" class="form-control"
                        value="{{ old('kapasitas_porsi', $sppg->kapasitas_porsi ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <select name="id_kecamatan" id="idKecamatanSelect" class="form-select" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($kecamatan as $item)
                            <option value="{{ $item->id_kecamatan }}"
                                @selected(old('id_kecamatan', $sppg->id_kecamatan ?? null) == $item->id_kecamatan)>
                                {{ $item->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kelurahan</label>
                    <select name="id_kelurahan" id="idKelurahanSelect" class="form-select" required>
                        <option value="">Pilih Kelurahan</option>
                        @foreach ($kelurahan as $item)
                            <option
                                value="{{ $item->id_kelurahan }}"
                                data-kecamatan="{{ $item->id_kecamatan }}"
                                @selected(old('id_kelurahan', $sppg->id_kelurahan ?? null) == $item->id_kelurahan)>
                                {{ $item->nama_kelurahan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Puskesmas</label>
                    <select name="id_puskesmas" class="form-select" required>
                        <option value="">Pilih Puskesmas</option>
                        @foreach ($puskesmas as $item)
                            <option value="{{ $item->id_puskesmas }}"
                                @selected(old('id_puskesmas', $sppg->id_puskesmas ?? null) == $item->id_puskesmas)>
                                {{ $item->nama_puskesmas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <hr>
                    <h6 class="mb-3">Foto SPPG</h6>
                    <label class="form-label">Upload Foto SPPG (boleh lebih dari satu)</label>
                    <input type="file" name="foto_sppg[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>

                    @if (isset($sppg) && $sppg && $sppg->fotoSppg->isNotEmpty())
                        <div class="row g-2 mt-2">
                            @foreach ($sppg->fotoSppg as $foto)
                                <div class="col-6 col-md-3">
                                    <img src="{{ asset('storage/' . $foto->foto_sppg) }}" class="img-fluid rounded border" alt="Foto SPPG">
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-1">Upload baru akan mengganti foto SPPG lama.</small>
                    @endif
                </div>

                <div class="col-md-12">
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Menu SPPG</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addMenuRow">
                            <i class="fas fa-plus me-1"></i>Tambah Baris Menu
                        </button>
                    </div>

                    <div id="menuRows">
                        @php
                            $oldMenuNama = old('menu_nama', []);
                            $hasOldMenu = is_array($oldMenuNama) && count($oldMenuNama) > 0;
                        @endphp

                        @if ($hasOldMenu)
                            @foreach ($oldMenuNama as $oldNama)
                                <div class="row g-2 menu-row mb-2">
                                    <div class="col-md-6">
                                        <input type="text" name="menu_nama[]" class="form-control" placeholder="Nama menu" value="{{ $oldNama }}">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" name="menu_foto[]" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                    </div>
                                    <div class="col-md-1 d-grid">
                                        <button type="button" class="btn btn-outline-danger remove-menu-row">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row g-2 menu-row mb-2">
                                <div class="col-md-6">
                                    <input type="text" name="menu_nama[]" class="form-control" placeholder="Nama menu">
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="menu_foto[]" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                </div>
                                <div class="col-md-1 d-grid">
                                    <button type="button" class="btn btn-outline-danger remove-menu-row">&times;</button>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (isset($sppg) && $sppg && $sppg->menuSppg->isNotEmpty())
                        <div class="table-responsive mt-3">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Menu</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sppg->menuSppg as $menu)
                                        <tr>
                                            <td>{{ $menu->nama_menu }}</td>
                                            <td>
                                                @if ($menu->foto_menu)
                                                    <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="Foto Menu" style="height:60px;" class="rounded border">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-1">Upload menu baru akan mengganti data menu lama.</small>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ isset($sppg) && $sppg ? 'Update Data' : 'Simpan Data' }}
                </button>
                <a href="{{ route('sppg.profile') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addMenuRowBtn = document.getElementById('addMenuRow');
    const menuRows = document.getElementById('menuRows');
    const kecamatanSelect = document.getElementById('idKecamatanSelect');
    const kelurahanSelect = document.getElementById('idKelurahanSelect');

    if (!addMenuRowBtn || !menuRows) {
        return;
    }

    const oldKelurahan = @json(old('id_kelurahan', $sppg->id_kelurahan ?? null));

    const allKelurahan = Array.from(kelurahanSelect.options)
        .slice(1)
        .map(function (opt) {
            return {
                value: opt.value,
                text: opt.textContent.trim(),
                kecamatan: opt.dataset.kecamatan || ''
            };
        });

    function syncKelurahanByKecamatan() {
        const kecamatanId = kecamatanSelect.value;
        const currentValue = kelurahanSelect.value;

        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';

        if (!kecamatanId) {
            kelurahanSelect.disabled = true;
            kelurahanSelect.value = '';
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

        const nextValue = filtered.some(function (item) {
            return item.value === currentValue;
        }) ? currentValue : (filtered.some(function (item) {
            return item.value === String(oldKelurahan || '');
        }) ? String(oldKelurahan) : '');

        kelurahanSelect.value = nextValue;
    }

    addMenuRowBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row g-2 menu-row mb-2';
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="menu_nama[]" class="form-control" placeholder="Nama menu">
            </div>
            <div class="col-md-5">
                <input type="file" name="menu_foto[]" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="col-md-1 d-grid">
                <button type="button" class="btn btn-outline-danger remove-menu-row">&times;</button>
            </div>
        `;
        menuRows.appendChild(row);
    });

    menuRows.addEventListener('click', function (event) {
        const target = event.target;
        if (!target.classList.contains('remove-menu-row')) {
            return;
        }

        const row = target.closest('.menu-row');
        if (!row) {
            return;
        }

        const rows = menuRows.querySelectorAll('.menu-row');
        if (rows.length === 1) {
            const nameInput = row.querySelector('input[name="menu_nama[]"]');
            const fileInput = row.querySelector('input[name="menu_foto[]"]');
            if (nameInput) {
                nameInput.value = '';
            }
            if (fileInput) {
                fileInput.value = '';
            }
            return;
        }

        row.remove();
    });

    kecamatanSelect.addEventListener('change', syncKelurahanByKecamatan);
    syncKelurahanByKecamatan();
});
</script>
@endsection