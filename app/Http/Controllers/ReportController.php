<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function monthlyReport(Request $request)
    {
        // Ambil bulan dan tahun dari request atau gunakan bulan dan tahun saat ini
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        // Menentukan tanggal saat ini dan bulan sebelumnya serta bulan berikutnya
        $currentMonth = Carbon::create($year, $month);
        $previousMonth = $currentMonth->copy()->subMonth()->format('m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('m');

        // Ambil semua pengguna, termasuk kolom nik
        $users = User::select('id', 'name', 'nik')->get(); // Pastikan 'nik' adalah kolom yang ada di tabel users
        $dates = $currentMonth->daysInMonth; // Total days in the current month
        
        // Ambil data presensi untuk bulan dan tahun yang dipilih
        $presensi = Presensi::whereMonth('tanggal', $month)
                            ->whereYear('tanggal', $year)
                            ->get()
                            ->groupBy('user_id');

        // Persiapkan data presensi yang dipetakan berdasarkan tanggal untuk setiap pengguna
        $presensiByDate = [];
        foreach ($presensi as $userId => $records) {
            foreach ($records as $record) {
                $date = Carbon::parse($record->tanggal)->format('Y-m-d');
                // Menentukan status kehadiran
                if ($record->masuk && $record->pulang) {
                    $status = 'H'; // Hadir
                } elseif ($record->masuk && !$record->pulang) {
                    $status = 'I'; // Izin
                } else {
                    $status = 'A'; // Absen
                }
                $presensiByDate[$userId][$date] = $status;
            }
        }

        return view('admin.monthly-report', [
            'users' => $users,
            'dates' => $dates,
            'presensi' => $presensiByDate, // Ubah menjadi presensi yang sudah dipetakan
            'month' => $month,
            'year' => $year,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth
        ]);
    }
}
