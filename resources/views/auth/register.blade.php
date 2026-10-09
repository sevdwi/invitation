{{-- FILE: resources/views/auth/register.blade.php --}}
@extends('layouts.head')

@section('title', 'Daftar — Indocreator')

@section('content')
    <main class="card w-full max-w-md overflow-hidden">
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-60"></div>

        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="font-serif text-4xl text-white mb-1">Indocreator</h1>
            <p class="font-serif italic text-lg text-white/80">Buat akun untuk mulai membuat undangan</p>
        </div>

        <form class="px-8 pb-10 space-y-5" action="{{ route('register') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="label">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                       autocomplete="name" placeholder="Nama Anda" class="field">
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       autocomplete="email" placeholder="nama@email.com" class="field">
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="label">Kata Sandi (min. 8 karakter)</label>
                <input type="password" id="password" name="password" required
                       autocomplete="new-password" class="field">
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="label">Ulangi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       autocomplete="new-password" class="field">
            </div>

            <button type="submit" class="btn-gold w-full !py-4 !text-sm">Daftar</button>

            @include('auth._google')

            <p class="text-center text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-brand-gold hover:underline">Masuk di sini</a>
            </p>
        </form>
    </main>
@endsection
