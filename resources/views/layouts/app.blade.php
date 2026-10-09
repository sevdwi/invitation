<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#141414">

    <meta property="og:title"       content="{{ $invitation->groom_name }} & {{ $invitation->bride_name }} — Wedding Invitation">
    <meta property="og:description" content="Kami mengundang Anda hadir di momen istimewa kami.">
    <meta property="og:type"        content="website">

    <title>{{ $invitation->groom_name }} & {{ $invitation->bride_name }} 💍</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nred:    '#E50914',
                        'nred-dk': '#B20710',
                        ndark:   '#141414',
                        ncard:   '#181818',
                        ncard2:  '#232323',
                        ngray:   '#808080',
                        nlight:  '#e5e5e5',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        body:    ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    keyframes: {
                        'nfx-n': {
                            '0%':   { transform: 'scale(0.3)', opacity: '0' },
                            '30%':  { transform: 'scale(1.15)', opacity: '1' },
                            '60%':  { transform: 'scale(1)', opacity: '1' },
                            '85%':  { transform: 'scale(1)', opacity: '1' },
                            '100%': { transform: 'scale(12)', opacity: '0' },
                        },
                        'nfx-glow': {
                            '0%,100%': { 'box-shadow': '0 0 0px #E50914' },
                            '50%':     { 'box-shadow': '0 0 60px 20px #E50914' },
                        },
                        'nfx-flash': {
                            '0%':   { opacity: '0' },
                            '30%':  { opacity: '1' },
                            '100%': { opacity: '0' },
                        },
                        'nfx-reveal': {
                            '0%':   { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'bar': {
                            '0%,100%': { height: '3px' },
                            '50%':     { height: '18px' },
                        },
                        'bounce-y': {
                            '0%,100%': { transform: 'translateY(0)' },
                            '50%':     { transform: 'translateY(8px)' },
                        },
                        'wish-in': {
                            '0%':   { opacity: '0', transform: 'translateY(12px)' },
                            '100%': { opacity: '1', transform: 'none' },
                        },
                        'pulse-red': {
                            '0%,100%': { opacity: '1' },
                            '50%':     { opacity: '0.7' },
                        },
                    },
                    animation: {
                        'nfx-n':      'nfx-n 2s cubic-bezier(.4,0,.2,1) forwards',
                        'nfx-glow':   'nfx-glow 2s ease-in-out',
                        'nfx-flash':  'nfx-flash 0.6s ease-in-out forwards',
                        'nfx-reveal': 'nfx-reveal 0.8s ease forwards',
                        'bar1': 'bar 1s 0.0s ease-in-out infinite',
                        'bar2': 'bar 1s 0.15s ease-in-out infinite',
                        'bar3': 'bar 1s 0.3s ease-in-out infinite',
                        'bar4': 'bar 1s 0.45s ease-in-out infinite',
                        'bar5': 'bar 1s 0.6s ease-in-out infinite',
                        'bounce-y': 'bounce-y 2s ease-in-out infinite',
                        'wish-in':  'wish-in 0.5s ease forwards',
                        'pulse-red': 'pulse-red 2s ease-in-out infinite',
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Base ───────────────────────── */
        html, body { font-family: 'Plus Jakarta Sans', sans-serif; background: #141414; color: #e5e5e5; overflow-x: hidden; }

        /* ── Scroll Reveal ──────────────── */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.on { opacity: 1; transform: none; }

        /* ── Input Netflix style ────────── */
        .n-field {
            width: 100%;
            background: #333;
            border: 1px solid #555;
            padding: 14px 16px;
            font-size: .875rem;
            color: #fff;
            outline: none;
            border-radius: 4px;
            transition: border-color .25s, background .25s;
            -webkit-appearance: none;
        }
        .n-field::placeholder { color: #aaa; }
        .n-field:focus { border-color: #E50914; background: #3a3a3a; }

        /* ── Music bars ─────────────────── */
        .mbar { display: block; width: 3px; border-radius: 99px; background: #E50914; }

        /* ── Gallery hover ──────────────── */
        .g-item img { transition: transform .45s ease; }
        .g-item:hover img { transform: scale(1.07); }

        /* ── Netflix N Logo ─────────────── */
        #nfx-screen {
            position: fixed; inset: 0; z-index: 9999;
            background: #000;
            display: flex; align-items: center; justify-content: center;
            transition: opacity .5s ease;
        }
        #nfx-screen.out { opacity: 0; pointer-events: none; }

        #nfx-logo {
            position: relative;
            width: 80px; height: 80px;
        }

        /* Netflix N SVG glow */
        #nfx-logo svg {
            width: 80px; height: 80px;
            filter: drop-shadow(0 0 0px #E50914);
            transition: filter .3s;
        }
        #nfx-logo.glow svg {
            filter: drop-shadow(0 0 30px #E50914) drop-shadow(0 0 60px #E50914);
        }

        /* Red flash overlay */
        #nfx-flash {
            position: fixed; inset: 0; z-index: 9998;
            background: #E50914;
            opacity: 0;
            pointer-events: none;
        }

        /* Cover page */
        #cover {
            position: fixed; inset: 0; z-index: 50;
            background: #141414;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 2rem;
            transition: opacity .8s ease, visibility .8s ease;
        }
        #cover.hide { opacity: 0; visibility: hidden; pointer-events: none; }

        /* Scrollbar Netflix style */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #141414; }
        ::-webkit-scrollbar-thumb { background: #E50914; border-radius: 2px; }

        /* Netflix red line separator */
        .n-line { height: 3px; background: #E50914; border-radius: 2px; }

        .text-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: #E50914;
            letter-spacing: 8px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
        }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════
     NETFLIX OPENING ANIMATION SCREEN
═══════════════════════════════════════════ --}}
<div id="nfx-screen">
    {{-- Red flash overlay --}}
    <div id="nfx-flash"></div>

    {{-- Netflix N Logo --}}
    <div id="nfx-logo">
        <svg viewBox="0 0 111 190" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0H30.72L55.5 80.996L80.28 0H111V190H80.28V109L55.5 190L30.72 109V190H0V0Z" fill="#E50914"/>
            <path d="M30.72 0L55.5 80.996V190L0 0H30.72Z" fill="#B20710"/>
            <path d="M80.28 0L55.5 80.996V190L111 0H80.28Z" fill="#B20710"/>
        </svg>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     COVER PAGE
═══════════════════════════════════════════ --}}
<div id="cover">

    {{-- Netflix Logo kecil di atas --}}
    <div class="absolute top-6 left-0 right-0 flex justify-center">
        <!-- <svg viewBox="0 0 111 190" fill="none" class="w-8 h-8" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0H30.72L55.5 80.996L80.28 0H111V190H80.28V109L55.5 190L30.72 109V190H0V0Z" fill="#E50914"/>
        </svg> -->
                <!-- NIKAHFIX Text -->
        <div class="text-logo">
            MENIKAHFIX
        </div>
    </div>

    {{-- Ornamen sudut merah --}}
    <div class="absolute top-16 left-5 w-10 h-10 border-t-2 border-l-2 border-nred/40"></div>
    <div class="absolute top-16 right-5 w-10 h-10 border-t-2 border-r-2 border-nred/40"></div>
    <div class="absolute bottom-8 left-5 w-10 h-10 border-b-2 border-l-2 border-nred/40"></div>
    <div class="absolute bottom-8 right-5 w-10 h-10 border-b-2 border-r-2 border-nred/40"></div>

    <div class="w-full max-w-xs mx-auto">
        <p class="text-ngray text-[9px] tracking-[5px] uppercase mb-6">✦ Undangan Pernikahan ✦</p>

        {{-- Nama Mempelai --}}
        <h1 class="font-display text-nlight font-light leading-none mb-4">
            <span class="block text-[3.2rem] tracking-wide">{{ $invitation->groom_name }}</span>
            <span class="block text-nred italic text-[2.2rem] my-1 font-light">&amp;</span>
            <span class="block text-[3.2rem] tracking-wide">{{ $invitation->bride_name }}</span>
        </h1>

        {{-- Red line divider --}}
        <div class="flex items-center gap-3 my-5 px-4">
            <div class="flex-1 h-px bg-nred/30"></div>
            <div class="w-2 h-2 bg-nred rounded-full animate-pulse-red"></div>
            <div class="flex-1 h-px bg-nred/30"></div>
        </div>

        <p class="text-ngray text-[9px] tracking-[3px] uppercase mb-1">Kepada Yth.</p>
        <p class="font-display text-nlight text-2xl italic mb-8 font-light">{{ $guestName }}</p>

        {{-- Tombol Buka Undangan (Netflix Style) --}}
        <button id="open-btn"
            class="w-full bg-nred text-white py-4 text-sm font-semibold tracking-widest uppercase rounded hover:bg-nred-dk active:scale-95 transition-all duration-200 flex items-center justify-center gap-3 shadow-lg shadow-nred/30">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
            </svg>
            Buka Undangan
        </button>

        <p class="text-ngray/50 text-[9px] mt-4 tracking-wider">
            {{ $invitation->reception_date->isoFormat('D MMMM Y') }}
        </p>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MUSIC PLAYER
═══════════════════════════════════════════ --}}
@if($invitation->music_file)
<div class="fixed bottom-5 right-5 z-40 flex items-end gap-2">
    <div id="mbars" class="hidden items-end gap-[3px] h-5 mb-3">
        <span class="mbar animate-bar1"></span>
        <span class="mbar animate-bar2"></span>
        <span class="mbar animate-bar3"></span>
        <span class="mbar animate-bar4"></span>
        <span class="mbar animate-bar5"></span>
    </div>
    <audio id="bgm" loop preload="none">
        <source src="{{ asset('storage/'.$invitation->music_file) }}" type="audio/mpeg">
    </audio>
    <button id="mbtn"
        class="w-12 h-12 rounded-full bg-nred text-white shadow-lg shadow-nred/40 flex items-center justify-center hover:bg-nred-dk hover:scale-110 active:scale-95 transition-all duration-200">
        <svg id="mico" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 19V6l12-3v13M9 19c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>
        </svg>
    </button>
</div>
@endif

{{-- ═══════════════════════════════════════════
     LIGHTBOX
═══════════════════════════════════════════ --}}
<div id="lbx" class="fixed inset-0 z-50 bg-black/95 hidden flex-col items-center justify-center p-4">
    <button id="lbclose" class="absolute top-5 right-5 text-white/60 hover:text-white transition-colors">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <img id="lbimg" src="" class="max-w-full max-h-[82vh] object-contain rounded" alt="">
    <p id="lbcap" class="text-ngray text-xs text-center mt-4 tracking-wide"></p>
</div>

{{-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ --}}
<main id="main" class="hidden">
    @yield('content')
</main>

{{-- Scripts --}}
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/lodash@4.17.21/lodash.min.js"></script>
<script>
    window.W = {{ Js::from([
        'csrf'      => csrf_token(),
        'lat'       => (float) $invitation->maps_lat,
        'lng'       => (float) $invitation->maps_lng,
        'loc'       => $invitation->reception_location,
        'rdate'     => $invitation->reception_date->toISOString(),
        'mapsKey'   => config('services.google_maps.key', ''),
        'rsvpUrl'   => route('rsvp.store', $invitation),
        'wishesUrl' => route('wishes.index', $invitation),
    ]) }};
</script>
<script src="{{ asset('js/app.js') }}"></script>

@if($invitation->maps_lat && $invitation->maps_lng && config('services.google_maps.key'))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&language=id"></script>
@endif

{{-- ═══════════════════════════════════════════
     NETFLIX OPENING + COVER SCRIPT
═══════════════════════════════════════════ --}}
<script>
(function() {
    const nfxScreen = document.getElementById('nfx-screen');
    const nfxLogo   = document.getElementById('nfx-logo');
    const nfxFlash  = document.getElementById('nfx-flash');
    const cover     = document.getElementById('cover');
    const main      = document.getElementById('main');
    const openBtn   = document.getElementById('open-btn');

    // Sembunyikan nfx-screen setelah 0.8 detik (cukup lihat logo, lalu ke cover)
    setTimeout(() => {
        nfxScreen.style.opacity = '0';
        nfxScreen.style.transition = 'opacity 0.6s ease';
        setTimeout(() => { nfxScreen.style.display = 'none'; }, 700);
    }, 800);

    // Tombol "Buka Undangan" → Netflix opening transition
    openBtn?.addEventListener('click', () => {
        // 1. Tampilkan flash merah
        nfxFlash.style.display = 'block';

        // 2. Animasi N logo muncul
        nfxScreen.style.display = 'flex';
        nfxScreen.style.opacity = '1';
        nfxScreen.style.transition = 'none';

        // Flash merah selebat
        nfxFlash.style.animation = 'none';
        nfxFlash.offsetHeight; // reflow
        nfxFlash.style.transition = 'opacity 0.15s ease';
        nfxFlash.style.opacity = '1';

        setTimeout(() => { nfxFlash.style.opacity = '0'; }, 150);

        // 3. Logo animasi
        nfxLogo.style.animation = 'none';
        nfxLogo.offsetHeight;
        nfxLogo.classList.add('glow');
        nfxLogo.style.animation = 'nfx-n 1.8s cubic-bezier(.4,0,.2,1) forwards';

        // 4. Sembunyikan cover
        setTimeout(() => {
            cover.classList.add('hide');
        }, 300);

        // 5. Tampilkan main content
        setTimeout(() => {
            main.classList.remove('hidden');
            main.style.opacity = '0';
            main.style.transform = 'translateY(20px)';
            main.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            requestAnimationFrame(() => {
                main.style.opacity = '1';
                main.style.transform = 'translateY(0)';
            });

            // Musik auto-play
            const audio = document.getElementById('bgm');
            if (audio) {
                audio.play().catch(() => {});
                setMusicState(true);
            }

            window.scrollTo({ top: 0, behavior: 'instant' });
            setTimeout(observeReveal, 200);
        }, 1000);

        // 6. Sembunyikan nfx screen setelah animasi selesai
        setTimeout(() => {
            nfxScreen.style.opacity = '0';
            nfxScreen.style.transition = 'opacity 0.4s ease';
            setTimeout(() => { nfxScreen.style.display = 'none'; }, 500);
        }, 1600);
    });
})();
</script>

@stack('scripts')
</body>
</html>