{{-- FILE: resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Indocreator</title>
    @include('layouts.partials.brand')
</head>
<body class="bg-brand-black text-brand-muted antialiased min-h-screen selection:bg-brand-gold selection:text-brand-black">

    <!-- Navigasi -->
    <header class="border-b border-white/5 bg-brand-dark">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="font-serif text-2xl text-white">
                Indocreator <span class="italic text-brand-gold text-base">Invitation</span>
            </a>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm text-white/70 truncate max-w-[12rem]">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-ghost !py-2">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-900 bg-green-900/30 px-4 py-3 text-sm text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-900 bg-red-900/30 px-4 py-3 text-sm text-red-200">
                <p>Masih ada data yang perlu diperbaiki:</p>
                <ul class="mt-1 pl-5 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center pb-8">
        <p class="font-serif italic text-white/20 text-sm">Indocreator © {{ date('Y') }}</p>
    </footer>

    @stack('scripts')
</body>
</html>
