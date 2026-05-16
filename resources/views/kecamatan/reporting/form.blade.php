@extends('layout.sppgLayout')

@section('title', 'Tambah Unit Usaha')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-header">
    <h1>Tambah Unit Usaha</h1>
</div>

<form method="POST" action="{{ route('kecamatan.laporan-unit.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Kecamatan</label>
            <input type="text" class="form-control" value="{{ $kecamatan->nama_kecamatan }}" disabled>
            <input type="hidden" name="id_kecamatan" value="{{ $kecamatan->id_kecamatan }}">
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Jenis Usaha</label>
            <select name="jenis_usaha" class="form-select" required>
                <option value="">Pilih jenis usaha</option>
                @foreach ($jenisUsahaOptions as $jenis)
                    <option value="{{ $jenis }}" @selected(old('jenis_usaha') === $jenis)>
                        {{ strtoupper($jenis) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
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

    <div class="card mb-3">
        <div class="card-body">
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

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Nama Unit Usaha</label>
            <input type="text" name="nama_unit_usaha" class="form-control" value="{{ old('nama_unit_usaha') }}" required>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Nama Pemilik</label>
            <input type="text" name="nama_pemilik" class="form-control" value="{{ old('nama_pemilik') }}" required>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Jumlah Pegawai</label>
            <input type="number" name="jumlah_pegawai" class="form-control" min="0" value="{{ old('jumlah_pegawai', 0) }}">
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Jumlah Penjamah Terlatih</label>
            <input type="number" name="jumlah_penjamah_terlatih" class="form-control" min="0" value="{{ old('jumlah_penjamah_terlatih', 0) }}">
        </div>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="statusAktif" name="status_aktif" value="1" @checked(old('status_aktif', true))>
        <label class="form-check-label" for="statusAktif">Aktif</label>
    </div>
    
    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label">Foto Unit Usaha</label>
            <input type="file" name="foto_unit_usaha[]" class="form-control" accept="image/*" multiple>
            <small class="text-muted">Format gambar: JPG/PNG, max 5MB.</small>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('kecamatan.laporan-unit.index') }}" class="btn btn-secondary">Kembali</a>
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

        // Notifikasi Validasi Error (misal: format foto salah, nama kosong, dll)
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