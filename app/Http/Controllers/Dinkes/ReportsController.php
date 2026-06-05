<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Contracts\View\View;
use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
   public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'id_kecamatan' => ['nullable', 'integer'],
            'jenis_sasaran' => ['nullable', 'in:Sekolah,B3,Umum'],
            'kategori' => ['nullable', 'in:all,sppg,catering,restoran,dam,kantin'],

        ]);

        $q = trim((string) ($filters['q'] ?? ''));
        $idKecamatan = $filters['id_kecamatan'] ?? null;
        $jenisSasaran = $filters['jenis_sasaran'] ?? null;
        $kategori = (string) ($filters['kategori'] ?? 'all');

        $reports = UnitUsaha::query()
            ->with([
                'puskesmas:id_puskesmas,nama_puskesmas',
                'kecamatan:id_kecamatan,nama_kecamatan',
                'kelurahan:id_kelurahan,nama_kelurahan',
                'sasaranManfaat',
            ])
            ->when($q !== '', function ($builder) use ($q): void {
                $builder->where(function ($sub) use ($q): void {
                    $sub->where('nama_unit_usaha', 'like', '%' . $q . '%')
                        ->orWhere('nama_pemilik', 'like', '%' . $q . '%')
                        ->orWhereHas('puskesmas', fn ($r) => $r->where('nama_puskesmas', 'like', '%' . $q . '%'));
                });
            })
            ->when($idKecamatan, fn ($builder) => $builder->where('id_kecamatan', $idKecamatan))
            ->when($jenisSasaran, function ($builder) use ($jenisSasaran): void {
                $builder->whereHas('sasaranManfaat', function ($q) use ($jenisSasaran): void {
                    $q->where('kategori', $jenisSasaran);
                });
            })
            ->when($kategori !== 'all', fn ($builder) => $builder->where('jenis_usaha', $kategori))
            ->where('jenis_usaha', 'sppg')
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

        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('dinkes.laporan', [
            'reports' => $reports,
            'stats' => $stats,
            'filters' => compact('q', 'idKecamatan', 'jenisSasaran'),
            'kecamatans' => $kecamatans,
        ]);
    }
}