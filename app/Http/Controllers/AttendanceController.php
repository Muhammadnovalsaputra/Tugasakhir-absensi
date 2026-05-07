<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport; 

class AttendanceController extends Controller
{
    public function index()
    {
    $user = Auth::user();
    $today = Carbon::today();

    $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    // Ambil setting global
    $setting = AttendanceSetting::getActive();

    return view('karyawan.index', compact('attendance', 'setting'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $fileName = 'rekap_absensi_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new AttendanceExport($startDate, $endDate), $fileName);
    }

    public function leaderIndex(Request $request)
    {
        $query = Attendance::with('user')->orderBy('date', 'desc');

        // Filter Nama
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendances = $query->paginate(10)->withQueryString();

        return view('pimpinan.rekapAbsensi.index', compact('attendances'));
    }

    public function checkin(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();

        $request->validate([
            'image_in'=>'required',
            'latitude_in' => 'required | numeric',
            'longitude_in' => 'required | numeric',

        ]);

        $setting = AttendanceSetting::getActive();
        if (!$setting) {
            return back()->with('error', 'Pengaturan absensi belum dikonfigurasi oleh admin.');
        }

        $currentDay = $now->translatedFormat('l');
        $workSchedule = $setting->work_schedule ?? [];

        if(!in_array($currentDay, $workSchedule)) {
            return back()->with('error', "Hari ini ($currentDay) bukan hari kerja. Jadwal kerja: " . implode(', ', $workSchedule));
        }

       $distance = $this->calculateDistance(
            $setting->latitude,
            $setting->longitude,   
            $request->latitude_in,
            $request->longitude_in
        );
        if ($distance > $setting->radius) {
        return back()->with('error',
            'Anda berada di luar jangkauan! Jarak Anda: ' . round($distance) . ' meter. ' .
            'Maksimal radius: ' . $setting->radius . ' meter.'
        );
        }

        $officeEntryTime = Carbon::parse($setting->start_time);

        if ($now->lt($officeEntryTime->copy()->subHour())) {
        return back()->with('error',
            'Terlalu pagi! Absensi hanya dapat dilakukan mulai ' .
            $officeEntryTime->copy()->subHour()->format('H:i') . '.'
        );
    }
    $alreadyCheckedIn = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->exists();

    if ($alreadyCheckedIn) {
        return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
    }

    $status = $now->greaterThan($officeEntryTime) ? 'Terlambat' : 'Hadir';

    // Simpan foto
    $fileName = null;
    if ($request->image_in) {
        $imageParts  = explode(";base64,", $request->image_in);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName    = uniqid() . '_' . $user->id . '.png';
        Storage::disk('public')->put('absensi/' . $fileName, $imageBase64);
    }

    Attendance::create([
        'user_id'      => $user->id,
        'date'         => $today,
        'check_in'     => $now->toTimeString(),
        'image_in'     => $fileName,
        'latitude_in'  => $request->latitude_in,
        'longitude_in' => $request->longitude_in,
        'status'       => $status,
    ]);

    return back()->with('success', 'Berhasil absen masuk! Status: ' . $status);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) 
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1609.344; 
    }

    public function checkout(Request $request)
    {
            $user  = Auth::user();
    $today = Carbon::today();
    $now   = Carbon::now();

    $request->validate([
        'latitude_out'  => 'required|numeric',
        'longitude_out' => 'required|numeric',
    ]);

    // Ambil setting global untuk jam pulang
    $setting = AttendanceSetting::getActive();

    if ($setting && $setting->quit_time) {
        $minExitTime = Carbon::createFromFormat('H:i:s', $setting->quit_time);
        if ($now->lessThan($minExitTime)) {
            return back()->with('error',
                'Absen pulang belum tersedia. Jam pulang adalah ' .
                Carbon::parse($setting->quit_time)->format('H:i') . '.'
            );
        }
    }

    $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->first();

    if (!$attendance) {
        return back()->with('error', 'Anda belum melakukan absen masuk.');
    }

    if ($attendance->check_out) {
        return back()->with('error', 'Anda sudah melakukan absen keluar hari ini.');
    }

    $attendance->update([
        'check_out'     => $now->toTimeString(),
        'latitude_out'  => $request->latitude_out,
        'longitude_out' => $request->longitude_out,
    ]);

    return back()->with('success', 'Berhasil melakukan absen keluar!');
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
        $stats = [
            'hadir'     => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'cuti'      => $attendances->whereIn('status', ['Cuti', 'Izin'])->count(), 
            'alpa'      => $attendances->where('status', 'Alpa')->count()
        ];

        return view('karyawan.riwayatKerja.index', compact('stats', 'attendances', 'startDate', 'endDate'));
    }

public function showSchedule()
{
    $user = Auth::user();
    $history = Attendance::where('user_id', $user->id)
        ->select('date', 'check_in', 'check_out', 'status')
        ->get();

    return view('karyawan.jadwalKerja.index', compact('user', 'history'));
}

}