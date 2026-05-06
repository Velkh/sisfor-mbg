@extends('layout.dinkes')

@section('title', 'Input Data Unit Usaha')

@section('content')
<div class="container-fluid">
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Unit Usaha</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                                <td>
                                    <a href="{{ route('admin.reporting.show', $item->id_unit_usaha) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.reporting.edit', $item->id_unit_usaha) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-pen"></i>
                                    </a>
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