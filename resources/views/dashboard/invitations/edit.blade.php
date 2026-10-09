{{-- FILE: resources/views/dashboard/invitations/edit.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Edit Undangan')

@section('content')
    <a href="{{ route('dashboard.invitations.show', $invitation) }}" class="text-sm text-brand-gold hover:underline">← Kembali ke undangan</a>
    <h1 class="font-serif text-3xl text-white mt-2 mb-6">Edit Undangan</h1>

    <form class="card p-6 sm:p-8 space-y-5" action="{{ route('dashboard.invitations.update', $invitation) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')

        {{-- Pilih Tema (di edit) --}}
        <h3 class="font-serif text-lg text-white border-b border-white/10 pb-2">Tema Undangan</h3>
        <div>
            <label for="theme" class="label">Ubah Tema</label>
            <select id="theme" name="theme" class="field">
                @foreach (\App\Models\Invitation::AVAILABLE_THEMES as $key => $label)
                    <option value="{{ $key }}" @selected(old('theme', $invitation->theme) === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <p class="text-xs ml-1 mt-2 text-brand-gold">Tema akan berubah setelah disimpan. Preview: buka halaman undangan di tab baru.</p>
        </div>

        @include('dashboard.invitations._form')

        <div class="pt-4 flex gap-2">
            <button type="submit" class="btn-gold">Simpan Perubahan</button>
            <a href="{{ route('dashboard.invitations.show', $invitation) }}" class="btn-ghost">Batal</a>
        </div>
    </form>
@endsection
