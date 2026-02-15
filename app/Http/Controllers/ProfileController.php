<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
    
    // Ambil data yang sudah divalidasi
    $validatedData = $request->validated();

    // Cek apakah ada file foto yang diunggah
    if ($request->hasFile('photo')) {
        // 1. Hapus foto lama dari storage jika ada (agar tidak memenuhi server)
        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        // 2. Simpan foto baru ke folder 'profile-photos' di disk public
        $path = $request->file('photo')->store('photos', 'public');
        
        // 3. Masukkan path foto ke dalam array data untuk disimpan ke DB
        $validatedData['photo'] = $path;
        }

    // Isi data user dengan data yang sudah divalidasi (termasuk path foto jika ada)
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
        $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
