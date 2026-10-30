<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profil\PasswordRequest;
use App\Http\Requests\Profil\ProfilRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('profil.edit', [
            'user' => $user,
            // Ringkasan di kartu profil (khusus asesor)
            'assessmentCount' => $user->isAsesor() ? $user->assessments()->count() : null,
            'completedCount' => $user->isAsesor() ? $user->assessments()->where('status', 'completed')->count() : null,
        ]);
    }

    public function update(ProfilRequest $request)
    {
        $user = $request->user();
        $data = $request->safe()->only(['name', 'email', 'phone', 'address']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('photo') || $request->boolean('remove_photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] ??= null;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(PasswordRequest $request)
    {
        $request->user()->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diganti.')->with('tab', 'password');
    }
}
