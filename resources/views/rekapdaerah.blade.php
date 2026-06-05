<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pengawasan SLHS per Kecamatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/rekap.css') }}">
    <script src="{{ asset('js/rekap.js') }}"></script>
</head>
<body>
    <header class="top">
        <div class="top-inner">
            <div class="title">
                <h1>Rekap Pengawasan SLHS per Kecamatan</h1>
                <p>Data publik pengawasan SLHS berdasarkan wilayah kecamatan.</p>
            </div>
            <a href="{{ route('guest.index') }}" class="btn btn-outline">Kembali ke Beranda</a>
        </div>
    </header>   

    <main class="wrap">
        <section class="card">
            <div class="card-head">
                <h2>Data Pengawasan SLHS</h2>
                <h2>Kota Depok</h2>
            </div>
            <div class="card-body">
                <div class="summary-wrap">
                     <div class="group-block">
                    <div class="group-pill">Ringkasan</div>
                    <div class="stats-grid">
                    <div class="stats-card clickable-card" data-jenis="" data-status="" title="Total Unit Usaha">
                        <div class="stats-title">Total TPP</div>
                        <div class="stats-main">{{ number_format($summaryTotals['total_units'] ?? 0,0,',','.') }}</div>
                        <div class="stats-sub">Semua unit</div>
                    </div>

                    <div class="stats-card clickable-card" data-jenis="" data-status="sudah_slhs" title="Unit Usaha Sudah SLHS">
                        <div class="stats-title">TPP BerSLHS</div>
                        <div class="stats-main">{{ number_format($summaryTotals['total_slhs'] ?? 0,0,',','.') }}</div>
                        <div class="stats-sub">Sudah SLHS</div>
                    </div>
                    </div>
                </div>

                <div class="group-block">
                    <div class="group-pill">Jenis TPP</div>
                    <div class="stats-grid">
                    @foreach ($jenisUsahaCards as $card)
                        <div class="stats-card clickable-card" data-jenis="{{ $card['jenis'] }}" data-status="">
                        <!-- Menggunakan strtoupper di sini -->
                        <div class="stats-title">{{ strtoupper($card['judul']) }}</div>
                        <div class="stats-main">{{ number_format((int) ($card['nilai'] ?? 0),0,',','.') }}</div>
                        <div class="stats-sub">{{ $card['subNilai'] ?? '' }}</div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <div class="group-block">
                    <div class="group-pill">TPP BerSLHS</div>
                    <div class="stats-grid">
                    @foreach ($jenisUsahaSlhsCards as $card)
                        <div class="stats-card clickable-card" data-jenis="{{ $card['jenis'] }}" data-status="sudah_slhs">
                        <!-- Menggunakan strtoupper di sini -->
                        <div class="stats-title">{{ strtoupper($card['judul']) }}</div>
                        <div class="stats-main">{{ number_format((int) ($card['nilai'] ?? 0),0,',','.') }}</div>
                        <div class="stats-sub">{{ $card['subNilai'] ?? 'Sudah SLHS' }}</div>
                        </div>
                    @endforeach
                    </div>
                </div>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <h2>Rekap Data Unit Usaha</h2>
            </div>
            <div class="card-body">
                <div class="recap-buttons">
                    <button class="recap-btn active" data-type="sppg">Data Rekap Unit Usaha</button>
                    <button class="recap-btn" data-type="kelompok-penerima">Data Rekap Sebaran Unit</button>
                </div>

                <div class="table-content active" data-type="sppg">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>KECAMATAN</th>
                                    <th>JUMLAH UNIT USAHA</th>
                                    <th>MEMENUHI IKL</th>
                                    <th>BELUM MEMENUHI IKL</th>
                                    <th>BELUM MENGAJUKAN IKL</th>
                                    <th>SUDAH SLHS</th>
                                    <th>BELUM SLHS</th>
                                </tr>
                            </thead>
                            <tbody id="table-sppg">
                                <tr>
                                    <td colspan="8" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-content" data-type="kelompok-penerima">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>KECAMATAN</th>
                                    <th>SPPG</th>
                                    <th>RESTORAN</th>
                                    <th>Catering</th>
                                    <th>DAM</th>
                                    <th>KANTIN</th>
                                </tr>
                            </thead>
                            <tbody id="table-kelompok-penerima">
                                <tr>
                                    <td colspan="6" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tambahkan ID section-tabel disini untuk target Auto-Scroll -->
        <section class="card" id="section-tabel">
            <div class="card-head">
                <h2>Daftar Unit Usaha</h2>
            </div>
            
            <form id="filterForm" method="GET" action="{{ route('guest.rekap') }}" class="filter-form">
                <input type="hidden" name="kecamatan_id" id="kecamatan_id" value="{{ request('kecamatan_id') }}">
                
                <!-- Input Hidden untuk ditangkap JS & Backend -->
                <input type="hidden" name="jenis_usaha" id="jenis_usaha_input" value="{{ request('jenis_usaha') }}">
                <input type="hidden" name="status_slhs" id="status_slhs_input" value="{{ request('status_slhs') }}">
                
                <!-- Search Box dengan Tombol Reset -->
                <div class="search-box">
                    <input type="text" name="q" id="searchInput" value="{{ request('q') }}" placeholder="Cari nama unit usaha/alamat...">
                    <button type="submit">Cari Data</button>
                    <button type="button" id="btnResetFilter" class="btn-reset" style="display: none;">Tampilkan Semua</button>
                </div>

                <!-- Tab Pills sebagai pengganti Dropdown -->
                <div class="category-tabs">
                    <button type="button" class="cat-tab {{ empty(request('jenis_usaha')) ? 'active' : '' }}" data-value="">Semua Jenis</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'Restoran' ? 'active' : '' }}" data-value="Restoran">Restoran</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'Catering' ? 'active' : '' }}" data-value="Catering">Catering</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'DAM' ? 'active' : '' }}" data-value="DAM">DAM</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'Kantin' ? 'active' : '' }}" data-value="Kantin">Kantin</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'SPPG' ? 'active' : '' }}" data-value="SPPG">SPPG</button>
                    <button type="button" class="cat-tab {{ request('jenis_usaha') == 'TPP' ? 'active' : '' }}" data-value="TPP">TPP</button>
                </div>
            </form>
            
            <div class="card-body">
                <div class="content-grid">
                    <aside>
                        <ul class="kec-list">
                            <li>
                                <a class="kec-link daftar-kec-link {{ empty($selectedKecamatanId) ? 'active' : '' }}" href="{{ route('guest.rekap') }}" data-kecamatan-id="">
                                    Semua Kecamatan
                                </a>
                            </li>
                            @foreach (($rekapPerKecamatan ?? collect()) as $rekap)
                                @php
                                    $idKec = (int) $rekap->id_kecamatan;
                                    $isActive = (int) ($selectedKecamatanId ?? 0) === $idKec;
                                @endphp
                                <li>
                                    <a class="kec-link daftar-kec-link {{ $isActive ? 'active' : '' }}" href="{{ route('guest.rekap', ['kecamatan_id' => $idKec]) }}" data-kecamatan-id="{{ $idKec }}">
                                        {{ $rekap->nama_kecamatan ?? 'Kecamatan' }}
                                        ({{ number_format((int) ($rekap->total_sppg ?? 0), 0, ',', '.') }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Nama Sarana</th>
                                    <th>Status IKL</th>
                                    <th>Status SLHS</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-daftar-sppg">
                                <tr>
                                    <td colspan="8" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
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

    <!-- Bagian JAVASCRIPT UTAMA -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Deklarasi Elemen
            const recapBtns = document.querySelectorAll('.recap-btn');
            const recapTables = document.querySelectorAll('.table-content');
            const daftarKecLinks = document.querySelectorAll('.daftar-kec-link');
            const filterForm = document.getElementById('filterForm');
            const kecamatanIdInput = document.getElementById('kecamatan_id');
            const jenisUsahaInput = document.getElementById('jenis_usaha_input');
            const statusSlhsInput = document.getElementById('status_slhs_input');
            const searchInput = document.getElementById('searchInput');
            const sectionTabel = document.getElementById('section-tabel');
            const btnResetFilter = document.getElementById('btnResetFilter');
            const clickableCards = document.querySelectorAll('.clickable-card');
            const catTabs = document.querySelectorAll('.cat-tab');

            // --- 2. LOGIKA TAB REKAP UNIT (Atas) ---
            recapBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.type;
                    recapBtns.forEach((item) => item.classList.remove('active'));
                    btn.classList.add('active');
                    recapTables.forEach((item) => item.classList.remove('active'));
                    const target = document.querySelector(`.table-content[data-type="${type}"]`);
                    if (target) target.classList.add('active');
                    loadRekapTable(type);
                });
            });

            // --- 3. LOGIKA KLIK KARTU (Card Angka UX Baru) ---
            clickableCards.forEach(card => {
                card.addEventListener('click', () => {
                    const jenis = card.dataset.jenis || '';
                    const status = card.dataset.status || '';

                    // Styling Card yang aktif
                    clickableCards.forEach(c => c.classList.remove('active-card'));
                    card.classList.add('active-card');

                    // Set Nilai ke Input
                    jenisUsahaInput.value = jenis;
                    statusSlhsInput.value = status;

                    // Sinkronisasi Tab Pills
                    catTabs.forEach(t => {
                        if(t.dataset.value === jenis && status === '') {
                            t.classList.add('active');
                        } else if(jenis === '' && status === '' && t.dataset.value === '') {
                            t.classList.add('active');
                        } else {
                            t.classList.remove('active');
                        }
                    });

                    // Tampilkan Tombol Reset & Submit Data
                    toggleResetButton();
                    filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));

                    // Animasi Auto Scroll
                    setTimeout(() => {
                        sectionTabel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                });
            });

            // --- 4. LOGIKA TAB PILLS JENIS USAHA ---
            catTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    catTabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    
                    jenisUsahaInput.value = tab.dataset.value;
                    
                    // Jika ganti pill, kita reset status SLHS agar tidak tercampur filter ganda
                    statusSlhsInput.value = ''; 
                    
                    // Bersihkan Card Aktif
                    clickableCards.forEach(c => c.classList.remove('active-card'));

                    toggleResetButton();
                    filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                });
            });

            // --- 5. LOGIKA TOMBOL RESET ---
            if (btnResetFilter) {
                btnResetFilter.addEventListener('click', () => {
                    jenisUsahaInput.value = '';
                    statusSlhsInput.value = '';
                    searchInput.value = '';
                    
                    // Reset Tampilan Card & Tab
                    clickableCards.forEach(c => c.classList.remove('active-card'));
                    catTabs.forEach(t => t.classList.remove('active'));
                    document.querySelector('.cat-tab[data-value=""]').classList.add('active'); // Aktifkan 'Semua Jenis'
                    
                    toggleResetButton();
                    filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                });
            }

            function toggleResetButton() {
                if (jenisUsahaInput.value !== '' || statusSlhsInput.value !== '' || searchInput.value !== '') {
                    btnResetFilter.style.display = 'block';
                } else {
                    btnResetFilter.style.display = 'none';
                }
            }

            // --- 6. LOGIKA FILTER & FETCH DATA (AJAX) ---
            if (filterForm) {
                filterForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const params = new URLSearchParams(new FormData(filterForm));
                    const tableBody = document.getElementById('table-daftar-sppg');
                    tableBody.innerHTML = '<tr><td colspan="8" class="loading-state"><div class="loading-spinner"></div> Loading...</td></tr>';

                    try {
                        const response = await fetch(`/api/rekap/kecamatan?${params.toString()}`);
                        const data = await response.json();

                        if (!data.success) throw new Error(data.message || 'Data gagal dimuat');

                        renderDaftarSppg(data.rows || []);
                        window.history.pushState({}, '', `{{ route('guest.rekap') }}?${params.toString()}`);
                    } catch (error) {
                        tableBody.innerHTML = '<tr><td colspan="8" class="empty">Error: Gagal memuat data.</td></tr>';
                    }
                });
            }

            // --- 7. LOGIKA MENU KIRI (Kecamatan) ---
            daftarKecLinks.forEach((link) => {
                link.addEventListener('click', async (event) => {
                    event.preventDefault();
                    const kecamatanId = link.dataset.kecamatanId || '';
                    
                    // Update tampilan menu samping aktif
                    daftarKecLinks.forEach((item) => item.classList.remove('active'));
                    link.classList.add('active');

                    // Update ID kecamatan di form hidden, lalu trigger submit form
                    if (kecamatanIdInput) {
                        kecamatanIdInput.value = kecamatanId;
                        filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                    }
                });
            });

            // (Fungsi renderRekapRows, loadRekapTable, escapeHtml, dan renderDaftarSppg TETAP SAMA)
            async function loadRekapTable(type) {
                const tbodyMap = { sppg: 'table-sppg', 'kelompok-penerima': 'table-kelompok-penerima' };
                const tableBody = document.getElementById(tbodyMap[type]);
                if (!tableBody) return;

                const colspan = type === 'kelompok-penerima' ? 9 : 8;
                tableBody.innerHTML = `<tr><td colspan="${colspan}" class="loading-state"><div class="loading-spinner"></div> Loading...</td></tr>`;

                try {
                    const response = await fetch(`/api/rekap/${type}`);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    if (!data.success) throw new Error(data.message);
                    renderRekapRows(type, data.rows || []);
                } catch (error) {
                    tableBody.innerHTML = `<tr><td colspan="${colspan}" class="empty">Error: Gagal memuat data.</td></tr>`;
                }
            }

            function renderRekapRows(type, rows) {
                if (type === 'sppg') {
                    const tableBody = document.getElementById('table-sppg');
                    if (!rows.length) return tableBody.innerHTML = '<tr><td colspan="8" class="empty">Belum ada data.</td></tr>';
                    tableBody.innerHTML = rows.map((row, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${escapeHtml(row.kecamatan)}</td>
                            <td>${row.jumlah_sppg}</td>
                            <td>${row.memenuhi_ikl}</td>
                            <td>${row.belum_memenuhi_ikl}</td>
                            <td>${row.belum_mengajukan_ikl}</td>
                            <td>${row.sudah_slhs}</td>
                            <td>${row.belum_slhs}</td>
                        </tr>
                    `).join('');
                }
                if (type === 'kelompok-penerima') {
                    const tableBody = document.getElementById('table-kelompok-penerima');
                    if (!rows.length) return tableBody.innerHTML = '<tr><td colspan="9" class="empty">Belum ada data.</td></tr>';
                    tableBody.innerHTML = rows.map((row) => `
                        <tr>
                            <td>${escapeHtml(row.kecamatan)}</td>
                            <td>${row.sppg}</td>
                            <td>${row.restoran}</td>
                            <td>${row.catering}</td>
                            <td>${row.dam}</td>
                            <td>${row.kantin}</td>
                        </tr>
                    `).join('');
                }
            }

            function renderDaftarSppg(rows) {
                const tableBody = document.getElementById('table-daftar-sppg');
                const baseDetailUrl = "{{ route('guest.sppg.show', ['unit' => '___ID___']) }}";
                if (!rows.length) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="empty">Belum ada data Unit Usaha yang sesuai kriteria.</td></tr>';
                    return;
                }
                tableBody.innerHTML = rows.map((row, index) => {
                    const unitId = row.id_sppg ?? row.id_unit_usaha ?? row.id;
                    const detailUrl = unitId ? baseDetailUrl.replace('___ID___', unitId) : '#';
                    return `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="name">
                                <strong>${escapeHtml(row.nama_unit_usaha ?? row.nama_sppg ?? '-')}</strong>
                                <small>${escapeHtml(String(row.jenis_usaha || '-').toUpperCase())}</small>
                            </td>
                            <td>${escapeHtml(row.status_ikl_label)}</td>
                            <td>${escapeHtml(row.status_slhs_label)}</td>
                            <td class="actions">
                                <a href="${detailUrl}" class="btn btn-detail">Detail</a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            function escapeHtml(value) {
                const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
                return String(value ?? '').replace(/[&<>"']/g, (char) => map[char]);
            }
            
            loadRekapTable('sppg');
            toggleResetButton();
            
            // Trigger form submit untuk load data tabel daftar sppg saat pertama kali
            if (filterForm) {
                filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            }
        });
    </script>
</body>
</html>