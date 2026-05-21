@extends('layout.sppgLayout')

@section('title', 'Tambah Unit Usaha')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/kecamatan/kelayakan.css') }}">

<div class="border rounded shadow-sm mb-4 page-header civic civic-fade" style="background: #ffffff !important; padding: 1.5rem !important;">
    <h1 style="margin-bottom: 0;">Tambah Unit Usaha</h1>
</div>

<form class="civic civic-fade" method="POST" action="{{ route('kecamatan.laporan-unit.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Container dengan background putih, border (stroke), padding, dan bayangan halus -->
    <div class="bg-white border rounded p-4 shadow-sm mb-4">
        
        <!-- Row 1: Kecamatan & Jenis Usaha -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-control" value="{{ $kecamatan->nama_kecamatan }}" disabled>
                    <input type="hidden" name="id_kecamatan" value="{{ $kecamatan->id_kecamatan }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jenis Usaha</label>
                    
                    @if (count($jenisUsahaOptions) === 1)
                        <input type="text" class="form-control" value="{{ strtoupper($jenisUsahaOptions[0]) }}" disabled>
                        <input type="hidden" name="jenis_usaha" value="{{ $jenisUsahaOptions[0] }}">
                    @else
                        <select name="jenis_usaha" class="form-select" required>
                            <option value="">Pilih jenis usaha</option>
                            @foreach ($jenisUsahaOptions as $jenis)
                                <option value="{{ $jenis }}" @selected(old('jenis_usaha') === $jenis)>
                                    {{ strtoupper($jenis) }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    
                </div>
            </div>
        </div>

        <!-- Row 2: Kelurahan & Puskesmas -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kelurahan</label>
                    <select name="id_kelurahan" class="form-select" required>
                        <option value="">Pilih kelurahan</option>
                        @foreach ($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id_kelurahan }}" @selected(old('id_kelurahan') == $kelurahan->id_kelurahan)>
                                {{ $kelurahan->nama_kelurahan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Puskesmas</label>
                    <select name="id_puskesmas" class="form-select" required>
                        <option value="">Pilih puskesmas</option>
                        @foreach ($puskesmas as $item)
                            <option value="{{ $item->id_puskesmas }}" @selected(old('id_puskesmas') == $item->id_puskesmas)>
                                {{ $item->nama_puskesmas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Row 3: Nama Unit & Nama Pemilik -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Unit Usaha</label>
                    <input type="text" name="nama_unit_usaha" class="form-control" value="{{ old('nama_unit_usaha') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" class="form-control" value="{{ old('nama_pemilik') }}" required>
                </div>
            </div>
        </div>

        <!-- Row 4: Alamat (Full Width) -->
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Row 5: Jumlah Pegawai & Jumlah Penjamah Terlatih -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jumlah Pegawai</label>
                    <input type="number" name="jumlah_pegawai" class="form-control" min="0" value="{{ old('jumlah_pegawai', 0) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jumlah Penjamah Terlatih</label>
                    <input type="number" name="jumlah_penjamah_terlatih" class="form-control" min="0" value="{{ old('jumlah_penjamah_terlatih', 0) }}">
                </div>
            </div>
        </div>

        <!-- Row 6: Status Aktif & Foto -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-3 mt-md-4">
                    <input type="checkbox" class="form-check-input" id="statusAktif" name="status_aktif" value="1" @checked(old('status_aktif', true))>
                    <label class="form-check-label" for="statusAktif">Aktif</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Foto Unit Usaha</label>
                    <input type="file" name="foto_unit_usaha[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Format: JPG/PNG, max 5MB.</small>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-secondary px-4">Kembali</a>
            </div>
        </div>    
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ==========================================
        // SWEETALERT2 POP-UP NOTIFICATIONS
        // ==========================================
        
        // Notifikasi Berhasil
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

        // Notifikasi Gagal (Custom Error)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
            });
        @endif

        // Notifikasi Validasi Error
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