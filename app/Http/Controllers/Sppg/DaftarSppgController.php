<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Puskesmas;
use App\Models\Sppg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DaftarSppgController extends Controller
{
    public function create(): View
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        $puskesmas = Puskesmas::query()
            ->orderBy('nama_puskesmas')
            ->get();

        return view('sppg.profile', compact('puskesmas', 'sppg'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'nama_sppg' => ['required', 'string', 'max:255'],
            'nama_kepala' => ['required', 'string', 'max:255'],
            'foto_kepala' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'nama_mitra' => ['required', 'string', 'max:255'],
            'jml_pegawai' => ['required', 'integer', 'min:1'],
            'kapasitas_porsi' => ['required', 'integer', 'min:1'],
            'id_puskesmas' => ['required', 'exists:puskesmas,id_puskesmas'],
        ]);

        $sppgLama = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        $sppg = DB::transaction(function () use ($request, $validated, $userId, $sppgLama): Sppg {
            $fotoKepalaPath = $sppgLama?->foto_kepala;

            if ($request->hasFile('foto_kepala')) {
                if ($sppgLama?->foto_kepala) {
                    Storage::disk('public')->delete($sppgLama->foto_kepala);
                }

                $fotoKepalaPath = $request->file('foto_kepala')->store('foto_kepala', 'public');
            }

            return Sppg::query()->updateOrCreate(
                ['id_users' => $userId],
                [
                    'nama_sppg' => $validated['nama_sppg'],
                    'nama_kepala' => $validated['nama_kepala'],
                    'foto_kepala' => $fotoKepalaPath,
                    'nama_mitra' => $validated['nama_mitra'],
                    'jml_pegawai' => $validated['jml_pegawai'],
                    'kapasitas_porsi' => $validated['kapasitas_porsi'],
                    'id_puskesmas' => $validated['id_puskesmas'],
                ]
            );
        });

        $pesan = $sppg->wasRecentlyCreated
            ? 'Data SPPG berhasil ditambahkan.'
            : 'Data SPPG berhasil diperbarui.';

        return redirect()
            ->route('sppg.profile')
            ->with('success', $pesan);
    }
}