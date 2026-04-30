<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap SPPG per Kecamatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/rekap.css') }}">
    <style>
        .recap-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .recap-btn {
            padding: 15px 20px;
            background-color: #3d5a7a;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .recap-btn:hover {
            background-color: #2d4461;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .recap-btn.active {
            background-color: #ff9800;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
        }
        .table-content {
            display: none;
        }
        .table-content.active {
            display: block;
        }
        .loading-state {
            text-align: center;
            padding: 30px;
            color: #999;
        }
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3d5a7a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <header class="top">
        <div class="top-inner">
            <div class="title">
                <h1>Rekap SPPG per Kecamatan</h1>
                <p>Data publik SPPG berdasarkan wilayah kecamatan.</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline">Kembali ke Beranda</a>
        </div>
    </header>

    <main class="wrap">
        <section class="card">
            <div class="card-head">
                <h2>Data Penyaluran Program MBG</h2>
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
                            <span class="summary-badge sppg">SPPG</span>
                            <div class="summary-value">{{ number_format($rekapTotalSppg ?? 0, 0, ',', '.') }}</div>
                            <div class="summary-label">SPPG</div>
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
                <h2>Rekap Data SPPG</h2>
            </div>
            <div class="card-body">
                <div class="recap-buttons">
                    <button class="recap-btn active" data-type="sppg">Data Rekap SPPG</button>
                    <button class="recap-btn" data-type="kelompok-penerima">Data Rekap Kelompok Penerima</button>
                    <button class="recap-btn" data-type="penerima">Data Rekap Penerima</button>
                </div>

                <div class="table-content active" data-type="sppg">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>KECAMATAN</th>
                                    <th>JUMLAH SPPG</th>
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
                                    <th>SMA SEDERAJAT</th>
                                    <th>SMP SEDERAJAT</th>
                                    <th>SD SEDERAJAT</th>
                                    <th>TKA/PAUD SEDERAJAT</th>
                                    <th>POSYANDU</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody id="table-kelompok-penerima">
                                <tr>
                                    <td colspan="7" class="loading-state"><div class="loading-spinner"></div> Loading...</td>
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
                <h2>Daftar SPPG</h2>
            </div>
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
                                    <th>Nama SPPG</th>
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
                                            + (int) ($row->total_balita ?? 0);

                                        $statusIklRaw = strtolower((string) ($row->status_ikl ?? ''));
                                        $statusSlhsRaw = strtolower((string) ($row->status_slhs ?? ''));

                                        $statusMap = [
                                            'lolos' => 'Lolos',
                                            'tidak lolos' => 'Belum Lolos',
                                            'belum lolos' => 'Belum Lolos',
                                            'gagal' => 'Belum Lolos',
                                            'pending' => 'Belum Ada',
                                            'belum ada' => 'Belum Ada',
                                            '' => 'Belum Ada',
                                        ];

                                        $statusIklLabel = $statusMap[$statusIklRaw] ?? ucfirst($statusIklRaw);
                                        $statusSlhsLabel = $statusMap[$statusSlhsRaw] ?? ucfirst($statusSlhsRaw);

                                        if ($statusIklRaw === 'selesai') {
                                            $nilaiIkl = (float) ($row->nilai_ikl ?? 0);
                                            $statusIklLabel = $nilaiIkl > 80 ? 'Memenuhi Syarat' : 'Belum Memenuhi';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="name">
                                            <strong>{{ $row->nama_sppg }}</strong>
                                            <small>{{ $row->nama_mitra ?? '-' }}</small>
                                        </td>
                                        <td>{{ $statusIklLabel }}</td>
                                        <td>{{ $statusSlhsLabel }}</td>
                                        <td>{{ number_format((int) ($row->jml_pegawai ?? 0), 0, ',', '.') }}</td>
                                        <td>{{ number_format((int) ($row->kelompok_penerima ?? 0), 0, ',', '.') }}</td>
                                        <td>{{ number_format($totalPenerima, 0, ',', '.') }}</td>
                                        <td class="actions">
                                            <a href="{{ route('guest.sppg.show', $row->id_sppg) }}" class="btn btn-detail">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty">Belum ada data SPPG.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const recapBtns = document.querySelectorAll('.recap-btn');
            const recapTables = document.querySelectorAll('.table-content');
            const daftarKecLinks = document.querySelectorAll('.daftar-kec-link');

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

            const colspan = type === 'kelompok-penerima' ? 7 : type === 'penerima' ? 10 : 8;
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
                    tableBody.innerHTML = '<tr><td colspan="7" class="empty">Belum ada data.</td></tr>';
                    return;
                }

                tableBody.innerHTML = rows.map((row) => `
                    <tr>
                        <td>${escapeHtml(row.kecamatan)}</td>
                        <td>${row.sma_sederajat}</td>
                        <td>${row.smp_sederajat}</td>
                        <td>${row.sd_sederajat}</td>
                        <td>${row.tka_paud_sederajat}</td>
                        <td>${row.posyandu}</td>
                        <td><strong>${row.jumlah}</strong></td>
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
                        <td><strong>${row.jumlah}</strong></td>
                    </tr>
                `).join('');
            }
        }

        function renderDaftarSppg(rows) {
            const tableBody = document.getElementById('table-daftar-sppg');

            if (!rows.length) {
                tableBody.innerHTML = '<tr><td colspan="8" class="empty">Belum ada data SPPG.</td></tr>';
                return;
            }

            tableBody.innerHTML = rows.map((row, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td class="name">
                        <strong>${escapeHtml(row.nama_sppg)}</strong>
                        <small>${escapeHtml(row.nama_mitra)}</small>
                    </td>
                    <td>${escapeHtml(row.status_ikl_label)}</td>
                    <td>${escapeHtml(row.status_slhs_label)}</td>
                    <td>${escapeHtml(row.jml_pegawai)}</td>
                    <td>${escapeHtml(row.kelompok_penerima)}</td>
                    <td>${escapeHtml(row.total_penerima)}</td>
                    <td class="actions">
                        <a href="{{ url('/sppg') }}/${row.id_sppg}" class="btn btn-detail">Detail</a>
                    </td>
                </tr>
            `).join('');
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
    </script>
</body>
</html>