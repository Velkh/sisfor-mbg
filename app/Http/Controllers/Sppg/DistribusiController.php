<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenerima;
use App\Models\Sppg;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistribusiController extends Controller{    
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        if (! $sppg) {
            return redirect()
                ->route('sppg.profile')
                ->with('error', 'Data SPPG belum tersedia. Lengkapi profil terlebih dahulu.');
        }

        $validated = $request->validate([
            'kategori' => ['required', 'in:Satuan Pendidikan,Kelompok B3'],
            'tipe_instansi' => ['required', 'in:TK Sederajat,SD Sederajat,SMP Sederajat,SMA Sederajat,Posyandu'],
            'nama_instansi' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:negeri,swasta'],
            'id_kelurahan' => ['required', 'exists:kelurahan,id_kelurahan'],
            'id_kecamatan' => ['required', 'exists:kecamatan,id_kecamatan'],
            'id_puskesmas' => ['required', 'exists:puskesmas,id_puskesmas'],
            'jml_siswa' => ['nullable', 'integer', 'min:0'],
            'jml_bumil' => ['nullable', 'integer', 'min:0'],
            'jml_busui' => ['nullable', 'integer', 'min:0'],
            'jml_balita' => ['nullable', 'integer', 'min:0'],
        ]);

        $tipeSekolah = ['TK Sederajat', 'SD Sederajat', 'SMP Sederajat', 'SMA Sederajat'];

        if ($validated['kategori'] === 'Satuan Pendidikan' && ! in_array($validated['tipe_instansi'], $tipeSekolah, true)) {
            return back()
                ->withErrors(['tipe_instansi' => 'Untuk kategori Satuan Pendidikan, tipe instansi harus sekolah.'])
                ->withInput();
        }

        if ($validated['kategori'] === 'Kelompok B3' && $validated['tipe_instansi'] !== 'Posyandu') {
            return back()
                ->withErrors(['tipe_instansi' => 'Untuk kategori Kelompok B3, tipe instansi harus Posyandu.'])
                ->withInput();
        }

        $jmlSiswa = (int) ($validated['jml_siswa'] ?? 0);
        $jmlBumil = (int) ($validated['jml_bumil'] ?? 0);
        $jmlBusui = (int) ($validated['jml_busui'] ?? 0);
        $jmlBalita = (int) ($validated['jml_balita'] ?? 0);

        if (in_array($validated['tipe_instansi'], $tipeSekolah, true) && $jmlSiswa <= 0) {
            return back()
                ->withErrors(['jml_siswa' => 'Jumlah siswa wajib diisi untuk instansi sekolah.'])
                ->withInput();
        }

        if ($validated['tipe_instansi'] === 'Posyandu' && ($jmlBumil + $jmlBusui + $jmlBalita) <= 0) {
            return back()
                ->withErrors(['jml_bumil' => 'Isi minimal satu data penerima (bumil, busui, atau balita) untuk posyandu.'])
                ->withInput();
        }

        if (in_array($validated['tipe_instansi'], $tipeSekolah, true)) {
            $jmlBumil = 0;
            $jmlBusui = 0;
            $jmlBalita = 0;
        } else {
            $jmlSiswa = 0;
        }

        $totalPenerimaInput = $jmlSiswa + $jmlBumil + $jmlBusui + $jmlBalita;

        $kapasitasSppg = (int) preg_replace('/\D+/', '', (string) ($sppg->kapasitas_porsi ?? 0));

        if ($kapasitasSppg <= 0) {
            return back()
                ->withErrors([
                    'jml_siswa' => 'Kapasitas porsi SPPG belum diatur. Lengkapi profil terlebih dahulu.',
                ])
                ->withInput();
        }

        $totalSudahTercatat = LaporanPenerima::query()
            ->where('id_sppg', $sppg->id_sppg)
            ->get()
            ->sum(function ($item) {
                return (int) $item->jml_siswa
                    + (int) $item->jml_bumil
                    + (int) $item->jml_busui
                    + (int) $item->jml_balita;
            });

        $totalSetelahDisimpan = $totalSudahTercatat + $totalPenerimaInput;

        if ($totalSetelahDisimpan > $kapasitasSppg) {
            $sisaKapasitas = max(0, $kapasitasSppg - $totalSudahTercatat);

            return back()
                ->withErrors([
                    'jml_siswa' => "Kapasitas terlampaui. Sisa kapasitas: {$sisaKapasitas}, input saat ini: {$totalPenerimaInput}, kapasitas total: {$kapasitasSppg}.",
                ])
                ->withInput();
        }

        LaporanPenerima::query()->create([
            'id_sppg' => $sppg->id_sppg,
            'kategori' => $validated['kategori'],
            'tipe_instansi' => $validated['tipe_instansi'],
            'nama_instansi' => $validated['nama_instansi'],
            'status' => $validated['status'],
            'id_kelurahan' => $validated['id_kelurahan'],
            'id_kecamatan' => $validated['id_kecamatan'],
            'id_puskesmas' => $validated['id_puskesmas'],
            'jml_siswa' => $jmlSiswa,
            'jml_bumil' => $jmlBumil,
            'jml_busui' => $jmlBusui,
            'jml_balita' => $jmlBalita,
        ]);

        return redirect()
            ->route('sppg.pelaporan')
            ->with('success', 'Laporan distribusi berhasil disimpan.');
    }
}