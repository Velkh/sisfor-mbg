<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanSasaranController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'kategori' => ['nullable', 'in:all,Sekolah,B3,Umum'],
        ]);

        $q = trim((string) ($filters['q'] ?? ''));
        $dari = $filters['dari'] ?? null;
        $sampai = $filters['sampai'] ?? null;
        $kategori = (string) ($filters['kategori'] ?? 'all');

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        $reports = UnitUsaha::query()
            ->with([
                'puskesmas:id_puskesmas,nama_puskesmas',
                'kecamatan:id_kecamatan,nama_kecamatan',
                'kelurahan:id_kelurahan,nama_kelurahan',
                'sasaranManfaat' => function ($q) use ($dari, $sampai, $kategori): void {
                    $q->when($dari, fn ($x) => $x->whereDate('created_at', '>=', $dari))
                    ->when($sampai, fn ($x) => $x->whereDate('created_at', '<=', $sampai))
                    ->when($kategori !== 'all', fn ($x) => $x->where('kategori', $kategori))
                    ->orderByDesc('created_at');
                },
            ])
            ->where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->when($q !== '', function ($builder) use ($q): void {
                $builder->where(function ($sub) use ($q): void {
                    $sub->where('nama_unit_usaha', 'like', '%' . $q . '%')
                        ->orWhere('nama_pemilik', 'like', '%' . $q . '%')
                        ->orWhereHas('puskesmas', fn ($r) => $r->where('nama_puskesmas', 'like', '%' . $q . '%'));
                });
            })
            ->orderBy('nama_unit_usaha')
            ->paginate(10)
            ->withQueryString();

        $reports->getCollection()->transform(function (UnitUsaha $item): UnitUsaha {
            $item->total_penerima = $item->sasaranManfaat->sum(function ($row): int {
                return (int) $row->jumlah_siswa
                    + (int) $row->jumlah_bumil
                    + (int) $row->jumlah_busui
                    + (int) $row->jumlah_balita
                    + (int) $row->jumlah_jiwa;
            });

            $item->kelompok_penerima = $item->sasaranManfaat
                ->pluck('kategori')
                ->filter()
                ->unique()
                ->values()
                ->implode(', ');

            $item->jumlah_distribusi = $item->sasaranManfaat->count();

            return $item;
        });

        $stats = [
            'total_unit' => $reports->total(),
            'total_penerima' => (int) $reports->getCollection()->sum('total_penerima'),
            'total_distribusi' => (int) $reports->getCollection()->sum('jumlah_distribusi'),
        ];

        return view('kecamatan.laporan', [
            'reports' => $reports,
            'stats' => $stats,
            'filters' => compact('q', 'dari', 'sampai', 'kategori'),
        ]);
    }

    private function allowedJenisUsaha(?string $aksesTipeUsaha): array
    {
        $rawItems = array_filter(array_map('trim', explode(',', (string) $aksesTipeUsaha)));

        return collect($rawItems)
            ->map(fn (string $item): string => strtolower($item))
            ->filter(fn (string $item): bool => in_array($item, ['sppg', 'tpp', 'dam', 'kantin'], true))
            ->unique()
            ->values()
            ->all();
    }
}