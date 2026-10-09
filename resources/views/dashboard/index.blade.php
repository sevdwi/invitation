{{-- FILE: resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Undangan Saya')

@section('content')
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif text-3xl text-white">Undangan Saya</h1>
            <p class="text-sm mt-1">Kelola undangan, tamu, dan ucapan dari sini.</p>
        </div>
        <a href="{{ route('dashboard.invitations.create') }}" class="btn-gold shrink-0">+ Buat Undangan</a>
    </div>

    @forelse ($invitations as $invitation)
        <div class="card p-6 mb-4 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="font-serif text-2xl text-white">{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</h2>
                    @if ($invitation->is_active)
                        <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-green-900/40 text-green-300">Aktif</span>
                    @else
                        <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/10 text-white/60">Nonaktif</span>
                    @endif
                </div>
                <p class="text-sm mt-1">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</p>
                <p class="text-xs mt-1 text-brand-gold truncate">{{ route('invitation.show', $invitation) }}</p>
                <p class="text-xs mt-2">{{ $invitation->rsvps_count }} RSVP · {{ $invitation->wishes_count }} ucapan</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('dashboard.invitations.show', $invitation) }}" class="btn-gold">Kelola</a>
                @if ($invitation->is_active)
                    <a href="{{ route('invitation.show', $invitation) }}" target="_blank" class="btn-ghost">Lihat</a>
                @endif
            </div>
        </div>
    @empty
        <div class="card p-10 text-center">
            <p class="font-serif text-2xl text-white mb-2">Belum ada undangan</p>
            <p class="text-sm mb-6">Buat undangan pertama Anda dalam beberapa menit.</p>
            <a href="{{ route('dashboard.invitations.create') }}" class="btn-gold">Buat Undangan</a>
        </div>
    @endforelse
@endsection
