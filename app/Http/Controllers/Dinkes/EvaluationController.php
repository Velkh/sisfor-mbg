<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\Sppg;
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
        $baseQuery = $this->buildFilteredQuery($filters);

        $items = (clone $baseQuery)
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => (clone $baseQuery)->count(),

            'belum_layak' => (clone $baseQuery)
                ->where(function (Builder $query): void {
                    $query->whereNull('status_ikl')
                        ->orWhere('status_ikl', '!=', 'selesai')
                        ->orWhereNull('hasil_ikl')
                        ->orWhere('hasil_ikl', '!=', 'memenuhi')
                        ->orWhereNull('nilai_ikl')
                        ->orWhere('nilai_ikl', '<', 80);
                })->count(),

            'bersyarat' => (clone $baseQuery)
                ->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where('nilai_ikl', '>=', 80)
                ->where(function (Builder $query): void {
                    $query->whereNull('status_slhs')
                        ->orWhere('status_slhs', '!=', 'selesai');
                })->count(),

            'laik_higiene' => (clone $baseQuery)
                ->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where('nilai_ikl', '>=', 80)
                ->where('status_slhs', 'selesai')
                ->count(),
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
            ->orderByDesc('updated_at')
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
            'evaluasi' => ['nullable', 'in:all,belum_layak,bersyarat,laik_higiene'],
            'status_ikl' => ['nullable', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'hasil_ikl' => ['nullable', 'in:memenuhi,tidak_memenuhi'],
            'status_slhs' => ['nullable', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        return [
            'q' => trim((string) ($validated['q'] ?? '')),
            'evaluasi' => (string) ($validated['evaluasi'] ?? 'all'),
            'status_ikl' => (string) ($validated['status_ikl'] ?? ''),
            'hasil_ikl' => (string) ($validated['hasil_ikl'] ?? ''),
            'status_slhs' => (string) ($validated['status_slhs'] ?? ''),
            'dari' => $validated['dari'] ?? null,
            'sampai' => $validated['sampai'] ?? null,
        ];
    }

    private function buildFilteredQuery(array $filters): Builder
    {
        $query = Sppg::query()
            ->with(['puskesmas:id_puskesmas,nama_puskesmas',
                    'fotoSppg:id_foto,id_sppg,foto_sppg',
                ]);

        if ($filters['q'] !== '') {
            $keyword = $filters['q'];
            $query->where(function (Builder $builder) use ($keyword): void {
                $builder->where('nama_sppg', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_mitra', 'like', '%' . $keyword . '%')
                    ->orWhereHas('puskesmas', function (Builder $relationQuery) use ($keyword): void {
                        $relationQuery->where('nama_puskesmas', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($filters['status_ikl'] !== '') {
            $query->where('status_ikl', $filters['status_ikl']);
        }

        if ($filters['hasil_ikl'] !== '') {
            $query->where('hasil_ikl', $filters['hasil_ikl']);
        }

        if ($filters['status_slhs'] !== '') {
            $query->where('status_slhs', $filters['status_slhs']);
        }

        if (! empty($filters['dari'])) {
            $query->whereDate('tanggal_ikl', '>=', $filters['dari']);
        }

        if (! empty($filters['sampai'])) {
            $query->whereDate('tanggal_ikl', '<=', $filters['sampai']);
        }

        if ($filters['evaluasi'] === 'laik_higiene') {
            $query->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where('nilai_ikl', '>=', 80)
                ->where('status_slhs', 'selesai');
        }

        if ($filters['evaluasi'] === 'bersyarat') {
            $query->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where('nilai_ikl', '>=', 80)
                ->where(function (Builder $builder): void {
                    $builder->whereNull('status_slhs')
                        ->orWhere('status_slhs', '!=', 'selesai');
                });
        }

        if ($filters['evaluasi'] === 'belum_layak') {
        $query->where(function (Builder $builder): void {
            $builder->whereNull('status_ikl')
                ->orWhere('status_ikl', '!=', 'selesai')
                ->orWhereNull('hasil_ikl')
                ->orWhere('hasil_ikl', '!=', 'memenuhi')
                ->orWhereNull('nilai_ikl')
                ->orWhere('nilai_ikl', '<', 80);
        });
    }
        return $query;
    }

    public function show(int $sppg): View
    {
        $item = Sppg::query()
            ->with([
                'puskesmas:id_puskesmas,nama_puskesmas',
                'user:id_users,username',
                'fotoSppg:id_foto,id_sppg,foto_sppg',
            ])
            ->findOrFail($sppg);

        $isIklLulus = $item->status_ikl === 'selesai'
            && $item->hasil_ikl === 'memenuhi'
            && (int) ($item->nilai_ikl ?? 0) >= 80;

        $isLaikHigiene = $isIklLulus && $item->status_slhs === 'selesai';
        $isBersyarat = $isIklLulus && $item->status_slhs !== 'selesai';

        if ($isLaikHigiene) {
            $evaluasiText = 'Laik Higiene';
            $evaluasiClass = 'success';
        } elseif ($isBersyarat) {
            $evaluasiText = 'Bersyarat';
            $evaluasiClass = 'warning text-dark';
        } else {
            $evaluasiText = 'Belum Layak';
            $evaluasiClass = 'danger';
        }

        return view('dinkes.kelayakan-detail', [
            'item' => $item,
            'evaluasiText' => $evaluasiText,
            'evaluasiClass' => $evaluasiClass,
        ]);
    }
}