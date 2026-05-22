<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil SLHS Unit Usaha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profilsppg.css') }}">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    
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
            grid-column: 1 / -1; /* Agar membentang penuh jika kosong */
        }
        
        /* Tambahan style dasar untuk tab (jika belum ada di profilsppg.css) */
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-btn.active { font-weight: bold; background-color: #e5e7eb; border-radius: 6px; }
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
            <li><a href="#sasaran">Sasaran & Lokasi</a></li>
            <li> <a href="{{ route('guest.rekap') }}" class="btn-green">Kembali</a></li>
        </ul>
    </nav>
    <div class="wrap">
        <div class="title">{{ strtoupper($item->jenis_usaha) }}</div>
        <div class="subtitle">{{ $item->nama_unit_usaha ?? 'Nama Unit Tidak Tersedia' }}</div>

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
                                <td>{{ $item->nama_pemilik ?? '-' }}</td>
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
                    @if ($item->fotos && $item->fotos->count() > 0)
                        @foreach ($item->fotos as $foto)
                            <div class="gallery-item">
                                <a href="{{ asset('storage/' . $foto->foto_unit_usaha) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto->foto_unit_usaha) }}" alt="Foto Unit">
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
            <div class="sasaran-header">Informasi Lanjutan</div>
            <div class="sasaran-layout">
                <div class="sidebar-menu">
                    <button class="tab-btn active" data-target="tab-map">Peta Lokasi</button>
                    <button class="tab-btn" data-target="tab-data">Data Sasaran</button>
                </div>

                <div class="sidebar-content">
                    
                    <div id="tab-map" class="tab-content active">
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; height: 400px; width: 100%;">
                            <div id="map" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>

                    <div id="tab-data" class="tab-content">
                        <table class="data-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f3f4f6;">
                                    <th style="padding: 12px; border-bottom: 2px solid #e5e7eb;">No</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #e5e7eb;">Kelompok Penerima</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #e5e7eb;">Tipe</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #e5e7eb;">Status</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #e5e7eb;">Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporans ?? [] as $lap)
                                    @php
                                        $penerima = (int) ($lap->jumlah_siswa ?? 0) + (int) ($lap->jumlah_bumil ?? 0)
                                            + (int) ($lap->jumlah_busui ?? 0) + (int) ($lap->jumlah_balita ?? 0) + (int) ($lap->jumlah_jiwa ?? 0);
                                    @endphp
                                    <tr>
                                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $loop->iteration }}</td>
                                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;"><strong>{{ $lap->nama_instansi ?? '-' }}</strong></td>
                                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $lap->tipe_instansi ?? '-' }}</td>
                                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ $lap->status ?? '-' }}</td>
                                        <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">{{ number_format($penerima, 0, ',', '.') }}</td>
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
        document.addEventListener('DOMContentLoaded', function () {
            // === 1. LOGIKA TABS ===
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Hapus class active dari semua tombol dan konten
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Tambahkan class active ke tombol yang diklik dan konten targetnya
                    btn.classList.add('active');
                    const targetContent = document.getElementById(btn.dataset.target);
                    targetContent.classList.add('active');

                    // FIX LEAFLET BUG: Peta sering error (grey area) jika ditaruh di dalam tab tersembunyi
                    // map.invalidateSize() memaksa peta mengukur ulang kotaknya setelah ditampilkan
                    if (btn.dataset.target === 'tab-map' && map) {
                        setTimeout(() => { map.invalidateSize(); }, 100);
                    }
                });
            });

            // === 2. LOGIKA PETA LEAFLET ===
            // Ambil koordinat dari unit, default ke Balaikota Depok jika kosong
            const lat = {{ $item->latitude ?? -6.4025 }};
            const lng = {{ $item->longitude ?? 106.7942 }};
            const adaKoordinat = {{ ($item->latitude && $item->longitude) ? 'true' : 'false' }};

            // Buat peta, render ke elemen id="map"
            const map = L.map('map').setView([lat, lng], adaKoordinat ? 16 : 12);

            // Muat peta jalan
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Pasang pin jika koordinat valid
            if (adaKoordinat) {
                const marker = L.marker([lat, lng]).addTo(map);
                
                const popupHTML = `
                    <div style="text-align: center; min-width: 180px;">
                        <h6 style="margin-bottom: 5px; font-weight: bold;">{{ $item->nama_unit_usaha }}</h6>
                        <p style="margin-bottom: 10px; font-size: 12px; color: #666;">
                            {{ $item->alamat }}
                        </p>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                           target="_blank" 
                           class="btn-green" 
                           style="display: inline-block; padding: 6px 12px; font-size: 12px;">
                           Buka di Google Maps
                        </a>
                    </div>
                `;
                
                marker.bindPopup(popupHTML).openPopup();
            } else {
                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<div style='text-align:center'><b>Koordinat belum disetel</b><br>Menampilkan titik default</div>").openPopup();
            }
        });
    </script>
</body>
</html>