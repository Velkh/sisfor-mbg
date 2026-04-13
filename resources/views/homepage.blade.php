<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard MBG – Kota Depok</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        :root {
            --g900: #0d3b1e;
            --g800: #155c30;
            --g700: #1a7a3f;
            --g600: #2e9e57;
            --g400: #52c77a;
            --g100: #d4f0df;
            --g50:  #edfaf2;
            --cream:  #faf8f3;
            --cream2: #f2ede2;
            --gold:   #c8922a;
            --text:   #1a1a1a;
            --muted:  #5e5e5e;
            --border: #ddd7cb;
            --white:  #ffffff;
            --r: 10px;
            --sh: 0 2px 16px rgba(0,0,0,.08);
            --sh-lg: 0 8px 40px rgba(0,0,0,.13);
        }

        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--cream); color:var(--text); font-size:15px; line-height:1.7; overflow-x:hidden; }
        a { text-decoration:none; color:inherit; }
        img { display:block; max-width:100%; }

        /* TOP BAR */
        .topbar { background:var(--g900); color:rgba(255,255,255,.7); font-size:.78rem; padding:6px 48px; display:flex; align-items:center; gap:24px; }
        .topbar a { color:rgba(255,255,255,.7); display:flex; align-items:center; gap:6px; }
        .topbar a:hover { color:var(--g400); }
        .topbar svg { width:13px; height:13px; flex-shrink:0; }

        /* NAVBAR */
        .navbar { position:sticky; top:0; z-index:200; background:var(--white); border-bottom:1px solid var(--border); padding:0 48px; display:flex; align-items:center; height:70px; box-shadow:0 2px 12px rgba(0,0,0,.06); }
        .navbar-brand { display:flex; align-items:center; gap:12px; flex-shrink:0; }
        .logo-pill { display:flex; align-items:center; gap:6px; }
        .logo-circle { width:38px; height:38px; border-radius:50%; background:var(--g800); display:grid; place-items:center; flex-shrink:0; overflow:hidden; }
        .logo-circle.gold-c { background:var(--gold); }
        .logo-circle img { width:100%; height:100%; object-fit:cover; }
        .logo-circle span { color:#fff; font-family:'Fraunces',serif; font-size:.7rem; font-weight:700; text-align:center; line-height:1.1; }
        .brand-text { font-family:'Fraunces',serif; font-size:1.05rem; font-weight:700; color:var(--g800); line-height:1.2; }
        .brand-text small { display:block; font-family:'Plus Jakarta Sans',sans-serif; font-size:.67rem; font-weight:500; color:var(--muted); }
        .nav-links { display:flex; align-items:center; gap:4px; list-style:none; margin-left:auto; }
        .nav-links a { padding:7px 13px; border-radius:7px; font-size:.84rem; font-weight:600; color:var(--muted); transition:all .2s; }
        .nav-links a:hover, .nav-links a.active { color:var(--g800); background:var(--g50); }
        .btn-login-nav { margin-left:8px; padding:8px 20px; background:var(--g700) !important; color:#fff !important; border-radius:7px; font-weight:700; }
        .btn-login-nav:hover { background:var(--g800) !important; }

        /* HERO */
        .hero { position:relative; background:linear-gradient(135deg, var(--g900) 0%, var(--g800) 55%, var(--g700) 100%); overflow:hidden; min-height:auto; display:flex; flex-direction:column; padding:60px 48px; }
        .hero-bg { position:absolute; inset:0; background:url('https://assets.promediateknologi.id/crop/0x0:0x0/1200x600/webp/photo/2023/07/26/DEPOK-2894385870.png') center/cover no-repeat; opacity:.13; z-index:0; }
        .hero-inner { position:relative; z-index:1; max-width:1200px; margin:0 auto; padding:0; display:grid; grid-template-columns:1fr 2fr; gap:40px; align-items:start; width:100%; }
        .why-box { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); border-radius:16px; padding:30px; backdrop-filter:blur(10px); color:#fff; height:100%; display:flex; flex-direction:column; }
        .why-box h3 { font-family:'Fraunces',serif; font-size:1.5rem; color:#fff; margin-bottom:16px; line-height:1.2; }
        .why-box p { color:rgba(255,255,255,.74); font-size:.9rem; margin-bottom:16px; line-height:1.7; }
        .why-box .more-btn { display:inline-flex; align-items:center; gap:8px; color:var(--g400); font-weight:700; font-size:.9rem; margin-top:auto; padding-top:16px; border-top:1px solid rgba(255,255,255,.1); }
        .why-box .more-btn:hover { text-decoration:underline; }
        .icon-box { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); border-radius:16px; padding:24px; backdrop-filter:blur(10px); color:#fff; height:100%; }
        .icon-box i { font-size:1.8rem; color:var(--g400); margin-bottom:12px; display:inline-block; }
        .icon-box h4 { font-family:'Fraunces',serif; font-size:1.2rem; color:#fff; margin-bottom:16px; line-height:1.2; }
        .icon-box p { color:rgba(255,255,255,.72); font-size:.87rem; margin-bottom:12px; line-height:1.6; }
        .icon-box p:last-child { margin-bottom:0; }
        .hero-content { display:grid; grid-template-columns:1fr 1fr; gap:24px; }

        /* ABOUT */
        .about { padding:88px 48px; max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:start; }
        .section-tag { display:inline-block; background:var(--g50); border:1px solid var(--g100); color:var(--g700); font-size:.7rem; font-weight:800; letter-spacing:.1em; text-transform:uppercase; padding:4px 13px; border-radius:100px; margin-bottom:12px; }
        .section-title { font-family:'Fraunces',serif; font-size:clamp(1.4rem,2.4vw,2rem); color:var(--g900); line-height:1.22; margin-bottom:18px; }
        .about-lead { color:var(--muted); font-size:.92rem; margin-bottom:22px; }

        .points { display:flex; flex-direction:column; gap:10px; }
        .point { background:var(--white); border:1px solid var(--border); border-radius:var(--r); overflow:hidden; }
        .point summary { padding:13px 17px; font-weight:700; font-size:.85rem; color:var(--g800); cursor:pointer; display:flex; align-items:center; gap:10px; list-style:none; user-select:none; }
        .point summary::-webkit-details-marker { display:none; }
        .point summary::before { content:''; width:6px; height:6px; border-radius:50%; background:var(--g600); flex-shrink:0; }
        .point summary .arr { margin-left:auto; width:15px; height:15px; stroke:var(--muted); fill:none; stroke-width:2; transition:transform .25s; }
        details[open] .point summary .arr { transform:rotate(90deg); }
        .point-body { padding:0 17px 15px 33px; font-size:.84rem; color:var(--muted); line-height:1.65; }
        .point-body table { width:100%; border-collapse:collapse; margin-top:10px; font-size:.8rem; }
        .point-body th, .point-body td { padding:6px 10px; border:1px solid var(--border); }
        .point-body th { background:var(--cream2); font-weight:700; color:var(--g900); }

        .about-img-wrap { position:relative; }
        .about-img { width:100%; aspect-ratio:4/5; object-fit:cover; border-radius:18px; box-shadow:var(--sh-lg); }
        .float-badge { position:absolute; bottom:-16px; right:-16px; background:var(--gold); color:#fff; padding:16px 20px; border-radius:13px; font-family:'Fraunces',serif; font-size:1.8rem; font-weight:700; text-align:center; line-height:1.15; box-shadow:var(--sh-lg); }
        .float-badge small { display:block; font-family:'Plus Jakarta Sans',sans-serif; font-size:.67rem; font-weight:600; opacity:.85; }

        /* STATS */
        .stats-section { background:var(--cream2); border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:76px 48px; }
        .stats-inner { max-width:1200px; margin:0 auto; }
        .sec-header { text-align:center; margin-bottom:44px; }
        .sec-header .section-title { margin-bottom:8px; }
        .sec-header p { color:var(--muted); font-size:.9rem; }

        .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:14px; }
        .stat-card { background:var(--white); border:1px solid var(--border); border-radius:var(--r); padding:20px 18px; display:flex; flex-direction:column; gap:9px; transition:all .25s; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--g600),var(--g400)); border-radius:var(--r) var(--r) 0 0; opacity:0; transition:opacity .25s; }
        .stat-card:hover { box-shadow:var(--sh); transform:translateY(-3px); }
        .stat-card:hover::before { opacity:1; }
        .stat-abbr { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; background:var(--g50); border:1.5px solid var(--g100); border-radius:8px; font-family:'Fraunces',serif; font-size:.95rem; font-weight:700; color:var(--g700); }
        .stat-abbr.gd { background:#fef8ec; border-color:#f5d9a1; color:var(--gold); }
        .stat-lbl { font-size:.77rem; color:var(--muted); font-weight:500; }
        .stat-val { font-family:'Fraunces',serif; font-size:1.55rem; color:var(--g900); line-height:1; }
        .stat-sub { font-size:.73rem; color:var(--muted); }

        .cta-center { text-align:center; margin-top:32px; }
        .btn-green { padding:11px 28px; background:var(--g700); color:#fff; border-radius:var(--r); font-weight:700; font-size:.88rem; display:inline-flex; align-items:center; gap:8px; transition:all .2s; }
        .btn-green:hover { background:var(--g800); transform:translateY(-2px); box-shadow:var(--sh); }

        /* REGULASI */
        .regulasi { padding:76px 48px; max-width:1200px; margin:0 auto; }
        .reg-tabs { display:flex; flex-wrap:wrap; gap:7px; margin-bottom:28px; }
        .reg-tab { padding:7px 15px; border-radius:8px; font-size:.79rem; font-weight:600; background:var(--white); border:1px solid var(--border); color:var(--muted); cursor:pointer; transition:all .2s; }
        .reg-tab.active, .reg-tab:hover { background:var(--g700); color:#fff; border-color:var(--g700); }
        .reg-panel { display:none; }
        .reg-panel.active { display:grid; grid-template-columns:1fr 300px; gap:44px; align-items:center; }
        .reg-panel h3 { font-family:'Fraunces',serif; font-size:1.3rem; color:var(--g900); margin-bottom:12px; }
        .reg-panel p { color:var(--muted); font-size:.89rem; margin-bottom:18px; }
        .btn-dl { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:var(--g50); border:1.5px solid var(--g600); color:var(--g700); border-radius:var(--r); font-weight:700; font-size:.85rem; transition:all .2s; }
        .btn-dl:hover { background:var(--g700); color:#fff; }
        .btn-dl svg { width:15px; height:15px; stroke:currentColor; fill:none; stroke-width:2; }
        .reg-img { width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:13px; box-shadow:var(--sh-lg); }
        .reg-img-ph { width:100%; aspect-ratio:3/4; background:var(--cream2); border:2px dashed var(--border); border-radius:13px; display:grid; place-items:center; color:var(--muted); font-size:.8rem; }

        /* FAQ */
        .faq-section { background:var(--cream2); border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:76px 48px; }
        .faq-inner { max-width:800px; margin:0 auto; }
        .faq-list { margin-top:32px; display:flex; flex-direction:column; gap:9px; }
        .faq-item { background:var(--white); border:1px solid var(--border); border-radius:var(--r); overflow:hidden; }
        .faq-q { padding:15px 19px; font-weight:700; font-size:.88rem; color:var(--g900); cursor:pointer; display:flex; justify-content:space-between; align-items:center; gap:12px; user-select:none; list-style:none; }
        .faq-q::-webkit-details-marker { display:none; }
        .faq-q .plus { width:21px; height:21px; border:2px solid var(--g600); border-radius:50%; display:grid; place-items:center; flex-shrink:0; font-size:.95rem; color:var(--g600); transition:transform .25s; }
        details[open] .faq-q .plus { transform:rotate(45deg); }
        .faq-a { padding:0 19px 15px; font-size:.86rem; color:var(--muted); line-height:1.7; }
        .faq-a a { color:var(--g700); font-weight:600; }
        .faq-a a:hover { text-decoration:underline; }
        .faq-more { text-align:center; margin-top:24px; }
        .faq-more a { color:var(--g700); font-weight:700; font-size:.88rem; display:inline-flex; align-items:center; gap:6px; }
        .faq-more a:hover { text-decoration:underline; }

        /* LOGIN */
        .login-section { padding:76px 48px; background:var(--white); }
        .login-inner { max-width:440px; margin:0 auto; text-align:center; }
        .login-inner > p { color:var(--muted); margin-bottom:28px; font-size:.9rem; }
        .login-form { background:var(--cream); border:1px solid var(--border); border-radius:16px; padding:30px; text-align:left; box-shadow:var(--sh); }
        .form-group { margin-bottom:16px; }
        .form-label { display:block; font-size:.78rem; font-weight:700; color:var(--g900); margin-bottom:5px; }
        .form-input { width:100%; padding:10px 13px; border:1.5px solid var(--border); border-radius:8px; font-family:inherit; font-size:.875rem; background:var(--white); color:var(--text); transition:border-color .2s; outline:none; }
        .form-input:focus { border-color:var(--g600); box-shadow:0 0 0 3px rgba(46,158,87,.1); }
        .captcha-wrap { display:flex; align-items:center; gap:9px; margin-bottom:16px; }
        .captcha-box { flex:1; height:42px; background:var(--cream2); border:1.5px solid var(--border); border-radius:8px; display:grid; place-items:center; font-size:.77rem; color:var(--muted); font-style:italic; }
        .captcha-reload { padding:8px 11px; background:var(--white); border:1.5px solid var(--border); border-radius:8px; color:var(--g700); font-size:.77rem; font-weight:600; cursor:pointer; transition:all .2s; font-family:inherit; }
        .captcha-reload:hover { background:var(--g50); border-color:var(--g600); }
        .btn-submit { width:100%; padding:12px; background:var(--g700); color:#fff; border:none; border-radius:8px; font-family:inherit; font-size:.9rem; font-weight:700; cursor:pointer; transition:background .2s,transform .15s; }
        .btn-submit:hover { background:var(--g800); transform:translateY(-1px); }

        /* FOOTER */
        footer { background:var(--g900); color:rgba(255,255,255,.68); padding:60px 48px 30px; }
        .footer-grid { max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1.8fr 1fr 1fr 1fr; gap:44px; padding-bottom:44px; border-bottom:1px solid rgba(255,255,255,.1); }
        .footer-brand-name { font-family:'Fraunces',serif; font-size:1.1rem; color:#fff; margin-bottom:5px; }
        .footer-brand-sub { font-size:.77rem; color:rgba(255,255,255,.5); margin-bottom:14px; line-height:1.6; }
        .footer-address { font-size:.8rem; line-height:1.85; }
        .footer-address strong { color:rgba(255,255,255,.88); }
        .footer-col-title { font-family:'Fraunces',serif; color:#fff; font-size:.93rem; margin-bottom:14px; }
        .footer-links { list-style:none; display:flex; flex-direction:column; gap:7px; }
        .footer-links a { font-size:.8rem; color:rgba(255,255,255,.62); transition:color .2s; }
        .footer-links a:hover { color:var(--g400); }
        .footer-bottom { max-width:1200px; margin:24px auto 0; display:flex; justify-content:space-between; align-items:center; font-size:.77rem; color:rgba(255,255,255,.32); }
        .footer-bottom strong { color:rgba(255,255,255,.6); }

        /* RESPONSIVE */
        @media(max-width:1024px) {
            .hero-inner { grid-template-columns:1fr; gap:24px; }
            .hero-content { grid-template-columns:1fr; }
            .about { grid-template-columns:1fr; gap:36px; }
            .stats-grid { grid-template-columns:1fr 1fr; }
            .footer-grid { grid-template-columns:1fr 1fr; gap:32px; }
        }
        @media(max-width:768px) {
            .topbar,.navbar { padding:0 18px; }
            .hero,.about,.stats-section,.regulasi,.faq-section,.login-section,footer { padding-left:18px; padding-right:18px; }
            .hero-inner { padding:0; }
            .nav-links { display:none; }
            .stats-grid { grid-template-columns:1fr; }
            .reg-panel.active { grid-template-columns:1fr; }
            .footer-grid { grid-template-columns:1fr; }
            .footer-bottom { flex-direction:column; gap:6px; text-align:center; }
            .hero-content { grid-template-columns:1fr; }
            .why-box { padding:20px; }
            .icon-box { padding:20px; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="" class="navbar-brand">
        <div class="logo-pill">
            <div class="logo-circle gold-c">
                <img src="https://upload.wikimedia.org/wikipedia/id/thumb/2/29/Logo_Badan_Gizi_Nasional.svg/3840px-Logo_Badan_Gizi_Nasional.svg.png" alt="BGN" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">>
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
        <li><a href="{{ route('login') }}" class="btn-login-nav">Login</a></li>
    </ul>
</nav>

{{-- HERO --}}
<section id="hero" class="hero">
    <div class="hero-bg"></div>
    <div class="hero-inner">
        {{-- Left: Why Box --}}
        <div class="why-box">
            <h3>Apa itu MBG?</h3>
            <p>Program Makanan Bergizi Gratis (MBG) merupakan program strategis nasional untuk memastikan pemenuhan gizi bagi peserta didik, anak usia dini, ibu hamil, dan menyusui.</p>
            <p>Kota Depok dengan jumlah penduduk yang terus berkembang memiliki potensi besar untuk mengembangkan model pemenuhan pasokan MBG secara mandiri dan berkelanjutan, melalui optimalisasi potensi lokal pertanian dan peternakan.</p>
            <div class="text-center" style="text-align: center;">
                <a href="#about" class="more-btn"><span>Lebih Lanjut</span> <i class="bi bi-chevron-right">→</i></a>
            </div>
        </div>

        {{-- Right: Content Grid --}}
        <div class="hero-content">
            {{-- Urgensi Box --}}
            <div class="icon-box">
                <i class="bi bi-gem">💎</i>
                <h4>Urgensi Program MBG</h4>
                <p>Menurunkan angka stunting dan malnutrisi di sekolah-sekolah yang konsisten menjalankan program MBG (contoh: dari 18% menjadi 7% dalam lima tahun).</p>
                <p>Meningkatkan kehadiran siswa hingga 15% dan meningkatkan skor akademik sebesar 12%.</p>
                <p>Memperkuat motivasi belajar serta menurunkan risiko putus sekolah, khususnya di daerah dengan keterbatasan ekonomi.</p>
                <p>Mendorong keterlibatan komunitas sebagai aktor pendukung utama penyediaan pangan lokal.</p>
            </div>

            {{-- Sasaran Box --}}
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

{{-- ABOUT --}}
<section id="about">
    <div class="about">
        {{-- Left text --}}
        <div>
            <span class="section-tag">Tentang Program</span>
            <h2 class="section-title">MBG Kota Depok</h2>
            <p class="about-lead">
                Program Makan Bergizi Gratis (MBG) Kota Depok resmi diselenggarakan serentak dimulai pada
                <strong>6 Januari 2025</strong>. Pada awalnya sebanyak 39 sekolah dengan total 8.667 siswa menjadi
                penerima MBG dan terus diperluas cakupannya.
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
                    <div class="point-body">Pemkab Depok mengalokasikan anggaran untuk mendukung program MBG melalui APBD demi memastikan kesinambungan distribusi gizi.</div>
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
                    <div class="point-body">Sinergi antara Pemkab Depok dengan TNI, POLRI, KADIN, PKK, DHARMAWANITA, Organisasi Keagamaan, Kepemudaan, dan unsur masyarakat lainnya.</div>
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

        {{-- Right image --}}
        <div class="about-img-wrap">
            <img src="https://mbg.depok.go.id/web/assets/img/tentang.jpg"
                 alt="MBG Kota Depok" class="about-img"
                 onerror="this.style.display='none';">
            <div class="float-badge">2025<br><small>Diluncurkan</small></div>
        </div>
    </div>
</section>

{{-- DATA PENYALURAN --}}
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
                <div class="stat-val">–</div>
                <div class="stat-sub">Kecamatan aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr">SPPG</div>
                <div class="stat-lbl">Satuan Pelayanan Pemenuhan Gizi</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Dapur aktif terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr">KPM</div>
                <div class="stat-lbl">Kelompok Penerima Manfaat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Kelompok terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr">PM</div>
                <div class="stat-lbl">Orang Penerima Manfaat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Total penerima aktif</div>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-abbr">SMA</div>
                <div class="stat-lbl">SMA & Sederajat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Sekolah aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr">SMP</div>
                <div class="stat-lbl">SMP & Sederajat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Sekolah aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr">SD</div>
                <div class="stat-lbl">SD & Sederajat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Sekolah aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-abbr gd">TK</div>
                <div class="stat-lbl">TK / PAUD & Sederajat</div>
                <div class="stat-val">–</div>
                <div class="stat-sub">Sekolah aktif</div>
            </div>
        </div>

        <div class="cta-center">
            <a href="" class="btn-green">
                Selengkapnya
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
{{-- FOOTER --}}
<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand-name">Dashboard MBG</div>
            <div class="footer-brand-sub">Program Makan Sehat Bergizi Gratis<br>Kota Depok</div>
            <div class="footer-address">
                Jalan Margonda Raya, Depok 16953<br>
                <strong>Phone:</strong> (021) 7877-7777<br>
                <strong>Email:</strong> diskominfo@depok.go.id
            </div>
        </div>
        <div>
            <h4 class="footer-col-title">Link</h4>
            <ul class="footer-links">
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#entrance">Login Aplikasi</a></li>
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
// Regulasi tabs
document.querySelectorAll('.reg-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const idx = tab.dataset.panel;
        document.querySelectorAll('.reg-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.reg-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.querySelector('.reg-panel[data-index="'+idx+'"]').classList.add('active');
    });
});

// Active nav highlight on scroll
const navLinks = document.querySelectorAll('.nav-links a');
window.addEventListener('scroll', () => {
    let cur = '';
    document.querySelectorAll('section[id]').forEach(s => {
        if (window.scrollY >= s.offsetTop - 90) cur = s.id;
    });
    navLinks.forEach(a => {
        a.classList.remove('active');
        if (a.getAttribute('href') === '#' + cur) a.classList.add('active');
    });
});
</script>
</body>
</html>