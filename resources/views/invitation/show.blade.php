{{-- FILE: resources/views/invitation/show.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════
     § 1  HERO
═══════════════════════════════════════════ --}}
<section id="hero"
    class="relative min-h-screen flex flex-col items-center justify-center text-center overflow-hidden"
    style="background: linear-gradient(180deg, rgba(0,0,0,.85) 0%, rgba(20,0,0,.75) 50%, rgba(20,20,20,1) 100%),
           url('{{ $invitation->hero_image ? asset("storage/".$invitation->hero_image) : asset("images/hero.jpg") }}')
           center/cover no-repeat;">

    {{-- Noise texture overlay --}}
    <div class="absolute inset-0 opacity-[.03]"
         style="background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E');background-size:200px"></div>

    {{-- Garis merah kiri --}}
    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-transparent via-nred to-transparent opacity-60"></div>

    <div class="relative z-10 px-8 py-24 w-full max-w-xs mx-auto">

        {{-- Netflix badge --}}
        <div class="inline-flex items-center gap-2 bg-nred/20 border border-nred/30 px-3 py-1 rounded mb-6 animate__animated animate__fadeInDown">
            <div class="w-2 h-2 bg-nred rounded-full animate-pulse"></div>
            <p class="text-nred text-[9px] tracking-[4px] uppercase font-semibold">The Wedding of</p>
        </div>

        <h1 class="font-display text-white font-light leading-[1.05] animate__animated animate__fadeIn">
            <span class="block text-[3.8rem] tracking-wide">{{ $invitation->groom_name }}</span>
            <span class="block text-nred italic text-[2.6rem] my-1 font-light">&amp;</span>
            <span class="block text-[3.8rem] tracking-wide">{{ $invitation->bride_name }}</span>
        </h1>

        <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent to-nred/50"></div>
            <svg class="w-4 h-4 text-nred" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
            </svg>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent to-nred/50"></div>
        </div>

        <p class="text-ngray text-[9px] tracking-[3px] uppercase">
            {{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    {{-- Scroll caret --}}
    <div class="absolute bottom-8 left-0 right-0 flex justify-center" style="animation: bounce-y 2s ease-in-out infinite">
        <svg class="w-5 h-5 text-nred/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     AYAT QURAN
═══════════════════════════════════════════ --}}
<div class="bg-ncard px-8 py-12 text-center border-t border-b border-white/5">
    <div class="max-w-xs mx-auto reveal">
        <div class="n-line w-12 mx-auto mb-5"></div>
        <p class="font-display text-nlight text-lg italic font-light leading-relaxed">
            "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri,
            agar kamu merasa tenteram kepadanya..."
        </p>
        <p class="text-nred/80 text-[10px] tracking-[2px] mt-4 font-semibold">— QS. Ar-Rum: 21</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     § 2  MEMPELAI
═══════════════════════════════════════════ --}}
<section id="couple" class="bg-ndark px-6 py-16">
    <div class="max-w-xs mx-auto text-center">

        <div class="reveal mb-10">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Mempelai</p>
            <h2 class="font-display text-white text-[2.2rem] font-light leading-tight">Dua Hati<br>Menjadi Satu</h2>
            <div class="n-line w-12 mx-auto mt-4"></div>
        </div>

        {{-- Foto Pria --}}
        <div class="reveal mb-8">
            <div class="relative mx-auto w-44 h-44 mb-5">
                {{-- Ring merah --}}
                <div class="absolute inset-0 rounded-full p-[3px]"
                     style="background: conic-gradient(#E50914 0deg, #B20710 180deg, #E50914 360deg);">
                    <div class="w-full h-full rounded-full bg-ndark p-[3px]">
                        <img src="{{ $invitation->groom_photo
                                    ? asset('storage/'.$invitation->groom_photo)
                                    : 'https://placehold.co/176x176/181818/E50914?text='.$invitation->groom_name }}"
                             class="w-full h-full rounded-full object-cover"
                             alt="{{ $invitation->groom_name }}">
                    </div>
                </div>
            </div>
            <h3 class="font-display text-[1.5rem] text-white font-light">{{ $invitation->groom_full_name }}</h3>
            <p class="text-ngray text-sm mt-2 leading-relaxed">
                Putra dari<br>
                <span class="text-nlight font-medium">Bpk. {{ $invitation->groom_father }}</span><br>
                <span class="text-nlight font-medium">Ibu {{ $invitation->groom_mother }}</span>
            </p>
        </div>

        {{-- Separator --}}
        <div class="reveal flex items-center gap-4 my-3">
            <div class="flex-1 h-px bg-nred/20"></div>
            <span class="font-display text-nred text-5xl italic font-light leading-none">&amp;</span>
            <div class="flex-1 h-px bg-nred/20"></div>
        </div>

        {{-- Foto Wanita --}}
        <div class="reveal mt-8">
            <div class="relative mx-auto w-44 h-44 mb-5">
                <div class="absolute inset-0 rounded-full p-[3px]"
                     style="background: conic-gradient(#E50914 0deg, #B20710 180deg, #E50914 360deg);">
                    <div class="w-full h-full rounded-full bg-ndark p-[3px]">
                        <img src="{{ $invitation->bride_photo
                                    ? asset('storage/'.$invitation->bride_photo)
                                    : 'https://placehold.co/176x176/181818/E50914?text='.$invitation->bride_name }}"
                             class="w-full h-full rounded-full object-cover"
                             alt="{{ $invitation->bride_name }}">
                    </div>
                </div>
            </div>
            <h3 class="font-display text-[1.5rem] text-white font-light">{{ $invitation->bride_full_name }}</h3>
            <p class="text-ngray text-sm mt-2 leading-relaxed">
                Putri dari<br>
                <span class="text-nlight font-medium">Bpk. {{ $invitation->bride_father }}</span><br>
                <span class="text-nlight font-medium">Ibu {{ $invitation->bride_mother }}</span>
            </p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 3  COUNTDOWN
═══════════════════════════════════════════ --}}
<section id="countdown" class="relative py-16 px-6 text-center overflow-hidden bg-ncard">

    {{-- Background grid --}}
    <div class="absolute inset-0 opacity-[.035]"
         style="background-image: repeating-linear-gradient(0deg,#E50914 0,#E50914 1px,transparent 0,transparent 40px),
                                  repeating-linear-gradient(90deg,#E50914 0,#E50914 1px,transparent 0,transparent 40px);
                background-size:40px 40px;"></div>

    {{-- Gradient vignette --}}
    <div class="absolute inset-0 bg-gradient-to-b from-ncard via-transparent to-ncard"></div>

    <div class="relative max-w-xs mx-auto">
        <div class="reveal mb-8">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Menghitung Mundur</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">Hari Bahagia Kami</h2>
            <p class="text-ngray text-[10px] mt-1 tracking-wider">
                {{ $invitation->reception_date->isoFormat('D MMMM Y • HH:mm') }} WIB
            </p>
        </div>

        <div class="reveal grid grid-cols-4 gap-2"
             id="cdown" data-target="{{ $invitation->reception_date->toISOString() }}">
            @foreach(['cd-d'=>'Hari','cd-h'=>'Jam','cd-m'=>'Menit','cd-s'=>'Detik'] as $id=>$lbl)
            <div class="bg-ndark border border-nred/20 py-4 px-1 rounded relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-nred/60 to-transparent"></div>
                <span class="font-display text-nred text-4xl font-light block leading-none" id="{{ $id }}">00</span>
                <span class="text-ngray text-[8px] tracking-[1.5px] uppercase mt-2 block">{{ $lbl }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 4  DETAIL ACARA
═══════════════════════════════════════════ --}}
<section id="event" class="bg-ndark px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-10">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Rangkaian Acara</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">Save The Date</h2>
            <div class="n-line w-12 mx-auto mt-4"></div>
        </div>

        {{-- Card Akad --}}
        <div class="reveal bg-ncard border border-white/10 p-7 text-center mb-4 rounded relative overflow-hidden hover:border-nred/40 transition-colors duration-300">
            <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-nred to-transparent"></div>
            <div class="text-3xl mb-3">🌙</div>
            <h3 class="font-display text-[1.6rem] text-white font-light mb-1">Akad Nikah</h3>
            <div class="n-line w-8 mx-auto mb-5"></div>
            <div class="space-y-3 text-sm text-ngray">
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-nred">📅</span>
                    <span class="text-nlight">{{ $invitation->akad_date->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-nred">⏰</span>
                    <span class="text-nlight">{{ $invitation->akad_date->format('H:i') }} WIB – Selesai</span>
                </div>
                <div class="flex items-start gap-3 justify-center">
                    <span class="text-nred mt-px">📍</span>
                    <div class="text-left">
                        <p class="font-medium text-white">{{ $invitation->akad_location }}</p>
                        <p class="text-xs text-ngray mt-0.5">{{ $invitation->akad_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Resepsi --}}
        <div class="reveal bg-ncard border border-white/10 p-7 text-center rounded relative overflow-hidden hover:border-nred/40 transition-colors duration-300">
            <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-nred to-transparent"></div>
            <div class="text-3xl mb-3">🤍</div>
            <h3 class="font-display text-[1.6rem] text-white font-light mb-1">Resepsi</h3>
            <div class="n-line w-8 mx-auto mb-5"></div>
            <div class="space-y-3 text-sm text-ngray">
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-nred">📅</span>
                    <span class="text-nlight">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="text-nred">⏰</span>
                    <span class="text-nlight">{{ $invitation->reception_date->format('H:i') }} WIB – Selesai</span>
                </div>
                <div class="flex items-start gap-3 justify-center">
                    <span class="text-nred mt-px">📍</span>
                    <div class="text-left">
                        <p class="font-medium text-white">{{ $invitation->reception_location }}</p>
                        <p class="text-xs text-ngray mt-0.5">{{ $invitation->reception_address }}</p>
                    </div>
                </div>
            </div>
            <a href="https://www.google.com/maps?q={{ $invitation->maps_lat }},{{ $invitation->maps_lng }}"
               target="_blank"
               class="inline-flex items-center gap-2 mt-6 bg-nred/10 border border-nred/40 text-nred px-5 py-2.5 text-[10px] tracking-[2px] uppercase rounded hover:bg-nred hover:text-white transition-all duration-300 font-semibold">
                🗺 Buka Maps
            </a>
        </div>

        {{-- Google Map --}}
        @if($invitation->maps_lat && $invitation->maps_lng)
        <div class="reveal mt-4 rounded overflow-hidden">
            @if(config('services.google_maps.key'))
                <div id="gmap" class="w-full h-52 bg-ncard2"></div>
            @else
                <a href="https://www.google.com/maps?q={{ $invitation->maps_lat }},{{ $invitation->maps_lng }}"
                   target="_blank"
                   class="w-full h-44 bg-ncard2 border border-white/10 rounded flex flex-col items-center justify-center gap-2 hover:border-nred/40 transition-colors">
                    <span class="text-4xl">🗺️</span>
                    <span class="text-nred text-sm font-semibold">Tap untuk lihat lokasi</span>
                    <span class="text-ngray text-xs">{{ $invitation->reception_location }}</span>
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
<section id="gallery" class="bg-ncard px-4 py-16">
    <div class="max-w-xs mx-auto">
        <div class="reveal text-center mb-8">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Galeri Foto</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">Momen Berharga</h2>
            <div class="n-line w-12 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-2 gap-2">
            @foreach($invitation->galleries->where('image_path','!=',null) as $photo)
            <div class="g-item relative aspect-square overflow-hidden cursor-pointer group reveal rounded"
                 data-src="{{ asset('storage/'.$photo->image_path) }}"
                 data-cap="{{ $photo->caption }}">
                <img src="{{ asset('storage/'.$photo->image_path) }}"
                     loading="lazy"
                     class="w-full h-full object-cover"
                     alt="{{ $photo->caption }}">
                {{-- Netflix-style overlay: dark bottom gradient + play icon --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-end pb-3">
                    <span class="text-white text-xs font-medium tracking-wide truncate px-2 text-center">{{ $photo->caption }}</span>
                </div>
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/40">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                        </svg>
                    </div>
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
<section id="rsvp" class="bg-ndark px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-8">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Konfirmasi Kehadiran</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">RSVP</h2>
            <div class="n-line w-12 mx-auto mt-4 mb-4"></div>
            <p class="text-ngray text-sm leading-relaxed">
                Mohon konfirmasi kehadiran Anda<br>agar kami dapat mempersiapkan segalanya.
            </p>
        </div>

        <div id="rsvp-alert" class="hidden mb-5 px-4 py-3 text-sm border-l-4 rounded"></div>

        <form id="rsvp-form" class="space-y-4 reveal" novalidate>
            @csrf

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-ngray mb-2 font-semibold">
                    Nama Tamu <span class="text-nred">*</span>
                </label>
                <input type="text" name="guest_name" class="n-field"
                       placeholder="Nama lengkap Anda" required autocomplete="name">
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-ngray mb-2 font-semibold">
                    No. WhatsApp
                </label>
                <input type="tel" name="guest_phone" class="n-field"
                       placeholder="08xxxxxxxxxx" autocomplete="tel">
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-ngray mb-2 font-semibold">
                    Kehadiran <span class="text-nred">*</span>
                </label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['hadir'=>['✅','Hadir'],'tidak_hadir'=>['❌','Tidak'],'belum_pasti'=>['🤔','Belum Pasti']] as $val=>[$ico,$lbl])
                    <label class="cursor-pointer">
                        <input type="radio" name="attendance" value="{{ $val }}" class="sr-only" required>
                        <div class="attendance-box bg-ncard2 border border-white/10 py-3 px-2 text-center rounded transition-all duration-200 hover:border-nred/50">
                            <span class="block text-xl mb-1">{{ $ico }}</span>
                            <span class="text-ngray text-[10px] font-medium leading-tight block">{{ $lbl }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-ngray mb-2 font-semibold">
                    Jumlah Tamu <span class="text-nred">*</span>
                </label>
                <div class="flex items-center bg-ncard2 border border-white/10 rounded overflow-hidden">
                    <button type="button" id="dec"
                        class="w-12 h-12 flex items-center justify-center text-nred text-xl hover:bg-nred/10 transition-colors flex-shrink-0">−</button>
                    <input type="number" name="guest_count" id="guest-count"
                           class="flex-1 text-center text-sm text-white bg-transparent border-0 py-3 min-w-0 outline-none"
                           value="1" min="1" max="10" readonly>
                    <button type="button" id="inc"
                        class="w-12 h-12 flex items-center justify-center text-nred text-xl hover:bg-nred/10 transition-colors flex-shrink-0">+</button>
                </div>
            </div>

            <div>
                <label class="block text-[9px] tracking-[2px] uppercase text-ngray mb-2 font-semibold">Catatan</label>
                <textarea name="note" rows="3" class="n-field resize-none"
                          placeholder="Pesan atau catatan tambahan (opsional)"></textarea>
            </div>

            <button type="submit" id="rsvp-btn"
                class="w-full bg-nred text-white py-4 text-[10px] tracking-[4px] uppercase font-bold rounded hover:bg-nred-dk active:scale-95 transition-all duration-200 disabled:opacity-50 shadow-lg shadow-nred/20 mt-2">
                Kirim Konfirmasi
            </button>
        </form>

    </div>
</section>

{{-- ═══════════════════════════════════════════
     § 7  UCAPAN & DOA
═══════════════════════════════════════════ --}}
<section id="wishes" class="bg-ncard px-6 py-16">
    <div class="max-w-xs mx-auto">

        <div class="reveal text-center mb-8">
            <p class="text-nred text-[9px] tracking-[5px] uppercase mb-2 font-semibold">Ucapan & Doa</p>
            <h2 class="font-display text-white text-[2.2rem] font-light">Kirim Doa Terbaik</h2>
            <div class="n-line w-12 mx-auto mt-4"></div>
        </div>

        <div id="wish-alert" class="hidden mb-4 px-4 py-3 text-sm border-l-4 rounded"></div>

        <form id="wish-form" class="space-y-3 reveal mb-8" novalidate>
            @csrf
            <input type="text" name="sender_name" class="n-field"
                   placeholder="Nama Anda" required autocomplete="name">
            <textarea name="message" rows="4" class="n-field resize-none"
                      placeholder="Tulis ucapan dan doa terbaik di sini... 🙏" required></textarea>
            <button type="submit" id="wish-btn"
                class="w-full bg-ncard2 border border-nred/40 text-nred py-3.5 text-[10px] tracking-[3px] uppercase rounded font-semibold hover:bg-nred hover:text-white transition-all duration-300 disabled:opacity-50">
                💌 Kirim Ucapan
            </button>
        </form>

        {{-- Daftar Ucapan --}}
        <div id="wish-list" class="space-y-3">
            @forelse($wishes as $wish)
            <div class="bg-ndark border border-white/10 p-4 rounded reveal hover:border-nred/30 transition-colors duration-300">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-nred flex items-center justify-center text-white font-display text-lg flex-shrink-0 shadow-sm shadow-nred/30">
                        {{ mb_strtoupper(mb_substr($wish->sender_name,0,1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-semibold">{{ $wish->sender_name }}</p>
                        <p class="text-ngray text-sm mt-1 leading-relaxed">{{ $wish->message }}</p>
                        <p class="text-ngray/50 text-xs mt-2">{{ $wish->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div id="no-wish" class="text-center py-8">
                <p class="text-3xl mb-2">💌</p>
                <p class="text-ngray text-sm">Jadilah yang pertama mengirim ucapan!</p>
            </div>
            @endforelse
        </div>

        @if($wishes->count() >= 10)
        <div id="more-wrap" class="text-center mt-5">
            <button id="more-btn" data-page="2"
                class="border border-nred/40 text-nred text-[10px] tracking-[3px] uppercase px-7 py-2.5 rounded hover:bg-nred hover:text-white transition-all duration-300 font-semibold">
                Lihat lebih banyak
            </button>
        </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ --}}
<footer class="px-8 py-14 text-center bg-ndark border-t border-white/5">

    {{-- Netflix logo --}}
    <div class="flex justify-center mb-5">
        <svg viewBox="0 0 111 190" fill="none" class="w-7 h-7 opacity-30" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0H30.72L55.5 80.996L80.28 0H111V190H80.28V109L55.5 190L30.72 109V190H0V0Z" fill="#E50914"/>
        </svg>
    </div>

    <h3 class="font-display text-white text-[2rem] font-light">
        {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}
    </h3>
    <p class="text-ngray text-[10px] tracking-[3px] uppercase mt-2">
        {{ $invitation->reception_date->isoFormat('D MMMM Y') }}
    </p>
    <div class="n-line w-10 mx-auto my-5 opacity-40"></div>
    <p class="text-ngray/30 text-xs">Made with ❤️ · Laravel + Tailwind CSS</p>
</footer>

@push('scripts')
<style>
    /* Radio RSVP selected */
    input[type="radio"]:checked + .attendance-box {
        border-color: #E50914 !important;
        background: rgba(229,9,20,.12) !important;
    }
    input[type="radio"]:checked + .attendance-box span:last-child {
        color: #E50914 !important;
    }
    /* Scroll reveal bounce */
    @keyframes bounce-y {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }
</style>
@endpush

@endsection