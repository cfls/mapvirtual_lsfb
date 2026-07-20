import '../css/app.css';

/**
 * Leaflet map (real GPS coordinates).
 *
 * The container (#lsfb-leaflet-map) is marked wire:ignore in the blade
 * view, so Livewire never touches its internals after the first paint —
 * Leaflet owns that DOM completely. We read the landmark list (id, name,
 * lat, lng) from its data-landmarks attribute, place one marker per
 * landmark, and on click dispatch a Livewire event instead of using
 * wire:click (the markers aren't part of Livewire's tracked DOM).
 */
/**
 * Simplified Belgium border (public domain, Natural Earth via
 * johan/world.geo.json). Kept here (unused for drawing) since it's
 * still handy for fitBounds — the Shortbread basemap already renders
 * country borders clearly, so we don't draw our own on top anymore.
 */
const BELGIUM_BORDER = [
    [3.314971, 51.345781], [4.047071, 51.267259], [4.973991, 51.475024],
    [5.606976, 51.037298], [6.156658, 50.803721], [6.043073, 50.128052],
    [5.782417, 50.090328], [5.674052, 49.529484], [4.799222, 49.985373],
    [4.286023, 49.907497], [3.588184, 50.378992], [3.123252, 50.780363],
    [2.658422, 50.796848], [2.513573, 51.148506], [3.314971, 51.345781],
];

function initLeafletMap() {
    const el = document.getElementById('lsfb-leaflet-map');
    if (!el || !window.L || el.dataset.leafletReady) return;
    el.dataset.leafletReady = 'true';

    let landmarks = [];
    try {
        landmarks = JSON.parse(el.dataset.landmarks || '[]');
    } catch (e) {
        console.error('Could not parse landmarks data for the map', e);
    }

    const map = window.L.map(el, {
        scrollWheelZoom: false,
    });

    const belgiumBounds = window.L.latLngBounds(
        BELGIUM_BORDER.map(([lng, lat]) => [lat, lng]),
    );
    map.fitBounds(belgiumBounds, { padding: [8, 8] });
    map.setZoom(map.getZoom() + 0.6);

    // Shortbread vector tiles (OpenStreetMap's official vector tile schema),
    // rendered with the free VersaTiles "Colorful" style — no API key
    // needed. MapLibre GL does the actual rendering; maplibre-gl-leaflet
    // plugs it into our existing Leaflet map/markers/panes.
    window.L.maplibreGL({
        style: 'https://tiles.versatiles.org/assets/styles/colorful/style.json',
    }).addTo(map);

    map.attributionControl.addAttribution(
        '<a href="https://versatiles.org" target="_blank" rel="noopener">VersaTiles</a> / ' +
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    );

    const markersById = {};

    const pinIcon = () => window.L.divIcon({
        className: '',
        html: `
            <div class="leaflet-pin" data-active="false">
                <svg class="leaflet-pin__svg" viewBox="0 0 32 40" width="32" height="40">
                    <circle class="leaflet-pin__pulse" cx="16" cy="16" r="11"></circle>
                    <path class="leaflet-pin__teardrop" d="M16 0C7.163 0 0 7.163 0 16c0 11 16 24 16 24s16-13 16-24C32 7.163 24.837 0 16 0z" />
                    <circle class="leaflet-pin__circle" cx="16" cy="16" r="6" />
                </svg>
            </div>
        `,
        iconSize: [32, 40],
        iconAnchor: [16, 40],
    });

    landmarks.forEach((landmark) => {
        if (!landmark.lat || !landmark.lng) return;

        const marker = window.L.marker([landmark.lat, landmark.lng], { icon: pinIcon() }).addTo(map);

        marker.bindTooltip(landmark.name, {
            direction: 'top',
            offset: [0, -38],
            className: 'leaflet-pin-tooltip',
        });

        marker.on('click', () => {
            window.Livewire.dispatch('landmark-clicked', { id: landmark.id });
        });

        markersById[landmark.id] = marker;
    });

    // Highlight the active marker when Livewire tells us the selection
    // changed (see the "landmark-selected" listener below).
    window.updateActiveLeafletMarker = (activeId) => {
        Object.entries(markersById).forEach(([id, marker]) => {
            const markerEl = marker.getElement();
            const pin = markerEl && markerEl.querySelector('.leaflet-pin');
            if (pin) pin.dataset.active = String(Number(id) === Number(activeId));
        });
    };
}

/**
 * Cloudinary Video Player integration.
 *
 * The <video id="lsfb-video-player"> element carries `wire:key="player-{id}"`,
 * so Livewire replaces it with a brand-new DOM node on every landmark
 * change — we just mount a fresh cloudinary.videoPlayer() on the new
 * node each time rather than reconfiguring an existing instance.
 */
function mountCloudinaryPlayer() {
    const el = document.getElementById('lsfb-video-player');
    if (!el || !window.cloudinary) return;

    const cloudName = el.dataset.cloudName;
    const publicId = el.dataset.publicId;
    const poster = el.getAttribute('poster');
    if (!cloudName || !publicId) return;

    const player = window.cloudinary.videoPlayer(el, {
        cloud_name: cloudName,
        fluid: true,
        controls: true,
        poster: poster || undefined,
        colors: { accent: '#C9971E', base: '#FFFFFF', text: '#121826' },
    });

    player.source(publicId, { poster: poster || undefined });
}

document.addEventListener('livewire:init', () => {
    initLeafletMap();

    Livewire.on('landmark-selected', (event) => {
        if (window.updateActiveLeafletMarker) {
            window.updateActiveLeafletMarker(event.id);
        }
        requestAnimationFrame(mountCloudinaryPlayer);
    });
});