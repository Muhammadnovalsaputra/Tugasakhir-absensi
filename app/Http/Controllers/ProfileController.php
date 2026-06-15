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

    public function index(Request $request)
{
    $user = $request->user(); 
    
    
    $currentTab = $request->query('tab', 'main'); 

    
    $menus = [
        ['icon' => '📩', 'label' => 'Email Setting', 'route' => 'profile.app', 'params' => ['tab' => 'email']],
        ['icon' => '🔒', 'label' => 'Account Security', 'route' => 'profile.app', 'params' => ['tab' => 'account']],
    ];

    return view('profile.mobile-profile', compact('user', 'currentTab', 'menus'));
}


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
    
    
    $validatedData = $request->validated();

    if ($request->hasFile('photo')) {
        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

    
        $path = $request->file('photo')->store('photos', 'public');
        $validatedData['photo'] = $path;
        }
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
