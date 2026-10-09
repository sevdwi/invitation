{{-- FILE: resources/views/themes/minimalist/show.blade.php --}}
{{-- Tema Minimalist (bersih, putih, elegan) --}}
@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════
     § 1  HERO
═══════════════════════════════════════════ --}}
<section id="hero" class="relative min-h-screen flex flex-col items-center justify-center px-6 bg-white">
    <div class="w-full max-w-xl mx-auto text-center">
        <p class="text-gray-400 text-xs tracking-[3px] uppercase mb-8">✦ Undangan Pernikahan ✦</p>

        <h1 class="font-serif text-6xl text-gray-900 mb-2 font-light leading-tight">
            {{ $invitation->groom_name }}
        </h1>
        <p class="text-2xl text-gray-400 italic mb-6">&</p>
        <h1 class="font-serif text-6xl text-gray-900 mb-8 font-light leading-tight">
            {{ $invitation->bride_name }}
        </h1>

        <div class="flex items-center gap-4 my-8 justify-center">
            <div class="w-12 h-px bg-gray-300"></div>
            <span class="text-gray-400 text-sm">{{  $invitation->reception_date->isoFormat('D MMMM Y') }}</span>
            <div class="w-12 h-px bg-gray-300"></div>
        </div>

        <p class="text-gray-600 text-base leading-relaxed max-w-sm">
            Kami mengundang Anda hadir di momen istimewa kami berbagi kebahagiaan dan berkah.
        </p>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-0 right-0 flex justify-center" style="animation: bounce-y 2s ease-in-out infinite">
        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     AYAT QURAN
═══════════════════════════════════════════ --}}
<div class="bg-gray-50 px-6 py-16 text-center border-b border-gray-200">
    <div class="max-w-xl mx-auto">
        <p class="font-serif text-gray-700 text-lg italic leading-relaxed mb-4">
            "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya..."
        </p>
        <p class="text-gray-400 text-xs tracking-widest">— QS. Ar-Rum: 21</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     § 2  MEMPELAI
═══════════════════════════════════════════ --}}
<section class="py-20 px-6 bg-white">
    <div class="max-w-2xl mx-auto">
        <h2 class="font-serif text-4xl text-gray-900 text-center mb-16 font-light">Mempelai</h2>

        <div class="grid md:grid-cols-2 gap-12 mb-12">
            {{-- Groom --}}
            <div class="text-center">
                <div class="w-40 h-40 rounded-full overflow-hidden mx-auto mb-6 border border-gray-300 shadow-sm">
                    <img src="{{ $invitation->groom_photo
                                ? asset('storage/'.$invitation->groom_photo)
                                : 'https://placehold.co/160x160/f3f4f6/d1d5db?text='.$invitation->groom_name }}"
                         class="w-full h-full object-cover" alt="{{ $invitation->groom_name }}">
                </div>
                <h3 class="font-serif text-2xl text-gray-900 mb-2">{{ $invitation->groom_full_name }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Putra dari<br>
                    <span class="font-medium">Bpk. {{ $invitation->groom_father }}</span><br>
                    <span class="font-medium">Ibu {{ $invitation->groom_mother }}</span>
                </p>
            </div>

            {{-- Bride --}}
            <div class="text-center">
                <div class="w-40 h-40 rounded-full overflow-hidden mx-auto mb-6 border border-gray-300 shadow-sm">
                    <img src="{{ $invitation->bride_photo
                                ? asset('storage/'.$invitation->bride_photo)
                                : 'https://placehold.co/160x160/f3f4f6/d1d5db?text='.$invitation->bride_name }}"
                         class="w-full h-full object-cover" alt="{{ $invitation->bride_name }}">
                </div>
                <h3 class="font-serif text-2xl text-gray-900 mb-2">{{ $invitation->bride_full_name }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Putri dari<br>
                    <span class="font-medium">Bpk. {{ $invitation->bride_father }}</span><br>
                    <span class="font-medium">Ibu {{ $invitation->bride_mother }}</span>
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 3  ACARA
═══════════════════════════════════════════ --}}
<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-2xl mx-auto">
        <h2 class="font-serif text-4xl text-gray-900 text-center mb-16 font-light">Rangkaian Acara</h2>

        <div class="space-y-8 mb-8">
            {{-- Akad --}}
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <p class="text-2xl mb-4">🌙</p>
                <h3 class="font-serif text-2xl text-gray-900 mb-4">Akad Nikah</h3>
                <div class="space-y-3 text-gray-600 text-sm">
                    <p><span class="font-medium text-gray-900">{{ $invitation->akad_date->isoFormat('dddd, D MMMM Y') }}</span></p>
                    <p><span class="font-medium text-gray-900">{{ $invitation->akad_date->format('H:i') }} WIB</span></p>
                    <p><span class="font-medium text-gray-900">{{ $invitation->akad_location }}</span><br>{{ $invitation->akad_address }}</p>
                </div>
            </div>

            {{-- Resepsi --}}
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <p class="text-2xl mb-4">🤍</p>
                <h3 class="font-serif text-2xl text-gray-900 mb-4">Resepsi</h3>
                <div class="space-y-3 text-gray-600 text-sm mb-6">
                    <p><span class="font-medium text-gray-900">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</span></p>
                    <p><span class="font-medium text-gray-900">{{ $invitation->reception_date->format('H:i') }} WIB</span></p>
                    <p><span class="font-medium text-gray-900">{{ $invitation->reception_location }}</span><br>{{ $invitation->reception_address }}</p>
                </div>
                @if ($invitation->maps_lat && $invitation->maps_lng)
                    <a href="https://www.google.com/maps?q={{ $invitation->maps_lat }},{{ $invitation->maps_lng }}"
                       target="_blank" class="inline-block text-sm border border-gray-300 px-4 py-2 rounded hover:border-gray-400 hover:bg-gray-50 transition">
                        🗺 Buka Maps
                    </a>
                @endif
            </div>
        </div>

        {{-- Countdown --}}
        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-gray-600 text-sm mb-6">Menghitung mundur hari istimewa</p>
            <div class="grid grid-cols-4 gap-3" id="cdown" data-target="{{ $invitation->reception_date->toISOString() }}">
                @foreach(['cd-d'=>'Hari','cd-h'=>'Jam','cd-m'=>'Menit','cd-s'=>'Detik'] as $id=>$lbl)
                <div class="text-center">
                    <div class="text-3xl font-serif text-gray-900 mb-1" id="{{ $id }}">00</div>
                    <div class="text-xs text-gray-500">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 4  ALBUM FOTO
═══════════════════════════════════════════ --}}
@if ($invitation->drive_folder_id)
<section class="py-20 px-6 bg-white text-center">
    <div class="max-w-xl mx-auto">
        <h2 class="font-serif text-4xl text-gray-900 mb-4 font-light">Album Foto</h2>
        <p class="text-gray-600 text-sm leading-relaxed mb-8">Lihat foto-foto dari hari pernikahan kami.</p>
        <a href="{{ route('album.show', $invitation) }}"
           class="inline-block text-sm border border-gray-900 text-gray-900 px-6 py-3 rounded hover:bg-gray-900 hover:text-white transition">
            Buka Album
        </a>
    </div>
</section>
@endif

{{-- Footer --}}
<footer class="py-12 text-center bg-white border-t border-gray-200">
    <h3 class="font-serif text-3xl text-gray-900 mb-1 font-light">{{ $invitation->groom_name }} & {{ $invitation->bride_name }}</h3>
    <p class="text-gray-400 text-xs tracking-widest">{{ $invitation->reception_date->isoFormat('D MMMM Y') }}</p>
    <p class="text-gray-400 text-xs mt-4">Made with ❤️ using Indocreator</p>
</footer>

@endsection
