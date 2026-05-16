@extends('layout.dinkes')

@section('title', 'Manajemen Operator')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dinkes/kelayakan.css') }}">

    <div class="container-fluid civic civic-fade">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Admin Kecamatan</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOperatorModal">
                        <i class="fas fa-plus me-2"></i>Tambah Admin Kecamatan
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.manage.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Cari Username</label>
                            <input
                                type="text"
                                class="form-control"
                                name="q"
                                id="q"
                                value="{{ $q ?? '' }}"
                                placeholder="Ketik username..."
                                autocomplete="off"
                                autofocus
                            >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Filter Kecamatan</label>
                            <select class="form-select" name="kecamatan_id" id="kecamatan_id">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatanOptions as $item)
                                    <option value="{{ $item->id_kecamatan }}" {{ (int) $selectedKecamatanId === (int) $item->id_kecamatan ? 'selected' : '' }}>
                                        {{ $item->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('admin.manage.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Akses Tipe Usaha</th>
                                <th>Kecamatan</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($operators as $operator)
                                <tr>
                                    <td>{{ $loop->iteration + ($operators->currentPage() - 1) * $operators->perPage() }}</td>
                                    <td>{{ $operator->username }}</td>
                                    <td class="text-uppercase">{{ $operator->akses_tipe_usaha ?? '-' }}</td>
                                    <td>{{ $operator->kecamatan->nama_kecamatan ?? '-' }}</td>
                                    <td>{{ optional($operator->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailOperatorModal"
                                                data-username="{{ e($operator->username) }}"
                                                data-akses="{{ e($operator->akses_tipe_usaha ?? '-') }}"
                                                data-kecamatan="{{ e($operator->kecamatan->nama_kecamatan ?? '-') }}"
                                                data-created="{{ e($operator->created_at?->format('d M Y H:i') ?? '-') }}"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editOperatorModal"
                                                data-update-url="{{ route('admin.manage.update', $operator) }}"
                                                data-username="{{ e($operator->username) }}"
                                                data-kecamatan-id="{{ $operator->id_kecamatan ?? '' }}"
                                                data-akses-type="{{ $operator->akses_tipe_usaha ?? '' }}"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteOperatorModal"
                                                data-delete-url="{{ route('admin.manage.destroy', $operator) }}"
                                                data-username="{{ e($operator->username) }}"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data admin kecamatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $operators->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createOperatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.manage.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Admin Kecamatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <select name="id_kecamatan" class="form-select @error('id_kecamatan') is-invalid @enderror" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatanOptions as $item)
                                    <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            @error('id_kecamatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Akses Tipe Usaha</label>
                            <select name="akses_tipe_usaha" class="form-select @error('akses_tipe_usaha') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Usaha</option>
                                <option value="sppg">SPPG</option>
                                <option value="tpp">TPP</option>
                                <option value="dam">DAM</option>
                                <option value="kantin">Kantin</option>
                            </select>
                            @error('akses_tipe_usaha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailOperatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Admin Kecamatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><strong>Username:</strong> <span id="detailUsername">-</span></div>
                    <div class="mb-2"><strong>Akses:</strong> <span id="detailAkses">-</span></div>
                    <div class="mb-2"><strong>Kecamatan:</strong> <span id="detailKecamatan">-</span></div>
                    <div class="mb-0"><strong>Dibuat:</strong> <span id="detailCreated">-</span></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editOperatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="editOperatorForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Admin Kecamatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Username: <strong id="editOperatorUsername">-</strong></p>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <select name="id_kecamatan" id="edit_id_kecamatan" class="form-select" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatanOptions as $item)
                                    <option value="{{ $item->id_kecamatan }}">{{ $item->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Akses Tipe Usaha</label>
                            <select name="akses_tipe_usaha" id="edit_akses_tipe_usaha" class="form-select" required>
                                <option value="">Pilih Jenis Usaha</option>
                                <option value="sppg">SPPG</option>
                                <option value="tpp">TPP</option>
                                <option value="dam">DAM</option>
                                <option value="kantin">Kantin</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Password Baru (kosongkan jika tidak mengganti)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteOperatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="deleteOperatorForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Admin Kecamatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Yakin ingin menghapus akun <strong id="deleteOperatorUsername">-</strong>?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const qInput = document.getElementById('q');
            const kecamatanSelect = document.getElementById('kecamatan_id');
            let typingTimer = null;

            if (qInput && sessionStorage.getItem('refocusSearch') === '1') {
                qInput.focus();
                const len = qInput.value.length;
                qInput.setSelectionRange(len, len);
                sessionStorage.removeItem('refocusSearch');
            }

            if (qInput) {
                qInput.addEventListener('input', function () {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(function () {
                        sessionStorage.setItem('refocusSearch', '1');
                        form.requestSubmit();
                    }, 400);
                });
            }

            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('change', function () {
                    form.requestSubmit();
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === '/' && document.activeElement !== qInput) {
                    event.preventDefault();
                    qInput.focus();
                }
            });

            const detailModal = document.getElementById('detailOperatorModal');
            const editModal = document.getElementById('editOperatorModal');
            const deleteModal = document.getElementById('deleteOperatorModal');

            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    document.getElementById('detailUsername').textContent = button.getAttribute('data-username') || '-';
                    document.getElementById('detailAkses').textContent = (button.getAttribute('data-akses') || '-').toUpperCase();
                    document.getElementById('detailKecamatan').textContent = button.getAttribute('data-kecamatan') || '-';
                    document.getElementById('detailCreated').textContent = button.getAttribute('data-created') || '-';
                });
            }

            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const updateUrl = button.getAttribute('data-update-url');
                    const username = button.getAttribute('data-username');
                    const kecamatanId = button.getAttribute('data-kecamatan-id') || '';
                    const aksesType = button.getAttribute('data-akses-type') || '';

                    document.getElementById('editOperatorForm').setAttribute('action', updateUrl);
                    document.getElementById('editOperatorUsername').textContent = username || '-';

                    const kecSelect = document.getElementById('edit_id_kecamatan');
                    if (kecSelect) kecSelect.value = kecamatanId;

                    const aksesSelect = document.getElementById('edit_akses_tipe_usaha');
                    if (aksesSelect) aksesSelect.value = aksesType;
                });
            }

            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const deleteUrl = button.getAttribute('data-delete-url');
                    const username = button.getAttribute('data-username');

                    document.getElementById('deleteOperatorForm').setAttribute('action', deleteUrl);
                    document.getElementById('deleteOperatorUsername').textContent = username || '-';
                });
            }
        });
    </script>
@endsection