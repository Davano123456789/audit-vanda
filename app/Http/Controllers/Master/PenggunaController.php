<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PenggunaRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('assessments')
            ->when($request->role, fn ($query, $role) => $query->where('role', $role))
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.pengguna.index', [
            'users' => $users,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function create()
    {
        return view('master.pengguna.create', [
            'pengguna' => new User(['role' => User::ROLE_ASESOR]),
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function store(PenggunaRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('master.pengguna.index')->with('success', 'Data saved. Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('master.pengguna.edit', [
            'pengguna' => $pengguna,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function update(PenggunaRequest $request, User $pengguna)
    {
        $data = $request->validated();

        if (blank($data['password'])) {
            unset($data['password']);
        }

        // Admin tidak boleh mengubah role akunnya sendiri agar tidak terkunci dari menu admin
        if ($pengguna->is($request->user())) {
            $data['role'] = $pengguna->role;
        }

        $pengguna->update($data);

        return redirect()->route('master.pengguna.index')->with('success', 'Data saved. Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna)
    {
        if ($pengguna->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($pengguna->assessments()->exists()) {
            return back()->with('error', "{$pengguna->name} masih memiliki assessment sehingga tidak dapat dihapus.");
        }

        $pengguna->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function roleOptions(): array
    {
        return [
            User::ROLE_ADMIN => 'Administrator',
            User::ROLE_ASESOR => 'Asesor',
        ];
    }
}
