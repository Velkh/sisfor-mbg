<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\FotoSppg;
use App\Models\MenuSppg;
use App\Models\Puskesmas;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sppg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DaftarSppgController extends Controller
{
    public function create(): View
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->with(['menuSppg', 'fotoSppg'])
            ->where('id_users', $userId)
            ->first();

        $puskesmas = Puskesmas::query()
            ->orderBy('nama_puskesmas')
            ->get();

        $kecamatan = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get();

        $kelurahan = Kelurahan::query()
            ->orderBy('nama_kelurahan')
            ->get();

        return view('sppg.profile', compact('puskesmas', 'sppg', 'kecamatan', 'kelurahan'));
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
            'id_kecamatan' => ['required', 'exists:kecamatan,id_kecamatan'],
            'id_kelurahan' => ['required', 'exists:kelurahan,id_kelurahan'],


            'foto_sppg' => ['nullable', 'array'],
            'foto_sppg.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'menu_nama' => ['nullable', 'array'],
            'menu_nama.*' => ['nullable', 'string', 'max:255'],
            'menu_foto' => ['nullable', 'array'],
            'menu_foto.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $sppgLama = Sppg::query()
            ->with(['menuSppg', 'fotoSppg'])
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

            $sppg = Sppg::query()->updateOrCreate(
                ['id_users' => $userId],
                [
                    'nama_sppg' => $validated['nama_sppg'],
                    'nama_kepala' => $validated['nama_kepala'],
                    'foto_kepala' => $fotoKepalaPath,
                    'nama_mitra' => $validated['nama_mitra'],
                    'jml_pegawai' => $validated['jml_pegawai'],
                    'kapasitas_porsi' => $validated['kapasitas_porsi'],
                    'id_puskesmas' => $validated['id_puskesmas'],
                    'id_kecamatan' => $validated['id_kecamatan'],
                    'id_kelurahan' => $validated['id_kelurahan'],
                ]
            );

            if ($request->hasFile('foto_sppg')) {
                $sppg->fotoSppg()->get()->each(function (FotoSppg $foto): void {
                    Storage::disk('public')->delete($foto->foto_sppg);
                });
                $sppg->fotoSppg()->delete();

                foreach ($request->file('foto_sppg') as $fotoFile) {
                    $path = $fotoFile->store('foto_sppg', 'public');
                    $sppg->fotoSppg()->create([
                        'foto_sppg' => $path,
                    ]);
                }
            }

            $menuNames = $request->input('menu_nama', []);
            $menuPhotos = $request->file('menu_foto', []);
            $hasMenuInput = count(array_filter($menuNames, fn ($item) => trim((string) $item) !== '')) > 0 || count($menuPhotos) > 0;

            if ($hasMenuInput) {
                $sppg->menuSppg()->get()->each(function (MenuSppg $menu): void {
                    if ($menu->foto_menu) {
                        Storage::disk('public')->delete($menu->foto_menu);
                    }
                });
                $sppg->menuSppg()->delete();

                $maxRows = max(count($menuNames), count($menuPhotos));

                for ($i = 0; $i < $maxRows; $i++) {
                    $namaMenu = trim((string) ($menuNames[$i] ?? ''));
                    $fotoMenuFile = $menuPhotos[$i] ?? null;

                    if ($namaMenu === '' && ! $fotoMenuFile) {
                        continue;
                    }

                    if ($namaMenu === '' || ! $fotoMenuFile) {
                        throw ValidationException::withMessages([
                            'menu_nama' => 'Nama menu dan foto menu harus diisi berpasangan.',
                        ]);
                    }

                    $fotoMenuPath = $fotoMenuFile->store('foto_menu', 'public');

                    $sppg->menuSppg()->create([
                        'nama_menu' => $namaMenu,
                        'foto_menu' => $fotoMenuPath,
                    ]);
                }
            }

            return $sppg;
        });

        $pesan = $sppg->wasRecentlyCreated
            ? 'Data SPPG berhasil ditambahkan.'
            : 'Data SPPG berhasil diperbarui.';

        return redirect()
            ->route('sppg.profile')
            ->with('success', $pesan);
    }
}