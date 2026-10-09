{{-- FILE: resources/views/dashboard/invitations/qr.blade.php --}}
{{-- QR daftar hadir siap cetak (latar putih) untuk diletakkan di meja penerima tamu --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Daftar Hadir — {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; background: #f3f1ec; color: #1a1512; font-family: 'Inter', sans-serif; }
        .toolbar { max-width: 560px; margin: 0 auto 16px; display: flex; gap: 8px; justify-content: space-between; }
        .toolbar a, .toolbar button {
            font: inherit; font-size: 13px; padding: 10px 16px; border-radius: 8px; cursor: pointer;
            border: 1px solid #1a1512; background: #fff; color: #1a1512; text-decoration: none;
        }
        .toolbar button { background: #1a1512; color: #fff; }
        .sheet {
            max-width: 560px; margin: 0 auto; padding: 48px 32px; text-align: center;
            background: #fff; border: 1px solid #d4af37; border-radius: 16px;
        }
        .eyebrow { font-size: 12px; letter-spacing: 5px; text-transform: uppercase; color: #9a7b1c; margin: 0 0 12px; }
        h1 { font-family: 'Playfair Display', serif; font-weight: 400; font-size: 40px; margin: 0; }
        .date { font-size: 14px; color: #6b655c; margin: 8px 0 28px; }
        .qr svg { width: 100%; max-width: 340px; height: auto; }
        .hint { font-size: 18px; font-weight: 600; margin: 24px 0 6px; }
        .sub { font-size: 14px; color: #6b655c; margin: 0; }
        .url { font-size: 12px; color: #6b655c; margin-top: 20px; word-break: break-all; }

        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .sheet { border: none; max-width: none; padding-top: 80px; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('dashboard.invitations.show', $invitation) }}">← Kembali</a>
        <button type="button" onclick="window.print()">Cetak</button>
    </div>

    <div class="sheet">
        <p class="eyebrow">Daftar Hadir</p>
        <h1>{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</h1>
        <p class="date">{{ $invitation->reception_date->isoFormat('dddd, D MMMM Y') }}</p>

        <div class="qr">{!! $qr !!}</div>

        <p class="hint">Scan untuk mengisi daftar hadir</p>
        <p class="sub">Arahkan kamera ponsel Anda ke kode di atas</p>
        <p class="url">{{ $url }}</p>
    </div>
</body>
</html>
