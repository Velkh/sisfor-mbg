<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pengawasan SLHS per Kecamatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/rekap.css') }}">
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
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="summary-badge kec">KEC</span>
                            <div class="summary-value">{{ number_format(($rekapPerKecamatan ?? collect())->count(), 0, ',', '.') }}</div>
                            <div class="summary-label">Kecamatan</div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-badge sppg">UU</span>
                            <div class="summary-value">{{ number_format($rekapTotalSppg ?? 0, 0, ',', '.') }}</div>
                            <div class="summary-label">Unit Usaha</div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-badge kpm">KPM</span>
                            <div class="summary-value">{{ number_format($rekapTotalKelompok ?? 0, 0, ',', '.') }}</div>
                            <div class="summary-label">Kelompok Penerima</div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-badge pm">PM</span>
                            <div class="summary-value">{{ number_format($rekapTotalPenerima ?? 0, 0, ',', '.') }}</div>
                            <div class="summary-label">Orang Penerima</div>
                        </div>
                    </div>

                    <div class="group-block">
                        <div class="group-pill">Unit Usaha</div>
                        <div class="stats-grid">
                            @foreach (($unitUsahaCards ?? []) as $card)
                                <div class="stats-card">
                                    <div class="stats-title">{{ $card['judul'] }}</div>
                                    <div class="stats-main">{{ number_format((int) ($card['nilai'] ?? 0), 0, ',', '.') }}</div>
                                    <div class="stats-sub">{{ $card['subNilai'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="group-block">
                        <div class="group-pill">Satuan Pendidikan</div>
                        <div class="stats-grid">
                            @foreach (($pendidikanCards ?? []) as $card)
                                <div class="stats-card">
                                    <span class="dot {{ $card['warna'] }}">{{ $card['kode'] }}</span>
                                    <div class="stats-title">{{ $card['judul'] }}</div>
                                    <div class="stats-main">{{ number_format((int) ($card['jumlah'] ?? 0), 0, ',', '.') }}</div>
                                    <div class="stats-sub">{{ $card['subJumlah'] }}</div>
                                    <div class="stats-secondary">{{ number_format((int) ($card['nilai'] ?? 0), 0, ',', '.') }}</div>
                                    <div class="stats-sub">{{ $card['subNilai'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="group-block">
                        <div class="group-pill">Kelompok B3</div>
                        <div class="stats-grid">
                            @foreach (($kelompokB3Cards ?? []) as $card)
                                <div class="stats-card">
                                    <div class="stats-title" style="min-height: 20px;">{{ $card['judul'] }}</div>
                                    <div class="stats-main">{{ number_format((int) ($card['nilai'] ?? 0), 0, ',', '.') }}</div>
                                    <div class="stats-sub">{{ $card['subNilai'] }}</div>
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
                    <button class="recap-btn" data-type="penerima">Data Rekap Penerima</button>
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
                                    <th>TPP</th>
                                    <th>DAM</th>
                                    <th>KANTIN</th>
                                    <th>JUMLAH PEGAWAI</th>
                                    <th>JUMLAH PENJAMAH TERLATIH</th>
                                    <th>AKTIF</th>
                                    <th>NONAKTIF</th>
                                </tr>
                            </thead>
                            <tbody id="table-kelompok-penerima">
                                <tr>
                                    <td colspan="9" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-content" data-type="penerima">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>KECAMATAN</th>
                                    <th>SMA SEDERAJAT</th>
                                    <th>SMP SEDERAJAT</th>
                                    <th>SD SEDERAJAT</th>
                                    <th>TKA/PAUD SEDERAJAT</th>
                                    <th>BALITA</th>
                                    <th>BUMIL</th>
                                    <th>BUSUI</th>
                                    <th>UMUM</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody id="table-penerima">
                                <tr>
                                    <td colspan="10" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <h2>Daftar Unit Usaha</h2>
            </div>
            <form id="filterForm" method="GET" action="{{ route('guest.rekap') }}" class="filter-form">
                <input type="hidden" name="kecamatan_id" id="kecamatan_id" value="{{ request('kecamatan_id') }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/alamat...">
                <select name="jenis_usaha">
                    <option value="">Semua Jenis</option>
                    <option value="TPP" @selected(request('jenis_usaha') === 'TPP')>TPP</option>
                    <option value="DAM" @selected(request('jenis_usaha') === 'DAM')>DAM</option>
                    <option value="Kantin" @selected(request('jenis_usaha') === 'Kantin')>Kantin</option>
                    <option value="SPPG" @selected(request('jenis_usaha') === 'SPPG')>SPPG</option>
                </select>
                <button type="submit">Filter</button>
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
                                    <th>Jumlah Pegawai</th>
                                    <th>Kelompok Penerima</th>
                                    <th>Penerima (orang)</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-daftar-sppg">
                                @forelse ($rows as $row)
                                    @php
                                        $totalPenerima = (int) ($row->total_siswa ?? 0)
                                            + (int) ($row->total_bumil ?? 0)
                                            + (int) ($row->total_busui ?? 0)
                                            + (int) ($row->total_balita ?? 0)
                                            + (int) ($row->total_jiwa ?? 0);

                                        $statusIklRaw = strtolower((string) ($row->laporanSlhs?->status_ikl ?? ''));
                                        $statusSlhsRaw = strtolower((string) ($row->laporanSlhs?->status_slhs ?? ''));

                                        $statusMap = [
                                            'belum_mengajukan' => 'Belum Mengajukan',
                                            'sudah_mengajukan' => 'Sudah Mengajukan',
                                            'selesai' => 'Selesai',
                                            '' => 'Belum Ada',
                                        ];

                                        $statusIklLabel = $statusMap[$statusIklRaw] ?? ucfirst($statusIklRaw);
                                        $statusSlhsLabel = $statusMap[$statusSlhsRaw] ?? ucfirst($statusSlhsRaw);

                                        if ($statusIklRaw === 'selesai') {
                                            $nilaiIkl = (float) ($row->laporanSlhs?->nilai_ikl ?? 0);
                                            $statusIklLabel = $nilaiIkl >= 80 ? 'Memenuhi Syarat' : 'Belum Memenuhi';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="name">
                                            <strong>{{ $row->nama_unit_usaha ?? '-' }}</strong>
                                            <small>{{ mb_strtoupper($row->jenis_usaha ?? '-') }}</small>
                                        </td>
                                        <td>{{ $statusIklLabel }}</td>
                                        <td>{{ $statusSlhsLabel }}</td>
                                        <td>{{ number_format((int) ($row->jumlah_pegawai ?? 0), 0, ',', '.') }}</td>
                                        <td>{{ number_format((int) ($row->kelompok_penerima ?? 0), 0, ',', '.') }}</td>
                                        <td>{{ number_format($totalPenerima, 0, ',', '.') }}</td>
                                        <td class="actions">
                                            <a href="{{ route('guest.sppg.show', $row->id_unit_usaha) }}" class="btn btn-detail">Detail</a>                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty">Belum ada data sarana.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
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
        document.addEventListener('DOMContentLoaded', () => {
            const recapBtns = document.querySelectorAll('.recap-btn');
            const recapTables = document.querySelectorAll('.table-content');
            const daftarKecLinks = document.querySelectorAll('.daftar-kec-link');
            const kecamatanIdInput = document.getElementById('kecamatan_id');

            loadRekapTable('sppg');

            recapBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.type;

                    recapBtns.forEach((item) => item.classList.remove('active'));
                    btn.classList.add('active');

                    recapTables.forEach((item) => item.classList.remove('active'));
                    const target = document.querySelector(`.table-content[data-type="${type}"]`);
                    if (target) {
                        target.classList.add('active');
                    }

                    loadRekapTable(type);
                });
            });

            daftarKecLinks.forEach((link) => {
                link.addEventListener('click', async (event) => {
                    event.preventDefault();

                    const kecamatanId = link.dataset.kecamatanId || '';
                    const tableBody = document.getElementById('table-daftar-sppg');

                    tableBody.innerHTML = '<tr><td colspan="8" class="loading-state"><div class="loading-spinner"></div> Loading...</td></tr>';

                    try {
                        const apiUrl = kecamatanId
                            ? `/api/rekap/kecamatan/${kecamatanId}`
                            : '/api/rekap/kecamatan';

                        const response = await fetch(apiUrl);

                        if (!response.ok) {
                            const errorText = await response.text();
                            throw new Error(`HTTP ${response.status}: ${errorText}`);
                        }

                        const data = await response.json();

                        if (!data.success) {
                            throw new Error(data.message || 'Data gagal dimuat');
                        }

                        daftarKecLinks.forEach((item) => item.classList.remove('active'));
                        link.classList.add('active');

                        if (kecamatanIdInput) {
                            kecamatanIdInput.value = kecamatanId;
                        }

                        renderDaftarSppg(data.rows || []);

                        if (kecamatanId) {
                            window.history.pushState({}, '', `{{ route('guest.rekap') }}?kecamatan_id=${kecamatanId}`);
                        } else {
                            window.history.pushState({}, '', `{{ route('guest.rekap') }}`);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        tableBody.innerHTML = '<tr><td colspan="8" class="empty">Error: Gagal memuat data.</td></tr>';
                    }
                });
            });
        });

        async function loadRekapTable(type) {
            const tbodyMap = {
                sppg: 'table-sppg',
                'kelompok-penerima': 'table-kelompok-penerima',
                penerima: 'table-penerima',
            };

            const tableBody = document.getElementById(tbodyMap[type]);
            if (!tableBody) {
                return;
            }

            const colspan = type === 'kelompok-penerima' ? 9 : type === 'penerima' ? 10 : 8;
            tableBody.innerHTML = `<tr><td colspan="${colspan}" class="loading-state"><div class="loading-spinner"></div> Loading...</td></tr>`;

            try {
                const response = await fetch(`/api/rekap/${type}`);

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Data gagal dimuat');
                }

                renderRekapRows(type, data.rows || []);
            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML = `<tr><td colspan="${colspan}" class="empty">Error: Gagal memuat data.</td></tr>`;
            }
        }

        function renderRekapRows(type, rows) {
            if (type === 'sppg') {
                const tableBody = document.getElementById('table-sppg');

                if (!rows.length) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="empty">Belum ada data.</td></tr>';
                    return;
                }

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
                return;
            }

            if (type === 'kelompok-penerima') {
                const tableBody = document.getElementById('table-kelompok-penerima');

                if (!rows.length) {
                    tableBody.innerHTML = '<tr><td colspan="9" class="empty">Belum ada data.</td></tr>';
                    return;
                }

                tableBody.innerHTML = rows.map((row) => `
                    <tr>
                        <td>${escapeHtml(row.kecamatan)}</td>
                        <td>${row.sppg}</td>
                        <td>${row.tpp}</td>
                        <td>${row.dam}</td>
                        <td>${row.kantin}</td>
                        <td>${row.jumlah_pegawai}</td>
                        <td>${row.jumlah_penjamah_terlatih}</td>
                        <td>${row.aktif}</td>
                        <td>${row.nonaktif}</td>
                    </tr>
                `).join('');
                return;
            }

            if (type === 'penerima') {
                const tableBody = document.getElementById('table-penerima');

                if (!rows.length) {
                    tableBody.innerHTML = '<tr><td colspan="10" class="empty">Belum ada data.</td></tr>';
                    return;
                }

                tableBody.innerHTML = rows.map((row, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(row.kecamatan)}</td>
                        <td>${row.sma_sederajat}</td>
                        <td>${row.smp_sederajat}</td>
                        <td>${row.sd_sederajat}</td>
                        <td>${row.tka_paud_sederajat}</td>
                        <td>${row.balita}</td>
                        <td>${row.bumil}</td>
                        <td>${row.busui}</td>
                        <td>${row.umum}</td>
                        <td><strong>${row.jumlah}</strong></td>
                    </tr>
                `).join('');
            }
        }

        function renderDaftarSppg(rows) {
            const tableBody = document.getElementById('table-daftar-sppg');
            const baseDetailUrl = "{{ route('guest.sppg.show', ['unit' => '___ID___']) }}";

            if (!rows.length) {
                tableBody.innerHTML = '<tr><td colspan="8" class="empty">Belum ada data Unit Usaha di Wilayah Ini.</td></tr>';
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
                        <td>${escapeHtml(row.jumlah_pegawai)}</td>
                        <td>${escapeHtml(row.kelompok_penerima)}</td>
                        <td>${escapeHtml(row.total_penerima)}</td>
                        <td class="actions">
                            <a href="${detailUrl}" class="btn btn-detail">Detail</a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function escapeHtml(value) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            };

            return String(value ?? '').replace(/[&<>"']/g, (char) => map[char]);
        }

        const filterForm = document.getElementById('filterForm');

        if (filterForm) {
            filterForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const params = new URLSearchParams(new FormData(filterForm));
                const tableBody = document.getElementById('table-daftar-sppg');

                tableBody.innerHTML = '<tr><td colspan="8" class="loading-state"><div class="loading-spinner"></div> Loading...</td></tr>';

                try {
                    const response = await fetch(`/api/rekap/kecamatan?${params.toString()}`);
                    const data = await response.json();

                    if (!data.success) {
                        throw new Error(data.message || 'Data gagal dimuat');
                    }

                    renderDaftarSppg(data.rows || []);
                    window.history.pushState({}, '', `{{ route('guest.rekap') }}?${params.toString()}`);
                } catch (error) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="empty">Error: Gagal memuat data.</td></tr>';
                }
            });
        }
    </script>
</body>
</html>