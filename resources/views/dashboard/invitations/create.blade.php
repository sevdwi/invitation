{{-- FILE: resources/views/dashboard/invitations/create.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Buat Undangan')

@section('content')
    <a href="{{ route('dashboard') }}" class="text-sm text-brand-gold hover:underline">← Undangan Saya</a>
    <h1 class="font-serif text-3xl text-white mt-2 mb-6">Buat Undangan</h1>

    <form class="card p-6 sm:p-8 space-y-5" action="{{ route('dashboard.invitations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Pilih Tema (hanya saat buat, tidak di edit) --}}
        <h3 class="font-serif text-lg text-white border-b border-white/10 pb-2">Tema Undangan</h3>
        <div>
            <label class="label">Pilih Tema <span class="text-red-400">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach (\App\Models\Invitation::AVAILABLE_THEMES as $key => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="theme" value="{{ $key }}" @checked($loop->first) required class="sr-only peer">
                        <div class="w-full border border-white/10 bg-brand-surface peer-checked:border-brand-gold peer-checked:bg-brand-gold/20 rounded-lg px-4 py-3 text-center transition">
                            <p class="text-white font-medium">{{ $label }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('theme') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        @include('dashboard.invitations._form')

        <div class="pt-4">
            <button type="submit" class="btn-gold">Buat Undangan</button>
        </div>
    </form>
@endsection
