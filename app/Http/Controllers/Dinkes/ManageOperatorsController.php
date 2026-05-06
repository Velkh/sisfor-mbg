<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kecamatan;
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
            ->where('role', 'admin_kecamatan')
            ->with(['kecamatan:id_kecamatan,nama_kecamatan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('nama_kecamatan', 'like', '%' . $search . '%');
                    });
            })
            ->when($kecamatanId, function ($query) use ($kecamatanId) {
                $query->where('id_kecamatan', $kecamatanId);
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

    public function create(): View
    {
        $kecamatanOptions = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get(['id_kecamatan', 'nama_kecamatan']);

        $jenisUsahaOptions = [
            'sppg' => 'SPPG',
            'tpp' => 'TPP',
            'dam' => 'DAM',
            'kantin' => 'Kantin',
        ];

        return view('dinkes.manage-operators.form', [
            'kecamatanOptions' => $kecamatanOptions,
            'jenisUsahaOptions' => $jenisUsahaOptions,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'id_kecamatan' => ['required', 'integer', 'exists:kecamatan,id_kecamatan'],
            'akses_tipe_usaha' => ['required', 'in:sppg,tpp,dam,kantin'],
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $validated['id_kecamatan'],
            'akses_tipe_usaha' => $validated['akses_tipe_usaha'],
        ]);

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Admin Kecamatan berhasil ditambahkan.');
    }

    public function show(User $operator): View
    {
        abort_unless($operator->role === 'admin_kecamatan', 404);

        $operator->load('kecamatan');

        return view('dinkes.manage-operators.show', [
            'operator' => $operator,
        ]);
    }

    public function edit(User $operator): View
    {
        abort_unless($operator->role === 'admin_kecamatan', 404);

        $operator->load('kecamatan');

        $kecamatanOptions = Kecamatan::query()
            ->orderBy('nama_kecamatan')
            ->get(['id_kecamatan', 'nama_kecamatan']);

        $jenisUsahaOptions = [
            'sppg' => 'SPPG',
            'tpp' => 'TPP',
            'dam' => 'DAM',
            'kantin' => 'Kantin',
        ];

        return view('dinkes.manage-operators.form', [
            'operator' => $operator,
            'kecamatanOptions' => $kecamatanOptions,
            'jenisUsahaOptions' => $jenisUsahaOptions,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'admin_kecamatan', 404);

        $validated = $request->validate([
            'password' => ['nullable', 'string', 'min:8'],
            'id_kecamatan' => ['required', 'integer', 'exists:kecamatan,id_kecamatan'],
            'akses_tipe_usaha' => ['required', 'in:sppg,tpp,dam,kantin'],
        ]);

        $updateData = [
            'id_kecamatan' => $validated['id_kecamatan'],
            'akses_tipe_usaha' => $validated['akses_tipe_usaha'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $operator->update($updateData);

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Admin Kecamatan berhasil diperbarui.');
    }

    public function destroy(User $operator): RedirectResponse
    {
        abort_unless($operator->role === 'admin_kecamatan', 404);

        $operator->delete();

        return redirect()
            ->route('admin.manage.index')
            ->with('success', 'Admin Kecamatan berhasil dihapus.');
    }
}