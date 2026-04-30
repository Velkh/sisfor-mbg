<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kecamatan;
use App\Http\Controllers\DinkesController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageOperatorsController extends Controller
{
    public function index(Request $request): View
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

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.manage.index', ['mode' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        User::query()->create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'operator_sppg',
        ]);

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Operator berhasil ditambahkan.');
    }

    public function show(User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'operator_sppg', 404);

        return redirect()->route('admin.manage.index', [
            'mode' => 'show',
            'selected' => $operator->id_users,
        ]);
    }

    public function edit(User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'operator_sppg', 404);

        return redirect()->route('admin.manage.index', [
            'mode' => 'edit',
            'selected' => $operator->id_users,
        ]);
    }

    public function update(Request $request, User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'operator_sppg', 404);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $operator->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Password operator berhasil diperbarui.');
    }

    public function destroy(User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'operator_sppg', 404);

        $operator->delete();

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Operator berhasil dihapus.');
    }
}