<?php
// FILE: app/Http/Controllers/Dashboard/AttendanceController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Support\QrCode;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    // Halaman QR siap cetak untuk diletakkan di meja penerima tamu
    public function qr(Invitation $myInvitation)
    {
        $url = route('attendance.create', $myInvitation);

        return view('dashboard.invitations.qr', [
            'invitation' => $myInvitation,
            'url'        => $url,
            'qr'         => QrCode::svg($url, 480),
        ]);
    }

    public function destroy(Invitation $myInvitation, int $attendance): RedirectResponse
    {
        $myInvitation->attendances()->findOrFail($attendance)->delete();

        return back()->with('success', 'Data kehadiran berhasil dihapus.');
    }
}
