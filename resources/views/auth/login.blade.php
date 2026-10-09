{{-- FILE: resources/views/auth/login.blade.php --}}
@extends('layouts.head')

@section('title', 'Masuk — Indocreator')

@section('content')
    <main class="card w-full max-w-md overflow-hidden">
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-60"></div>

        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="font-serif text-4xl text-white mb-1">Indocreator</h1>
            <p class="font-serif italic text-lg text-white/80">Masuk ke akun Anda</p>
        </div>

        <form class="px-8 pb-10 space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       autocomplete="email" placeholder="nama@email.com" class="field">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="label">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                       autocomplete="current-password" class="field">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-white/70 cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="accent-[#d4af37]">
                Ingat saya
            </label>

            <button type="submit" class="btn-gold w-full !py-4 !text-sm">Masuk</button>

            @include('auth._google')

            <p class="text-center text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-gold hover:underline">Daftar di sini</a>
            </p>
        </form>
    </main>
@endsection
