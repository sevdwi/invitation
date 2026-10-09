{{-- FILE: resources/views/album/show.blade.php --}}
{{-- Album foto acara. Foto tersimpan di Google Drive; di sini hanya ditampilkan. --}}
@extends('layouts.head')

@section('title', 'Album Foto — ' . $invitation->groom_name . ' & ' . $invitation->bride_name)

@section('content')
    @php $driveUrl = 'https://drive.google.com/drive/folders/' . $folderId; @endphp

    <main class="w-full max-w-5xl">
        <div class="text-center mb-8">
            <p class="text-xs uppercase tracking-[4px] text-brand-gold mb-2">Album Foto</p>
            <h1 class="font-serif text-3xl sm:text-4xl text-white">{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</h1>
            <p class="text-sm mt-2">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</p>

            <div class="flex flex-wrap justify-center gap-2 mt-5">
                <a href="{{ route('invitation.show', $invitation) }}" class="btn-ghost">← Undangan</a>
                <a href="{{ $driveUrl }}" target="_blank" rel="noopener" class="btn-ghost">Buka di Google Drive</a>
            </div>
        </div>

        @if ($photos === null)
            {{-- Daftar foto tidak bisa diambil → pakai tampilan folder bawaan Google Drive --}}
            <div class="card overflow-hidden">
                <iframe src="{{ \App\Services\GoogleDriveAlbum::embedUrl($folderId) }}" title="Album foto di Google Drive"
                        class="w-full h-[75vh] bg-white" loading="lazy"></iframe>
            </div>
        @elseif (empty($photos))
            <div class="card p-10 text-center">
                <p class="text-sm">Belum ada foto di album ini. Silakan kembali lagi nanti.</p>
            </div>
        @else
            <p class="text-center text-xs uppercase tracking-wider mb-4">{{ count($photos) }} foto</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                @foreach ($photos as $i => $photo)
                    <button type="button" data-index="{{ $i }}"
                            class="album-thumb aspect-square rounded-lg overflow-hidden bg-brand-surface border border-white/5 hover:border-brand-gold transition">
                        <img src="{{ \App\Services\GoogleDriveAlbum::thumbnailUrl($photo['id'], 400) }}" alt="{{ $photo['name'] }}"
                             loading="lazy" referrerpolicy="no-referrer" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>

            {{-- ── Penampil foto ukuran besar ─────────── --}}
            <div id="lightbox" class="hidden fixed inset-0 z-50 bg-black/95 flex-col items-center justify-center p-4">
                <button type="button" id="lb-close" aria-label="Tutup" class="absolute top-4 right-4 btn-ghost !py-2">Tutup ✕</button>
                <button type="button" id="lb-prev" aria-label="Foto sebelumnya" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 btn-ghost !px-3">‹</button>
                <button type="button" id="lb-next" aria-label="Foto berikutnya" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 btn-ghost !px-3">›</button>

                <img id="lb-image" src="" alt="" referrerpolicy="no-referrer" class="max-w-full max-h-[80vh] object-contain rounded">

                <div class="flex items-center gap-4 mt-4">
                    <span id="lb-counter" class="text-xs tabular-nums"></span>
                    <a id="lb-download" href="#" target="_blank" rel="noopener" class="text-xs text-brand-gold hover:underline">Buka ukuran asli</a>
                </div>
            </div>

            <script>
            (function () {
                const photos  = {{ Js::from(array_column($photos, 'id')) }};
                const box     = document.getElementById('lightbox');
                const image   = document.getElementById('lb-image');
                const counter = document.getElementById('lb-counter');
                const link    = document.getElementById('lb-download');
                let current   = 0;

                function show(index) {
                    current = (index + photos.length) % photos.length;
                    image.src = 'https://drive.google.com/thumbnail?id=' + photos[current] + '&sz=w1600';
                    link.href = 'https://drive.google.com/file/d/' + photos[current] + '/view';
                    counter.textContent = (current + 1) + ' / ' + photos.length;
                }

                function open(index) {
                    show(index);
                    box.classList.replace('hidden', 'flex');
                }

                function close() {
                    box.classList.replace('flex', 'hidden');
                    image.src = '';
                }

                document.querySelectorAll('.album-thumb').forEach(el =>
                    el.addEventListener('click', () => open(Number(el.dataset.index))));

                document.getElementById('lb-close').addEventListener('click', close);
                document.getElementById('lb-prev').addEventListener('click', () => show(current - 1));
                document.getElementById('lb-next').addEventListener('click', () => show(current + 1));
                box.addEventListener('click', e => { if (e.target === box) close(); });

                document.addEventListener('keydown', e => {
                    if (box.classList.contains('hidden')) return;
                    if (e.key === 'Escape')     close();
                    if (e.key === 'ArrowLeft')  show(current - 1);
                    if (e.key === 'ArrowRight') show(current + 1);
                });
            })();
            </script>
        @endif
    </main>
@endsection
