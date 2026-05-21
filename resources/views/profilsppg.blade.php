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
        <div class="subtitle">{{ $item->nama_unit ?? 'Nama Unit Tidak Tersedia' }}</div>

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
                                <td>Jumlah Penjamah Terlatih</td>
                                <td>{{ $item->jumlah_penjamah_terlatih ?? 0 }} orang</td>
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
                    <button class="tab-btn" data-target="tab-data">Data Sasaran</button>
                </div>

                <div class="sidebar-content">
                    <div id="tab-data" class="tab-content active">
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
                </div>
            </div>
        </section>
    </div>
     <footer class="footer">
                <div class="footer-grid">
                    <div>
                        <div class="footer-brand-name">Dashboard SLHS</div>
                        <div class="footer-brand-sub">Sistem Informasi Pengawasan<br>Higiene Sanitasi Pangan</div>
                        <div class="footer-address"> Jalan Margonda Raya No.54, Depok 16431<br> Gedung Balai Kota Depok </div>
                    </div>
                    
                    <div>
                        <h4 class="footer-col-title">Link</h4>
                        <ul class="footer-links">
                            <li><a href="#hero">Beranda</a></li>
                            <li><a href="#about">Tentang SLHS</a></li>
                            <li><a href="{{ route('login') }}">Login Sistem</a></li>
                            <li><a href="#faq">FAQ Syarat IKL</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="footer-col-title">Pusat</h4>
                        <ul class="footer-links">
                            <li><a href="https://kemkes.go.id/" target="_blank">Kementerian Kesehatan</a></li>
                            <li><a href="https://badanpangan.go.id/" target="_blank">Badan Pangan Nasional</a></li>
                            <li><a href="https://www.pom.go.id/" target="_blank">Badan POM</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="footer-col-title">Daerah</h4>
                        <ul class="footer-links">
                            <li><a href="https://depok.go.id/" target="_blank">Pemkot Depok</a></li>
                            <li><a href="https://dinkes.depok.go.id/" target="_blank">Dinas Kesehatan Depok</a></li>
                            <li><a href="https://dpmptsp.depok.go.id/" target="_blank">DPMPTSP Depok</a></li>
                            <li><a href="https://diskominfo.depok.go.id/" target="_blank">Diskominfo Depok</a></li>
                        </ul>
                        <h4 class="footer-col-title" style="margin-top:18px;">Portal Layanan</h4>
                        <ul class="footer-links">
                            <li><a href="https://oss.go.id/" target="_blank">OSS RBA</a></li>
                            <li><a href="https://opendata.depok.go.id/" target="_blank">Opendata Depok</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <span><strong>Dinas Kesehatan & Diskominfo</strong> Kota Depok</span>
                    <span>Copyright &copy; {{ date('Y') }}</span>
                </div>
            </footer>

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