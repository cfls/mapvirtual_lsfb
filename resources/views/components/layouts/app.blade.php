<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Carte LSFB — Decouvrez la Belgique en langue des signes' }}</title>
    <meta name="description" content="Découvrez les musées et lieux culturels de Belgique avec des vidéos en LSFB, des informations accessibles et une expérience pensée pour les personnes sourdes.">
    <link rel="canonical" href="{{ url('/') }}">
    <link
            rel="icon"
            type="image/png"
            href="{{ asset('images/cfls.png') }}"
    >
    <link
            rel="apple-touch-icon"
            href="{{ asset('images/cfls.png') }}"
    >
    <!-- Open Graph (Facebook, LinkedIn, etc.) -->
    <meta property="og:type" content="website">
    <meta
            property="og:title"
            content="Visite Museum — Découvrez les musées en LSFB"
    >
    <meta
            property="og:description"
            content="Explorez les musées et lieux culturels de Belgique grâce à des vidéos en LSFB et des informations accessibles."
    >
    <meta
            property="og:image"
            content="{{ asset('images/visite-museum-lsfb.png') }}"
    >
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="fr_BE">
    <meta property="og:site_name" content="Visite Museum">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta
            name="twitter:title"
            content="Visite Museum — Découvrez les musées en LSFB"
    >
    <meta
            name="twitter:description"
            content="Explorez les musées et lieux culturels de Belgique avec des vidéos en LSFB et des informations accessibles."
    >
    <meta
            name="twitter:image"
            content="{{ asset('images/visite-museum-lsfb.png') }}"
    >
    {{-- Apply saved accessibility preference before first paint, to avoid a flash of the wrong theme --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('lsfb-contrast') === 'high') {
                    document.documentElement.setAttribute('data-contrast', 'high');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://unpkg.com/maplibre-gl@4.5.0/dist/maplibre-gl.css" rel="stylesheet" />
    <link href="https://unpkg.com/cloudinary-video-player@2/dist/cld-video-player.min.css" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
<header class="border-b border-[var(--ink-800)] bg-[var(--ink-900)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">


            <a href="/">
                <img src="{{ asset('images/cfls.png') }}" alt="CFLS" class="w-20">
            </a>

{{--        <a href="/" class="flex items-center gap-2">--}}
{{--            <span class="font-display text-xl tracking-tight text-[var(--paper)]">CFLS</span>--}}
{{--            <span class="font-mono-label text-[10px] text-[var(--paper-muted)] hidden sm:inline">Centre Federal LSFB</span>--}}
{{--        </a>--}}
    </div>
</header>

{{ $slot }}

<footer class="border-t border-[var(--ink-800)] mt-8 sm:mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <span class="font-mono-label text-[11px] text-[var(--paper-muted)]">
                @php($currentYear = date('Y'))
                &copy; {{ $currentYear > 2026 ? '2026–'.$currentYear : '2026' }} CFLS. Tous droits reserves.
            </span>
        <span class="font-mono-label text-[11px] text-[var(--paper-muted)]">
                Carte LSFB — Belgique
            </span>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
{{-- MapLibre GL renders the Shortbread vector tiles; the bridge plugin lets it plug into our existing Leaflet map/markers --}}
<script src="https://unpkg.com/maplibre-gl@4.5.0/dist/maplibre-gl.js"></script>
<script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.22/leaflet-maplibre-gl.js"></script>
<script src="https://unpkg.com/cloudinary-video-player@2/dist/cld-video-player.min.js"></script>

@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>
</body>
</html>