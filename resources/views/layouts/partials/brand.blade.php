{{-- FILE: resources/views/layouts/partials/brand.blade.php --}}
{{-- Font, Tailwind, dan palet warna bersama untuk halaman login & dashboard --}}
<!-- Google Fonts: Playfair Display (Luxury) & Inter (UI) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Tailwind Configuration for Custom Colors -->
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    serif: ['Playfair Display', 'serif'],
                },
                colors: {
                    // Custom Premium Palette
                    brand: {
                        black: '#0f0c0c',       // Deepest Black/Brown
                        dark: '#1a1512',        // Main Background (Coffee Bean)
                        surface: '#2b221c',     // Card Background (Dark Leather)
                        gold: '#d4af37',        // Metallic Gold
                        goldHover: '#b5952f',  // Darker Gold
                        muted: '#8c857b',      // Muted Text
                    }
                }
            }
        }
    }
</script>

<style type="text/tailwindcss">
    @layer components {
        .card      { @apply bg-brand-dark border border-white/5 rounded-2xl shadow-2xl; }
        .label     { @apply block text-xs uppercase tracking-wider text-brand-gold font-semibold ml-1 mb-2; }
        .field     { @apply w-full bg-brand-surface border border-white/10 rounded-lg px-4 py-3 text-white placeholder-brand-muted/40 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-all; }
        .field-file { @apply field file:mr-3 file:rounded file:border-0 file:bg-brand-gold file:px-3 file:py-1 file:text-xs file:font-semibold file:text-brand-black; }
        .btn-gold  { @apply inline-flex items-center justify-center bg-gradient-to-r from-brand-gold via-[#ebd076] to-brand-gold text-brand-black font-bold uppercase tracking-widest text-xs px-5 py-3 rounded-lg shadow-lg shadow-brand-gold/20 hover:brightness-110 transition-all duration-200; }
        .btn-ghost { @apply inline-flex items-center justify-center border border-white/10 bg-brand-surface text-white/80 uppercase tracking-widest text-xs px-4 py-3 rounded-lg hover:border-brand-gold hover:text-brand-gold transition-all duration-200; }
        .btn-danger { @apply inline-flex items-center justify-center border border-red-900 bg-red-900/30 text-red-200 uppercase tracking-widest text-xs px-4 py-3 rounded-lg hover:bg-red-900/60 transition-all duration-200; }
        .field-error { @apply text-xs text-red-400 ml-1 mt-2; }
    }
</style>

<style>
    /* Custom Scrollbar for Webkit */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #1a1512;
    }
    ::-webkit-scrollbar-thumb {
        background: #d4af37;
        border-radius: 4px;
    }

    /* Smooth inputs transition */
    input, select {
        transition: all 0.3s ease;
    }

    /* Gold gradient text helper */
    .text-gold-gradient {
        background: linear-gradient(45deg, #D4AF37, #F2D06B, #D4AF37);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
