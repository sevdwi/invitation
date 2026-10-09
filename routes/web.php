<?php
// FILE: routes/web.php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\WishController;
use App\Models\Invitation;
use Illuminate\Support\Facades\Route;

// Halaman utama → sementara diarahkan ke dashboard (tamu akan dilempar ke login)
Route::redirect('/', '/dashboard');

// ── Login & Registrasi ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

    // Daftar / masuk lewat akun Google
    Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Dashboard Klien ──────────────────────────────────────────────
// {myInvitation} hanya cocok dengan undangan milik user yang login (selain itu 404),
// termasuk undangan yang sedang dinonaktifkan.
Route::bind('myInvitation', fn (string $slug) => request()->user()?->invitations()->where('slug', $slug)->first() ?? abort(404));

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [Dashboard\InvitationController::class, 'index'])->name('dashboard');

    Route::name('dashboard.')->group(function () {
        Route::get('/invitations/create',                [Dashboard\InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations',                      [Dashboard\InvitationController::class, 'store'])->name('invitations.store');
        Route::get('/invitations/{myInvitation}',        [Dashboard\InvitationController::class, 'show'])->name('invitations.show');
        Route::get('/invitations/{myInvitation}/edit',   [Dashboard\InvitationController::class, 'edit'])->name('invitations.edit');
        Route::put('/invitations/{myInvitation}',        [Dashboard\InvitationController::class, 'update'])->name('invitations.update');
        Route::delete('/invitations/{myInvitation}',     [Dashboard\InvitationController::class, 'destroy'])->name('invitations.destroy');

        // Galeri
        Route::post('/invitations/{myInvitation}/gallery',             [Dashboard\GalleryController::class, 'store'])->name('gallery.store');
        Route::delete('/invitations/{myInvitation}/gallery/{gallery}', [Dashboard\GalleryController::class, 'destroy'])->name('gallery.destroy');

        // Moderasi ucapan
        Route::patch('/invitations/{myInvitation}/wishes/{wish}',  [Dashboard\WishController::class, 'update'])->name('wishes.update');
        Route::delete('/invitations/{myInvitation}/wishes/{wish}', [Dashboard\WishController::class, 'destroy'])->name('wishes.destroy');

        // Daftar hadir
        Route::get('/invitations/{myInvitation}/qr',                           [Dashboard\AttendanceController::class, 'qr'])->name('attendances.qr');
        Route::delete('/invitations/{myInvitation}/attendances/{attendance}', [Dashboard\AttendanceController::class, 'destroy'])->name('attendances.destroy');
    });
});

// ── Halaman Undangan Publik ──────────────────────────────────────
// Halaman publik hanya boleh membuka undangan yang aktif (selain itu 404)
Route::bind('invitation', fn (string $slug) => Invitation::where('slug', $slug)->where('is_active', true)->firstOrFail());

// Route per undangan → /{slug}?to=NamaTamu
// Harus paling bawah agar tidak menimpa route statis di atas.
Route::prefix('{invitation}')->where(['invitation' => '[a-z0-9-]+'])->group(function () {
    Route::get('/', [InvitationController::class, 'show'])->name('invitation.show');

    // RSVP
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');

    // Ucapan & Doa
    Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store');
    Route::get('/wishes',  [WishController::class, 'index'])->name('wishes.index');

    // Daftar hadir — dibuka tamu lewat scan QR di lokasi acara
    Route::get('/hadir',  [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/hadir', [AttendanceController::class, 'store'])->middleware('throttle:60,1')->name('attendance.store');

    // Album foto acara (foto tersimpan di Google Drive)
    Route::get('/album', [AlbumController::class, 'show'])->name('album.show');
});
