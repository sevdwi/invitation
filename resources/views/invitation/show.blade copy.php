{{-- FILE: resources/views/invitation/show.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════
     § 1  HERO
═══════════════════════════════════════════ --}}
<section id="hero"
    class="relative min-h-screen flex flex-col items-center justify-center text-center overflow-hidden"
    style="background: linear-gradient(160deg,rgba(20,8,0,.62) 0%,rgba(10,4,0,.72) 100%),
           url('{{ $invitation->hero_image ? asset("storage/".$invitation->hero_image) : asset("images/hero.jpg") }}')
           center/cover no-repeat;">

    {{-- ornamen floating --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-4 w-px h-24 bg-gradient-to-b from-transparent via-gold/25 to-transparent"></div>
        <div class="absolute top-1/4 right-4 w-px h-24 bg-gradient-to-b from-transparent via-gold/25 to-transparent"></div>
    </div>

    <div class="relative z-10 px-8 py-24 w-full max-w-xs mx-auto">
        <p class="text-gold-lt/70 text-[9px] tracking-[5px] uppercase mb-7 animate__animated animate__fadeInDown">
            ✦ &nbsp;The Wedding of&nbsp; ✦
        </p>

        <h1 class="font-display text-white font-light leading-[1.05] animate__animated animate__fadeIn animate__delay-1s">
            <span class="block text-[3.8rem] tracking-wide">{{ $invitation->groom_name }}</span>
            <span class="block text-gold italic text-[2.6rem] my-1 font-light">&amp;</span>
            <span class="block text-[3.8rem] tracking-wide">{{ $invitation->bride_name }}</span>
        </h1>

        <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent to-gold/50"></div>
            <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
            </svg>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent to-gold/50"></div>
        </div>

        <p class="text-gold-lt/70 text-[9px] tracking-[3px] uppercase animate__animated animate__fadeInUp animate__delay-1s">
            {{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    {{-- scroll caret --}}
    <div class="absolute bottom-8 left-0 right-0 flex justify-center bounce">
        <svg class="w-5 h-5 text-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     AYAT QURAN
═══════════════════════════════════════════ --}}
<div class="bg-white px-8 py-12 text-center">
    <div class="max-w-xs mx-auto reveal">
        <p class="text-gold text-2xl mb-4 leading-none">❦</p>
        <p class="font-display text-[#3B2C1A] text-lg italic font-light leading-relaxed">
            "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri,
            agar kamu merasa tenteram kepadanya..."
        </p>
        <p class="text-gold-dk/80 text-[10px] tracking-[2px] mt-4">— QS. Ar-Rum: 21</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     § 2  MEMPELAI
═══════════════════════════════════════════ --}}
<section id="couple" class="bg-cream-2 px-6 py-16">
    <div class="max-w-xs mx-auto text-center">

        <div class="reveal mb-10">
            <p class="text-gold text-[9px] tracking-[5px] uppercase mb-2">Mempelai</p>
            <h2 class="font-display text-rose text-[2.2rem] font-light leading-tight">Dua Hati<br>Menjadi Satu</h2>
            <div class="w-10 h-px bg-gold mx-auto mt-4"></div>
        </div>

        {{-- Foto Pria --}}
        <div class="reveal mb-8">
            <div class="relative mx-auto w-44 h-44 mb-5">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-gold via-gold-lt to-gold p-[3px]">
                    <div class="w-full h-full rounded-full bg-cream-2 p-[3px]">
                        <img src="{{ $invitation->groom_photo
                                    ? asset('storage/'.$invitation->groom_photo)
                                    : 'https://placehold.co/176x176/f0e6d3/b8924a?text='.$invitation->groom_name }}"
                             class="w-full h-full rounded-full object-cover"
                             alt="{{ $invitation->groom_name }}">
                    </div>
                </div>
            </div>
            <h3 class="font-display text-[1.5rem] text-[#1a0a00] font-light">{{ $invitation->groom_full_name }}</h3>
            <p class="text-[#3B2C1A]/60 text-sm mt-2 leading-relaxed">
                Putra dari<br>
                <span class="text-gold-dk font-medium">Bpk. {{ $invitation->groom_father }}</span><br>
                <span class="text-gold-dk font-medium">Ibu {{ $invitation->groom_mother }}</span>
            </p>
        </div>

        {{-- separator --}}
        <div class="reveal flex items-center gap-4 my-3">
            <div class="flex-1 h-px bg-gold/25"></div>
            <span class="font-display text-gold text-5xl italic font-light leading-none">&amp;</span>
            <div class="flex-1 h-px bg-gold/25"></div>
        </div>

        {{-- Foto Wanita --}}
        <div class="reveal mt-8">
            <div class="relative mx-auto w-44 h-44 mb-5">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-gold via-gold-lt to-gold p-[3px]">
                    <div class="w-full h-full rounded-full bg-cream-2 p-[3px]">
                        <img src="{{ $invitation->bride_photo
                                    ? asset('storage/'.$invitation->bride_photo)
                                    : 'https://placehold.co/176x176/f0e6d3/b8924a?text='.$invitation->bride_name }}"
                             class="w-full h-full rounded-full object-cover"
                             alt="{{ $invitation->bride_name }}">
                    </div>
                </div>
            </div>
            <h3 class="font-display text-[1.5rem] text-[#1a0a00] font-light">{{ $invitation->bride_full_name }}</h3>
            <p class="text-[#3B2C1A]/60 text-sm mt-2 leading-relaxed">
                Putri dari<br>
                <span class="text-gold-dk font-medium">Bpk. {{ $invitation->bride_father }}</span><br>
                <span class="text-gold-dk font-medium">Ibu {{ $invitation->bride_mother }}</span>
            </p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 3  COUNTDOWN
═══════════════════════════════════════════ --}}
<section id="countdown" class="relative py-16 px-6 text-center overflow-hidden"
         style="background:radial-gradient(ellipse at 25% 0%, rgba(80,40,0,.9),transparent 60%),
                radial-gradient(ellipse at 75% 100%, rgba(30,10,0,.95),transparent 60%), #0f0800;">

    {{-- grid texture --}}
    <div class="absolute inset-0 opacity-[.04]"
         style="background-image: repeating-linear-gradient(0deg, #C9A96E 0, #C9A96E 1px, transparent 0, transparent 50%),
                                  repeating-linear-gradient(90deg, #C9A96E 0, #C9A96E 1px, transparent 0, transparent 50%);
                background-size: 30px 30px;"></div>

    <div class="relative max-w-xs mx-auto">
        <div class="reveal mb-8">
            <p class="text-gold/50 text-[9px] tracking-[5px] uppercase mb-2">Menghitung Mundur</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">Hari Bahagia Kami</h2>
            <p class="text-gold-lt/40 text-[10px] mt-1 tracking-wider">
                {{ $invitation->reception_date->isoFormat('D MMMM Y • HH:mm') }} WIB
            </p>
        </div>

        <div class="reveal grid grid-cols-4 gap-2" id="cdown" data-target="{{ $invitation->reception_date->toISOString() }}">
            @foreach(['cd-d'=>'Hari','cd-h'=>'Jam','cd-m'=>'Menit','cd-s'=>'Detik'] as $id=>$lbl)
            <div class="relative bg-white/[.05] border border-gold/20 py-4 px-1 rounded-sm overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
                <span class="font-display text-gold text-4xl font-light block leading-none" id="{{ $id }}">00</span>
                <span class="text-white/30 text-[8px] tracking-[1.5px] uppercase mt-2 block">{{ $lbl }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 4  DETAIL ACARA
═══════════════════════════════════════════ --}}
<section id="event" class="bg-cream px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-10">
            <p class="text-gold text-[9px] tracking-[5px] uppercase mb-2">Rangkaian Acara</p>
            <h2 class="font-display text-rose text-[2.2rem] font-light">Save The Date</h2>
            <div class="w-10 h-px bg-gold mx-auto mt-4"></div>
        </div>

        {{-- Card Akad --}}
        <div class="reveal bg-white border border-gold/25 p-7 text-center mb-5 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-gold/50 to-transparent"></div>
            <div class="text-3xl mb-3">🌙</div>
            <h3 class="font-display text-[1.6rem] text-[#1a0a00] font-light mb-1">Akad Nikah</h3>
            <div class="w-8 h-px bg-gold mx-auto mb-5"></div>
            <div class="space-y-3 text-sm text-[#3B2C1A]/75">
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-gold text-base leading-none">📅</span>
                    <span>{{ $invitation->akad_date->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-gold text-base leading-none">⏰</span>
                    <span>{{ $invitation->akad_date->format('H:i') }} WIB – Selesai</span>
                </div>
                <div class="flex items-start gap-3 justify-center">
                    <span class="text-gold text-base leading-none mt-px">📍</span>
                    <div class="text-left">
                        <p class="font-medium text-[#1a0a00]">{{ $invitation->akad_location }}</p>
                        <p class="text-xs text-[#3B2C1A]/50 mt-0.5">{{ $invitation->akad_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Resepsi --}}
        <div class="reveal bg-white border border-gold/25 p-7 text-center shadow-sm relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-gold/50 to-transparent"></div>
            <div class="text-3xl mb-3">🤍</div>
            <h3 class="font-display text-[1.6rem] text-[#1a0a00] font-light mb-1">Resepsi</h3>
            <div class="w-8 h-px bg-gold mx-auto mb-5"></div>
            <div class="space-y-3 text-sm text-[#3B2C1A]/75">
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-gold text-base leading-none">📅</span>
                    <span>{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-gold text-base leading-none">⏰</span>
                    <span>{{ $invitation->reception_date->format('H:i') }} WIB – Selesai</span>
                </div>
                <div class="flex items-start gap-3 justify-center">
                    <span class="text-gold text-base leading-none mt-px">📍</span>
                    <div class="text-left">
                        <p class="font-medium text-[#1a0a00]">{{ $invitation->reception_location }}</p>
                        <p class="text-xs text-[#3B2C1A]/50 mt-0.5">{{ $invitation->reception_address }}</p>
                    </div>
                </div>
            </div>
            <a href="https://www.google.com/maps?q={{ $invitation->maps_lat }},{{ $invitation->maps_lng }}"
               target="_blank"
               class="ripple inline-flex items-center gap-2 mt-6 border border-gold/60 text-gold px-6 py-2.5 text-[10px] tracking-[3px] uppercase hover:bg-gold hover:text-rose transition-all duration-300">
                🗺 Buka Maps
            </a>
        </div>

        {{-- Google Map embed --}}
        @if($invitation->maps_lat && $invitation->maps_lng)
        <div class="reveal mt-5">
            @if(config('services.google_maps.key'))
                <div id="gmap" class="w-full h-56 border border-gold/25 bg-cream-2"></div>
            @else
                <a href="https://www.google.com/maps?q={{ $invitation->maps_lat }},{{ $invitation->maps_lng }}"
                   target="_blank"
                   class="w-full h-48 border border-gold/25 bg-cream-2 flex flex-col items-center justify-center gap-2 text-center active:bg-gold/10 transition-colors">
                    <span class="text-4xl">🗺️</span>
                    <span class="text-gold text-sm font-medium">Tap untuk lihat lokasi</span>
                    <span class="text-[#3B2C1A]/40 text-xs">{{ $invitation->reception_location }}</span>
                </a>
            @endif
        </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 5  GALERI
═══════════════════════════════════════════ --}}
@if($invitation->galleries->where('image_path','!=',null)->isNotEmpty())
<section id="gallery" class="bg-cream-2 px-4 py-16">
    <div class="max-w-xs mx-auto">
        <div class="reveal text-center mb-8">
            <p class="text-gold text-[9px] tracking-[5px] uppercase mb-2">Galeri Foto</p>
            <h2 class="font-display text-rose text-[2.2rem] font-light">Momen Berharga</h2>
            <div class="w-10 h-px bg-gold mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-2 gap-2">
            @foreach($invitation->galleries->where('image_path','!=',null) as $photo)
            <div class="g-item relative aspect-square overflow-hidden cursor-pointer group reveal"
                 data-src="{{ asset('storage/'.$photo->image_path) }}"
                 data-cap="{{ $photo->caption }}">
                <img src="{{ asset('storage/'.$photo->image_path) }}"
                     loading="lazy"
                     class="w-full h-full object-cover"
                     alt="{{ $photo->caption }}">
                <div class="absolute inset-0 bg-gold/0 group-active:bg-gold/50 transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-active:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803zM10.5 7.5v6m3-3h-6"/>
                    </svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════
     § 6  RSVP
═══════════════════════════════════════════ --}}
<section id="rsvp" class="bg-cream px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-8">
            <p class="text-gold text-[9px] tracking-[5px] uppercase mb-2">Konfirmasi Kehadiran</p>
            <h2 class="font-display text-rose text-[2.2rem] font-light">RSVP</h2>
            <div class="w-10 h-px bg-gold mx-auto mt-4 mb-4"></div>
            <p class="text-[#3B2C1A]/55 text-sm leading-relaxed">
                Mohon konfirmasi kehadiran Anda<br>agar kami dapat mempersiapkan segalanya.
            </p>
        </div>

        {{-- Alert --}}
        <div id="rsvp-alert" class="hidden mb-5 px-4 py-3 text-sm border-l-4 reveal"></div>

        <form id="rsvp-form" class="space-y-4 reveal" novalidate>
            @csrf

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-[#3B2C1A]/50 mb-2 font-medium">
                    Nama Tamu <span class="text-gold">*</span>
                </label>
                <input type="text" name="guest_name" class="w-field"
                       placeholder="Nama lengkap Anda" required autocomplete="name">
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-[#3B2C1A]/50 mb-2 font-medium">
                    No. WhatsApp
                </label>
                <input type="tel" name="guest_phone" class="w-field"
                       placeholder="08xxxxxxxxxx" autocomplete="tel">
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-[#3B2C1A]/50 mb-2 font-medium">
                    Kehadiran <span class="text-gold">*</span>
                </label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['hadir'=>['✅','Hadir'],'tidak_hadir'=>['❌','Tidak'],'belum_pasti'=>['🤔','Belum\nPasti']] as $val=>[$ico,$lbl])
                    <label class="rsvp-opt relative cursor-pointer">
                        <input type="radio" name="attendance" value="{{ $val }}" class="sr-only" required>
                        <div class="attendance-box border border-gold/30 bg-white py-3 px-2 text-center text-xs transition-all duration-200 hover:border-gold/60">
                            <span class="block text-lg mb-1">{{ $ico }}</span>
                            <span class="text-[#3B2C1A]/70 text-[10px] font-medium leading-tight">{{ $lbl }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-[#3B2C1A]/50 mb-2 font-medium">
                    Jumlah Tamu <span class="text-gold">*</span>
                </label>
                <div class="flex items-center border border-gold/30 bg-white">
                    <button type="button" id="dec"
                        class="w-12 h-12 flex items-center justify-center text-gold text-xl hover:bg-gold/10 transition-colors flex-shrink-0">−</button>
                    <input type="number" name="guest_count" id="guest-count"
                           class="flex-1 text-center text-sm text-[#2c1a08] bg-transparent border-0 py-3 min-w-0"
                           value="1" min="1" max="10" readonly>
                    <button type="button" id="inc"
                        class="w-12 h-12 flex items-center justify-center text-gold text-xl hover:bg-gold/10 transition-colors flex-shrink-0">+</button>
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-[#3B2C1A]/50 mb-2 font-medium">
                    Catatan
                </label>
                <textarea name="note" rows="3" class="w-field resize-none"
                          placeholder="Pesan atau catatan tambahan (opsional)"></textarea>
            </div>

            <button type="submit" id="rsvp-btn"
                class="ripple w-full bg-gold text-rose py-4 text-[10px] tracking-[4px] uppercase font-semibold hover:bg-gold-dk transition-colors duration-300 disabled:opacity-60 mt-2">
                Kirim Konfirmasi
            </button>
        </form>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 7  UCAPAN & DOA
═══════════════════════════════════════════ --}}
<section id="wishes" class="bg-cream-2 px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-8">
            <p class="text-gold text-[9px] tracking-[5px] uppercase mb-2">Ucapan & Doa</p>
            <h2 class="font-display text-rose text-[2.2rem] font-light">Kirim Doa Terbaik</h2>
            <div class="w-10 h-px bg-gold mx-auto mt-4"></div>
        </div>

        {{-- Form Kirim Ucapan --}}
        <div id="wish-alert" class="hidden mb-4 px-4 py-3 text-sm border-l-4"></div>
        <form id="wish-form" class="space-y-3 reveal mb-8" novalidate>
            @csrf
            <input type="text" name="sender_name" class="w-field"
                   placeholder="Nama Anda" required autocomplete="name">
            <textarea name="message" rows="4" class="w-field resize-none"
                      placeholder="Tulis ucapan dan doa terbaik di sini... 🙏" required></textarea>
            <button type="submit" id="wish-btn"
                class="ripple w-full border border-gold text-gold py-3.5 text-[10px] tracking-[3px] uppercase hover:bg-gold hover:text-rose transition-all duration-300 disabled:opacity-60">
                💌 Kirim Ucapan
            </button>
        </form>

        {{-- Daftar Ucapan --}}
        <div id="wish-list" class="space-y-3">
            @forelse($wishes as $wish)
            <div class="bg-white border border-gold/20 p-4 reveal">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold to-gold-dk flex items-center justify-center text-white font-display text-lg flex-shrink-0 shadow-sm">
                        {{ mb_strtoupper(mb_substr($wish->sender_name,0,1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[#1a0a00] text-sm font-semibold">{{ $wish->sender_name }}</p>
                        <p class="text-[#3B2C1A]/65 text-sm mt-1 leading-relaxed">{{ $wish->message }}</p>
                        <p class="text-[#3B2C1A]/35 text-xs mt-2">{{ $wish->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div id="no-wish" class="text-center py-8">
                <p class="text-3xl mb-2">💌</p>
                <p class="text-[#3B2C1A]/45 text-sm">Jadilah yang pertama mengirim ucapan!</p>
            </div>
            @endforelse
        </div>

        {{-- Load More --}}
        @if($wishes->count() >= 10)
        <div id="more-wrap" class="text-center mt-5">
            <button id="more-btn" data-page="2"
                class="border border-gold/50 text-gold text-[10px] tracking-[3px] uppercase px-7 py-2.5 hover:bg-gold hover:text-rose transition-all duration-300">
                Lihat lebih banyak
            </button>
        </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ --}}
<footer class="px-8 py-14 text-center" style="background:#0f0800;">
    <p class="text-gold/30 tracking-[8px] text-xs mb-5">✦ ✦ ✦</p>
    <h3 class="font-display text-white text-[2rem] font-light">
        {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}
    </h3>
    <p class="text-gold-lt/35 text-[10px] tracking-[3px] uppercase mt-2">
        {{ $invitation->reception_date->isoFormat('D MMMM Y') }}
    </p>
    <div class="w-10 h-px bg-gold/20 mx-auto my-5"></div>
    <p class="text-white/15 text-xs">Made with ❤️ · Laravel + Tailwind CSS</p>
</footer>

@push('scripts')
<style>
    /* Radio RSVP selected state */
    input[type="radio"]:checked + .attendance-box {
        border-color: #C9A96E;
        background: rgba(201,169,110,.08);
        color: #9A7340;
    }
</style>
@endpush

@endsection