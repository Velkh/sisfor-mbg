<?php

namespace App\Http\Controllers;
use App\Models\LaporanPenerima;
use App\Models\Kecamatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class DinkesController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dinkes.index');
    }

    /**
     * Show the SPPG management page.
     *
     * @return \Illuminate\View\View
     */
    public function kelola(Request $request): View
    {
        $search = trim((string) $request->get('q', ''));
        $kecamatanId = $request->integer('kecamatan_id');

        $operators = User::query()
            ->where('role', 'operator_sppg')
            ->with([
                'sppg:id_sppg,id_users,nama_sppg,nama_mitra,id_puskesmas',
                'sppg.laporanPenerimas:id_laporan,id_sppg,id_kecamatan,created_at',
                'sppg.laporanPenerimas.kecamatan:id_kecamatan,nama_kecamatan',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('username', 'like', '%' . $search . '%')
                        ->orWhereHas('sppg', function ($sppgQuery) use ($search) {
                            $sppgQuery->where('nama_sppg', 'like', '%' . $search . '%')
                                ->orWhere('nama_mitra', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($kecamatanId, function ($query) use ($kecamatanId) {
                $query->whereHas('sppg.laporanPenerimas', function ($subQuery) use ($kecamatanId) {
                    $subQuery->where('id_kecamatan', $kecamatanId);
                });
            })
            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        $kecamatanOptions = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get(['id_kecamatan', 'nama_kecamatan']);

        return view('dinkes.kelola', [
            'operators' => $operators,
            'kecamatanOptions' => $kecamatanOptions,
            'selectedKecamatanId' => $kecamatanId,
            'q' => $search,
        ]);
    }

    /**
     * Show the eligibility data page.
     *
     * @return \Illuminate\View\View
     */
    public function kelayakan()
    {
        return view('dinkes.kelayakan');
    }

    /**
     * Show the report summary page.
     *
     * @return \Illuminate\View\View
     */
    public function laporan()
    {
        return view('dinkes.laporan');
    }
}
