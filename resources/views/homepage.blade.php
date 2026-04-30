<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard MBG – Kota Depok</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
</head>
<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="logo-pill">
                <div class="logo-circle gold-c">
                    <img src="https://upload.wikimedia.org/wikipedia/id/thumb/2/29/Logo_Badan_Gizi_Nasional.svg/3840px-Logo_Badan_Gizi_Nasional.svg.png" alt="BGN" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <span style="display:none;">BGN</span>
                </div>
            </div>
            <div class="brand-text">
                Dashboard MBG
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
                <h3>Apa itu MBG?</h3>
                <p>Program Makanan Bergizi Gratis (MBG) merupakan program strategis nasional untuk memastikan pemenuhan gizi bagi peserta didik, anak usia dini, ibu hamil, dan menyusui.</p>
                <p>Kota Depok dengan jumlah penduduk yang terus berkembang memiliki potensi besar untuk mengembangkan model pemenuhan pasokan MBG secara mandiri dan berkelanjutan, melalui optimalisasi potensi lokal pertanian dan peternakan.</p>
                <div class="text-center" style="text-align: center;">
                    <a href="#about" class="more-btn">
                        <span>Lebih Lanjut</span> <i class="bi bi-chevron-right">→</i>
                    </a>
                </div>
            </div>    

            <div class="hero-content">
                <div class="icon-box">
                    <i class="bi bi-gem">💎</i>
                    <h4>Urgensi Program MBG</h4>
                    <p>Menurunkan angka stunting dan malnutrisi di sekolah-sekolah yang konsisten menjalankan program MBG (contoh: dari 18% menjadi 7% dalam lima tahun).</p>
                    <p>Meningkatkan kehadiran siswa hingga 15% dan meningkatkan skor akademik sebesar 12%.</p>
                    <p>Memperkuat motivasi belajar serta menurunkan risiko putus sekolah, khususnya di daerah dengan keterbatasan ekonomi.</p>
                    <p>Mendorong keterlibatan komunitas sebagai aktor pendukung utama penyediaan pangan lokal.</p>
                </div>

                <div class="icon-box">
                    <i class="bi bi-person-bounding-box">👥</i>
                    <h4>Sasaran Program MBG</h4>
                    <p>Pada tahun 2025, jumlah sasaran MBG di Kota Depok diperkirakan mencapai 1,7 juta jiwa:</p>
                    <p>±6.000 sekolah dari jenjang SD, SMP, SMA/SMK hingga PAUD</p>
                    <p>±1,43 juta peserta didik, yang berhak menerima makanan bergizi gratis setiap hari</p>
                    <p>±250.000 ibu hamil, menyusui, dan anak usia dini non PAUD (0–6 tahun) sebagai bagian dari kelompok rawan gizi</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="about">
            <div>
                <span class="section-tag">Tentang Program</span>
                <h2 class="section-title">MBG Kota Depok</h2>
                <p class="about-lead">
                    Program Makan Bergizi Gratis (MBG) Kota Depok resmi diselenggarakan serentak dimulai pada <strong>6 Januari 2025</strong>. Pada awalnya sebanyak 39 sekolah dengan total 8.667 siswa menjadi penerima MBG dan terus diperluas cakupannya.
                </p>
                
                <div class="points">
                    <details class="point">
                        <summary>Pembangunan Infrastruktur <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Terdapat <strong>238 aset Pemda</strong> yang disiapkan untuk Dapur SPPG (94 lokasi direkomendasikan).<br><br>
                            • Dibangun Kemen PU di Limusnunggal, Kec. Cileungsi<br>
                            • Dibangun BGN di Ciangsana, Kec. Gunung Putri
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Dukungan Anggaran <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Pemkab Depok mengalokasikan anggaran untuk mendukung program MBG melalui APBD demi memastikan kesinambungan distribusi gizi.
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Dukungan Pangan Lokal <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Kebutuhan harian untuk seluruh sasaran:
                            <table>
                                <tr><th>Komoditas</th><th>Estimasi Kebutuhan</th></tr>
                                <tr><td>Sayuran</td><td>50–100 ton/hari</td></tr>
                                <tr><td>Telur Ayam</td><td>±500.000 butir/hari</td></tr>
                                <tr><td>Ayam / Ikan</td><td>±75–100 ton/hari</td></tr>
                                <tr><td>Cabe & Bumbu</td><td>±10 ton/hari</td></tr>
                            </table>
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Sinergi Stakeholder <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            Sinergi antara Pemkab Depok dengan TNI, POLRI, KADIN, PKK, DHARMAWANITA, Organisasi Keagamaan, Kepemudaan, dan unsur masyarakat lainnya.
                        </div>
                    </details>
                    
                    <details class="point">
                        <summary>Pengawasan & Evaluasi <svg class="arr" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></summary>
                        <div class="point-body">
                            • Pelatihan Keamanan Pangan Siap Saji (PKPSS): 162 SPPG<br>
                            • Pemeriksaan laboratorium: 57 SPPG<br>
                            • Proses SLHS: 37 SLHS<br>
                            • Inspeksi Kesehatan Lingkungan (Puskesmas): 143 SPPG
                        </div>
                    </details>
                </div>
            </div>    
            
            <div class="about-img-wrap">
                <img src="https://mbg.depok.go.id/web/assets/img/tentang.jpg" alt="MBG Kota Depok" class="about-img" onerror="this.style.display='none';">
                <div class="float-badge">2025<br><small>Diluncurkan</small></div>
            </div>
        </div>
    </section>

    <section id="stats" class="stats-section">
        <div class="stats-inner">
            <div class="sec-header">
                <span class="section-tag">Monitoring Real-Time</span>
                <h2 class="section-title">Data Penyaluran Program MBG<br>di Kota Depok</h2>
            </div>    

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-abbr">Kec</div>
                    <div class="stat-lbl">Kecamatan</div>
                    <div class="stat-val">{{ number_format($kecamatanAktif ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Kecamatan aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr">SPPG</div>
                    <div class="stat-lbl">Satuan Pelayanan Pemenuhan Gizi</div>
                    <div class="stat-val">{{ number_format($totalSppg ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Dapur aktif terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr">KPM</div>
                    <div class="stat-lbl">Kelompok Penerima Manfaat</div>
                    <div class="stat-val">{{ number_format($totalKpm ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Kelompok terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr">PM</div>
                    <div class="stat-lbl">Orang Penerima Manfaat</div>
                    <div class="stat-val">{{ number_format($totalPm ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Total penerima aktif</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-abbr">SMA</div>
                    <div class="stat-lbl">SMA & Sederajat</div>
                    <div class="stat-val">{{ number_format($totalSma ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sekolah aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr">SMP</div>
                    <div class="stat-lbl">SMP & Sederajat</div>
                    <div class="stat-val">{{ number_format($totalSmp ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sekolah aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr">SD</div>
                    <div class="stat-lbl">SD & Sederajat</div>
                    <div class="stat-val">{{ number_format($totalSd ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sekolah aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-abbr gd">TK</div>
                    <div class="stat-lbl">TK / PAUD & Sederajat</div>
                    <div class="stat-val">{{ number_format($totalTk ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Sekolah aktif</div>
                </div>
            </div>

            <div class="cta-center">
                <a href="{{ route('guest.rekap') }}" class="btn-green">
                    Selengkapnya
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">Dashboard MBG</div>
                <div class="footer-brand-sub">Program Makan Sehat Bergizi Gratis<br>Kota Depok</div>
                <div class="footer-address"> Jalan Margonda Raya, Depok 16953<br> </div>
            </div>
            
            <div>
                <h4 class="footer-col-title">Link</h4>
                <ul class="footer-links">
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="{{ route('login') }}">Login Aplikasi</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="footer-col-title">Pusat</h4>
                <ul class="footer-links">
                    <li><a href="https://www.bgn.go.id/" target="_blank">Badan Gizi Nasional</a></li>
                    <li><a href="https://badanpangan.go.id/" target="_blank">Badan Pangan Nasional</a></li>
                    <li><a href="https://kemkes.go.id/" target="_blank">Kementerian Kesehatan</a></li>
                    <li><a href="https://kemendikdasmen.go.id/" target="_blank">Kementerian Pendidikan</a></li>
                    <li><a href="https://www.kemendukbangga.go.id/" target="_blank">Kemendukbangga/BKKBN</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="footer-col-title">Daerah</h4>
                <ul class="footer-links">
                    <li><a href="https://depok.go.id/" target="_blank">Kota Depok</a></li>
                    <li><a href="https://diskominfo.depok.go.id/" target="_blank">Diskominfo</a></li>
                    <li><a href="https://dinkes.depok.go.id/" target="_blank">Dinas Kesehatan</a></li>
                    <li><a href="https://bppkb.depok.go.id/" target="_blank">Dinas BPPKB</a></li>
                    <li><a href="https://disdik.depok.go.id/" target="_blank">Dinas Pendidikan</a></li>
                </ul>
                <h4 class="footer-col-title" style="margin-top:18px;">Portal</h4>
                <ul class="footer-links">
                    <li><a href="https://opendata.depok.go.id/" target="_blank">Opendata</a></li>
                    <li><a href="https://jdih.depok.go.id/" target="_blank">JDIH</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <span><strong>Diskominfo</strong> Kota Depok</span>
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