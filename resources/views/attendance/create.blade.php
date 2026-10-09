{{-- FILE: resources/views/attendance/create.blade.php --}}
{{-- Form daftar hadir yang dibuka tamu setelah scan QR di lokasi acara --}}
@extends('layouts.head')

@section('title', 'Daftar Hadir — ' . $invitation->groom_name . ' & ' . $invitation->bride_name)

@section('content')
    <main class="card w-full max-w-md overflow-hidden">
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-brand-gold to-transparent opacity-60"></div>

        <div class="px-8 pt-10 pb-6 text-center">
            <p class="text-xs uppercase tracking-[4px] text-brand-gold mb-2">Daftar Hadir</p>
            <h1 class="font-serif text-3xl text-white">{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</h1>
            <p class="text-sm mt-2">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</p>
        </div>

        @if (session('attended'))
            {{-- ── Sudah mengisi ──────────────────────── --}}
            <div class="px-8 pb-10 text-center space-y-5">
                <div class="rounded-lg border border-green-900 bg-green-900/30 px-4 py-5">
                    <p class="font-serif text-xl text-white">Terima kasih, {{ session('attended') }}!</p>
                    <p class="text-sm text-green-200 mt-1">Kehadiran Anda sudah tercatat. Selamat menikmati acara. 🙏</p>
                </div>

                <div class="flex flex-col gap-2">
                    @if ($invitation->drive_folder_id)
                        <a href="{{ route('album.show', $invitation) }}" class="btn-gold">Lihat Album Foto</a>
                    @endif
                    <a href="{{ route('invitation.show', $invitation) }}" class="btn-ghost">Lihat Undangan</a>
                    <a href="{{ route('attendance.create', $invitation) }}" class="text-sm text-brand-gold hover:underline mt-2">Isi untuk tamu lain</a>
                </div>
            </div>
        @else
            {{-- ── Form ───────────────────────────────── --}}
            <form class="px-8 pb-10 space-y-5" action="{{ route('attendance.store', $invitation) }}" method="POST">
                @csrf

                <div>
                    <label for="guest_name" class="label">Nama <span class="text-red-400">*</span></label>
                    <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name', $guestName) }}" required
                           maxlength="100" autocomplete="name" placeholder="Nama lengkap Anda" class="field">
                    @error('guest_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="label">Alamat / Instansi</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}"
                           maxlength="255" placeholder="Mis. Cilacap, atau nama kantor" class="field">
                    @error('address') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="guest_phone" class="label">No. WhatsApp</label>
                    <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}"
                           maxlength="20" autocomplete="tel" placeholder="08xxxxxxxxxx" class="field">
                    @error('guest_phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="guest_count" class="label">Jumlah Orang <span class="text-red-400">*</span></label>
                    <select id="guest_count" name="guest_count" required class="field">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" @selected((int) old('guest_count', 1) === $i)>{{ $i }} orang</option>
                        @endfor
                    </select>
                    @error('guest_count') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-gold w-full !py-4 !text-sm">Saya Hadir</button>
            </form>
        @endif
    </main>
@endsection
