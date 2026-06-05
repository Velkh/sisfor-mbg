<?php

namespace App\Exports;

use App\Models\UnitUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class KelayakanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    private $filters;

    public function __construct($filters = null)
    {
        $this->filters = $filters ?? [];
    }

    public function collection()
    {
        // 1. Tambahkan 'kelurahan' ke dalam eager loading
        $query = UnitUsaha::query()
            ->with(['kecamatan', 'kelurahan', 'laporanSlhs']);

        // Apply filters jika ada
        if (!empty($this->filters['q'])) {
            $search = $this->filters['q'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_unit_usaha', 'like', "%{$search}%")
                  ->orWhere('nama_pemilik', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['jenis_usaha'])) {
            $query->where('jenis_usaha', $this->filters['jenis_usaha']);
        }
        
        if (!empty($this->filters['status_ikl'])) {
            $query->whereHas('laporanSlhs', function ($q) {
                $q->where('status_ikl', $this->filters['status_ikl']);
            });
        }

        if (!empty($this->filters['status_slhs'])) {
            $query->whereHas('laporanSlhs', function ($q) {
                $q->where('status_slhs', $this->filters['status_slhs']);
            });
        }

        $items = $query->get();

        // Format data untuk Excel
        return $items->map(function ($item) {
            $laporan = $item->laporanSlhs;
            $nilaiIkl = (int) ($laporan?->nilai_ikl ?? 0);

            $evaluasiText = $nilaiIkl >= 80 ? 'Memenuhi' : 'Tidak Memenuhi';

            // 2. Susunan urutan diubah: Alamat -> Kelurahan -> Kecamatan, Status IKL dihapus
            return [
                $item->nama_unit_usaha,
                $item->nama_pemilik,
                strtoupper($item->jenis_usaha ?? '-'),
                $item->alamat ?? '-',
                $item->kelurahan?->nama_kelurahan ?? '-',
                $item->kecamatan?->nama_kecamatan ?? '-',
                $laporan?->nilai_ikl ?? '-',
                $laporan?->hasil_ikl ? ucfirst(str_replace('_', ' ', $laporan->hasil_ikl)) : '-',
                $laporan?->status_slhs ? ucfirst(str_replace('_', ' ', $laporan->status_slhs)) : '-',
                optional($laporan?->tgl_terbit_slhs)->format('d-m-Y') ?? '-',
                optional($laporan?->tgl_berakhir_slhs)->format('d-m-Y') ?? '-',
                $laporan?->link_slhs ?? '-',
                $laporan?->ketersediaan_ipal ? ucfirst(str_replace('_', ' ', $laporan->ketersediaan_ipal)) : '-',
                $laporan?->jenis_ipal ?? '-',
                $laporan?->pengelolaan_sampah ? ucfirst(str_replace('_', ' ', $laporan->pengelolaan_sampah)) : '-',
                $laporan?->jenis_pengelolaan ?? '-',
                $evaluasiText,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Unit Usaha',
            'Nama Pemilik',
            'Jenis Usaha',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Nilai IKL',
            'Hasil IKL',
            'Status SLHS',
            'Tgl Terbit SLHS',
            'Tgl Berakhir SLHS',
            'Link SLHS',
            'Ketersediaan IPAL',
            'Jenis IPAL',
            'Pengelolaan Sampah',
            'Jenis Pengelolaan',
            'Evaluasi', // Kolom ke-17 (Q)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Border style
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        // 3. Penyesuaian Kolom Style (A1:P1 menjadi A1:Q1 karena total ada 17 kolom)
        $sheet->getStyle('A1:Q1')->applyFromArray($headerStyle);

        // Apply border ke semua cells yang ada data
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray($borderStyle);

        // Center alignment untuk kolom tertentu
        $sheet->getStyle('C:Q')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Kolom E (Kelurahan) & F (Kecamatan) di-center
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Kolom G (Nilai IKL) di-center

        // Set row height untuk header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Freeze pane (header tetap terlihat saat scroll)
        $sheet->freezePane('A2');
        
        $lastRow = $sheet->getHighestRow();
        if ($lastRow >= 2) {
            // Evaluasi sekarang ada di kolom Q, bukan P
            $evalRange = 'Q2:Q' . $lastRow;

            // Nilai IKL sekarang ada di kolom G, bukan F
            $condGreen = new Conditional();
            $condGreen->setConditionType(Conditional::CONDITION_EXPRESSION)
                ->setOperatorType(Conditional::OPERATOR_NONE)
                ->addCondition('=$G2>=80'); 
            $condGreen->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('C6EFCE');
            $condGreen->getStyle()->getFont()->getColor()->setRGB('006100');

            $condRed = new Conditional();
            $condRed->setConditionType(Conditional::CONDITION_EXPRESSION)
                ->setOperatorType(Conditional::OPERATOR_NONE)
                ->addCondition('=$G2<80');
            $condRed->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFC7CE');
            $condRed->getStyle()->getFont()->getColor()->setRGB('9C0006');

            $conditionalStyles = $sheet->getStyle($evalRange)->getConditionalStyles();
            $conditionalStyles[] = $condGreen;
            $conditionalStyles[] = $condRed;
            $sheet->getStyle($evalRange)->setConditionalStyles($conditionalStyles);
        }
        return [];
    }
}