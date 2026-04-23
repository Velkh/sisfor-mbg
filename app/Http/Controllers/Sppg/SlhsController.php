<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use App\Models\Sppg;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SlhsController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        if (! $sppg) {
            return redirect()
                ->route('sppg.profile')
                ->with('error', 'Data SPPG belum ada. Lengkapi profil terlebih dahulu.');
        }

        $isIklMemenuhi = $sppg->status_ikl === 'selesai'
            && $sppg->hasil_ikl === 'memenuhi'
            && (int) ($sppg->nilai_ikl ?? 0) >= 80;

        if (! $isIklMemenuhi) {
            return redirect()
                ->route('sppg.suratlaik')
                ->with('error', 'SLHS tidak bisa diubah karena IKL belum memenuhi (status IKL selesai, hasil memenuhi, nilai minimal 80).');
        }

        $validated = $request->validate([
            'status_slhs' => ['required', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'tgl_berlaku' => ['nullable', 'date'],
            'tgl_berakhir' => ['nullable', 'date', 'after_or_equal:tgl_berlaku'],
            'foto_slhs' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($validated['status_slhs'] === 'selesai') {
            $request->validate([
                'tgl_berlaku' => ['required', 'date'],
                'tgl_berakhir' => ['required', 'date', 'after_or_equal:tgl_berlaku'],
            ]);
        } else {
            $validated['tgl_berlaku'] = null;
            $validated['tgl_berakhir'] = null;
        }

        $filePath = $sppg->foto_slhs;

        if ($validated['status_slhs'] === 'selesai') {
            if ($request->hasFile('foto_slhs')) {
                if ($sppg->foto_slhs) {
                    Storage::disk('public')->delete($sppg->foto_slhs);
                }
                $filePath = $request->file('foto_slhs')->store('foto_slhs', 'public');
            } elseif (! $sppg->foto_slhs) {
                return back()
                    ->withErrors(['foto_slhs' => 'File SLHS wajib diisi saat status selesai.'])
                    ->withInput();
            }
        }

        if ($validated['status_slhs'] !== 'selesai' && $sppg->foto_slhs) {
            Storage::disk('public')->delete($sppg->foto_slhs);
            $filePath = null;
        }

        $sppg->update([
            'status_slhs' => $validated['status_slhs'],
            'tgl_berlaku' => $validated['tgl_berlaku'],
            'tgl_berakhir' => $validated['tgl_berakhir'],
            'foto_slhs' => $filePath,
        ]);

        $redirect = redirect()
            ->route('sppg.suratlaik')
            ->with('success', 'Data SLHS berhasil disimpan.');

        if ($sppg->status_slhs === 'selesai' && ! empty($sppg->tgl_berakhir)) {
            $today = Carbon::today();
            $endDate = Carbon::parse($sppg->tgl_berakhir)->startOfDay();
            $remainingDays = $today->diffInDays($endDate, false);

            if ($remainingDays <= 30) {
                $redirect->with('warning', 'Masa berlaku SLHS tinggal ' . max($remainingDays, 0) . ' hari. Segera lakukan pembaruan.');
                $redirect->with('warning_type', 'danger');
            } elseif ($remainingDays <= 90) {
                $redirect->with('warning', 'Masa berlaku SLHS tinggal ' . $remainingDays . ' hari. Sudah masuk periode wajib diperbarui.');
                $redirect->with('warning_type', 'warning');
            }
        }

        return $redirect;
    }
}