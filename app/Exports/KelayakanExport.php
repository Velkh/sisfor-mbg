<?php
namespace App\Exports;

use App\Models\UnitUsaha;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KelayakanExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $filters) { }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
    public function collection(): Collection
    {
        $query = UnitUsaha::query()
            ->with([
                'laporanSlhs',
                'puskesmas:id_puskesmas,nama_puskesmas',
            ]);

        if (($this->filters['q'] ?? '') !== '') {
            $keyword = $this->filters['q'];
            $query->where(function (Builder $builder) use ($keyword): void {
                $builder->where('nama_unit_usaha', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_pemilik', 'like', '%' . $keyword . '%')
                    ->orWhereHas('puskesmas', function (Builder $relationQuery) use ($keyword): void {
                        $relationQuery->where('nama_puskesmas', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if (($this->filters['status_ikl'] ?? '') !== '') {
            $query->whereHas('laporanSlhs', function (Builder $q): void {
                $q->where('status_ikl', $this->filters['status_ikl']);
            });
        }

        if (($this->filters['status_slhs'] ?? '') !== '') {
            $query->whereHas('laporanSlhs', function (Builder $q): void {
                $q->where('status_slhs', $this->filters['status_slhs']);
            });
        }

        return $query->orderBy('nama_unit_usaha')->get();
    }

    public function headings(): array
    {
    return [
        'Nama Unit Usaha',
        'Pemilik',
        'Jenis Usaha',
        'Puskesmas',
        'Status IKL',
        'Nilai IKL',
        'Status SLHS',
        'Tanggal Terbit SLHS',
        'Tanggal Berakhir SLHS',
        'Ketersediaan IPAL',
        'Jenis IPAL',
        'Pengelolaan Sampah',
        'Jenis Pengelolaan Sampah',
    ];
    }

    public function map($item): array
    {
        $laporan = $item->laporanSlhs;

        return [
            $item->nama_unit_usaha,
            $item->nama_pemilik,
            strtoupper((string) $item->jenis_usaha),
            $item->puskesmas?->nama_puskesmas ?? '-',
            $laporan?->status_ikl ?? '-',
            $laporan?->nilai_ikl ?? '-',
            $laporan?->status_slhs ?? '-',
            optional($laporan?->tgl_terbit_slhs)->format('Y-m-d') ?? '-',
            optional($laporan?->tgl_berakhir_slhs)->format('Y-m-d') ?? '-',
            $laporan?->ketersediaan_ipal ?? '-',
            $laporan?->jenis_ipal ?? '-',
            $laporan?->pengelolaan_sampah ?? '-',
            $laporan?->jenis_pengelolaan ?? '-',
        ];
    }
}