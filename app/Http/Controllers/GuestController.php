<?php 

namespace App\Http\Controllers; 

use App\Models\Kecamatan; 
use App\Models\LaporanSlhs; 
use App\Models\SasaranManfaat; 
use App\Models\UnitUsaha; 
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View; 
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request; 

class GuestController extends Controller 
{ 
    public function index(): View 
    { 
        $totalSarana = UnitUsaha::count();

        $prosesIkl = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->whereNull('status_ikl')
                ->orWhere('status_ikl', 'belum_mengajukan')
                ->orWhere('status_ikl', 'sudah_mengajukan');
        })->count();

        $memenuhiIkl = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->where('status_ikl', 'selesai')
                ->where('nilai_ikl', '>=', 80)
                ->where(function ($sub) {
                    $sub->whereNull('status_slhs')
                        ->orWhere('status_slhs', '!=', 'selesai');
                });
        })->count();

        $slhsTerbit = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->where('status_slhs', 'selesai');
        })->count();

        $penjamahTerlatih = (int) UnitUsaha::sum('jumlah_penjamah_terlatih');

        $cakupanSekolah = SasaranManfaat::query()
            ->where('kategori', 'Sekolah')
            ->distinct('nama_instansi')
            ->count('nama_instansi');

        $cakupanB3 = SasaranManfaat::query()
            ->where('kategori', 'B3')
            ->distinct('nama_instansi')
            ->count('nama_instansi');

        $penerimaManfaat = (int) SasaranManfaat::query()
            ->selectRaw('COALESCE(SUM(COALESCE(jumlah_siswa,0) + COALESCE(jumlah_bumil,0) + COALESCE(jumlah_busui,0) + COALESCE(jumlah_balita,0) + COALESCE(jumlah_jiwa,0)), 0) as total')
            ->value('total');

        return view('homepage', compact(
            'totalSarana',
            'prosesIkl',
            'memenuhiIkl',
            'slhsTerbit',
            'penjamahTerlatih',
            'cakupanSekolah',
            'cakupanB3',
            'penerimaManfaat'
        ));
    } 

    public function rekap(Request $request): View 
    { 
        $filters = $this->validatedFilters($request); 

        $baseQuery = UnitUsaha::query() 
            ->with([ 
                'kecamatan:id_kecamatan,nama_kecamatan', 
                'kelurahan:id_kelurahan,nama_kelurahan', 
                'puskesmas:id_puskesmas,nama_puskesmas', 
                'laporanSlhs',
            ]) 
            ->withCount(['sasaranManfaat as kelompok_penerima']) 
            ->withSum('sasaranManfaat as total_siswa', 'jumlah_siswa') 
            ->withSum('sasaranManfaat as total_bumil', 'jumlah_bumil') 
            ->withSum('sasaranManfaat as total_busui', 'jumlah_busui') 
            ->withSum('sasaranManfaat as total_balita', 'jumlah_balita') 
            ->withSum('sasaranManfaat as total_jiwa', 'jumlah_jiwa'); 

        $this->applyFilters($baseQuery, $filters); 

        $rows = (clone $baseQuery) 
            ->latest('created_at') 
            ->paginate(15)
            ->withQueryString();

        $kecamatanOptions = Kecamatan::query() 
            ->orderBy('nama_kecamatan') 
            ->get(['id_kecamatan', 'nama_kecamatan']); 

        $rekapPerKecamatanCounts = UnitUsaha::query() 
            ->select('id_kecamatan') 
            ->selectRaw('COUNT(*) as total_sppg') 
            ->whereNotNull('id_kecamatan') 
            ->groupBy('id_kecamatan') 
            ->pluck('total_sppg', 'id_kecamatan');
            
        $unitUsahaCounts = UnitUsaha::query()
            ->selectRaw('jenis_usaha, COUNT(*) as total')
            ->groupBy('jenis_usaha')
            ->pluck('total', 'jenis_usaha');
        
        $rekapPerKecamatan = $kecamatanOptions->map(function (Kecamatan $kecamatan) use ($rekapPerKecamatanCounts): Kecamatan { 
            $kecamatan->setAttribute( 
                'total_sppg', (int) ($rekapPerKecamatanCounts[$kecamatan->id_kecamatan] ?? 0) 
            ); 
            return $kecamatan; 
        }); 
    
        $unitUsahaSlhsCounts = UnitUsaha::query()
        ->whereHas('laporanSlhs', function ($q) {
            $q->where('status_slhs', 'selesai');
        })
        ->selectRaw('jenis_usaha, COUNT(*) as total')
        ->groupBy('jenis_usaha')
        ->pluck('total', 'jenis_usaha');
        
            $totalAllUnits = UnitUsaha::count();
            $unitUsahaCards = [
                [
                    'judul'         => 'Total Unit',
                    'nilai'         => $totalAllUnits,
                    'subNilai'      => 'Unit',
                    'filter_jenis'  => '', 
                    'filter_status' => '',
                ]
            ];

            $jenisUnit = [
                'SPPG'     => 'sppg',
                'Catering' => 'catering',
                'Restoran' => 'restoran',
                'DAM'      => 'dam',
                'Kantin'   => 'kantin',
            ];

            foreach ($jenisUnit as $label => $key) {
                $unitUsahaCards[] = [
                    'judul'         => $label,
                    'nilai'         => (int) ($unitUsahaCounts[$key] ?? $unitUsahaCounts[strtolower($label)] ?? $unitUsahaCounts[strtoupper($label)] ?? 0),
                    'subNilai'      => 'Unit',
                    'filter_jenis'  => $label,
                    'filter_status' => '',
                ];
                
                $unitUsahaCards[] = [
                    'judul'         => "$label - Sudah SLHS",
                    'nilai'         => (int) ($unitUsahaSlhsCounts[$key] ?? $unitUsahaSlhsCounts[strtolower($label)] ?? $unitUsahaSlhsCounts[strtoupper($label)] ?? 0),
                    'subNilai'      => 'Unit',
                    'filter_jenis'  => $label,
                    'filter_status' => 'sudah_slhs',
                ];
            }
        return view('rekapdaerah', [ 
            'rows' => $rows, 
            'kecamatanOptions' => $kecamatanOptions, 
            'rekapPerKecamatan' => $rekapPerKecamatan, 
            'selectedKecamatanId' => $filters['kecamatan_id'],
            'q' => $filters['q'],
            'unitUsahaCards' => $unitUsahaCards, 
        ]); 
    } 

    public function showUnit(int $unit): View 
    { 
        $item = UnitUsaha::query() 
            ->with([ 
                'kecamatan:id_kecamatan,nama_kecamatan', 
                'kelurahan:id_kelurahan,nama_kelurahan', 
                'puskesmas:id_puskesmas,nama_puskesmas', 
                'sasaranManfaat:id_sasaran_manfaat,id_unit_usaha,kategori,tipe_instansi,nama_instansi,status,jumlah_siswa,jumlah_bumil,jumlah_busui,jumlah_balita,jumlah_jiwa,created_at',
                'laporanSlhs',
                'fotos',
            ]) 
            ->findOrFail($unit);

        $item->setAttribute('nama_unit', $item->nama_unit_usaha);
        $item->setAttribute('nama_kepala', $item->nama_pemilik);
        $item->setAttribute('jenis_usaha', $item->jenis_usaha ?? '-');
        $item->setAttribute('jumlah_pegawai', $item->jumlah_pegawai ?? 0);
        $item->setAttribute('jumlah_penjamah_terlatih',$item->jumlah_penjamah_terlatih ?? 0);
        $item->setAttribute('kapasitas_porsi', 0);
        $item->setAttribute('foto_kepala', null);
        $item->load('fotos');

        $laporans = collect($item->sasaranManfaat ?? []);
        $getSchoolStats = function($tipe) use ($laporans) {
            $data = $laporans->where('kategori', 'Sekolah')->where('tipe_instansi', $tipe);
            return [
                'sekolah' => $data->pluck('nama_instansi')->filter()->unique()->count(),
                'siswa' => (int) $data->sum('jumlah_siswa')
            ];
        };

        $sma = $getSchoolStats('SMA');
        $smp = $getSchoolStats('SMP'); 
        $sd  = $getSchoolStats('SD');
        $tk  = $getSchoolStats('TK');

        $posyandu = $laporans->where('kategori', 'B3')->where('tipe_instansi', 'Posyandu')
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

    public function getKecamatanData(?Request $request, ?int $kecamatanId = null): JsonResponse
    {
        $q = trim((string) $request->query('q'));
        $jenisUsaha = $request->query('jenis_usaha');
        $statusSlhs = $request->query('status_slhs'); 

        $kecamatanId = $kecamatanId ?? (int) $request->query('kecamatan_id');

        $baseQuery = UnitUsaha::query()
            ->with([
                'kecamatan:id_kecamatan,nama_kecamatan',
                'kelurahan:id_kelurahan,nama_kelurahan',
                'puskesmas:id_puskesmas,nama_puskesmas',
                'laporanSlhs',
            ])
            ->withCount(['sasaranManfaat as kelompok_penerima'])
            ->withSum('sasaranManfaat as total_siswa', 'jumlah_siswa')
            ->withSum('sasaranManfaat as total_bumil', 'jumlah_bumil')
            ->withSum('sasaranManfaat as total_busui', 'jumlah_busui')
            ->withSum('sasaranManfaat as total_balita', 'jumlah_balita')
            ->withSum('sasaranManfaat as total_jiwa', 'jumlah_jiwa');

        if ($kecamatanId) {
            $baseQuery->where('id_kecamatan', $kecamatanId);
        }

        if ($q !== '') {
            $baseQuery->where(function ($sub) use ($q) {
                $sub->where('nama_unit_usaha', 'like', "%{$q}%")
                    ->orWhere('nama_pemilik', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        if (! empty($jenisUsaha)) {
            $baseQuery->where('jenis_usaha', $jenisUsaha);
        }

        if ($statusSlhs === 'sudah_slhs') {
            $baseQuery->whereHas('laporanSlhs', function ($q) {
                $q->where('status_slhs', 'selesai');
            });
        }

        $rows = (clone $baseQuery)
            ->latest('created_at')
            ->paginate(15);

        $formattedRows = $rows->map(function ($row) {
            $totalPenerima = (int) ($row->total_siswa ?? 0)
                + (int) ($row->total_bumil ?? 0)
                + (int) ($row->total_busui ?? 0)
                + (int) ($row->total_balita ?? 0);

            $statusIklRaw = strtolower((string) ($row->laporanSlhs?->status_ikl ?? ''));
            $statusSlhsRaw = strtolower((string) ($row->laporanSlhs?->status_slhs ?? ''));

            $statusMap = [
                'belum_mengajukan' => 'Belum Mengajukan',
                'sudah_mengajukan' => 'Sudah Mengajukan',
                'selesai' => 'Selesai',
                '' => 'Belum Ada',
            ];

            $statusIklLabel = $statusMap[$statusIklRaw] ?? ucfirst($statusIklRaw);
            $statusSlhsLabel = $statusMap[$statusSlhsRaw] ?? ucfirst($statusSlhsRaw);

            if ($statusIklRaw === 'selesai') {
                $nilaiIkl = (float) ($row->laporanSlhs?->nilai_ikl ?? 0);
                $statusIklLabel = $nilaiIkl >= 80 ? 'Memenuhi Syarat' : 'Belum Memenuhi';
            }

            return [
                'id_sppg' => $row->id_unit_usaha,
                'nama_sppg' => $row->nama_unit_usaha,
                'jenis_usaha' => $row->jenis_usaha,
                'status_ikl_label' => $statusIklLabel,
                'status_slhs_label' => $statusSlhsLabel,
                'jumlah_pegawai' => number_format((int) ($row->jumlah_pegawai ?? 0), 0, ',', '.'),
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

    public function getRekapByKecamatan(string $type): JsonResponse
    {
        return match ($type) {
            'sppg','unit' => $this->rekapSppg(),
            'kelompok-penerima' => $this->rekapKelompokPenerima(),
            'penerima' => $this->rekapPenerima(),
            default => response()->json(['success' => false, 'message' => 'Tipe tidak valid'], 400)
        };
    }


    private function rekapSppg(): JsonResponse
    {
        try {
            $kecamatans = Kecamatan::query()
                ->orderBy('nama_kecamatan')
                ->get();

            $rows = $kecamatans->map(function ($kec) {
                $units = UnitUsaha::where('id_kecamatan', $kec->id_kecamatan)->with('laporanSlhs')->get();

                $jumlahSppg = $units->count();
                
                $memenuhi = $units->filter(function ($unit) {
                    $status = strtolower((string) ($unit->laporanSlhs?->status_ikl ?? ''));
                    $nilai = (float) ($unit->laporanSlhs?->nilai_ikl ?? 0);
                    return $status === 'selesai' && $nilai >= 80;
                })->count();
                
                $belumMemenuhi = $units->filter(function ($unit) {
                    $status = strtolower((string) ($unit->laporanSlhs?->status_ikl ?? ''));
                    $nilai = (float) ($unit->laporanSlhs?->nilai_ikl ?? 0);
                    return $status === 'selesai' && $nilai < 80;
                })->count();
                
                $belumMengajukan = $units->filter(function ($unit) {
                    $status = strtolower((string) ($unit->laporanSlhs?->status_ikl ?? ''));
                    return empty($unit->laporanSlhs?->status_ikl) || $status === 'belum_mengajukan';
                })->count();
                
                $sudahSlhs = $units->filter(function ($unit) {
                    $status = strtolower((string) ($unit->laporanSlhs?->status_slhs ?? ''));
                    return !empty($unit->laporanSlhs?->status_slhs) && $status === 'selesai';
                })->count();
                
                $belumSlhs = $units->filter(function ($unit) {
                    $status = strtolower((string) ($unit->laporanSlhs?->status_slhs ?? ''));
                    return empty($unit->laporanSlhs?->status_slhs) || $status !== 'selesai';
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

    private function rekapKelompokPenerima(): JsonResponse
    {
        $kecamatans = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get();

        $rows = $kecamatans->map(function ($kec) {
            $units = UnitUsaha::where('id_kecamatan', $kec->id_kecamatan)->get();

            $sppg = $units->where('jenis_usaha', 'sppg')->count();
            $tpp = $units->where('jenis_usaha', 'tpp')->count();
            $catering = $units->where('jenis_usaha','catering')->count();
            $restoran = $units->where('jenis_usaha','restoran')->count();
            $dam = $units->where('jenis_usaha', 'dam')->count();
            $kantin = $units->where('jenis_usaha', 'kantin')->count();

            $totalPegawai = (int) $units->sum('jumlah_pegawai');
            $totalPenjamahTerlatih = (int) $units->sum('jumlah_penjamah_terlatih');

            $aktif = $units->where('status_aktif', true)->count();
            $nonaktif = $units->where('status_aktif', false)->count();

            return [
                'kecamatan' => $kec->nama_kecamatan,
                'sppg' => $sppg,
                'tpp' => $tpp,
                'restoran'=>$restoran,
                'catering'=>$catering,
                'dam' => $dam,
                'kantin' => $kantin,
                'jumlah_pegawai' => $totalPegawai,
                'jumlah_penjamah_terlatih' => $totalPenjamahTerlatih,
                'aktif' => $aktif,
                'nonaktif' => $nonaktif,
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'rows' => $rows,
        ]);
    }

    private function rekapPenerima(): JsonResponse
    {
        $kecamatans = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get();

        $rows = $kecamatans->map(function ($kec) {
            $laporans = SasaranManfaat::whereHas('unitUsaha', function (Builder $query) use ($kec) {
                $query->where('id_kecamatan', $kec->id_kecamatan);
            })->get();

            $sma = (int) $laporans->where('kategori', 'Sekolah')->where('tipe_instansi', 'SMA')
                ->sum('jumlah_siswa');
            
            $smp = (int) $laporans->where('kategori', 'Sekolah')->where('tipe_instansi', 'SMP')
                ->sum('jumlah_siswa');
            
            $sd = (int) $laporans->where('kategori', 'Sekolah')->where('tipe_instansi', 'SD')
                ->sum('jumlah_siswa');
            
            $tk = (int) $laporans->where('kategori', 'Sekolah')->where('tipe_instansi', 'TK')
                ->sum('jumlah_siswa');
            
            $balita = (int) $laporans->where('kategori', 'B3')->sum('jumlah_balita');
            $bumil = (int) $laporans->where('kategori', 'B3')->sum('jumlah_bumil');
            $busui = (int) $laporans->where('kategori', 'B3')->sum('jumlah_busui');
            $umum = (int) $laporans->where('kategori', 'Umum')->sum('jumlah_jiwa');


            $jumlah = $sma + $smp + $sd + $tk + $balita + $bumil + $busui + $umum;

            return [
                'kecamatan' => $kec->nama_kecamatan,
                'sma_sederajat' => number_format($sma, 0, ',', '.'),
                'smp_sederajat' => number_format($smp, 0, ',', '.'),
                'sd_sederajat' => number_format($sd, 0, ',', '.'),
                'tka_paud_sederajat' => number_format($tk, 0, ',', '.'),
                'balita' => number_format($balita, 0, ',', '.'),
                'bumil' => number_format($bumil, 0, ',', '.'),
                'busui' => number_format($busui, 0, ',', '.'),
                'umum' => number_format($umum, 0, ',', '.'),
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
            'jenis_usaha'  => ['nullable', 'string', 'max:255'],
            'q'            => ['nullable', 'string', 'max:255'],
            'status_slhs'  => ['nullable', 'string', 'max:255'], 
        ]); 

        return [ 
            'kecamatan_id' => isset($validated['kecamatan_id']) ? (int) $validated['kecamatan_id'] : null, 
            'jenis_usaha'  => $validated['jenis_usaha'] ?? null,
            'q'            => $validated['q'] ?? null,
            'status_slhs'  => $validated['status_slhs'] ?? null, 
        ]; 
    } 

    private function applyFilters(Builder $query, array $filters): void 
    { 
        if (! empty($filters['kecamatan_id'])) { 
            $query->where('id_kecamatan', $filters['kecamatan_id']); 
        }
        
        if (! empty($filters['jenis_usaha'])) {
            $query->where('jenis_usaha', $filters['jenis_usaha']);
        }
        
        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($sub) use ($search) {
                $sub->where('nama_unit_usaha', 'like', "%{$search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        if (! empty($filters['status_slhs']) && $filters['status_slhs'] === 'sudah_slhs') {
            $query->whereHas('laporanSlhs', function ($q) {
                $q->where('status_slhs', 'selesai'); 
            });
        }
    }

    private function buildPendidikanCard(string $kode, string $judul, string $tipeInstansi, string $warna): array 
    { 
        $query = SasaranManfaat::query()
            ->where('kategori', 'Sekolah')
            ->where('tipe_instansi', $tipeInstansi); 
        return [ 
            'kode' => $kode, 
            'judul' => $judul, 
            'jumlah' => (int) (clone $query)->distinct('nama_instansi')->count('nama_instansi'), 
            'subJumlah' => 'Sekolah', 
            'nilai' => (int) (clone $query)->sum('jumlah_siswa'), 
            'subNilai' => 'Siswa', 
            'warna' => $warna, 
        ]; 
    }
}