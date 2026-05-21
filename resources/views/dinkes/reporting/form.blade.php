@extends('layout.dinkes')

@section('title', isset($unit) ? 'Edit Data Unit Usaha' : 'Tambah Data Unit Usaha')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

@php
    $isEdit = isset($unit);
    $laporan = $laporan ?? null;
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
            : []; // Array kosong agar default tidak ada baris
    }

    $kecamatans = $kecamatans ?? collect();
    $kelurahans = $kelurahans ?? collect();

    // Build a plain array for JS to consume
    $kelurahanData = $kelurahans->map(function($it){
        return [
            'id' => $it->id_kelurahan ?? $it->id ?? null,
            'name' => $it->nama ?? $it->nama_kelurahan ?? ($it->name ?? ''),
            'kecamatan_id' => $it->id_kecamatan ?? $it->kecamatan_id ?? ($it->kecamatan ?? null),
        ];
    })->toArray();

    $oldKec = old('id_kecamatan', $unit->id_kecamatan ?? '');
    $oldKel = old('id_kelurahan', $unit->id_kelurahan ?? '');
@endphp

<div class="container-fluid civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-file-alt me-2"></i>
            {{ $isEdit ? 'Edit Data Unit Usaha' : 'Tambah Data Unit Usaha' }}
        </h4>
        <a href="{{ route('admin.reporting.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.reporting.update', $unit->id_unit_usaha) : route('admin.reporting.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="card mb-3">
            <div class="card-header"><h5 class="mb-0">Data Unit Usaha</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Usaha</label>
                        <select name="jenis_usaha" class="form-select" required>
                            <option value="">Pilih</option>
                            @foreach (['sppg' => 'SPPG', 'tpp' => 'TPP', 'dam' => 'DAM', 'kantin' => 'Kantin'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('jenis_usaha', $unit->jenis_usaha ?? '') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Nama Unit Usaha</label>
                        <input type="text" name="nama_unit_usaha" class="form-control" value="{{ old('nama_unit_usaha', $unit->nama_unit_usaha ?? '') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" class="form-control" value="{{ old('nama_pemilik', $unit->nama_pemilik ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $unit->alamat ?? '') }}</textarea>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Jumlah Pegawai</label>
                        <input type="number" min="0" name="jumlah_pegawai" class="form-control" value="{{ old('jumlah_pegawai', $unit->jumlah_pegawai ?? 0) }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Penjamah Terlatih</label>
                        <input type="number" min="0" name="jumlah_penjamah_terlatih" class="form-control" value="{{ old('jumlah_penjamah_terlatih', $unit->jumlah_penjamah_terlatih ?? 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kecamatan</label>
                        <select name="id_kecamatan" id="selectKecamatan" class="form-select" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kec)
                                @php
                                    $kecId = $kec->id_kecamatan ?? $kec->id ?? null;
                                    $kecName = $kec->nama ?? $kec->nama_kecamatan ?? ($kec->name ?? '—');
                                @endphp
                                <option value="{{ $kecId }}" @selected((string)old('id_kecamatan', $unit->id_kecamatan ?? '') === (string)$kecId)>{{ $kecName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelurahan</label>
                        <select name="id_kelurahan" id="selectKelurahan" class="form-select" required>
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Puskesmas</label>
                        <select name="id_puskesmas" class="form-select" required>
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
            <div class="card-header"><h5 class="mb-0">Data Laporan SLHS</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status IKL</label>
                        <select name="status_ikl" id="statusIkl" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="belum_mengajukan" @selected(old('status_ikl', $laporan->status_ikl ?? '') === 'belum_mengajukan')>Belum Mengajukan</option>
                            <option value="sudah_mengajukan" @selected(old('status_ikl', $laporan->status_ikl ?? '') === 'sudah_mengajukan')>Sudah Mengajukan</option>
                            <option value="selesai" @selected(old('status_ikl', $laporan->status_ikl ?? '') === 'selesai')>Sudah IKL</option>
                        </select>
                    </div>

                    <div class="col-md-9" id="iklSearchBox" style="display: none;">
                        <label class="form-label">Cari Data IKL</label>
                        <div class="input-group">
                            <input type="text" id="iklSearchInput" class="form-control" placeholder="Cari nama usaha, contoh: Solaria">
                            <button type="button" id="btnCariIkl" class="btn btn-outline-primary">Cari</button>
                        </div>
                        <div id="iklResults" class="list-group mt-2"></div>
                        <input type="hidden" name="selected_api_data" id="selectedApiData">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nilai IKL</label>
                        <input type="number" name="nilai_ikl" id="nilaiIkl" class="form-control" readonly
                            value="{{ old('nilai_ikl', $laporan->nilai_ikl ?? '') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hasil IKL</label>
                        <input type="text" name="hasil_ikl" id="hasilIkl" class="form-control" readonly
                            value="{{ old('hasil_ikl', $laporan->hasil_ikl ?? '') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status SLHS</label>
                        <select name="status_slhs" id="statusSlhs" class="form-select">
                            <option value="">Pilih</option>
                            @foreach (['belum_mengajukan', 'sudah_mengajukan', 'selesai'] as $value)
                                <option value="{{ $value }}" @selected(old('status_slhs', $laporan->status_slhs ?? '') === $value)>
                                    {{ ucfirst(str_replace('_', ' ', $value)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3" id="slhsTglTerbitBox">
                        <label class="form-label">Tanggal Terbit SLHS</label>
                        <input type="date" name="tgl_terbit_slhs" class="form-control" value="{{ old('tgl_terbit_slhs', optional($laporan?->tgl_terbit_slhs)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-3" id="slhsTglBerakhirBox">
                        <label class="form-label">Tanggal Berakhir SLHS</label>
                        <input type="date" name="tgl_berakhir_slhs" class="form-control" value="{{ old('tgl_berakhir_slhs', optional($laporan?->tgl_berakhir_slhs)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-6" id="slhsLinkBox">
                        <label class="form-label">Link SLHS</label>
                        <input type="text" name="link_slhs" class="form-control" value="{{ old('link_slhs', $laporan->link_slhs ?? '') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Ketersediaan IPAL</label>
                            <select name="ketersediaan_ipal" id="ketersediaan_ipal" class="form-select">
                                <option value="">Pilih</option>
                                <option value="ada" @selected(old('ketersediaan_ipal', $laporan->ketersediaan_ipal ?? '') === 'ada')>Ada</option>
                                <option value="tidak_ada" @selected(old('ketersediaan_ipal', $laporan->ketersediaan_ipal ?? '') === 'tidak_ada')>Tidak Ada</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Jenis IPAL</label>
                            @php
                                $jenisIpalList = [
                                    'Grease Trap' => 'Grease Trap (Penangkap Lemak)',
                                    'Septic Tank' => 'Septic Tank Konvensional',
                                    'Biofilter' => 'Bio Septic Tank / Biofilter',
                                    'IPAL Komunal' => 'IPAL Terpusat / Komunal',
                                    'Lainnya' => 'Lainnya'
                                ];
                                $currentIpal = old('jenis_ipal', $laporan->jenis_ipal ?? '');
                            @endphp
                            
                            <select name="jenis_ipal" id="jenis_ipal" class="form-select">
                                <option value="">-- Pilih Jenis IPAL --</option>
                                @foreach ($jenisIpalList as $value => $label)
                                    <option value="{{ $value }}" @selected($currentIpal === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Pengelolaan Sampah</label>
                            <select name="pengelolaan_sampah" id="pengelolaan_sampah" class="form-select">
                                <option value="">Pilih</option>
                                <option value="ada" @selected(old('pengelolaan_sampah', $laporan->pengelolaan_sampah ?? '') === 'ada')>Ada</option>
                                <option value="tidak_ada" @selected(old('pengelolaan_sampah', $laporan->pengelolaan_sampah ?? '') === 'tidak_ada')>Tidak Ada</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Jenis Pengelolaan</label>
                            @php
                                $jenisPengelolaanList = [
                                    'Diangkut Dinas/Petugas' => 'Diangkut Petugas Kebersihan/DLHK',
                                    'Dibuang ke TPS' => 'Dibuang ke TPS Terdekat',
                                    'Dikelola Mandiri' => 'Dikelola Mandiri (Kompos/Daur Ulang)',
                                    'Maggot' => 'Biokonversi Maggot (BSF)',
                                    'Pihak Ketiga' => 'Bekerja Sama dengan Pihak Swasta',
                                    'Lainnya' => 'Lainnya'
                                ];
                                $currentPengelolaan = old('jenis_pengelolaan', $laporan->jenis_pengelolaan ?? '');
                            @endphp
                            
                            <select name="jenis_pengelolaan" id="jenis_pengelolaan" class="form-select">
                                <option value="">-- Pilih Jenis Pengelolaan --</option>
                                @foreach ($jenisPengelolaanList as $value => $label)
                                    <option value="{{ $value }}" @selected($currentPengelolaan === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
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

                {{-- Template for new rows (hidden) --}}
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
                {{-- end template --}}
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.reporting.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ketersediaanIpal = document.getElementById('ketersediaan_ipal');
    const jenisIpal = document.getElementById('jenis_ipal');
    const pengelolaanSampah = document.getElementById('pengelolaan_sampah');
    const jenisPengelolaan = document.getElementById('jenis_pengelolaan');

    // Fungsi disable/enable IPAL
    function toggleIpal() {
        console.log('toggleIpal called, value:', ketersediaanIpal?.value); // DEBUG
        if (!ketersediaanIpal || !jenisIpal) return;
        
        if (ketersediaanIpal.value === 'ada') {
            jenisIpal.disabled = false;
            console.log('IPAL enabled');
        } else {
            jenisIpal.disabled = true;
            jenisIpal.value = '';
            console.log('IPAL disabled');
        }
    }

    // Fungsi disable/enable Sampah
    function toggleSampah() {
        console.log('toggleSampah called, value:', pengelolaanSampah?.value); // DEBUG
        if (!pengelolaanSampah || !jenisPengelolaan) return;
        
        if (pengelolaanSampah.value === 'ada') {
            jenisPengelolaan.disabled = false;
            console.log('Sampah enabled');
        } else {
            jenisPengelolaan.disabled = true;
            jenisPengelolaan.value = '';
            console.log('Sampah disabled');
        }
    }

    // PENTING: Jalankan saat halaman load pertama kali
    if (ketersediaanIpal) {
        toggleIpal();
        // Tambahkan event listener
        ketersediaanIpal.addEventListener('change', function(e) {
            console.log('ketersediaanIpal changed to:', e.target.value);
            toggleIpal();
        });
    }

    if (pengelolaanSampah) {
        toggleSampah();
        // Tambahkan event listener
        pengelolaanSampah.addEventListener('change', function(e) {
            console.log('pengelolaanSampah changed to:', e.target.value);
            toggleSampah();
        });
    }

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

    // ==========================================
    // 3. IKL / SLHS LOGIC
    // ==========================================
    
    const statusIkl = document.getElementById('statusIkl');
    const statusSlhs = document.getElementById('statusSlhs');
    const iklSearchBox = document.getElementById('iklSearchBox');
    const iklSearchInput = document.getElementById('iklSearchInput');
    const btnCariIkl = document.getElementById('btnCariIkl');
    const iklResults = document.getElementById('iklResults');
    const selectedApiData = document.getElementById('selectedApiData');
    const slhsTglTerbitBox = document.getElementById('slhsTglTerbitBox');
    const slhsTglBerakhirBox = document.getElementById('slhsTglBerakhirBox');
    const slhsLinkBox = document.getElementById('slhsLinkBox');

    function toggleIklSearch() {
        const show = statusIkl && statusIkl.value === 'selesai';
        if (iklSearchBox) iklSearchBox.style.display = show ? 'block' : 'none';

        if (!show) {
            if (iklSearchInput) iklSearchInput.value = '';
            if (iklResults) iklResults.innerHTML = '';
            if (selectedApiData) selectedApiData.value = '';
        }
    }

    function toggleSlhsFields() {
        const show = statusSlhs && statusSlhs.value === 'selesai';

        if (slhsTglTerbitBox) slhsTglTerbitBox.style.display = show ? 'block' : 'none';
        if (slhsTglBerakhirBox) slhsTglBerakhirBox.style.display = show ? 'block' : 'none';
        if (slhsLinkBox) slhsLinkBox.style.display = show ? 'block' : 'none';

        if (!show) {
            const terbitInput = slhsTglTerbitBox ? slhsTglTerbitBox.querySelector('input') : null;
            const berakhirInput = slhsTglBerakhirBox ? slhsTglBerakhirBox.querySelector('input') : null;
            const linkInput = slhsLinkBox ? slhsLinkBox.querySelector('input') : null;

            if (terbitInput) terbitInput.value = '';
            if (berakhirInput) berakhirInput.value = '';
            if (linkInput) linkInput.value = '';
        }
    }

    async function searchIkl() {
        if (!iklSearchInput) return;
        const keyword = iklSearchInput.value.trim();
        if (!keyword) {
            if (iklResults) iklResults.innerHTML = '';
            return;
        }

        if (iklResults) iklResults.innerHTML = '<div class="list-group-item">Mencari...</div>';

        try {
            const response = await fetch('{{ route('admin.reporting.ikl.search') }}?search=' + encodeURIComponent(keyword));
            const payload = await response.json();

            if (!payload.success || !Array.isArray(payload.data) || payload.data.length === 0) {
                iklResults.innerHTML = '<div class="list-group-item text-muted">Data tidak ditemukan.</div>';
                return;
            }

            iklResults.innerHTML = '';
            payload.data.forEach(function (item) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action';
                button.innerHTML = `
                    <div class="fw-semibold">${item.nama}</div>
                    <div class="small text-muted">
                        Skor: ${item.nilai_ikl} | Tanggal: ${item.tanggal_penilaian || '-'}
                    </div>
                `;

                button.addEventListener('click', function () {
                    if (selectedApiData) selectedApiData.value = JSON.stringify(item);
                    if (iklSearchInput) iklSearchInput.value = item.nama;

                    const nilaiIkl = document.getElementById('nilaiIkl');
                    const hasilIkl = document.getElementById('hasilIkl');

                    if (nilaiIkl) nilaiIkl.value = item.nilai_ikl ?? '';
                    if (hasilIkl) hasilIkl.value = item.hasil_ikl ?? '';

                    iklResults.innerHTML = `
                        <div class="list-group-item list-group-item-success">
                            Dipilih: ${item.nama} (${item.nilai_ikl})
                        </div>
                    `;
                });

                iklResults.appendChild(button);
            });
        } catch (err) {
            if (iklResults) iklResults.innerHTML = '<div class="list-group-item text-danger">Terjadi kesalahan.</div>';
        }
    }

    if (statusIkl) {
        statusIkl.addEventListener('change', toggleIklSearch);
    }
    if (statusSlhs) {
        statusSlhs.addEventListener('change', toggleSlhsFields);
    }
    if (btnCariIkl) {
        btnCariIkl.addEventListener('click', searchIkl);
    }
    if (iklSearchInput) {
        iklSearchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchIkl();
            }
        });
    }

    toggleIklSearch();
    toggleSlhsFields();

    // ==========================================
    // 4. KECAMATAN / KELURAHAN DEPENDENT SELECT
    // ==========================================
    
    const selectKec = document.getElementById('selectKecamatan');
    const selectKel = document.getElementById('selectKelurahan');
    const kelurahanData = @json($kelurahanData);
    const oldKec = @json($oldKec);
    const oldKel = @json($oldKel);

    function populateKelurahan(kecamatanId, selectedKelId = null) {
        if (!selectKel) return;
        selectKel.innerHTML = '<option value="">Pilih Kelurahan</option>';
        if (!kecamatanId) {
            selectKel.disabled = true;
            return;
        }
        selectKel.disabled = false;
        const list = kelurahanData.filter(k => String(k.kecamatan_id) === String(kecamatanId));
        if (list.length === 0) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Tidak ada kelurahan';
            selectKel.appendChild(opt);
            return;
        }
        list.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name || ('Kelurahan ' + item.id);
            if (String(item.id) === String(selectedKelId)) opt.selected = true;
            selectKel.appendChild(opt);
        });
    }

    if (selectKec) {
        if (oldKec) {
            selectKec.value = oldKec;
            populateKelurahan(oldKec, oldKel);
        } else {
            selectKel.disabled = true;
        }

        selectKec.addEventListener('change', function () {
            populateKelurahan(this.value, null);
        });
    }

    // ==========================================
    // 5. SASARAN MANFAAT LOGIC
    // ==========================================
    
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
            jumlahJiwa: row.querySelector('input[name$="[jumlah_jiwa]"]'),
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
        control.value = control.type === 'number' ? '' : '';
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

        if (state.jumlahJiwa) {
            const label = state.fields.jumlah_jiwa ? state.fields.jumlah_jiwa.querySelector('.form-label') : null;
            if (label) {
                label.textContent = kategori === 'Umum' ? 'Jumlah Orang Per Hari' : 'Jumlah Jiwa';
            }
        }

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

});
</script>
@endsection