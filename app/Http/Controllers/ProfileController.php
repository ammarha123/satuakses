<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone'    => ['nullable', 'string', 'max:30'],
            'gender'   => ['nullable', 'in:Laki-laki,Perempuan'],
            'province' => ['nullable', 'string', 'max:100'],
            'city'     => ['nullable', 'string', 'max:100'],
            'bio'      => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // normalisasi email
        $validated['email'] = Str::lower($validated['email']);

        // handle foto
        if ($request->hasFile('profile_photo')) {
            // hapus lama
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'confirm' => ['required', 'in:HAPUS'],
        ], [
            'confirm.in' => 'Tulis "HAPUS" untuk konfirmasi.',
        ]);

        $user = Auth::user();

        // hapus foto bila ada
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Akun berhasil dihapus.');
    }
}
