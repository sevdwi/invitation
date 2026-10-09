# Wedding SaaS — CLAUDE.md

## Tujuan & Status

- **Tujuan:** platform SaaS undangan pernikahan digital.
- **Model:** multi-tenant — banyak pasangan membuat undangannya sendiri di satu aplikasi.
- **Stack:** Laravel 13, PHP 8.3, MySQL, Blade, Tailwind (masih lewat CDN), axios + lodash (CDN).
- **Status (2026-10-05):**
  - ✅ Routing berbasis slug (`/{slug}?to=Nama+Tamu`)
  - ✅ RSVP, ucapan, dan galeri terikat ke undangan masing-masing
  - ✅ Login, registrasi, dan dashboard klien (kelola undangan, link tamu, RSVP, ucapan, galeri)
  - ✅ Sistem tema (Netflix & Minimalist, klien bisa ganti tema saat buat/edit undangan)
  - ✅ Daftar hadir lewat scan QR (`/{slug}/hadir`) dan album foto acara dari Google Drive (`/{slug}/album`) — 2026-10-08
  - ⏳ Paket & pembayaran (langkah berikutnya)

## Perintah

```bash
php artisan serve      # jalankan aplikasi
php artisan test       # tes (SQLite in-memory, tidak menyentuh MySQL)
php artisan migrate    # terapkan migrasi ke MySQL lokal
php artisan route:list # cek daftar route
```

## Arsitektur Kunci

### URL & undangan

- Halaman publik: `/{slug}?to=Nama+Tamu`. Tanpa `?to=`, nama tamu menjadi "Tamu Undangan".
- Slug dibuat otomatis dari nama panggilan mempelai di `Invitation::uniqueSlug()` (hook `creating`); bila bentrok diberi akhiran `-2`, `-3`, dst.
- `Invitation::RESERVED_SLUGS` berisi kata yang tidak boleh jadi slug. Tambahkan ke daftar itu setiap kali membuat URL statis baru (mis. `login`, `dashboard`).
- `/` sementara dialihkan ke `/dashboard` (tamu dilempar ke `/login`) sampai ada halaman depan.

### Scoping data per undangan

- Jangan pernah mengambil undangan dengan `Invitation::where('is_active', true)->first()`. Selalu terima `Invitation $invitation` dari route.
- `Route::bind('invitation', ...)` di `routes/web.php` hanya meloloskan undangan aktif; selain itu 404. Dipakai halaman publik.
- `Route::bind('myInvitation', ...)` hanya meloloskan undangan milik user yang login (aktif maupun nonaktif); milik orang lain 404. Semua route dashboard memakai `{myInvitation}` — jangan pakai `{invitation}` di dashboard.
- Data anak di dashboard (`{wish}`, `{gallery}`) diterima sebagai id lalu dicari lewat relasi (`$myInvitation->wishes()->findOrFail($wish)`) supaya tidak bisa menyentuh data undangan lain.
- Data turunan dibuat lewat relasi (`$invitation->rsvps()->create(...)`), bukan dengan mengisi `invitation_id` manual.
- Grup route `{invitation}` harus tetap paling bawah di `routes/web.php` agar tidak menimpa route statis.
- URL endpoint untuk JavaScript dikirim lewat `window.W` di `layouts/app.blade.php` (`W.rsvpUrl`, `W.wishesUrl`); jangan menulis path mati di `public/js/app.js`.

### Login & dashboard

- Auth dibuat manual di `AuthController` (tanpa Breeze): `/login`, `/register`, `/logout`. Belum ada lupa kata sandi dan verifikasi email.
- Daftar/masuk lewat Google memakai Laravel Socialite di `GoogleAuthController` (`/auth/google`, `/auth/google/callback`; kredensial `GOOGLE_*` di `.env`). Akun baru dari Google punya `google_id` dan `password` `null` (tidak bisa login lewat form kata sandi). Email yang sudah terdaftar otomatis ditautkan, hanya bila Google menyatakan emailnya terverifikasi.
- Dashboard ada di `/dashboard/...`, controller di `app/Http/Controllers/Dashboard/`, view di `resources/views/dashboard/`.
- Undangan dibuat lewat `$request->user()->invitations()->create(...)` sehingga `user_id` selalu terisi. Kolomnya masih nullable; undangan lama tanpa pemilik tidak muncul di dashboard siapa pun.
- Aturan validasi undangan (buat & edit) ada di `SaveInvitationRequest`; pemilik boleh mengganti slug saat edit, dan tema saat edit (bukan buat).
- Tampilan login & dashboard memakai palet emas "Indocreator" dari `layouts/partials/brand.blade.php` (kelas `.card`, `.field`, `.btn-gold`, dst.).

### Sistem Tema

- Kolom `theme` di tabel `invitations`, default `netflix`, enum di `Invitation::AVAILABLE_THEMES`.
- Halaman publik load view sesuai tema: `view("themes.{$theme}.show", ...)` dengan fallback ke netflix jika tema tidak ada.
- Folder theme: `resources/views/themes/{netflix|minimalist}/show.blade.php` — setiap tema layout sendiri, jangan share komponen.
- Dashboard form buat menampilkan pilihan tema (radio button); edit hanya bisa ubah tema lewat dropdown (tema bisa diubah nanti).
- Daftar tema bisa ditambah dengan membuat folder baru, update `AVAILABLE_THEMES`, dan publish view baru.

### Daftar hadir (scan QR)

- Berbeda dari RSVP: RSVP diisi sebelum acara, daftar hadir (`attendances`) diisi tamu sendiri di lokasi setelah scan QR.
- Tamu: `GET/POST /{slug}/hadir` (`AttendanceController`, form biasa tanpa JavaScript, `throttle:60,1` karena tamu satu gedung bisa berbagi IP). `?to=Nama` mengisi kolom nama.
- Pemilik: QR + rekap ada di halaman kelola undangan; halaman siap cetak di `/dashboard/invitations/{slug}/qr`.
- QR dibuat di server sebagai SVG lewat `App\Support\QrCode::svg()` (paket `bacon/bacon-qr-code`). QR berisi URL slug, jadi QR yang sudah dicetak tidak berlaku lagi bila slug diganti.

### Album foto acara (Google Drive)

- Pemilik menempel link folder Drive di form undangan (kolom `drive_folder_url`); foto tidak pernah disalin ke server. `$invitation->drive_folder_id` mengambil id foldernya.
- `App\Services\GoogleDriveAlbum::photos()` membaca daftar foto lewat Drive API v3 memakai `GOOGLE_DRIVE_API_KEY` (satu key untuk seluruh platform), disimpan di cache 10 menit, maksimal 1000 foto.
- Bila API key kosong atau Google menolak, `photos()` mengembalikan `null` dan `album/show.blade.php` memakai iframe `embeddedfolderview` bawaan Drive.
- Folder harus dibagikan "Siapa saja yang memiliki link"; tanpa itu album kosong/ditolak.
- Halaman daftar hadir dan album memakai palet Indocreator (`layouts.head`), bukan tema undangan. Tombol "Buka Album" ditulis terpisah di tiap tema.

## Konvensi

- Komentar kode, label, dan pesan validasi **berbahasa Indonesia**.
- Timezone `Asia/Jakarta`, locale `id`. Jam acara ditampilkan dengan label "WIB" (belum mendukung WITA/WIT).
- Unggahan disimpan di disk `public`: foto mempelai → `photos/`, foto sampul → `heroes/`, musik → `music/`, galeri → `gallery/`.
- Kirim data PHP ke JavaScript dengan `Js::from([...])`, bukan `@json` (tidak aman untuk array berisi koma) atau `addslashes`.

## Langkah Berikutnya

1. **Sistem tema** — kolom `theme`, satu view per tema. Tema saat ini bergaya Netflix ("MENIKAHFIX"); logo "N" berisiko merek dagang bila dijual.
2. **Paket & pembayaran** — mis. Midtrans atau Xendit.
3. **Pelengkap akun** — lupa kata sandi, verifikasi email, daftar tamu tersimpan (saat ini link tamu hanya dibuat di browser, tidak disimpan).

## Pekerjaan Rumah yang Diketahui

- Ucapan langsung tampil (`is_approved` default `true`) dan endpoint RSVP/ucapan belum dibatasi (`throttle`).
- `.gitignore` hanya berisi `.env` dan `/vendor`; `node_modules`, `public/build`, dan log belum dikecualikan.
- Tailwind masih dari CDN; Vite + Tailwind 4 sudah ada di `package.json` tetapi belum dipakai.
- `public/images/hero.jpg` (gambar sampul cadangan) tidak ada.
- Tombol "Buka Maps" tetap tampil saat koordinat kosong.
- File sisa yang bisa dihapus: `public/js/app copy.js`, `resources/views/invitation/show.blade copy.php`, `resources/views/layouts/applama.blade.php`, `resources/views/test.blade.php`, `database/seeders/InvitationSeederlama.php`, folder `cobaa/`, `app/Http/Controllers/GalleryController.php` (kosong).

## File Penting

- `routes/web.php` — binding slug dan urutan route
- `app/Models/Invitation.php` — pembuatan slug, relasi
- `app/Http/Controllers/InvitationController.php` — halaman undangan publik (load tema)
- `app/Http/Controllers/Dashboard/` — kelola undangan, galeri, moderasi ucapan
- `app/Http/Requests/SaveInvitationRequest.php` — validasi undangan + tema
- `resources/views/themes/{theme}/show.blade.php` — layout per tema
- `resources/views/layouts/app.blade.php`, `resources/views/invitation/show.blade.php` — tampilan undangan
- `resources/views/dashboard/invitations/_form.blade.php` — kolom form undangan (buat & edit)
- `app/Services/GoogleDriveAlbum.php` — daftar foto album dari Drive; `app/Support/QrCode.php` — pembuat QR
- `tests/Feature/` — `InvitationTest` (publik), `AuthTest`, `DashboardTest` (kepemilikan), `AttendanceTest`, `AlbumTest`; harus tetap lulus
