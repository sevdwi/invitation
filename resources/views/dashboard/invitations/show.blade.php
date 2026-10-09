{{-- FILE: resources/views/dashboard/invitations/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', $invitation->groom_name . ' & ' . $invitation->bride_name)

@section('content')
    @php
        $publicUrl = route('invitation.show', $invitation);
        $attendanceLabels = ['hadir' => 'Hadir', 'tidak_hadir' => 'Tidak hadir', 'belum_pasti' => 'Belum pasti'];
    @endphp

    <a href="{{ route('dashboard') }}" class="text-sm text-brand-gold hover:underline">← Undangan Saya</a>

    {{-- ── Header ─────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mt-2 mb-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="font-serif text-3xl text-white">{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</h1>
                @if ($invitation->is_active)
                    <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-green-900/40 text-green-300">Aktif</span>
                @else
                    <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/10 text-white/60">Nonaktif</span>
                @endif
                <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/10 text-brand-gold">Tema: {{ \App\Models\Invitation::AVAILABLE_THEMES[$invitation->theme] ?? $invitation->theme }}</span>
            </div>
            <p class="text-sm mt-1">Resepsi {{ $invitation->reception_date->isoFormat('dddd, D MMMM Y • HH:mm') }} WIB</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('dashboard.invitations.edit', $invitation) }}" class="btn-gold">Edit</a>
            @if ($invitation->is_active)
                <a href="{{ $publicUrl }}" target="_blank" class="btn-ghost">Lihat</a>
            @endif
        </div>
    </div>

    {{-- ── Link Tamu ──────────────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-1">Link untuk Tamu</h2>
        <p class="text-sm mb-4">Ketik nama tamu untuk membuat link undangan personal.</p>

        @unless ($invitation->is_active)
            <p class="text-sm text-red-300 mb-4">Undangan sedang nonaktif, jadi link belum bisa dibuka tamu.</p>
        @endunless

        <div class="grid sm:grid-cols-[1fr_2fr] gap-3">
            <input type="text" id="guest-name" class="field" placeholder="Nama tamu, mis. Pak Joko">
            <input type="text" id="guest-link" class="field" value="{{ $publicUrl }}" data-base="{{ $publicUrl }}" readonly>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="button" id="copy-link" class="btn-ghost">Salin Link</button>
            <a id="wa-link" href="#" target="_blank" rel="noopener" class="btn-ghost">Kirim via WhatsApp</a>
        </div>
    </section>

    {{-- ── Rekap RSVP ─────────────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-4">Konfirmasi Kehadiran (RSVP)</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            @foreach ([
                'Hadir'            => $stats['hadir'],
                'Tidak hadir'      => $stats['tidak_hadir'],
                'Belum pasti'      => $stats['belum_pasti'],
                'Total tamu hadir' => $stats['total_tamu'],
            ] as $label => $value)
                <div class="bg-brand-surface/60 border border-white/5 rounded-xl p-4">
                    <p class="font-serif text-3xl text-white">{{ $value }}</p>
                    <p class="text-xs uppercase tracking-wider mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        @if ($rsvps->isEmpty())
            <p class="text-sm">Belum ada tamu yang mengonfirmasi.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase tracking-wider text-brand-gold">
                        <tr class="border-b border-white/10">
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">WhatsApp</th>
                            <th class="py-2 pr-4">Kehadiran</th>
                            <th class="py-2 pr-4 text-right">Jumlah</th>
                            <th class="py-2 pr-4">Catatan</th>
                            <th class="py-2">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rsvps as $rsvp)
                            <tr class="border-b border-white/5 align-top">
                                <td class="py-2 pr-4 text-white">{{ $rsvp->guest_name }}</td>
                                <td class="py-2 pr-4">{{ $rsvp->guest_phone ?: '—' }}</td>
                                <td class="py-2 pr-4">{{ $attendanceLabels[$rsvp->attendance] ?? $rsvp->attendance }}</td>
                                <td class="py-2 pr-4 text-right tabular-nums">{{ $rsvp->guest_count }}</td>
                                <td class="py-2 pr-4">{{ $rsvp->note ?: '—' }}</td>
                                <td class="py-2 whitespace-nowrap">{{ $rsvp->created_at->isoFormat('D MMM Y HH:mm') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ── Daftar Hadir ───────────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-1">Daftar Hadir (Scan QR)</h2>
        <p class="text-sm mb-4">Cetak QR ini dan letakkan di meja penerima tamu. Tamu memindainya dengan kamera ponsel lalu mengisi daftar hadir sendiri.</p>

        @unless ($invitation->is_active)
            <p class="text-sm text-red-300 mb-4">Undangan sedang nonaktif, jadi QR belum bisa dipakai tamu.</p>
        @endunless

        <div class="flex flex-col sm:flex-row gap-5 mb-5">
            <div class="bg-white rounded-xl p-2 w-[196px] shrink-0 mx-auto sm:mx-0">{!! $attendanceQr !!}</div>

            <div class="flex-1 min-w-0 flex flex-col justify-between gap-4">
                <div class="grid grid-cols-2 gap-3">
                    @foreach ([
                        'Tamu mengisi'     => $attendances->count(),
                        'Total orang hadir' => $attendances->sum('guest_count'),
                    ] as $label => $value)
                        <div class="bg-brand-surface/60 border border-white/5 rounded-xl p-4">
                            <p class="font-serif text-3xl text-white">{{ $value }}</p>
                            <p class="text-xs uppercase tracking-wider mt-1">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>

                <div>
                    <input type="text" class="field" value="{{ route('attendance.create', $invitation) }}" readonly onfocus="this.select()">
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('dashboard.attendances.qr', $invitation) }}" target="_blank" class="btn-gold">Cetak QR</a>
                        @if ($invitation->is_active)
                            <a href="{{ route('attendance.create', $invitation) }}" target="_blank" class="btn-ghost">Coba Form</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($attendances->isEmpty())
            <p class="text-sm">Belum ada tamu yang mengisi daftar hadir.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase tracking-wider text-brand-gold">
                        <tr class="border-b border-white/10">
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Alamat / Instansi</th>
                            <th class="py-2 pr-4">WhatsApp</th>
                            <th class="py-2 pr-4 text-right">Jumlah</th>
                            <th class="py-2 pr-4">Waktu Hadir</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr class="border-b border-white/5 align-top">
                                <td class="py-2 pr-4 text-white">{{ $attendance->guest_name }}</td>
                                <td class="py-2 pr-4">{{ $attendance->address ?: '—' }}</td>
                                <td class="py-2 pr-4">{{ $attendance->guest_phone ?: '—' }}</td>
                                <td class="py-2 pr-4 text-right tabular-nums">{{ $attendance->guest_count }}</td>
                                <td class="py-2 pr-4 whitespace-nowrap">{{ $attendance->created_at->isoFormat('D MMM Y HH:mm') }}</td>
                                <td class="py-2 text-right">
                                    <form action="{{ route('dashboard.attendances.destroy', [$invitation, $attendance->id]) }}" method="POST"
                                          onsubmit="return confirm('Hapus data kehadiran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger !px-2 !py-1">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ── Ucapan ─────────────────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-1">Ucapan &amp; Doa</h2>
        <p class="text-sm mb-4">Ucapan yang disembunyikan tidak tampil di halaman undangan.</p>

        @forelse ($invitation->wishes as $wish)
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 border-t border-white/5 py-4 {{ $wish->is_approved ? '' : 'opacity-60' }}">
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold">
                        {{ $wish->sender_name }}
                        @unless ($wish->is_approved)
                            <span class="ml-2 text-[10px] uppercase tracking-wider px-2 py-0.5 rounded bg-white/10 text-white/60 font-normal">Disembunyikan</span>
                        @endunless
                    </p>
                    <p class="text-sm mt-1 break-words">{{ $wish->message }}</p>
                    <p class="text-xs mt-1 opacity-60">{{ $wish->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <form action="{{ route('dashboard.wishes.update', [$invitation, $wish->id]) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-ghost !py-2">{{ $wish->is_approved ? 'Sembunyikan' : 'Tampilkan' }}</button>
                    </form>
                    <form action="{{ route('dashboard.wishes.destroy', [$invitation, $wish->id]) }}" method="POST"
                          onsubmit="return confirm('Hapus ucapan ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger !py-2">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm">Belum ada ucapan.</p>
        @endforelse
    </section>

    {{-- ── Galeri ─────────────────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-4">Galeri Foto</h2>

        <form action="{{ route('dashboard.gallery.store', $invitation) }}" method="POST" enctype="multipart/form-data"
              class="grid sm:grid-cols-[2fr_2fr_auto] gap-3 items-end mb-5">
            @csrf
            <div>
                <label for="photos" class="label">Foto (JPG/PNG, maks. 3 MB, hingga 10 foto)</label>
                <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png" multiple required class="field-file">
            </div>
            <div>
                <label for="caption" class="label">Keterangan (opsional)</label>
                <input type="text" id="caption" name="caption" class="field" placeholder="Mis. Prewedding">
            </div>
            <button type="submit" class="btn-gold">Unggah</button>
        </form>

        @if ($invitation->galleries->isEmpty())
            <p class="text-sm">Belum ada foto di galeri.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($invitation->galleries as $photo)
                    <div class="relative aspect-square rounded-lg overflow-hidden bg-brand-surface">
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->caption }}"
                             loading="lazy" class="w-full h-full object-cover">
                        <form action="{{ route('dashboard.gallery.destroy', [$invitation, $photo->id]) }}" method="POST"
                              onsubmit="return confirm('Hapus foto ini?')" class="absolute top-2 right-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger !px-2 !py-1 !bg-red-900/80">Hapus</button>
                        </form>
                        @if ($photo->caption)
                            <p class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-xs px-2 py-1 truncate">{{ $photo->caption }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Album Foto Acara ───────────────────── --}}
    <section class="card p-6 mb-6">
        <h2 class="font-serif text-xl text-white mb-1">Album Foto Acara (Google Drive)</h2>
        <p class="text-sm mb-4">Foto acara tetap tersimpan di Google Drive Anda dan bisa dilihat tamu lewat halaman album undangan ini.</p>

        @if ($invitation->drive_folder_id)
            <input type="text" class="field" value="{{ route('album.show', $invitation) }}" readonly onfocus="this.select()">
            <p class="text-xs ml-1 mt-2">Pastikan folder dibagikan ke "Siapa saja yang memiliki link". Foto baru muncul paling lambat 10 menit setelah diunggah ke Drive.</p>
            <div class="flex gap-2 mt-3">
                @if ($invitation->is_active)
                    <a href="{{ route('album.show', $invitation) }}" target="_blank" class="btn-gold">Lihat Album</a>
                @endif
                <a href="{{ $invitation->drive_folder_url }}" target="_blank" rel="noopener" class="btn-ghost">Buka Folder Drive</a>
                <a href="{{ route('dashboard.invitations.edit', $invitation) }}" class="btn-ghost">Ganti Link</a>
            </div>
        @else
            <p class="text-sm mb-3">Belum ada album. Tempel link folder Google Drive di halaman edit undangan.</p>
            <a href="{{ route('dashboard.invitations.edit', $invitation) }}" class="btn-gold">Tambahkan Album</a>
        @endif
    </section>

    {{-- ── Hapus Undangan ─────────────────────── --}}
    <section class="card p-6 border-red-900/40">
        <h2 class="font-serif text-xl text-white mb-1">Hapus Undangan</h2>
        <p class="text-sm mb-4">Semua data RSVP, daftar hadir, ucapan, dan foto galeri ikut terhapus dan tidak dapat dikembalikan. Foto di Google Drive tidak ikut terhapus.</p>
        <form action="{{ route('dashboard.invitations.destroy', $invitation) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus undangan ini beserta seluruh datanya?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">Hapus Undangan</button>
        </form>
    </section>

    @push('scripts')
    <script>
    (function () {
        const nameEl = document.getElementById('guest-name');
        const linkEl = document.getElementById('guest-link');
        const waEl   = document.getElementById('wa-link');
        const copyEl = document.getElementById('copy-link');
        const couple = {{ Js::from($invitation->groom_name . ' & ' . $invitation->bride_name) }};

        function refresh() {
            const name = nameEl.value.trim();
            const link = linkEl.dataset.base + (name ? '?to=' + encodeURIComponent(name) : '');
            linkEl.value = link;

            const text = `Kepada Yth. ${name || 'Bapak/Ibu/Saudara/i'},\n\nKami mengundang Anda untuk hadir di pernikahan ${couple}.\n\n${link}`;
            waEl.href = 'https://wa.me/?text=' + encodeURIComponent(text);
        }

        nameEl.addEventListener('input', refresh);
        refresh();

        copyEl.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(linkEl.value);
            } catch {
                linkEl.select();
                document.execCommand('copy');
            }
            const orig = copyEl.textContent;
            copyEl.textContent = 'Tersalin ✓';
            setTimeout(() => copyEl.textContent = orig, 1500);
        });
    })();
    </script>
    @endpush
@endsection
