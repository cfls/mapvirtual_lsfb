<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Carte LSFB — Decouvrez la Belgique en langue des signes' }}</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://unpkg.com/maplibre-gl@4.5.0/dist/maplibre-gl.css" rel="stylesheet" />
    <link href="https://unpkg.com/cloudinary-video-player@2/dist/cld-video-player.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
<header class="border-b border-[var(--ink-800)] bg-[var(--ink-900)]">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">


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

<footer class="border-t border-[var(--ink-800)] mt-12">
    <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
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
</body>
</html>