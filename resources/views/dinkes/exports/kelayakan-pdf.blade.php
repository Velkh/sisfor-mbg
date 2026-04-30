<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kelayakan SPPG</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        h2 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        .meta {
            margin-bottom: 10px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #555;
            padding: 6px;
            vertical-align: top;
        }
        th {
            background: #efefef;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h2>Laporan Kelayakan SPPG</h2>

    <div class="meta">
        <strong>Dicetak:</strong> {{ $generatedAt->format('d-m-Y H:i') }} |
        <strong>Filter Evaluasi:</strong> {{ $filters['evaluasi'] ?? 'all' }} |
        <strong>Pencarian:</strong> {{ !empty($filters['q']) ? $filters['q'] : '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">SPPG</th>
                <th style="width: 12%;">Puskesmas</th>
                <th style="width: 15%;">IKL</th>
                <th style="width: 15%;">SLHS</th>
                <th style="width: 12%;">Tgl IKL</th>
                <th style="width: 16%;">Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $index => $item)
                @php
                    $isIklLulus = $item->status_ikl === 'selesai'
                        && $item->hasil_ikl === 'memenuhi'
                        && (int) ($item->nilai_ikl ?? 0) >= 80;

                    $isLaikHigiene = $isIklLulus && $item->status_slhs === 'selesai';
                    $isBersyarat = $isIklLulus && $item->status_slhs !== 'selesai';

                    if ($isLaikHigiene) {
                        $evaluasiText = 'Laik Higiene';
                    } elseif ($isBersyarat) {
                        $evaluasiText = 'Bersyarat';
                    } else {
                        $evaluasiText = 'Belum Layak';
                    }
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->nama_sppg }}</strong><br>
                        Mitra: {{ $item->nama_mitra }}
                    </td>
                    <td>{{ $item->puskesmas?->nama_puskesmas ?? '-' }}</td>
                    <td>
                        <strong>{{ ucfirst(str_replace('_', ' ', $item->status_ikl ?? '-')) }}</strong><br>
                        Nilai: {{ $item->nilai_ikl ?? '-' }}<br>
                        Hasil: {{ $item->hasil_ikl ? ucfirst(str_replace('_', ' ', $item->hasil_ikl)) : '-' }}
                    </td>
                    <td>
                        <strong>{{ ucfirst(str_replace('_', ' ', $item->status_slhs ?? '-')) }}</strong><br>
                        Berlaku: {{ $item->tgl_berlaku?->format('d-m-Y') ?? '-' }}<br>
                        s/d: {{ $item->tgl_berakhir?->format('d-m-Y') ?? '-' }}
                    </td>
                    <td>{{ $item->tanggal_ikl?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $evaluasiText }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 20px;">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>