<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SLHS – Kota Depok</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <style>
        /* Tambahan CSS inline untuk menata foto operasional yang baru */
        .new-operational-photo {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('guest.index') }}" class="navbar-brand">
            <div class="logo-pill">
                <div class="logo-circle gold-c">
                    <img src="{{ asset('images/images.jpeg') }}"  alt="Pemkot Depok" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <span style="display:none;">DPK</span>
                </div>
            </div>
            <div class="brand-text">
                Dashboard SLHS
                <small>Kota Depok</small>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#hero" class="active">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#stats">Statistik</a></li>
            
            @auth
                <li class="user-info">
                    <span class="username">{{ auth()->user()->username }}</span>
                    <span class="badge">{{ auth()->user()->role }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-login-nav btn-logout">Keluar</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="btn-login-nav">Login</a></li>
            @endauth
        </ul>
    </nav>

    <section id="hero" class="hero">
        <div class="hero-bg"></div>
        <div class="hero-inner">
            <div class="why-box">
                <h3>Apa itu IKL & SLHS?</h3>
                <p><strong>Inspeksi Kesehatan Lingkungan (IKL)</strong> adalah pengamatan, pemeriksaan, dan penilaian langsung terhadap kondisi fisik dan sanitasi tempat usaha untuk memastikan standar kesehatan terpenuhi.</p>
                <p><strong>Sertifikat Laik Higiene Sanitasi (SLHS)</strong> adalah bukti tertulis dan jaminan keamanan pangan yang dikeluarkan oleh Dinas Kesehatan bagi usaha yang telah memenuhi standar baku mutu kesehatan lingkungan.</p>
                <div class="text-center" style="text-align: center;">
                    <a href="#about" class="more-btn">
                        <span>Lebih Lanjut</span> <i class="bi bi-chevron-right">→</i>
                    </a>
                </div>
            </div>    

            <div class="hero-content">
                <div class="icon-box">
                    <i class="bi bi-shield-check">🛡️</i>
                    <h4>Urgensi Keamanan Pangan</h4>
                    <p>Mencegah terjadinya kontaminasi silang, keracunan makanan, dan penyebaran penyakit menular (foodborne diseases) di masyarakat.</p>
                    <p>Meningkatkan kepercayaan publik dan konsumen terhadap kualitas serta higienitas produk yang dihasilkan.</p>
                    <p>Memastikan kepatuhan hukum pelaku usaha sesuai dengan regulasi kesehatan dari Kementerian Kesehatan RI dan Pemkot Depok.</p>
                </div>

                <div class="icon-box">
                    <i class="bi bi-shop-window">🏪</i>
                    <h4>Sasaran & Cakupan</h4>
                    <p>Web Dashboard SLHS ini menjadi wadah besar pemantauan dan pendaftaran untuk berbagai jenis usaha pangan di Kota Depok, meliputi:</p>
                    <p><strong>SPPG</strong> (Satuan Pelayanan Pemenuhan Gizi)</p>
                    <p><strong>TPP</strong> (Tempat Pengelolaan Pangan / Jasaboga / Restoran / Rumah Makan)</p>
                    <p><strong>DAM</strong> (Depot Air Minum)</p>
                    <p><strong>Kantin</strong> (Kantin Sekolah, Institusi, dan Perkantoran)</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="about">
            <div>
                <span class="section-tag">Tentang Standar</span>
                <h2 class="section-title">Menuju Depok Sehat<br>& Aman Pangan</h2>
                <p class="about-lead">
                    Pemerintah Kota Depok berkomitmen mewujudkan lingkungan dan pangan yang sehat. Kepemilikan <strong>SLHS</strong> bukan sekadar formalitas administrasi, melainkan wujud tanggung jawab moral pelaku usaha terhadap kesehatan konsumen.
                </p>
                
                <div class="points">
                    <details class="point">
                        <summary>Pentingnya IKL Berkala <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Inspeksi berkala oleh Sanitarian Puskesmas atau Dinas Kesehatan memastikan fasilitas sanitasi, penanganan bahan baku, hingga kebersihan penjamah makanan (food handler) selalu terjaga dan tidak mengalami penurunan kualitas.
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Pengujian Laboratorium <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Selain observasi fisik, penilaian SLHS juga didukung oleh bukti ilmiah melalui pengujian sampel air bersih, sampel makanan, hingga usap alat masak untuk mendeteksi keberadaan bakteri <i>E. coli</i> atau cemaran kimia.
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Pelatihan Keamanan Pangan <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Setiap penjamah makanan wajib mengikuti Pelatihan Keamanan Pangan Siap Saji (PKPSS) agar memahami prinsip dasar higiene perorangan dan tata cara mengelola makanan dengan aman.
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Alur Penerbitan SLHS <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            • Pengajuan formulir dan dokumen persyaratan melalui sistem.<br>
                            • Pelaksanaan IKL oleh tim petugas kesehatan (Puskesmas/Dinkes).<br>
                            • Pemeriksaan hasil uji laboratorium (jika dipersyaratkan).<br>
                            • Penerbitan SLHS melalui DPMPTSP atau sistem perizinan terkait jika dinyatakan memenuhi syarat (MS).
                        </div>
                    </details>
                </div>
            </div>    
            
            <div class="about-img-wrap">
                <!-- Foto operasional sanitarian Kota Depok sedang inspeksi -->
                <img src="images/inspeksi.webp" alt="Seorang petugas sanitarian Kota Depok sedang melakukan inspeksi higienitas menggunakan alat pengukur suhu pada permukaan persiapan makanan di dapur SPPG yang bersih dan modern, menekankan urgensi SLHS." class="about-img new-operational-photo">
                <div class="float-badge">Higiene<br><small>Prioritas Utama</small></div>
            </div>
        </div>
    </section>

    <section id="stats" class="stats-section">
        <div class="stats-inner">
            <div class="sec-header">
                <span class="section-tag">Monitoring Real-Time</span>
                <h2 class="section-title">Data Pengawasan IKL & SLHS<br>di Kota Depok</h2>
            </div>

            <!-- Baris 1 -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-abbr">TS</span>
                    <div class="stat-lbl">Total Sarana</div>
                    <div class="stat-val">{{ number_format($totalSarana ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sarana terdaftar</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr">IKL</span>
                    <div class="stat-lbl">Proses IKL</div>
                    <div class="stat-val">{{ number_format($prosesIkl ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Menunggu inspeksi</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr">MS</span>
                    <div class="stat-lbl">Memenuhi IKL</div>
                    <div class="stat-val">{{ number_format($memenuhiIkl ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">IKL ≥ 80</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr">SLHS</span>
                    <div class="stat-lbl">SLHS Terbit</div>
                    <div class="stat-val">{{ number_format($slhsTerbit ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sertifikat resmi</div>
                </div>
            </div>

            <!-- Baris 2 -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-abbr">PK</span>
                    <div class="stat-lbl">Penjamah Terlatih</div>
                    <div class="stat-val">{{ number_format($penjamahTerlatih ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Lulus PKPSS</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr">SKL</span>
                    <div class="stat-lbl">Cakupan Sekolah</div>
                    <div class="stat-val">{{ number_format($cakupanSekolah ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Institusi pendidikan</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr">B3</span>
                    <div class="stat-lbl">Cakupan B3</div>
                    <div class="stat-val">{{ number_format($cakupanB3 ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Posyandu/instansi</div>
                </div>
                <div class="stat-card">
                    <span class="stat-abbr gd">PM</span>
                    <div class="stat-lbl">Penerima Manfaat</div>
                    <div class="stat-val">{{ number_format($penerimaManfaat ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Orang per hari</div>
                </div>
            </div>
        </div>
            <div class="cta-center">
                <a href="{{ route('guest.rekap') }}" class="btn-green">
                    Lihat Rekap Daerah
                </a>
            </div>
    </section>

    <footer>
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
        // Script untuk mendeteksi scroll dan mengubah state 'active' pada navbar
        const navLinks = document.querySelectorAll('.nav-links a');
        
        window.addEventListener('scroll', () => {
            let cur = '';
            document.querySelectorAll('section[id]').forEach(s => {
                if (window.scrollY >= s.offsetTop - 90) {
                    cur = s.id;
                }
            });
            
            navLinks.forEach(a => {
                a.classList.remove('active');
                if (a.getAttribute('href') === '#' + cur) {
                    a.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>