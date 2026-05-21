@extends('layout.sppgLayout')

@section('title', 'Data Unit Usaha Kecamatan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/kecamatan/kelayakan.css') }}">

<div class="container-fluid civic civic-fade">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0"><i class="fas fa-building me-2"></i>Data Unit Usaha</h4>
            <small class="text-muted">Daftar unit usaha pada kecamatan Anda</small>
        </div>
        <a href="{{ route('kecamatan.laporan-unit.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Unit Usaha
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
                            <th>Kelurahan</th>
                            <th>Puskesmas</th>
                            <th>Pegawai</th>
                            <th>Status</th>
                            <th style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>{{ $item->nama_unit_usaha }}</td>
                                <td class="text-uppercase">{{ $item->jenis_usaha }}</td>
                                <td>{{ $item->nama_pemilik }}</td>
                                <td>{{ $item->kelurahan?->nama_kelurahan ?? '-' }}</td>
                                <td>{{ $item->puskesmas?->nama_puskesmas ?? '-' }}</td>
                                <td>{{ number_format((int) ($item->jumlah_pegawai ?? 0), 0, ',', '.') }}</td>
                                <td>
                                    @if ($item->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('kecamatan.laporan-unit.show', $item->id_unit_usaha) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data unit usaha.</td>
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