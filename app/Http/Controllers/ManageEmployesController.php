<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ManageEmployesController extends Controller
{
    public function settingAttendance()
    {
        $employes = User::all(); 
        return view('pimpinan.settingAbsensi.index', compact('employes'));
    }

    
    public function editAttendanceSetting($id)
    {
        $user = User::findOrFail($id); 
        return view('pimpinan.settingAbsensi.edit', compact('user'));
    }

    public function updateAttendanceSetting(Request $request, $id)
{
    
    $request->validate([
        'latitude'  => 'required',
        'longitude' => 'required',
        'radius'    => 'required|numeric|min:10',
        'startTime' => 'required', 
        'quitTime'  => 'required', 
        'jadwal'    => 'required|array'
    ]);

    $user = User::findOrFail($id);
    
    
    $user->update([
        'latitude'     => $request->latitude,
        'longitude'    => $request->longitude,
        'radius'       => $request->radius,
        'startTime'    => $request->startTime,
        'quitTime'     => $request->quitTime,
        'workSchedule' => $request->jadwal, 
    ]);

    
    return redirect()->route('pimpinan.settingAbsensi.edit', $id)
                     ->with('success', 'Konfigurasi absensi berhasil diperbarui!');
}


    

    public function index() {
        $employes = User::all();
        return view('pimpinan.kelolaKaryawan.index', compact('employes'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role'     => 'required',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        User::create($data);
        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role'  => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if($request->hasfile('photo')){
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->save();
        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri');
        }
        
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'Karyawan berhasil dihapus');
    }
}