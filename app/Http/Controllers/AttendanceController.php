<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function checkin(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();

        // 1. Validasi Input
        $request->validate([
            'image' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // 2. Validasi Batas Keterlambatan (08:20)
        $batasMasuk = Carbon::createFromTime(8, 20, 0);
        if ($now->greaterThan($batasMasuk)) {
            return back()->with('error', 'Maaf, Anda sudah melewati batas keterlambatan (Maksimal 08:20).');
        }

        // 3. Cek apakah sudah absen hari ini
        $alreadyCheckedIn = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->exists();

        if ($alreadyCheckedIn) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // 4. Proses Foto Selfie (Base64)
        try {
            $img = $request->image; // Data Base64 dari Webcam
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $fileName = 'absensi_' . $user->id . '_' . time() . '.jpg';
            
            // Simpan ke storage/app/public/absensi
            Storage::disk('public')->put('absensi/' . $fileName, base64_decode($img));

            // 5. Simpan Data Absensi ke Database
            Attendance::create([
                'user_id'     => $user->id,
                'date'        => $today,
                'check_in'    => $now->toTimeString(),
                'image_in'    => $fileName,
                'latitude_in' => $request->latitude,
                'longitude_in'=> $request->longitude,
                'status'      => $now->greaterThan(Carbon::createFromTime(8, 0, 0)) ? 'Terlambat' : 'Hadir',
            ]);

            return back()->with('success', 'Berhasil melakukan absen masuk pada jam ' . $now->format('H:i'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses absensi: ' . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        // Validasi Koordinat
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
        }

        // Simpan Jam Keluar
        $attendance->update([
            'check_out' => $now->toTimeString(),
            'latitude_out' => $request->latitude,
            'longitude_out' => $request->longitude,
        ]);

        return back()->with('success', 'Berhasil melakukan absen keluar. Selamat beristirahat!');
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Ambil data asli dari database untuk riwayat
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        // Hitung Statistik Sederhana
        $stats = [
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'cuti' => 0, // Bisa dihubungkan ke tabel cuti nanti
            'alpa' => 0
        ];

        return view('karyawan.riwayat.index', compact('stats', 'attendances', 'startDate', 'endDate'));
    }
}