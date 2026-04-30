<?php 

namespace App\Http\Controllers; 

use App\Models\Kecamatan; 
use App\Models\LaporanPenerima; 
use App\Models\Sppg; 
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View; 
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request; 

class GuestController extends Controller 
{ 
    public function home(): View 
    { 
        $kecamatanAktif = Kecamatan::count();
            
        $totalSppg = Sppg::count(); 
        $totalKpm = LaporanPenerima::count(); 
        
        $totalPm = (int) LaporanPenerima::query() 
            ->selectRaw('COALESCE(SUM(jml_siswa + jml_bumil + jml_busui + jml_balita), 0) as total') 
            ->value('total'); 
            
        $totalSma = LaporanPenerima::where('tipe_instansi', LaporanPenerima::TIPE_SMA)->distinct('nama_instansi')->count('nama_instansi'); 
        $totalSmp = LaporanPenerima::where('tipe_instansi', LaporanPenerima::TIPE_SMP)->distinct('nama_instansi')->count('nama_instansi'); 
        $totalSd  = LaporanPenerima::where('tipe_instansi', LaporanPenerima::TIPE_SD)->distinct('nama_instansi')->count('nama_instansi'); 
        $totalTk  = LaporanPenerima::where('tipe_instansi', LaporanPenerima::TIPE_TK)->distinct('nama_instansi')->count('nama_instansi'); 

        return view('homepage', compact( 
            'kecamatanAktif', 'totalSppg', 'totalKpm', 'totalPm', 
            'totalSma', 'totalSmp', 'totalSd', 'totalTk' 
        )); 
    } 

    public function rekap(Request $request): View 
    { 
        $filters = $this->validatedFilters($request); 

        $baseQuery = Sppg::query() 
            ->with([ 
                'kecamatan:id_kecamatan,nama_kecamatan', 
                'kelurahan:id_kelurahan,nama_kelurahan', 
                'puskesmas:id_puskesmas,nama_puskesmas', 
            ]) 
            ->withCount(['laporanPenerimas as kelompok_penerima']) 
            ->withSum('laporanPenerimas as total_siswa', 'jml_siswa') 
            ->withSum('laporanPenerimas as total_bumil', 'jml_bumil') 
            ->withSum('laporanPenerimas as total_busui', 'jml_busui') 
            ->withSum('laporanPenerimas as total_balita', 'jml_balita'); 

        $this->applyFilters($baseQuery, $filters); 

        $rows = (clone $baseQuery) 
            ->latest('created_at') 
            ->paginate(15)
            ->withQueryString();

        $kecamatanOptions = Kecamatan::query() 
            ->orderBy('nama_kecamatan') 
            ->get(['id_kecamatan', 'nama_kecamatan']); 

        $rekapPerKecamatanCounts = Sppg::query() 
            ->select('id_kecamatan') 
            ->selectRaw('COUNT(*) as total_sppg') 
            ->whereNotNull('id_kecamatan') 
            ->groupBy('id_kecamatan') 
            ->pluck('total_sppg', 'id_kecamatan'); 

        $rekapPerKecamatan = $kecamatanOptions->map(function (Kecamatan $kecamatan) use ($rekapPerKecamatanCounts): Kecamatan { 
            $kecamatan->setAttribute( 
                'total_sppg', (int) ($rekapPerKecamatanCounts[$kecamatan->id_kecamatan] ?? 0) 
            ); 
            return $kecamatan; 
        }); 

        $rekapTotalSppg = Sppg::count(); 
        $rekapTotalKelompok = LaporanPenerima::count(); 
        $rekapTotalPenerima = (int) LaporanPenerima::query()
            ->selectRaw('COALESCE(SUM(jml_siswa + jml_bumil + jml_busui + jml_balita), 0) as total')
            ->value('total');

        $pendidikanCards = [ 
            $this->buildPendidikanCard('SMA', 'SMA & Sederajat', LaporanPenerima::TIPE_SMA, 'sma'), 
            $this->buildPendidikanCard('SMP', 'SMP & Sederajat', LaporanPenerima::TIPE_SMP, 'smp'), 
            $this->buildPendidikanCard('SD', 'SD & Sederajat', LaporanPenerima::TIPE_SD, 'sd'), 
            $this->buildPendidikanCard('TK', 'TK / PAUD & Sederajat', LaporanPenerima::TIPE_TK, 'tk'), 
        ]; 

        $kelompokB3Cards = [ 
            [ 
                'kode' => 'POSYANDU', 
                'judul' => 'POSYANDU', 
                'nilai' => (int) LaporanPenerima::where('kategori', LaporanPenerima::KATEGORI_KELOMPOK_B3)->where('tipe_instansi', LaporanPenerima::TIPE_POSYANDU)->distinct('nama_instansi')->count('nama_instansi'), 
                'subNilai' => 'Unit', 
            ], 
            [ 
                'kode' => 'BALITA', 
                'judul' => 'BALITA', 
                'nilai' => (int) LaporanPenerima::where('kategori', LaporanPenerima::KATEGORI_KELOMPOK_B3)->sum('jml_balita'), 
                'subNilai' => 'Anak', 
            ], 
            [ 
                'kode' => 'BUMIL', 
                'judul' => 'BUMIL', 
                'nilai' => (int) LaporanPenerima::where('kategori', LaporanPenerima::KATEGORI_KELOMPOK_B3)->sum('jml_bumil'), 
                'subNilai' => 'Penerima', 
            ], 
            [ 
                'kode' => 'BUSUI', 
                'judul' => 'BUSUI', 
                'nilai' => (int) LaporanPenerima::where('kategori', LaporanPenerima::KATEGORI_KELOMPOK_B3)->sum('jml_busui'), 
                'subNilai' => 'Penerima', 
            ], 
        ]; 

        return view('rekapdaerah', [ 
            'rows' => $rows, 
            'kecamatanOptions' => $kecamatanOptions, 
            'rekapPerKecamatan' => $rekapPerKecamatan, 
            'selectedKecamatanId' => $filters['kecamatan_id'],
            'q' => $filters['q'],
            'rekapTotalSppg' => $rekapTotalSppg, 
            'rekapTotalKelompok' => $rekapTotalKelompok, 
            'rekapTotalPenerima' => $rekapTotalPenerima, 
            'pendidikanCards' => $pendidikanCards, 
            'kelompokB3Cards' => $kelompokB3Cards, 
        ]); 
    } 

    public function showSppg(int $sppg): View 
    { 
        $item = Sppg::query() 
            ->with([ 
                'user:id_users,username', 
                'kecamatan:id_kecamatan,nama_kecamatan', 
                'kelurahan:id_kelurahan,nama_kelurahan', 
                'puskesmas:id_puskesmas,nama_puskesmas', 
                'menuSppg:id_menu,id_sppg,nama_menu,foto_menu', 
                'fotoSppg:id_foto,id_sppg,foto_sppg', 
                'laporanPenerimas:id_laporan,id_sppg,kategori,tipe_instansi,nama_instansi,status,id_kelurahan,id_kecamatan,id_puskesmas,jml_siswa,jml_bumil,jml_busui,jml_balita,created_at',                'laporanPenerimas.kelurahan:id_kelurahan,nama_kelurahan',
                'laporanPenerimas.kecamatan:id_kecamatan,nama_kecamatan',
                'laporanPenerimas.puskesmas:id_puskesmas,nama_puskesmas', 
            ]) 
            ->findOrFail($sppg);

        $laporans = collect($item->laporanPenerimas ?? []);
        $getSchoolStats = function($tipe) use ($laporans) {
            $data = $laporans->where('tipe_instansi', $tipe);
            return [
                'sekolah' => $data->pluck('nama_instansi')->filter()->unique()->count(),
                'siswa' => (int) $data->sum('jml_siswa')
            ];
        };

        $sma = $getSchoolStats(LaporanPenerima::TIPE_SMA);
        $smp = $getSchoolStats(LaporanPenerima::TIPE_SMP);
        $sd  = $getSchoolStats(LaporanPenerima::TIPE_SD);
        $tk  = $getSchoolStats(LaporanPenerima::TIPE_TK);

        $posyandu = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_POSYANDU)
            ->pluck('nama_instansi')->filter()->unique()->count();
                     
        return view('profilsppg', [ 
            'item' => $item,
            'laporans' => $laporans,
            'sma' => $sma,
            'smp' => $smp,
            'sd' => $sd,
            'tk' => $tk,
            'posyandu' => $posyandu, 
        ]); 
    } 

    /**
     * API: Get kecamatan data untuk section "Daftar SPPG"
     */
    public function getKecamatanData(?int $kecamatanId = null): JsonResponse
    {
        $baseQuery = Sppg::query()
            ->with([
                'kecamatan:id_kecamatan,nama_kecamatan',
                'kelurahan:id_kelurahan,nama_kelurahan',
                'puskesmas:id_puskesmas,nama_puskesmas',
            ])
            ->withCount(['laporanPenerimas as kelompok_penerima'])
            ->withSum('laporanPenerimas as total_siswa', 'jml_siswa')
            ->withSum('laporanPenerimas as total_bumil', 'jml_bumil')
            ->withSum('laporanPenerimas as total_busui', 'jml_busui')
            ->withSum('laporanPenerimas as total_balita', 'jml_balita');

        if ($kecamatanId) {
            $baseQuery->where('id_kecamatan', $kecamatanId);
        }

        $rows = (clone $baseQuery)
            ->latest('created_at')
            ->get();

        $formattedRows = $rows->map(function ($row) {
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

            return [
                'id_sppg' => $row->id_sppg,
                'nama_sppg' => $row->nama_sppg,
                'nama_mitra' => $row->nama_mitra ?? '-',
                'status_ikl_label' => $statusIklLabel,
                'status_slhs_label' => $statusSlhsLabel,
                'jml_pegawai' => number_format((int) ($row->jml_pegawai ?? 0), 0, ',', '.'),
                'kelompok_penerima' => number_format((int) ($row->kelompok_penerima ?? 0), 0, ',', '.'),
                'total_penerima' => number_format($totalPenerima, 0, ',', '.'),
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'rows' => $formattedRows,
            'count' => count($formattedRows),
        ]);
    }

    /**
     * API: Get rekap data per kecamatan untuk 3 tabel rekap
     * Type: sppg, kelompok-penerima, penerima
     */
    public function getRekapByKecamatan(string $type): JsonResponse
    {
        return match ($type) {
            'sppg' => $this->rekapSppg(),
            'kelompok-penerima' => $this->rekapKelompokPenerima(),
            'penerima' => $this->rekapPenerima(),
            default => response()->json(['success' => false, 'message' => 'Tipe tidak valid'], 400)
        };
    }

    /**
     * Rekap SPPG per Kecamatan
     * Kolom: #, KECAMATAN, JUMLAH SPPG, MEMENUHI IKL, BELUM MEMENUHI IKL, BELUM MENGAJUKAN IKL, SUDAH SLHS, BELUM SLHS
     */
    private function rekapSppg(): JsonResponse
    {
        try {
            $kecamatans = Kecamatan::query()
                ->orderBy('nama_kecamatan')
                ->get();

            $rows = $kecamatans->map(function ($kec) {
                $sppgs = Sppg::where('id_kecamatan', $kec->id_kecamatan)->get();

                $jumlahSppg = $sppgs->count();
                
                $memenuhi = $sppgs->filter(function ($s) {
                    $status = strtolower($s->status_ikl ?? '');
                    $nilai = (float) ($s->nilai_ikl ?? 0);
                    return $status === 'selesai' && $nilai > 80;
                })->count();
                
                $belumMemenuhi = $sppgs->filter(function ($s) {
                    $status = strtolower($s->status_ikl ?? '');
                    $nilai = (float) ($s->nilai_ikl ?? 0);
                    return $status === 'selesai' && $nilai <= 80;
                })->count();
                
                $belumMengajukan = $sppgs->filter(function ($s) {
                    $status = strtolower($s->status_ikl ?? '');
                    return empty($s->status_ikl) || $status === 'pending';
                })->count();
                
                $sudahSlhs = $sppgs->filter(function ($s) {
                    $status = strtolower($s->status_slhs ?? '');
                    return !empty($s->status_slhs) && $status !== 'pending';
                })->count();
                
                $belumSlhs = $sppgs->filter(function ($s) {
                    $status = strtolower($s->status_slhs ?? '');
                    return empty($s->status_slhs) || $status === 'pending';
                })->count();

                return [
                    'kecamatan' => $kec->nama_kecamatan,
                    'jumlah_sppg' => $jumlahSppg,
                    'memenuhi_ikl' => $memenuhi,
                    'belum_memenuhi_ikl' => $belumMemenuhi,
                    'belum_mengajukan_ikl' => $belumMengajukan,
                    'sudah_slhs' => $sudahSlhs,
                    'belum_slhs' => $belumSlhs,
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'rows' => $rows,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'rows' => []
            ], 500);
        }
    }

    /**
     * Rekap Kelompok Penerima per Kecamatan
     * Kolom: KECAMATAN, SMA SEDERAJAT, SMP SEDERAJAT, SD SEDERAJAT, TKA/PAUD SEDERAJAT, POSYANDU, JUMLAH
     */
    private function rekapKelompokPenerima(): JsonResponse
    {
        $kecamatans = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get();

        $rows = $kecamatans->map(function ($kec) {
            $laporans = LaporanPenerima::where('id_kecamatan', $kec->id_kecamatan)->get();

            $sma = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SMA)
                ->pluck('nama_instansi')
                ->filter()
                ->unique()
                ->count();
            
            $smp = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SMP)
                ->pluck('nama_instansi')
                ->filter()
                ->unique()
                ->count();
            
            $sd = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SD)
                ->pluck('nama_instansi')
                ->filter()
                ->unique()
                ->count();
            
            $tk = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_TK)
                ->pluck('nama_instansi')
                ->filter()
                ->unique()
                ->count();
            
            $posyandu = $laporans->where('tipe_instansi', LaporanPenerima::TIPE_POSYANDU)
                ->pluck('nama_instansi')
                ->filter()
                ->unique()
                ->count();

            $jumlah = $sma + $smp + $sd + $tk + $posyandu;

            return [
                'kecamatan' => $kec->nama_kecamatan,
                'sma_sederajat' => $sma,
                'smp_sederajat' => $smp,
                'sd_sederajat' => $sd,
                'tka_paud_sederajat' => $tk,
                'posyandu' => $posyandu,
                'jumlah' => $jumlah,
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'rows' => $rows,
        ]);
    }

    /**
     * Rekap Penerima per Kecamatan
     * Kolom: #, KECAMATAN, SMA SEDERAJAT, SMP SEDERAJAT, SD SEDERAJAT, TKA/PAUD SEDERAJAT, BALITA, BUMIL, BUSUI, JUMLAH
     */
    private function rekapPenerima(): JsonResponse
    {
        $kecamatans = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get();

        $rows = $kecamatans->map(function ($kec) {
            $laporans = LaporanPenerima::where('id_kecamatan', $kec->id_kecamatan)->get();

            $sma = (int) $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SMA)
                ->sum('jml_siswa');
            
            $smp = (int) $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SMP)
                ->sum('jml_siswa');
            
            $sd = (int) $laporans->where('tipe_instansi', LaporanPenerima::TIPE_SD)
                ->sum('jml_siswa');
            
            $tk = (int) $laporans->where('tipe_instansi', LaporanPenerima::TIPE_TK)
                ->sum('jml_siswa');
            
            $balita = (int) $laporans->sum('jml_balita');
            $bumil = (int) $laporans->sum('jml_bumil');
            $busui = (int) $laporans->sum('jml_busui');

            $jumlah = $sma + $smp + $sd + $tk + $balita + $bumil + $busui;

            return [
                'kecamatan' => $kec->nama_kecamatan,
                'sma_sederajat' => number_format($sma, 0, ',', '.'),
                'smp_sederajat' => number_format($smp, 0, ',', '.'),
                'sd_sederajat' => number_format($sd, 0, ',', '.'),
                'tka_paud_sederajat' => number_format($tk, 0, ',', '.'),
                'balita' => number_format($balita, 0, ',', '.'),
                'bumil' => number_format($bumil, 0, ',', '.'),
                'busui' => number_format($busui, 0, ',', '.'),
                'jumlah' => number_format($jumlah, 0, ',', '.'),
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'rows' => $rows,
        ]);
    }

    private function validatedFilters(Request $request): array 
    { 
        $validated = $request->validate([ 
            'kecamatan_id' => ['nullable', 'exists:kecamatan,id_kecamatan'], 
            'q' => ['nullable', 'string', 'max:255'],
        ]); 

        return [ 
            'kecamatan_id' => isset($validated['kecamatan_id']) ? (int) $validated['kecamatan_id'] : null, 
            'q' => $validated['q'] ?? null,
        ]; 
    } 

    private function applyFilters(Builder $query, array $filters): void 
    { 
        if (! empty($filters['kecamatan_id'])) { 
            $query->where('id_kecamatan', $filters['kecamatan_id']); 
        } 
        
        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($sub) use ($search) {
                $sub->where('nama_sppg', 'like', "%{$search}%")
                    ->orWhere('nama_mitra', 'like', "%{$search}%")
                    ->orWhere('nama_kepala', 'like', "%{$search}%");
            });
        }
    } 

    private function buildPendidikanCard(string $kode, string $judul, string $tipeInstansi, string $warna): array 
    { 
        $query = LaporanPenerima::query()->where('tipe_instansi', $tipeInstansi); 
        return [ 
            'kode' => $kode, 
            'judul' => $judul, 
            'jumlah' => (int) (clone $query)->distinct('nama_instansi')->count('nama_instansi'), 
            'subJumlah' => 'Sekolah', 
            'nilai' => (int) (clone $query)->sum('jml_siswa'), 
            'subNilai' => 'Siswa', 
            'warna' => $warna, 
        ]; 
    }
}