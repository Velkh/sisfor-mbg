<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil SPPG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profilsppg.css') }}">
    
    <style>
        .foto-lokasi-header {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 0.5rem;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            width: 100%;
            height: 160px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .gallery-item:hover {
            transform: scale(1.02);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-placeholder {
            width: 100%;
            height: 160px;
            border-radius: 12px;
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.9rem;
        }
        .gallery-placeholder svg {
            width: 32px;
            height: 32px;
            margin-bottom: 8px;
            fill: #9ca3af;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('guest.index') }}" class="navbar-brand">
            <div class="logo-pill">
                <div class="logo-circle gold-c">
                    <img src="https://upload.wikimedia.org/wikipedia/id/thumb/2/29/Logo_Badan_Gizi_Nasional.svg/3840px-Logo_Badan_Gizi_Nasional.svg.png" alt="BGN" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <span style="display:none;">BGN</span>
                </div>
            </div>
            <div class="brand-text">
                Dashboard SLHS
                <small>Kota Depok</small>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#profil">Profil</a></li>
            <li><a href="#foto-lokasi">Foto</a></li>
            <li><a href="#sasaran">Sasaran</a></li>
            <li> <a href="{{ route('guest.rekap') }}" class="btn-green">Kembali</a></li>
        </ul>
    </nav>
    <div class="wrap">
        <div class="title">{{ strtoupper($item->jenis_usaha) }}</div>
        <div class="subtitle">{{ $item->nama_sppg ?? 'Nama SPPG Tidak Tersedia' }}</div>

        <section class="card" id="profil">
            <div class="card-body">
                <div class="profile-grid">
                    <div class="photo-box">
                        <div class="photo-circle">
                            @if(!empty($item->foto_kepala))
                                <img src="{{ asset('storage/' . $item->foto_kepala) }}" alt="Foto Kepala">
                            @else
                                <span>👤</span>
                            @endif
                        </div>
                        <div style="font-weight:700;">Pemilik Unit Usaha</div>
                    </div>

                    <div>
                        <table class="detail-table">
                            <tr>
                                <td>Nama Pemilik Usaha</td>
                                <td>{{ $item->nama_kepala ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>{{ $item->alamat ?? '-' }} Kelurahan {{ $item->kelurahan?->nama_kelurahan ?? '-' }} Kecamatan {{ $item->kecamatan?->nama_kecamatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Kelurahan</td>
                                <td>{{ $item->kelurahan?->nama_kelurahan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Kecamatan</td>
                                <td>{{ $item->kecamatan?->nama_kecamatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama Mitra</td>
                                <td>{{ $item->nama_mitra ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Pegawai</td>
                                <td>{{ $item->jumlah_pegawai ?? 0 }} orang</td>
                            </tr>
                            <tr>
                                <td>Kelompok Penerima</td>
                                <td>{{ $laporans->count() }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Penerima</td>
                                <td>
                                    {{
                                        (int) $laporans->sum('jumlah_siswa') +
                                        (int) $laporans->sum('jumlah_bumil') +
                                        (int) $laporans->sum('jumlah_busui') +
                                        (int) $laporans->sum('jumlah_balita')
                                    }} orang
                                </td>
                            </tr>
                            <tr>
                                <td>Pengawasan</td>
                                <td>{{ $item->puskesmas?->nama_puskesmas ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="card" id="foto-lokasi" style="margin-top: 24px;">
            <div class="card-body">
                <div class="foto-lokasi-header">Foto Lokasi Unit Usaha</div>
                <div class="gallery-grid">
                    @if ($item->fotos->count() > 0)
                        @foreach ($item->fotos as $foto)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ asset('storage/' . $foto->foto_unit_usaha) }}" target="_blank" class="d-block text-center text-decoration-none">
                                    <img
                                        src="{{ asset('storage/' . $foto->foto_unit_usaha) }}"
                                        alt="Foto Unit"
                                        class="img-thumbnail shadow-sm rounded"
                                        style="width: 100%; height: 150px; object-fit: cover;"
                                    >
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="gallery-placeholder">
                            Belum ada foto unit usaha.
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="sasaran-section" id="sasaran" style="margin-top: 24px;">
            <div class="sasaran-header">Sasaran Penerima</div>
            <div class="sasaran-layout">
                <div class="sidebar-menu">
                    <button class="tab-btn active" data-target="tab-rekap">Rekap Sasaran</button>
                    <button class="tab-btn" data-target="tab-data">Data Sasaran</button>
                    <button class="tab-btn" data-target="tab-peta">Peta Lokasi</button>
                </div>

                <div class="sidebar-content">

                    <div id="tab-rekap" class="tab-content active">
                        <div class="group-title">Satuan Pendidikan</div>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="badge-circle bg-sma">SMA</div>
                                <div class="stat-title">SMA & Sederajat</div>
                                <div class="stat-value">{{ number_format($sma['sekolah'], 0, ',', '.') }}</div>
                                <div class="stat-label">Sekolah</div>
                                <div class="stat-value">{{ number_format($sma['siswa'], 0, ',', '.') }}</div>
                                <div class="stat-label">Siswa</div>
                            </div>
                            <div class="stat-card">
                                <div class="badge-circle bg-smp">SMP</div>
                                <div class="stat-title">SMP & Sederajat</div>
                                <div class="stat-value">{{ number_format($smp['sekolah'], 0, ',', '.') }}</div>
                                <div class="stat-label">Sekolah</div>
                                <div class="stat-value">{{ number_format($smp['siswa'], 0, ',', '.') }}</div>
                                <div class="stat-label">Siswa</div>
                            </div>
                            <div class="stat-card">
                                <div class="badge-circle bg-sd">SD</div>
                                <div class="stat-title">SD & Sederajat</div>
                                <div class="stat-value">{{ number_format($sd['sekolah'], 0, ',', '.') }}</div>
                                <div class="stat-label">Sekolah</div>
                                <div class="stat-value">{{ number_format($sd['siswa'], 0, ',', '.') }}</div>
                                <div class="stat-label">Siswa</div>
                            </div>
                            <div class="stat-card">
                                <div class="badge-circle bg-tk">TK</div>
                                <div class="stat-title">TK / PAUD</div>
                                <div class="stat-value">{{ number_format($tk['sekolah'], 0, ',', '.') }}</div>
                                <div class="stat-label">Sekolah</div>
                                <div class="stat-value">{{ number_format($tk['siswa'], 0, ',', '.') }}</div>
                                <div class="stat-label">Siswa</div>
                            </div>
                        </div>

                        <div class="group-title">Kelompok B3</div>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-title">POSYANDU</div>
                                <div class="stat-value">{{ number_format($posyandu, 0, ',', '.') }}</div>
                                <div class="stat-label">Unit</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-title">BALITA</div>
                                <div class="stat-value">{{ number_format((int) $laporans->sum('jumlah_balita'), 0, ',', '.') }}</div>
                                <div class="stat-label">Anak</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-title">BUMIL</div>
                                <div class="stat-value">{{ number_format((int) $laporans->sum('jumlah_bumil'), 0, ',', '.') }}</div>
                                <div class="stat-label">Penerima</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-title">BUSUI</div>
                                <div class="stat-value">{{ number_format((int) $laporans->sum('jumlah_busui'), 0, ',', '.') }}</div>
                                <div class="stat-label">Penerima</div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-data" class="tab-content">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelompok Penerima</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporans as $lap)
                                    @php
                                        $penerima = (int) ($lap->jumlah_siswa ?? 0) + (int) ($lap->jumlah_bumil ?? 0)
                                            + (int) ($lap->jumlah_busui ?? 0) + (int) ($lap->jumlah_balita ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $lap->nama_instansi ?? '-' }}</strong></td>
                                        <td>{{ $lap->tipe_instansi ?? '-' }}</td>
                                        <td>{{ $lap->status ?? '-' }}</td>
                                        <td>{{ number_format($penerima, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #999; padding: 20px;">Belum ada data sasaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="tab-peta" class="tab-content">
                        <div style="width: 100%; height: 300px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #888;">
                            Area Peta Lokasi (Sematkan Leaflet JS di sini)
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                btn.classList.add('active');
                document.getElementById(btn.dataset.target).classList.add('active');
            });
        });
    </script>
</body>
</html>