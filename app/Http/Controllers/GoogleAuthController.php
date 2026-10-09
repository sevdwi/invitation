<?php
// FILE: app/Http/Controllers/GoogleAuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Dipakai untuk daftar maupun masuk: akun dibuat bila belum ada
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            // Tamu membatalkan di halaman Google, atau sesi/state sudah kedaluwarsa
            return $this->failed('Masuk dengan Google gagal. Silakan coba lagi.');
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return $this->failed('Akun Google Anda tidak memiliki alamat email.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user && $user = User::where('email', $email)->first()) {
            // Email sudah terdaftar lewat kata sandi → tautkan, tapi hanya bila
            // Google menjamin email itu memang milik pemilik akun Google-nya
            if (! data_get($googleUser->user, 'email_verified')) {
                return $this->failed('Email Google Anda belum terverifikasi. Silakan masuk dengan kata sandi.');
            }

            $user->forceFill([
                'google_id'         => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        $isNew = ! $user;

        if ($isNew) {
            $user = User::create([
                'name'      => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna',
                'email'     => $email,
                'google_id' => $googleUser->getId(),
            ]);

            if (data_get($googleUser->user, 'email_verified')) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        $response = redirect()->intended(route('dashboard'));

        return $isNew ? $response->with('success', 'Akun berhasil dibuat. Selamat datang!') : $response;
    }

    private function failed(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['email' => $message]);
    }
}
