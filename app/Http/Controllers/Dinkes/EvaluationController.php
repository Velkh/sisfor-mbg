<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use App\Models\LaporanSlhs;
use App\Exports\KelayakanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);

        // Data tabel tetap pakai filter
        $itemsQuery = $this->buildFilteredQuery($filters);
        $items = (clone $itemsQuery)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // Statistik kartu: global, tidak ikut filter
        $statsBase = UnitUsaha::query();

        $stats = [
            'total' => (clone $statsBase)->count(),

            'belum_layak' => (clone $statsBase)
                ->where(function (Builder $q): void {
                    $q->whereDoesntHave('laporanSlhs')
                    ->orWhereHas('laporanSlhs', function (Builder $query): void {
                        $query->whereNull('nilai_ikl')
                                ->orWhere('nilai_ikl', '<', 80);
                    });
                })->count(),

            'laik_higiene' => (clone $statsBase)
                ->whereHas('laporanSlhs', function (Builder $query): void {
                    $query->where('status_ikl', 'selesai')
                        ->where('hasil_ikl', 'memenuhi')
                        ->where('nilai_ikl', '>=', 80);
                })->count(),
        ];

        return view('dinkes.kelayakan', [
            'items' => $items,
            'stats' => $stats,
            'filters' => $filters,
            'today' => Carbon::today(),
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $rows = $this->buildFilteredQuery($filters)
            ->orderByDesc('created_at')
            ->get();

        $fileName = 'laporan-kelayakan-' . now()->format('Ymd-His') . '.pdf';

        $pdf = Pdf::loadView('dinkes.exports.kelayakan-pdf', [
            'rows' => $rows,
            'generatedAt' => now(),
            'filters' => $filters,
            'today' => Carbon::today(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'status_ikl' => ['nullable', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'status_slhs' => ['nullable', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'evaluasi' => ['nullable', 'in:all,memenuhi,tidak_memenuhi'],
        ]);

        return [
            'q' => trim((string) ($validated['q'] ?? '')),
            'status_ikl' => (string) ($validated['status_ikl'] ?? ''),
            'status_slhs' => (string) ($validated['status_slhs'] ?? ''),
            'evaluasi' => (string) ($validated['evaluasi'] ?? 'all'),
        ];
    }

    private function buildFilteredQuery(array $filters): Builder
    {
        $query = UnitUsaha::query()
            ->with([
                'laporanSlhs:id_laporan_slhs,id_unit_usaha,status_ikl,nilai_ikl,hasil_ikl,status_slhs,tgl_terbit_slhs,tgl_berakhir_slhs,link_slhs,ketersediaan_ipal,jenis_ipal,pengelolaan_sampah,jenis_pengelolaan',
                'puskesmas:id_puskesmas,nama_puskesmas',
                'sasaranManfaat',
            ]);

        if ($filters['q'] !== '') {
            $keyword = $filters['q'];
            $query->where(function (Builder $builder) use ($keyword): void {
                $builder->where('nama_unit_usaha', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_pemilik', 'like', '%' . $keyword . '%')
                    ->orWhereHas('puskesmas', function (Builder $relationQuery) use ($keyword): void {
                        $relationQuery->where('nama_puskesmas', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($filters['status_ikl'] !== '') {
            $query->whereHas('laporanSlhs', function (Builder $q) use ($filters): void {
                $q->where('status_ikl', $filters['status_ikl']);
            });
        }

        if ($filters['status_slhs'] !== '') {
            $query->whereHas('laporanSlhs', function (Builder $q) use ($filters): void {
                $q->where('status_slhs', $filters['status_slhs']);
            });
        }

        if ($filters['evaluasi'] === 'memenuhi') {
            $query->whereHas('laporanSlhs', function (Builder $q): void {
                $q->whereNotNull('nilai_ikl')
                ->where('nilai_ikl', '>=', 80);
            });
        }

        if ($filters['evaluasi'] === 'tidak_memenuhi') {
            $query->whereHas('laporanSlhs', function (Builder $q): void {
                $q->whereNull('nilai_ikl')
                ->orWhere('nilai_ikl', '<', 80);
            });
        }

        return $query;
    }

    public function exportExcel(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $fileName = 'laporan-kelayakan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new KelayakanExport($filters), $fileName);
    }

    public function show(int $unitId): View
    {
        $item = UnitUsaha::query()
            ->with([
                'laporanSlhs',
                'sasaranManfaat',
                'puskesmas:id_puskesmas,nama_puskesmas',
                'kecamatan:id_kecamatan,nama_kecamatan',
                'kelurahan:id_kelurahan,nama_kelurahan',
            ])
            ->findOrFail($unitId);

        $laporan = $item->laporanSlhs;
        $nilaiIkl = (int) ($laporan?->nilai_ikl ?? 0);

        if ($nilaiIkl >= 80) {
            $evaluasiText = 'Memenuhi';
            $evaluasiClass = 'success';
        } else {
            $evaluasiText = 'Tidak Memenuhi';
            $evaluasiClass = 'danger';
        }

        return view('dinkes.kelayakan-detail', [
            'item' => $item,
            'laporan' => $laporan,
            'evaluasiText' => $evaluasiText,
            'evaluasiClass' => $evaluasiClass,
        ]);
    }
}