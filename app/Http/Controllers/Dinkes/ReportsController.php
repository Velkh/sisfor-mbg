<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\Sppg;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'kategori' => ['nullable', 'in:all,Satuan Pendidikan,Kelompok B3'],
        ]);

        $q = trim((string) ($filters['q'] ?? ''));
        $dari = $filters['dari'] ?? null;
        $sampai = $filters['sampai'] ?? null;
        $kategori = (string) ($filters['kategori'] ?? 'all');

        $query = Sppg::query()
            ->with([
                'puskesmas:id_puskesmas,nama_puskesmas',
                'laporanPenerimas' => function ($relationQuery) use ($dari, $sampai, $kategori): void {
                    $relationQuery
                        ->with([
                            'kelurahan:id_kelurahan,nama_kelurahan',
                            'kecamatan:id_kecamatan,nama_kecamatan',
                            'puskesmas:id_puskesmas,nama_puskesmas',
                        ])
                        ->when($dari, fn ($q) => $q->whereDate('created_at', '>=', $dari))
                        ->when($sampai, fn ($q) => $q->whereDate('created_at', '<=', $sampai))
                        ->when($kategori !== 'all', fn ($q) => $q->where('kategori', $kategori))
                        ->orderByDesc('created_at');
                },
            ])
            ->when($q !== '', function (Builder $builder) use ($q): void {
                $builder->where(function (Builder $sub) use ($q): void {
                    $sub->where('nama_sppg', 'like', '%' . $q . '%')
                        ->orWhere('nama_mitra', 'like', '%' . $q . '%')
                        ->orWhereHas('puskesmas', function (Builder $relation) use ($q): void {
                            $relation->where('nama_puskesmas', 'like', '%' . $q . '%');
                        });
                });
            });

        $reports = $query->orderBy('nama_sppg')->paginate(10)->withQueryString();

        $reports->getCollection()->transform(function (Sppg $item): Sppg {
            $item->total_kapasitas = $this->parseKapasitas((string) ($item->kapasitas_porsi ?? '0'));

            $totalPenerima = $item->laporanPenerimas->sum(function ($laporan): int {
                return (int) $laporan->jml_siswa
                    + (int) $laporan->jml_bumil
                    + (int) $laporan->jml_busui
                    + (int) $laporan->jml_balita;
            });

            $item->total_penerima = $totalPenerima;
            $item->kelompok_penerima = $item->laporanPenerimas
                ->pluck('kategori')
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');
            $item->jumlah_distribusi = $item->laporanPenerimas->count();

            return $item;
        });

        $stats = [
            'total_sppg' => $reports->total(),
            'total_kapasitas' => (int) $reports->getCollection()->sum('total_kapasitas'),
            'total_penerima' => (int) $reports->getCollection()->sum('total_penerima'),
            'total_distribusi' => (int) $reports->getCollection()->sum('jumlah_distribusi'),
        ];

        return view('dinkes.laporan', [
            'reports' => $reports,
            'stats' => $stats,
            'filters' => [
                'q' => $q,
                'dari' => $dari,
                'sampai' => $sampai,
                'kategori' => $kategori,
            ],
        ]);
    }

    private function parseKapasitas(string $kapasitas): int
    {
        $onlyDigits = preg_replace('/\D+/', '', $kapasitas);

        return (int) ($onlyDigits ?: 0);
    }
}