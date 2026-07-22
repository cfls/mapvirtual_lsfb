<div class="min-h-screen bg-[var(--ink-950)] text-[var(--paper)]">

    <header class="max-w-7xl mx-auto px-6 pt-10 pb-6 flex flex-col gap-2">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <span class="font-mono-label text-xs text-[var(--gold)]">CARTE INTERACTIVE</span>

            <div class="flex items-center gap-3 flex-wrap">
                <label class="flex items-center gap-2 font-mono-label text-[11px]">
                    <span class="text-[var(--paper-muted)] sr-only sm:not-sr-only">PROVINCE</span>
                    <select
                            wire:model.live="selectedProvince"
                            class="bg-[var(--ink-900)] text-[var(--paper)] border border-[var(--ink-800)] rounded-full px-3 py-1.5 text-[11px] font-mono-label focus-visible:outline-none"
                    >
                        <option value="all">Toutes les provinces</option>
                        @foreach ($this->provinces as $province)
                            <option value="{{ $province }}">{{ $province }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="flex items-center gap-2 font-mono-label text-[11px]">
                    <span class="text-[var(--paper-muted)] sr-only sm:not-sr-only">ACCESSIBILITE</span>
                    <select
                            wire:model.live="selectedAccessibility"
                            class="bg-[var(--ink-900)] text-[var(--paper)] border border-[var(--ink-800)] rounded-full px-3 py-1.5 text-[11px] font-mono-label focus-visible:outline-none"
                    >
                        <option value="all">Accessibilite : tous</option>
                        <option value="yes">Accessible (LSFB ou QR)</option>
                        <option value="no">Non accessible</option>
                    </select>
                </label>

                <label class="flex items-center gap-2 font-mono-label text-[11px]">
                    <span class="text-[var(--paper-muted)] sr-only sm:not-sr-only">AGE</span>
                    <select
                            wire:model.live="selectedAgeRange"
                            class="bg-[var(--ink-900)] text-[var(--paper)] border border-[var(--ink-800)] rounded-full px-3 py-1.5 text-[11px] font-mono-label focus-visible:outline-none"
                    >
                        <option value="all">Tranche d'age : toutes</option>
                        @foreach ($this->ageRanges as $ageRange)
                            <option value="{{ $ageRange }}">{{ $ageRange }}</option>
                        @endforeach
                    </select>
                </label>

                <button
                        type="button"
                        x-data="{ high: document.documentElement.getAttribute('data-contrast') === 'high' }"
                        x-on:click="
                        high = !high;
                        document.documentElement.setAttribute('data-contrast', high ? 'high' : 'normal');
                        try { localStorage.setItem('lsfb-contrast', high ? 'high' : 'normal'); } catch (e) {}
                    "
                        :aria-pressed="high"
                        class="shrink-0 flex items-center gap-2 font-mono-label text-[11px] px-3 py-1.5 rounded-full border border-[var(--ink-800)] text-[var(--paper)] hover:border-[var(--gold)] transition-colors"
                >
                    <span class="w-2.5 h-2.5 rounded-full border-2 border-[var(--gold)]" :class="high ? 'bg-[var(--gold)]' : 'bg-transparent'"></span>
                    <span x-text="high ? 'CONTRASTE ELEVE : ACTIVE' : 'ACTIVER CONTRASTE ELEVE'"></span>
                </button>
            </div>
        </div>

        <h1 class="font-display text-4xl md:text-5xl leading-tight">
            Decouvrez la Belgique <span class="text-[var(--gold)]">en LSFB</span>
        </h1>
        <p class="text-[var(--paper-muted)] max-w-xl">
            Neuf lieux, neuf histoires racontees en langue des signes de Belgique francophone.
            Touchez un point sur la carte pour lancer la video.
        </p>

        {{-- color legend for the accessibility dots on the pins --}}
        <div class="flex items-center gap-4 mt-1">
            <span class="flex items-center gap-1.5 font-mono-label text-[10px] text-[var(--paper-muted)]">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#2F855A"></span>
                Accessible sur place (LSFB et/ou QR code)
            </span>
            <span class="flex items-center gap-1.5 font-mono-label text-[10px] text-[var(--paper-muted)]">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#C2477E"></span>
                Non accessible sur place
            </span>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 pb-16 grid grid-cols-1 lg:grid-cols-[1.3fr_1fr] gap-8 items-start">

        {{-- ============== MAP CANVAS (Leaflet, real GPS coordinates) ============== --}}
        <div class="relative z-0 w-full aspect-[4/5] md:aspect-[4/4.4] rounded-2xl border border-[var(--ink-800)] bg-[var(--ink-900)] overflow-hidden">

            {{--
                wire:ignore is essential here: Leaflet takes full control of
                this element's internal DOM (tiles, panes, markers). If
                Livewire tried to morph/diff it on every re-render, it would
                fight with Leaflet and break the map.
            --}}
            <div
                    id="lsfb-leaflet-map"
                    wire:ignore
                    class="absolute inset-0 w-full h-full"
                    data-landmarks="{{ $landmarks->map(fn ($l) => [
                    'id' => $l->id,
                    'name' => $l->name,
                    'lat' => $l->latitude,
                    'lng' => $l->longitude,
                    'accessible' => $l->lsfb_accessible || $l->qr_accessible,
                ])->filter(fn ($l) => $l['lat'] && $l['lng'])->values()->toJson() }}"
            ></div>
        </div>

        {{-- ============== DETAIL PANEL (desktop: sticky aside / mobile: centered modal) ============== --}}
        {{--
            lg:contents makes this wrapper disappear from the box model on
            desktop (it renders no box of its own), so <aside> becomes a
            direct grid child of <main> again — exactly like before. Only
            on mobile does the wrapper actually act as a fixed, centering
            overlay.
        --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 lg:contents {{ $panelOpen ? '' : 'pointer-events-none lg:pointer-events-auto' }}">

            {{-- backdrop (mobile only) --}}
            <div
                    wire:click="closePanel"
                    class="lg:hidden absolute inset-0 bg-black/50 transition-opacity duration-300 {{ $panelOpen ? 'opacity-100' : 'opacity-0' }}"
            ></div>

            <aside
                    class="detail-panel relative z-10
                       lg:sticky lg:top-10
                       w-full max-w-md lg:max-w-none
                       rounded-2xl border border-[var(--ink-800)] bg-[var(--ink-900)]
                       max-h-[85vh] lg:max-h-none overflow-y-auto
                       transition-all duration-300
                       {{ $panelOpen ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none' }}
                       lg:opacity-100 lg:scale-100 lg:pointer-events-auto"
            >
                @if ($this->selectedLandmark)
                    @php($landmark = $this->selectedLandmark)

                    <div class="p-6 flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="font-mono-label text-[11px] text-[var(--gold)]">{{ strtoupper($landmark->region) }}</span>
                                <h2 class="font-display text-2xl mt-1">{{ $landmark->name }}</h2>
                            </div>
                            <button wire:click="closePanel" class="lg:hidden shrink-0 w-8 h-8 rounded-full border border-[var(--ink-800)] flex items-center justify-center text-[var(--paper-muted)]" aria-label="Fermer">
                                &times;
                            </button>
                        </div>

                        {{-- accessibility + age range badges --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            @if ($landmark->lsfb_accessible)
                                <span class="flex items-center gap-1.5 font-mono-label text-[10px] px-2.5 py-1 rounded-full border" style="color:#2F855A; border-color:#2F855A;">
                                    <span class="w-2 h-2 rounded-full" style="background:#2F855A"></span>
                                    Accessible en LSFB (interprete/guide)
                                </span>
                            @endif

                            @if ($landmark->qr_accessible)
                                <span class="flex items-center gap-1.5 font-mono-label text-[10px] px-2.5 py-1 rounded-full border" style="color:#2F855A; border-color:#2F855A;">
                                    <span class="w-2 h-2 rounded-full" style="background:#2F855A"></span>
                                    Accessible via QR code
                                </span>
                            @endif

                            @if (! $landmark->lsfb_accessible && ! $landmark->qr_accessible)
                                <span class="flex items-center gap-1.5 font-mono-label text-[10px] px-2.5 py-1 rounded-full border" style="color:#C2477E; border-color:#C2477E;">
                                    <span class="w-2 h-2 rounded-full" style="background:#C2477E"></span>
                                    Non accessible sur place
                                </span>
                            @endif

                            @if ($landmark->age_range)
                                <span class="font-mono-label text-[10px] px-2.5 py-1 rounded-full border border-[var(--ink-800)] text-[var(--paper-muted)]">
                                    {{ $landmark->age_range }}
                                </span>
                            @endif
                        </div>

                        {{-- Cloudinary video player --}}
                        <video
                                id="lsfb-video-player"
                                wire:key="player-{{ $landmark->id }}"
                                class="cld-video-player w-full aspect-video"
                                data-cloud-name="{{ $landmark->cloudinary_cloud_name }}"
                                data-public-id="{{ $landmark->cloudinary_public_id }}"
                                poster="{{ $landmark->image_url }}"
                                controls
                        ></video>

                        <p class="text-sm text-[var(--paper-muted)] leading-relaxed">
                            {{ $landmark->description }}
                        </p>

                        {{-- external links: directions + official site --}}
                        <div class="flex items-center gap-2 pt-1">
                            @if ($landmark->google_maps_url)
                                <a
                                        href="{{ $landmark->google_maps_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center gap-1.5 font-mono-label text-[11px] px-3 py-1.5 rounded-full border border-[var(--ink-800)] text-[var(--paper)] hover:border-[var(--gold)] transition-colors"
                                >
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z" />
                                        <circle cx="12" cy="10" r="2.5" />
                                    </svg>
                                    Itineraire
                                </a>
                            @endif

                            @if ($landmark->website_url)
                                <a
                                        href="{{ $landmark->website_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center gap-1.5 font-mono-label text-[11px] px-3 py-1.5 rounded-full border border-[var(--ink-800)] text-[var(--paper)] hover:border-[var(--gold)] transition-colors"
                                >
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M3 12h18M12 3c2.5 2.7 3.8 6 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-6-3.8-9s1.3-6.3 3.8-9z" />
                                    </svg>
                                    Site officiel
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-6 flex flex-col items-center text-center gap-3 py-16">
                        <div class="w-10 h-10 rounded-full border border-[var(--gold)] border-opacity-40 flex items-center justify-center">
                            <span class="w-2 h-2 rounded-full bg-[var(--gold)]"></span>
                        </div>
                        <p class="font-display text-lg text-[var(--paper)]">Touchez un point sur la carte</p>
                        <p class="text-sm text-[var(--paper-muted)] max-w-xs">
                            Chaque repere ouvre une courte video en LSFB qui presente le lieu.
                        </p>
                    </div>
                @endif
            </aside>
        </div>
    </main>
</div>