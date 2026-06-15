<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCorrectionRequest;
use App\Http\Requests\ReviewCorrectionRequest;
use App\Models\AttendanceCorrection;
use App\Services\AttendanceCorrectionService;
use Illuminate\Http\RedirectResponse;
use App\Models\KoreksiAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceCorrectionController extends Controller
{
    public function __construct(protected AttendanceCorrectionService $correctionService)
    {
    }

    // ─── Karyawan ────────────────────────────────────────────────

    /**
     * Tampilkan form pengajuan koreksi.
     */
    public function create(): View|RedirectResponse
    {
        $state = $this->correctionService->getTodayState(Auth::id());

        // Hanya boleh akses jika state memang 'show_correction'
        if ($state !== 'show_correction') {
            return redirect()->route('dashboard')
            ->with('error', 'Anda tidak dapat mengajukan koreksi saat ini.');
    }

        return view('karyawan.koreksiAbsen.create', [
            'threshold' => AttendanceCorrectionService::ALPA_THRESHOLD_MINUTES,
        ]);
    }

    /**
     * Proses submit pengajuan koreksi dari karyawan.
     */
    public function store(StoreCorrectionRequest $request): RedirectResponse
    {
        $result = $this->correctionService->submitCorrection(
            Auth::user(),
            $request->validated(),
            $request->file('proof_photo')
        );

        return redirect()->route('dashboard')
        ->with($result['status'], $result['message']);
    }

    // ─── Pimpinan / Admin ─────────────────────────────────────────

   
    public function leaderIndex(Request $request): View
    {
        $corrections = AttendanceCorrection::with(['user', 'reviewer'])
            ->when(
                $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )
            ->when(
                $request->filled('search'),
                fn($q) => $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%')
                )
            )
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected')")
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $pendingCount = AttendanceCorrection::pending()->count();

        return view('pimpinan.koreksiAbsen.index', compact('corrections', 'pendingCount'));
    }

    /**
     * Detail pengajuan koreksi.
     */
    public function show(AttendanceCorrection $correction): View
    {
        $correction->load(['user', 'attendance', 'reviewer']);

        return view('pimpinan.koreksiAbsen.show', compact('correction'));
    }

    /**
     * Setujui atau tolak pengajuan koreksi.
     */
    public function review(ReviewCorrectionRequest $request, AttendanceCorrection $correction): RedirectResponse
    {
        $result = match ($request->action) {
            'approve' => $this->correctionService->approve($correction, Auth::user(), $request->note),
            'reject'  => $this->correctionService->reject($correction, Auth::user(), $request->note),
            default   => ['status' => 'error', 'message' => 'Aksi tidak valid.'],
        };

        return redirect()->route('pimpinan.koreksiAbsen.index')
            ->with($result['status'], $result['message']);
    }
}