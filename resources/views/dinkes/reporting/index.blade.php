@extends('layout.dinkes')

@section('title', 'Input Data Unit Usaha')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

<div class="container-fluid civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-building me-2"></i>Data Unit Usaha</h4>
        <a href="{{ route('admin.reporting.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Data
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}">
                <div class="row g-2 align-items-center">
                    
                    <div class="col-md-5">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Cari nama unit / pemilik"
                            value="{{ $filters['q'] ?? '' }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <select name="id_kecamatan" class="form-select">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($kecamatans as $kec)
                                @php
                                    $kecId = $kec->id_kecamatan ?? $kec->id ?? null;
                                    $kecName = $kec->nama_kecamatan ?? $kec->nama ?? '-';
                                @endphp
                                <option value="{{ $kecId }}" @selected(($filters['id_kecamatan'] ?? '') == $kecId)>
                                    {{ $kecName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-outline-primary w-50" type="submit">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-dark w-50">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>         
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Unit Usaha</h5>
        </div>
        <div class="card-body border rounded-3 overflow-hidden">
            <div class="table-responsive border rounded-3 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Unit</th>
                            <th>Jenis Usaha</th>
                            <th>Pemilik</th>
                            <th>Pegawai</th>
                            <th>Status IKL</th>
                            <th>Status SLHS</th>
                            <th style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>{{ $item->nama_unit_usaha }}</td>
                                <td class="text-uppercase">{{ $item->jenis_usaha }}</td>
                                <td>{{ $item->nama_pemilik }}</td>
                                <td>{{ $item->jumlah_pegawai }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->laporanSlhs->status_ikl ?? '-')) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->laporanSlhs->status_slhs ?? '-')) }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('admin.reporting.show', $item->id_unit_usaha) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reporting.edit', $item->id_unit_usaha) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.reporting.destroy', $item->id_unit_usaha) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection