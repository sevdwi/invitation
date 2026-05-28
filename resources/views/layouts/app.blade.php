<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f0800">

    {{-- Open Graph --}}
    <meta property="og:title"       content="{{ $invitation->groom_name }} & {{ $invitation->bride_name }} — Wedding Invitation">
    <meta property="og:description" content="Kami mengundang Anda hadir di momen istimewa kami.">
    <meta property="og:type"        content="website">

    <title>{{ $invitation->groom_name }} & {{ $invitation->bride_name }} 💍</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold:        '#C9A96E',
                        'gold-lt':   '#E8D5B0',
                        'gold-dk':   '#9A7340',
                        cream:       '#FDF8F2',
                        'cream-2':   '#F5EDE0',
                        rose:        '#1a0800',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        body:    ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://unpkg.com/animate.css@4.1.1/animate.min.css">

    <style>
        /* ── Base ───────────────────────────────── */
        html { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #FDF8F2; color: #2c1a08; overflow-x: hidden; }

        /* ── Cover ──────────────────────────────── */
        #cover {
            background:
                radial-gradient(ellipse at 20% 10%, rgba(80,40,0,.9) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 90%, rgba(30,10,0,.95) 0%, transparent 55%),
                #0f0800;
        }
        #cover { transition: opacity .9s ease, visibility .9s ease; }
        #cover.hide { opacity: 0; visibility: hidden; pointer-events: none; }

        /* ── Scroll reveal ──────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .65s ease, transform .65s ease;
        }
        .reveal.on { opacity: 1; transform: none; }

        /* ── Music bars ─────────────────────────── */
        .mbar { display: block; width: 3px; border-radius: 99px; background: #C9A96E; }
        @keyframes mbar { 0%,100%{ height:3px } 50%{ height:18px } }
        .mbar:nth-child(1){ animation: mbar 1s .0s ease-in-out infinite; }
        .mbar:nth-child(2){ animation: mbar 1s .15s ease-in-out infinite; }
        .mbar:nth-child(3){ animation: mbar 1s .30s ease-in-out infinite; }
        .mbar:nth-child(4){ animation: mbar 1s .45s ease-in-out infinite; }
        .mbar:nth-child(5){ animation: mbar 1s .60s ease-in-out infinite; }

        /* ── Scroll caret ───────────────────────── */
        @keyframes bounce { 0%,100%{ transform:translateY(0) } 50%{ transform:translateY(7px) } }
        .bounce { animation: bounce 2s ease-in-out infinite; }

        /* ── Input ──────────────────────────────── */
        .w-field {
            width: 100%;
            border: 1px solid rgba(201,169,110,.35);
            background: #fff;
            padding: 13px 16px;
            font-size: .875rem;
            color: #2c1a08;
            outline: none;
            transition: border-color .25s;
            border-radius: 0;
            -webkit-appearance: none;
        }
        .w-field::placeholder { color: rgba(44,26,8,.3); }
        .w-field:focus { border-color: #C9A96E; box-shadow: 0 0 0 3px rgba(201,169,110,.12); }

        /* ── Gallery ────────────────────────────── */
        .g-item img { transition: transform .45s ease; }
        .g-item:active img { transform: scale(1.05); }

        /* ── Wish card ──────────────────────────── */
        .wish-in {
            animation: wishIn .5s ease forwards;
        }
        @keyframes wishIn {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:none; }
        }

        /* ── Ripple btn ─────────────────────────── */
        .ripple { position: relative; overflow: hidden; }
        .ripple:after {
            content:'';
            position:absolute; inset:0;
            background:rgba(255,255,255,.15);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }
        .ripple:active:after { transform: scaleX(1); }

        /* section spacing */
        section { scroll-margin-top: 0px; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════
     COVER PAGE
═══════════════════════════════════════════════ --}}
<div id="cover" class="fixed inset-0 z-50 flex flex-col items-center justify-center px-8 text-center">

    {{-- ornamen sudut --}}
    <div class="absolute top-6 left-6 w-12 h-12 border-t border-l border-gold/30"></div>
    <div class="absolute top-6 right-6 w-12 h-12 border-t border-r border-gold/30"></div>
    <div class="absolute bottom-6 left-6 w-12 h-12 border-b border-l border-gold/30"></div>
    <div class="absolute bottom-6 right-6 w-12 h-12 border-b border-r border-gold/30"></div>

    <div class="animate__animated animate__fadeIn animate__delay-1s">
        <p class="text-gold-lt/60 text-[9px] tracking-[6px] uppercase mb-5">✦ Undangan Pernikahan ✦</p>

        <h1 class="font-display text-white font-light leading-none">
            <span class="block text-[3.5rem]">{{ $invitation->groom_name }}</span>
            <span class="block text-gold italic text-[2.2rem] my-1 font-light">&amp;</span>
            <span class="block text-[3.5rem]">{{ $invitation->bride_name }}</span>
        </h1>

        <div class="flex items-center justify-center gap-3 my-5">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gold/40"></div>
            <span class="text-gold text-sm">💍</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gold/40"></div>
        </div>

        <p class="text-white/30 text-[9px] tracking-[3px] uppercase mb-1">Kepada Yth.</p>
        <p class="font-display text-gold-lt text-xl italic mb-8 font-light">{{ $guestName }}</p>

        <button id="open-btn"
            class="ripple inline-flex items-center justify-center gap-2 border border-gold/70 text-gold px-10 py-3.5 text-[10px] tracking-[4px] uppercase hover:bg-gold hover:text-rose transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
            Buka Undangan
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MUSIC PLAYER
═══════════════════════════════════════════════ --}}
@if($invitation->music_file)
<div class="fixed bottom-5 right-5 z-40 flex items-end gap-2">
    {{-- Wave bars --}}
    <div id="mbars" class="hidden items-end gap-[3px] h-5 mb-3">
        <span class="mbar"></span><span class="mbar"></span><span class="mbar"></span>
        <span class="mbar"></span><span class="mbar"></span>
    </div>
    <audio id="bgm" loop preload="none">
        <source src="{{ asset('storage/'.$invitation->music_file) }}" type="audio/mpeg">
    </audio>
    <button id="mbtn"
        class="ripple w-13 h-13 w-12 h-12 rounded-full bg-gold text-rose shadow-lg shadow-gold/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-transform duration-200">
        <svg id="mico" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>
        </svg>
    </button>
</div>
@endif

{{-- ═══════════════════════════════════════════════
     LIGHTBOX
═══════════════════════════════════════════════ --}}
<div id="lbx" class="fixed inset-0 z-50 bg-black/92 hidden flex-col items-center justify-center p-4">
    <button id="lbclose"
        class="absolute top-5 right-5 text-white/60 hover:text-white transition-colors">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <img id="lbimg" src="" class="max-w-full max-h-[80vh] object-contain" alt="">
    <p id="lbcap" class="text-white/50 text-xs text-center mt-4 tracking-wide"></p>
</div>

{{-- ═══════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════ --}}
<main id="main" class="hidden">
    @yield('content')
</main>

{{-- Scripts --}}
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/lodash@4.17.21/lodash.min.js"></script>
<script>
    window.W = {
        csrf:    "{{ csrf_token() }}",
        lat:     {{ $invitation->maps_lat  ?? 0 }},
        lng:     {{ $invitation->maps_lng  ?? 0 }},
        loc:     "{{ addslashes($invitation->reception_location) }}",
        rdate:   "{{ $invitation->reception_date->toISOString() }}",
        hasMap:  {{ ($invitation->maps_lat && $invitation->maps_lng) ? 'true' : 'false' }},
        mapsKey: "{{ config('services.google_maps.key','') }}",
    };
</script>
<script src="{{ asset('js/app.js') }}"></script>

@if($invitation->maps_lat && $invitation->maps_lng && config('services.google_maps.key'))
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&language=id">
</script>
@endif

@stack('scripts')
</body>
</html>