@extends('layout.sppgLayout')

@section('title', 'Daftar SPPG')

@section('content')
<div class="page-header">
    <h1>
        <i class="fas fa-building"></i>
        Daftar SPPG
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

                <div class="col-md-12">
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
@endsection