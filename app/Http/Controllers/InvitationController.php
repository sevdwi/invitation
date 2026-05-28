<?php
// FILE: app/Http/Controllers/InvitationController.php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show(Request $request)
    {
        $invitation = Invitation::where('is_active', true)
            ->with([
                'galleries',
                'wishes' => fn($q) => $q->where('is_approved', true)->latest()->limit(10),
            ])
            ->firstOrFail();

        $guestName = $request->query('to', 'Tamu Undangan');

        
        // Pisahkan $wishes sebagai variabel tersendiri agar bisa diakses di view
        $wishes = $invitation->wishes;
        return view('invitation.show', compact('invitation', 'guestName', 'wishes'));

    }
}