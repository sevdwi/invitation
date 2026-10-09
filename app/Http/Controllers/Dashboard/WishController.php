<?php
// FILE: app/Http/Controllers/Dashboard/WishController.php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;

class WishController extends Controller
{
    // Tampilkan / sembunyikan ucapan di halaman undangan
    public function update(Invitation $myInvitation, int $wish): RedirectResponse
    {
        $wish = $myInvitation->wishes()->findOrFail($wish);
        $wish->update(['is_approved' => ! $wish->is_approved]);

        return back()->with('success', $wish->is_approved ? 'Ucapan ditampilkan kembali.' : 'Ucapan disembunyikan.');
    }

    public function destroy(Invitation $myInvitation, int $wish): RedirectResponse
    {
        $myInvitation->wishes()->findOrFail($wish)->delete();

        return back()->with('success', 'Ucapan berhasil dihapus.');
    }
}
