@extends('layout.sppgLayout')

@section('title', 'Tambah Unit Usaha')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/kecamatan/kelayakan.css') }}">

@php
    $isEdit = isset($unit);
    $selectedSasaran = old('sasaran');

    if ($selectedSasaran === null) {
        $selectedSasaran = isset($sasaran) && $sasaran->count() > 0
            ? $sasaran->map(function ($row) {
                return [
                    'kategori' => $row->kategori,
                    'tipe_instansi' => $row->tipe_instansi,
                    'nama_instansi' => $row->nama_instansi,
                    'status' => $row->status,
                    'jumlah_siswa' => $row->jumlah_siswa,
                    'jumlah_bumil' => $row->jumlah_bumil,
                    'jumlah_busui' => $row->jumlah_busui,
                    'jumlah_balita' => $row->jumlah_balita,
                    'detail_jangkauan' => $row->detail_jangkauan,
                    'jumlah_jiwa' => $row->jumlah_jiwa,
                ];
            })->toArray()
            : [];
    }

    $kelurahans = $kelurahans ?? collect();
    $kelurahanData = $kelurahans->map(function($it){
        return [
            'id' => $it->id_kelurahan ?? $it->id ?? null,
            'name' => $it->nama ?? $it->nama_kelurahan ?? ($it->name ?? ''),
        ];
    })->toArray();

    $oldKel = old('id_kelurahan', $unit->id_kelurahan ?? '');
    $oldApiId = old('api_unit_id', $unit->api_unit_id ?? '');
@endphp

<div class="container-fluid civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-file-alt me-2"></i>
            {{ $isEdit ? 'Edit Data Unit Usaha SPPG' : 'Tambah Data Unit Usaha SPPG' }}
        </h4>
        <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <form method="POST" action="{{ $isEdit ? route('kecamatan.laporan-unit.update', $unit->id_unit_usaha) : route('kecamatan.laporan-unit.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-search me-2"></i>Cari Data Unit Usaha SPPG</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Cari Nama Unit Usaha SPPG</label>
                        <div class="input-group">
                            <input type="text" id="searchUnitInput" class="form-control" placeholder="Cari nama SPPG, contoh: SPPG tapos">
                            <button type="button" id="btnCariUnit" class="btn btn-outline-primary">Cari</button>
                        </div>
                        <div id="searchResults" class="list-group mt-2"></div>
                        <input type="hidden" name="api_unit_id" id="apiUnitId" value="{{ $oldApiId }}">
                        <input type="hidden" name="nilai_ikl" id="nilaiIkl">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0">Data Unit Usaha</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Unit Usaha</label>
                        <input type="text" name="nama_unit_usaha" id="namaUnitUsaha" class="form-control" value="{{ old('nama_unit_usaha', $unit->nama_unit_usaha ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" id="namaPemilik" class="form-control" value="{{ old('nama_pemilik', $unit->nama_pemilik ?? '') }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2" required>{{ old('alamat', $unit->alamat ?? '') }}</textarea>
                    </div>
                    <input type="hidden" name="latitude" id="inputLatitude" value="{{ old('latitude', $unit->latitude ?? '') }}">
                    <input type="hidden" name="longitude" id="inputLongitude" value="{{ old('longitude', $unit->longitude ?? '') }}">
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Pegawai</label>
                        <input type="number" min="0" name="jumlah_pegawai" id="jumlahPegawai" class="form-control" value="{{ old('jumlah_pegawai', $unit->jumlah_pegawai ?? 0) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Penjamah Terlatih</label>
                        <input type="number" min="0" name="jumlah_penjamah_terlatih" id="jumlahPenjamahTerlatih" class="form-control" value="{{ old('jumlah_penjamah_terlatih', $unit->jumlah_penjamah_terlatih ?? 0) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kelurahan</label>
                        <select name="id_kelurahan" id="selectKelurahan" class="form-select" required>
                            <option value="">Pilih Kelurahan</option>
                            @foreach($kelurahans as $kel)
                                @php
                                    $kelId = $kel->id_kelurahan ?? $kel->id ?? null;
                                    $kelName = $kel->nama ?? $kel->nama_kelurahan ?? ($kel->name ?? '—');
                                @endphp
                                <option value="{{ $kelId }}" @selected((string)old('id_kelurahan', $unit->id_kelurahan ?? '') === (string)$kelId)>{{ $kelName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Puskesmas</label>
                        <select name="id_puskesmas" id="selectPuskesmas" class="form-select" required>
                            <option value="">Pilih Puskesmas</option>
                            @foreach ($puskesmas as $puskesmasItem)
                                @php
                                    $puskesmasId = $puskesmasItem->id_puskesmas ?? $puskesmasItem->id ?? null;
                                    $puskesmasName = $puskesmasItem->nama_puskesmas ?? $puskesmasItem->nama ?? '—';
                                @endphp
                                <option value="{{ $puskesmasId }}" @selected((string) old('id_puskesmas', $unit->id_puskesmas ?? '') === (string) $puskesmasId)>
                                    {{ $puskesmasName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">Foto Unit Usaha</label>
                <input type="file" name="foto_unit_usaha[]" class="form-control" accept="image/*" multiple>
                <small class="text-muted">Format gambar: JPG/PNG, max 5MB.</small>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sasaran Manfaat</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahSasaran">
                    <i class="fas fa-plus me-1"></i>Tambah Baris
                </button>
            </div>
            <div class="card-body">
                <div id="sasaranWrapper">
                    @foreach ($selectedSasaran as $index => $row)
                        <div class="border rounded p-3 mb-3 sasaran-row" data-index="{{ $index }}">
                            <div class="row g-2">
                                <div class="col-md-2" data-sasaran-field="kategori">
                                    <label class="form-label">Kategori</label>
                                    <select name="sasaran[{{ $index }}][kategori]" class="form-select" required>
                                        <option value="">Pilih</option>
                                        @foreach (['Sekolah', 'B3', 'Umum'] as $value)
                                            <option value="{{ $value }}" @selected(($row['kategori'] ?? '') === $value)>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2" data-sasaran-field="tipe_instansi">
                                    <label class="form-label">Tipe Instansi</label>
                                    <select name="sasaran[{{ $index }}][tipe_instansi]" class="form-select">
                                        <option value="">Pilih</option>
                                        @foreach (['TK', 'SD', 'SMP', 'SMA', 'Posyandu', 'TPP', 'DAM', 'Kantin', 'Lainnya'] as $value)
                                            <option value="{{ $value }}" @selected(($row['tipe_instansi'] ?? '') === $value)>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3" data-sasaran-field="nama_instansi">
                                    <label class="form-label">Nama Instansi</label>
                                    <input type="text" name="sasaran[{{ $index }}][nama_instansi]" class="form-control" value="{{ $row['nama_instansi'] ?? '' }}">
                                </div>

                                <div class="col-md-2" data-sasaran-field="status">
                                    <label class="form-label">Status</label>
                                    <select name="sasaran[{{ $index }}][status]" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="negeri" @selected(($row['status'] ?? '') === 'negeri')>Negeri</option>
                                        <option value="swasta" @selected(($row['status'] ?? '') === 'swasta')>Swasta</option>
                                    </select>
                                </div>

                                <div class="col-md-2" data-sasaran-field="jumlah_siswa">
                                    <label class="form-label">Jumlah Siswa</label>
                                    <input type="number" min="0" name="sasaran[{{ $index }}][jumlah_siswa]" class="form-control" value="{{ $row['jumlah_siswa'] ?? '' }}">
                                </div>

                                <div class="col-md-2" data-sasaran-field="jumlah_bumil">
                                    <label class="form-label">Jumlah Bumil</label>
                                    <input type="number" min="0" name="sasaran[{{ $index }}][jumlah_bumil]" class="form-control" value="{{ $row['jumlah_bumil'] ?? '' }}">
                                </div>

                                <div class="col-md-2" data-sasaran-field="jumlah_busui">
                                    <label class="form-label">Jumlah Busui</label>
                                    <input type="number" min="0" name="sasaran[{{ $index }}][jumlah_busui]" class="form-control" value="{{ $row['jumlah_busui'] ?? '' }}">
                                </div>

                                <div class="col-md-2" data-sasaran-field="jumlah_balita">
                                    <label class="form-label">Jumlah Balita</label>
                                    <input type="number" min="0" name="sasaran[{{ $index }}][jumlah_balita]" class="form-control" value="{{ $row['jumlah_balita'] ?? '' }}">
                                </div>

                                <div class="col-md-2" data-sasaran-field="jumlah_jiwa">
                                    <label class="form-label">Jumlah Jiwa</label>
                                    <input type="number" min="0" name="sasaran[{{ $index }}][jumlah_jiwa]" class="form-control" value="{{ $row['jumlah_jiwa'] ?? 0 }}">
                                </div>

                                <div class="col-md-12" data-sasaran-field="detail_jangkauan">
                                    <label class="form-label">Detail Jangkauan</label>
                                    <textarea name="sasaran[{{ $index }}][detail_jangkauan]" class="form-control" rows="2">{{ $row['detail_jangkauan'] ?? '' }}</textarea>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-2" data-sasaran-field="hapus">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btnHapusBaris">Hapus Baris</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <template id="sasaranTemplate">
                    <div class="border rounded p-3 mb-3 sasaran-row" data-index="__INDEX__">
                        <div class="row g-2">
                            <div class="col-md-2" data-sasaran-field="kategori">
                                <label class="form-label">Kategori</label>
                                <select name="sasaran[__INDEX__][kategori]" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Sekolah">Sekolah</option>
                                    <option value="B3">B3</option>
                                    <option value="Umum">Umum</option>
                                </select>
                            </div>

                            <div class="col-md-2" data-sasaran-field="tipe_instansi">
                                <label class="form-label">Tipe Instansi</label>
                                <select name="sasaran[__INDEX__][tipe_instansi]" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="TK">TK</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="Posyandu">Posyandu</option>
                                    <option value="TPP">TPP</option>
                                    <option value="DAM">DAM</option>
                                    <option value="Kantin">Kantin</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-3" data-sasaran-field="nama_instansi">
                                <label class="form-label">Nama Instansi</label>
                                <input type="text" name="sasaran[__INDEX__][nama_instansi]" class="form-control" value="">
                            </div>

                            <div class="col-md-2" data-sasaran-field="status">
                                <label class="form-label">Status</label>
                                <select name="sasaran[__INDEX__][status]" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="negeri">Negeri</option>
                                    <option value="swasta">Swasta</option>
                                </select>
                            </div>

                            <div class="col-md-2" data-sasaran-field="jumlah_siswa">
                                <label class="form-label">Jumlah Siswa</label>
                                <input type="number" min="0" name="sasaran[__INDEX__][jumlah_siswa]" class="form-control" value="">
                            </div>

                            <div class="col-md-2" data-sasaran-field="jumlah_bumil">
                                <label class="form-label">Jumlah Bumil</label>
                                <input type="number" min="0" name="sasaran[__INDEX__][jumlah_bumil]" class="form-control" value="">
                            </div>

                            <div class="col-md-2" data-sasaran-field="jumlah_busui">
                                <label class="form-label">Jumlah Busui</label>
                                <input type="number" min="0" name="sasaran[__INDEX__][jumlah_busui]" class="form-control" value="">
                            </div>

                            <div class="col-md-2" data-sasaran-field="jumlah_balita">
                                <label class="form-label">Jumlah Balita</label>
                                <input type="number" min="0" name="sasaran[__INDEX__][jumlah_balita]" class="form-control" value="">
                            </div>

                            <div class="col-md-2" data-sasaran-field="jumlah_jiwa">
                                <label class="form-label">Jumlah Jiwa</label>
                                <input type="number" min="0" name="sasaran[__INDEX__][jumlah_jiwa]" class="form-control" value="0">
                            </div>

                            <div class="col-md-12" data-sasaran-field="detail_jangkauan">
                                <label class="form-label">Detail Jangkauan</label>
                                <textarea name="sasaran[__INDEX__][detail_jangkauan]" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="col-12 d-flex justify-content-end mt-2" data-sasaran-field="hapus">
                                <button type="button" class="btn btn-sm btn-outline-secondary btnHapusBaris">Hapus Baris</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_JENIS_MAP = {
        'jasa_boga': 'sppg',
        'rumah_makan': 'catering',
    };

    function mapJenisFromApi(apiJenis) {
        if (!apiJenis) return '';
        const normalized = String(apiJenis).toLowerCase().trim();
        return API_JENIS_MAP[normalized] || '';
    }

    const kelurahanData = @json($kelurahanData);
    const oldKel = @json($oldKel);

    function findKelurahanIdByName(name) {
        if (!name) return null;
        const normalized = String(name).toUpperCase().trim();
        const found = kelurahanData.find(k => 
            String(k.name).toUpperCase().trim() === normalized
        );
        return found ? found.id : null;
    }

    const searchUnitInput = document.getElementById('searchUnitInput');
    const btnCariUnit = document.getElementById('btnCariUnit');
    const searchResults = document.getElementById('searchResults');
    const apiUnitId = document.getElementById('apiUnitId');

    async function searchUnit() {
        const keyword = searchUnitInput.value.trim();
        if (!keyword) {
            searchResults.innerHTML = '';
            return;
        }

        searchResults.innerHTML = '<div class="list-group-item">Mencari...</div>';

        try {
            // Search dengan filter jenis=sppg
            const response = await fetch('{{ route('kecamatan.laporan-unit.ikl.search') }}?search=' + encodeURIComponent(keyword));            const payload = await response.json();

            if (!payload.success || !Array.isArray(payload.data) || payload.data.length === 0) {
                searchResults.innerHTML = '<div class="list-group-item text-muted">Data SPPG tidak ditemukan.</div>';
                return;
            }

            // Filter hasil hanya untuk jenis SPPG
            const sppgOnly = payload.data.filter(item => {
                const jenis = String(item.jenis).toLowerCase().trim();
                return jenis === 'jasa_boga' || jenis === 'sppg';
            });

            if (sppgOnly.length === 0) {
                searchResults.innerHTML = '<div class="list-group-item text-muted">Data SPPG tidak ditemukan.</div>';
                return;
            }

            searchResults.innerHTML = '';
            sppgOnly.forEach(function (item) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action';
                button.innerHTML = `
                    <div class="fw-semibold">${item.nama}</div>
                    <div class="small text-muted">
                        Pengelola: ${item.pengelola} | Skor: ${item.nilai_ikl}
                    </div>
                `;

                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    fillFormFromApiData(item);
                });

                searchResults.appendChild(button);
            });
        } catch (err) {
            console.error('Search error:', err);
            searchResults.innerHTML = '<div class="list-group-item text-danger">Terjadi kesalahan.</div>';
        }
    }

    function fillFormFromApiData(apiData) {
        if (apiUnitId) {
            apiUnitId.value = apiData.id || '';
        }
        if (document.getElementById('nilaiIkl')) {
            document.getElementById('nilaiIkl').value = apiData.nilai_ikl || 0;
        }
        if (document.getElementById('namaUnitUsaha')) {
            document.getElementById('namaUnitUsaha').value = apiData.nama || '';
        }
        if (document.getElementById('namaPemilik')) {
            document.getElementById('namaPemilik').value = apiData.pengelola || '';
        }
        if (document.getElementById('alamat')) {
            document.getElementById('alamat').value = apiData.alamat || '';
        }
        if (document.getElementById('jumlahPegawai')) {
            document.getElementById('jumlahPegawai').value = apiData.penjamah_pangan_total || 0;
        }
        if (document.getElementById('jumlahPenjamahTerlatih')) {
            document.getElementById('jumlahPenjamahTerlatih').value = apiData.penjamah_pangan_bersertifikat || 0;
        }

        const inputLat = document.getElementById('inputLatitude');
        const inputLng = document.getElementById('inputLongitude');
        
        if (apiData.koordinat && String(apiData.koordinat).includes(',')) {
            const parts = String(apiData.koordinat).split(',');
            if (inputLat) inputLat.value = parts[0].trim();
            if (inputLng) inputLng.value = parts[1].trim();
        } else {
            if (inputLat) inputLat.value = '';
            if (inputLng) inputLng.value = '';
        }

        if (document.getElementById('selectKelurahan')) {
            const kelId = findKelurahanIdByName(apiData.kelurahan);
            if (kelId) {
                document.getElementById('selectKelurahan').value = kelId;
            }
        }

        if (document.getElementById('selectKelurahan')) {
            const kelId = findKelurahanIdByName(apiData.kelurahan);
            if (kelId) {
                document.getElementById('selectKelurahan').value = kelId;
            }
        }

        searchResults.innerHTML = `
            <div class="list-group-item list-group-item-success">
                <i class="fas fa-check me-2"></i>Dipilih: ${apiData.nama}
            </div>
        `;

        document.querySelector('.card:nth-of-type(2)').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    if (btnCariUnit) {
        btnCariUnit.addEventListener('click', searchUnit);
    }
    if (searchUnitInput) {
        searchUnitInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchUnit();
            }
        });
    }

    // SASARAN MANFAAT LOGIC (sama seperti admin form)
    function getSasaranState(row) {
        if (!row) return null;
        if (row._sasaranState) return row._sasaranState;

        const fields = {};
        row.querySelectorAll('[data-sasaran-field]').forEach(function (field) {
            fields[field.dataset.sasaranField] = field;
        });

        row._sasaranState = {
            fields: fields,
            kategori: row.querySelector('select[name$="[kategori]"]'),
            tipe: row.querySelector('select[name$="[tipe_instansi]"]'),
        };

        return row._sasaranState;
    }

    function toggleField(state, key, visible) {
        const field = state.fields[key];
        if (field) {
            field.classList.toggle('d-none', !visible);
        }
    }

    function clearFieldValue(field) {
        if (!field) return;
        const control = field.querySelector('input, select, textarea');
        if (!control) return;

        if (control.tagName === 'SELECT') {
            control.value = '';
            return;
        }
        control.value = '';
    }

    function setVisibilityForRow(row) {
        const state = getSasaranState(row);
        if (!state || !state.kategori) return;

        const kategori = state.kategori.value || '';
        const visibleFields = new Set(['kategori', 'hapus', 'detail_jangkauan']);

        if (kategori === 'Sekolah') {
            visibleFields.add('tipe_instansi');
            visibleFields.add('nama_instansi');
            visibleFields.add('jumlah_siswa');
            visibleFields.add('status');
        } else if (kategori === 'B3') {
            visibleFields.add('tipe_instansi');
            visibleFields.add('nama_instansi');
            visibleFields.add('jumlah_bumil');
            visibleFields.add('jumlah_busui');
            visibleFields.add('jumlah_balita');
            visibleFields.add('status');
        } else if (kategori === 'Umum') {
            visibleFields.add('jumlah_jiwa');
        }

        Object.keys(state.fields).forEach(function (key) {
            toggleField(state, key, visibleFields.has(key));

            if (!visibleFields.has(key) && key !== 'kategori' && key !== 'hapus') {
                clearFieldValue(state.fields[key]);
            }
        });

        if (state.tipe) {
            const allowed = kategori === 'Sekolah'
                ? ['TK', 'SD', 'SMP', 'SMA']
                : (kategori === 'B3' ? ['Posyandu'] : []);

            Array.from(state.tipe.options).forEach(function (opt) {
                if (opt.value === '') {
                    opt.hidden = false;
                    return;
                }
                opt.hidden = allowed.length > 0 && !allowed.includes(opt.value);
            });

            if (allowed.length > 0 && !allowed.includes(state.tipe.value)) {
                state.tipe.value = '';
            }
        }
    }

    function bindSasaranRow(row) {
        const state = getSasaranState(row);
        if (!state || !state.kategori) return;

        if (!state.kategori.dataset.bound) {
            state.kategori.addEventListener('change', function () {
                setVisibilityForRow(row);
            });
            state.kategori.dataset.bound = '1';
        }

        const deleteBtn = row.querySelector('.btnHapusBaris');
        if (deleteBtn && !deleteBtn.dataset.bound) {
            deleteBtn.addEventListener('click', function () {
                row.remove();
            });
            deleteBtn.dataset.bound = '1';
        }

        setVisibilityForRow(row);
    }

    function initAllSasaranRows() {
        document.querySelectorAll('.sasaran-row').forEach(function (row) {
            bindSasaranRow(row);
        });
    }

    initAllSasaranRows();

    const btnTambah = document.getElementById('btnTambahSasaran');
    const sasaranWrapper = document.getElementById('sasaranWrapper');
    const templateHtml = document.getElementById('sasaranTemplate') ? document.getElementById('sasaranTemplate').innerHTML : null;

    function nextIndex() {
        const nodes = Array.from(document.querySelectorAll('.sasaran-row'));
        const idxs = nodes.map(n => parseInt(n.getAttribute('data-index') || '0', 10)).filter(n => !isNaN(n));
        return idxs.length ? (Math.max(...idxs) + 1) : 0;
    }

    if (btnTambah && sasaranWrapper && templateHtml) {
        btnTambah.addEventListener('click', function () {
            const idx = nextIndex();
            const html = templateHtml.split('__INDEX__').join(idx);
            const div = document.createElement('div');
            div.innerHTML = html.trim();
            const newRow = div.firstElementChild;
            sasaranWrapper.appendChild(newRow);
            bindSasaranRow(newRow);
            newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    // Alerts
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: `
                <ul class="text-start mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    @endif
});
</script>
@endsection