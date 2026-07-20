<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2   { text-align: center; margin-bottom: 4px; }
        p    { text-align: center; margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th   { background: #181f49; color: #fff; padding: 6px 8px; text-align: center; }
        td   { padding: 5px 8px; border: 1px solid #ccc; text-align: center; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Rekap Absensi Karyawan</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->check_in  ?? '-' }}</td>
                <td>{{ $item->check_out ?? '-' }}</td>
                <td>{{ $item->status    ?? '-' }}</td>
                <td>{{ $item->latitude_in ?? '-' }} {{ $item->longitude_in ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Tidak ada data absensi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>