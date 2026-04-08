<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

   
    public function collection()
    {
        $query = Attendance::with('user');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        return $query->get();
    }

    
    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Tanggal Absensi',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Koordinat Lokasi'
        ];
    }

    /**
    * Memetakan data ke kolom Excel agar rapi.
    */
    public function map($attendance): array
    {
        return [
            $attendance->user->name,
            Carbon::parse($attendance->created_at)->format('d-m-Y'),
            Carbon::parse($attendance->check_in)->format('H:i A'),
            $attendance->check_out ? Carbon::parse($attendance->check_out)->format('H:i A') : '--:--',
            $attendance->status,
            'Lat: ' . $attendance->latitude_in . ', Lon: ' . $attendance->longitude_in
            
        ];
    }

   
    public function styles(Worksheet $sheet)
    {
        return [
            
            1 => ['font' => ['bold' => true]],
        ];
    }
}