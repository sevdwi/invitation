<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Indocreator Invitation')</title>
    @include('layouts.partials.brand')
</head>
<body class="bg-brand-black text-brand-muted antialiased min-h-screen flex flex-col items-center justify-center p-4 selection:bg-brand-gold selection:text-brand-black">
@yield('content')

    <!-- Footer Branding -->
    <footer class="mt-8 text-center pb-4">
        <p class="font-serif italic text-white/20 text-sm">Indocreator © {{ date('Y') }}</p>
    </footer>

</body>
</html>
