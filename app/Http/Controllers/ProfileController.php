<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();

        // Return appropriate view based on user role
        if ($user->role === 'marketing') {
            return view('marketing.pengaturan.edit', compact('user'));
        }

        return view('admin.pengaturan.edit', compact('user'));
    }

    /**
     * Update the user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $formType = $request->input('form_type', 'profile');

        if ($formType === 'password') {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ], [
                'current_password.required' => 'Password lama harus diisi untuk mengubah password',
                'password.required' => 'Password baru harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
            ]);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->withInput();
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Masukkan format email yang valid',
            'email.unique' => 'Email sudah terdaftar',
            'profile_photo.image' => 'File foto harus berupa gambar',
            'profile_photo.mimes' => 'Format foto harus jpg, jpeg, png, atau webp',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        unset($validated['profile_photo']);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }
}
