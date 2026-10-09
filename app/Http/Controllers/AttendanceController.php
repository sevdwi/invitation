<?php
// FILE: app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// Daftar hadir: tamu scan QR di lokasi acara lalu mengisi form ini sendiri
class AttendanceController extends Controller
{
    public function create(Request $request, Invitation $invitation)
    {
        return view('attendance.create', [
            'invitation' => $invitation,
            // Nama bisa ikut terisi bila QR/link membawa ?to=Nama+Tamu
            'guestName'  => trim((string) $request->query('to')),
        ]);
    }

    public function store(StoreAttendanceRequest $request, Invitation $invitation): RedirectResponse
    {
        $attendance = $invitation->attendances()->create($request->validated());

        return redirect()
            ->route('attendance.create', $invitation)
            ->with('attended', $attendance->guest_name);
    }
}
