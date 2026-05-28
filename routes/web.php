<?php
// FILE: routes/web.php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

// Halaman undangan → /?to=NamaTamu
Route::get('/', [InvitationController::class, 'show'])->name('invitation.show');

// RSVP
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');

// Ucapan & Doa
Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store');
Route::get('/wishes',  [WishController::class, 'index'])->name('wishes.index');