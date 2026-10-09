{{-- FILE: resources/views/dashboard/invitations/_form.blade.php --}}
{{-- Kolom form undangan, dipakai oleh halaman buat & edit. $invitation = null saat membuat baru. --}}
@php
    $invitation ??= null;

    // [name, label, type, placeholder / accept, wajib?]
    $sections = [
        'Mempelai Pria' => [
            ['groom_name',      'Nama Panggilan',  'text', 'Contoh: Desi', true],
            ['groom_full_name', 'Nama Lengkap',    'text', 'Nama lengkap beserta gelar', true],
            ['groom_father',    'Nama Ayah',       'text', 'Nama ayah mempelai pria', true],
            ['groom_mother',    'Nama Ibu',        'text', 'Nama ibu mempelai pria', true],
            ['groom_photo',     'Foto (JPG/PNG, maks. 2 MB)', 'file', 'image/jpeg,image/png', false],
        ],
        'Mempelai Wanita' => [
            ['bride_name',      'Nama Panggilan',  'text', 'Contoh: Zaky', true],
            ['bride_full_name', 'Nama Lengkap',    'text', 'Nama lengkap beserta gelar', true],
            ['bride_father',    'Nama Ayah',       'text', 'Nama ayah mempelai wanita', true],
            ['bride_mother',    'Nama Ibu',        'text', 'Nama ibu mempelai wanita', true],
            ['bride_photo',     'Foto (JPG/PNG, maks. 2 MB)', 'file', 'image/jpeg,image/png', false],
        ],
        'Akad Nikah' => [
            ['akad_date',       'Tanggal & Jam (WIB)', 'datetime-local', '', true],
            ['akad_location',   'Nama Tempat',     'text', 'Contoh: Masjid Al-Ikhlas', true],
            ['akad_address',    'Alamat',          'text', 'Alamat lengkap lokasi akad', true],
        ],
        'Resepsi' => [
            ['reception_date',     'Tanggal & Jam (WIB)', 'datetime-local', '', true],
            ['reception_location', 'Nama Tempat',   'text', 'Contoh: Gedung Serbaguna', true],
            ['reception_address',  'Alamat',        'text', 'Alamat lengkap lokasi resepsi', true],
            ['maps_lat',           'Latitude Lokasi (opsional)',  'text', 'Contoh: -7.7126', false],
            ['maps_lng',           'Longitude Lokasi (opsional)', 'text', 'Contoh: 109.0138', false],
        ],
        'Media' => [
            ['hero_image', 'Foto Sampul (JPG/PNG, maks. 3 MB)', 'file', 'image/jpeg,image/png', false],
            ['music_file', 'Musik Latar (MP3/WAV, maks. 5 MB)', 'file', 'audio/mpeg,audio/wav', false],
        ],
        'Album Foto Acara' => [
            ['drive_folder_url', 'Link Folder Google Drive (opsional)', 'url', 'https://drive.google.com/drive/folders/...', false],
        ],
    ];

    // Keterangan tambahan di bawah judul bagian
    $notes = [
        'Album Foto Acara' => 'Foto tetap tersimpan di Google Drive Anda. Atur folder ke "Siapa saja yang memiliki link" → "Pelihat" agar bisa dilihat tamu.',
    ];
@endphp

@csrf

@if ($invitation)
    <h3 class="font-serif text-lg text-white border-b border-white/10 pb-2">Pengaturan</h3>

    <div>
        <label for="theme" class="label">Tema <span class="text-red-400">*</span></label>
        <select id="theme" name="theme" required class="field">
            @foreach (\App\Models\Invitation::AVAILABLE_THEMES as $key => $label)
                <option value="{{ $key }}" @selected(old('theme', $invitation->theme ?? 'netflix') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('theme') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="slug" class="label">Alamat Undangan <span class="text-red-400">*</span></label>
        <div class="flex items-center gap-2">
            <span class="text-sm shrink-0">{{ url('/') }}/</span>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $invitation->slug) }}" required class="field">
        </div>
        <p class="text-xs ml-1 mt-2">Huruf kecil, angka, dan tanda hubung. Link lama tidak berlaku lagi setelah alamat diganti.</p>
        @error('slug') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm text-white/80 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" class="accent-[#d4af37]" @checked(old('is_active', $invitation->is_active))>
        Undangan aktif (bisa dibuka oleh tamu)
    </label>
@endif

@foreach ($sections as $title => $fields)
    <h3 class="font-serif text-lg text-white pt-4 border-b border-white/10 pb-2">{{ $title }}</h3>
    @isset($notes[$title]) <p class="text-xs ml-1">{{ $notes[$title] }}</p> @endisset

    <div class="grid sm:grid-cols-2 gap-5">
        @foreach ($fields as [$name, $label, $type, $hint, $required])
            <div>
                <label for="{{ $name }}" class="label">
                    {{ $label }} @if ($required)<span class="text-red-400">*</span>@endif
                </label>

                @if ($type === 'file')
                    <input type="file" id="{{ $name }}" name="{{ $name }}" accept="{{ $hint }}" class="field-file">
                    @if ($invitation?->$name)
                        <p class="text-xs ml-1 mt-2">Sudah ada file. Kosongkan bila tidak ingin mengganti.</p>
                    @endif
                @else
                    @php
                        $value = $invitation?->$name;
                        if ($type === 'datetime-local') $value = $value?->format('Y-m-d\TH:i');
                    @endphp
                    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
                        placeholder="{{ $hint }}" @required($required) class="field">
                @endif

                @error($name) <p class="field-error">{{ $message }}</p> @enderror
            </div>
        @endforeach
    </div>
@endforeach
