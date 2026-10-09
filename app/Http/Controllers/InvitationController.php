<?php
// FILE: app/Http/Controllers/InvitationController.php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

// Halaman undangan publik. Pembuatan & pengelolaan ada di Dashboard\InvitationController.
class InvitationController extends Controller
{
    public function show(Request $request, Invitation $invitation)
    {
        $invitation->load('galleries');

        $wishes = $invitation->wishes()->where('is_approved', true)->limit(10)->get();

        // Nama tamu dari /{slug}?to=Nama+Tamu
        $guestName = trim((string) $request->query('to')) ?: 'Tamu Undangan';

        // Load view sesuai tema yang dipilih
        $theme = $invitation->theme ?: 'netflix';
        $view = "themes.{$theme}.show";

        if (! view()->exists($view)) {
            $view = 'themes.netflix.show';
        }

        return view($view, compact('invitation', 'guestName', 'wishes'));
    }
}
