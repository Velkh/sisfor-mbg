<?php

namespace App\Http\Controllers\Kecamatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Models\UnitUsaha;

class SlhsController extends Controller {
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        $items = UnitUsaha::query()
            ->with(['laporanSlhs', 'sasaranManfaat', 'kelurahan', 'puskesmas'])
            ->where('id_kecamatan', $user->id_kecamatan)
            ->whereIn('jenis_usaha', $allowedJenisUsaha)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('kecamatan.kelayakan', [
            'items' => $items,
        ]);
    }

    public function showKelayakan(UnitUsaha $unit): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        abort_unless(
            (int) $unit->id_kecamatan === (int) $user->id_kecamatan
            && in_array(strtolower((string) $unit->jenis_usaha), $allowedJenisUsaha, true),
            403
        );

        $unit->load(['laporanSlhs', 'sasaranManfaat', 'kelurahan', 'puskesmas']);
        $items = UnitUsaha::query()
            ->with(['laporanSlhs', 'sasaranManfaat', 'kelurahan', 'puskesmas'])
            ->where('id_kecamatan', $user->id_kecamatan)
            ->whereIn('jenis_usaha', $allowedJenisUsaha)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('kecamatan.kelayakan', [
            'unit' => $unit,
            'items' => $items,
        ]);
    }

    private function allowedJenisUsaha($aksesTipeUsaha)
    {
        if (!$aksesTipeUsaha) {
            return [];
        }
        
        return is_array($aksesTipeUsaha) ? $aksesTipeUsaha : explode(',', $aksesTipeUsaha);
    }
}