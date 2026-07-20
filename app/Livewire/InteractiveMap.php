<?php

namespace App\Livewire;

use App\Models\Landmark;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InteractiveMap extends Component
{
    public ?int $selectedLandmarkId = null;

    public bool $panelOpen = false;

    /**
     * 'all' shows every landmark; otherwise the exact province name
     * (matches Landmark::province, e.g. "Hainaut", "Flandre orientale").
     */
    public string $selectedProvince = 'all';

    /**
     * 'all' | 'yes' | 'no' — filters by whether the site itself has
     * on-site LSFB accessibility (QR code / interpreter), not to be
     * confused with our own video, which every landmark has.
     */
    public string $selectedAccessibility = 'all';

    /**
     * 'all' or the exact age_range string (e.g. "Ados (12-17 ans)").
     */
    public string $selectedAgeRange = 'all';

    /**
     * All pins shown on the map. Kept as a normal property (not computed)
     * so the collection is available both server and client side without
     * re-querying on every interaction.
     */
    public $landmarks;

    public function mount(): void
    {
        $this->landmarks = Landmark::ordered()->get();
    }

    public function selectLandmark(int $id): void
    {
        $this->selectedLandmarkId = $id;
        $this->panelOpen = true;

        // Lets the front-end (Alpine) know a new landmark was picked so it
        // can (re)initialise the Cloudinary player with the right source.
        $this->dispatch('landmark-selected', id: $id);
    }

    /**
     * Leaflet markers are plain JS objects living outside Livewire's DOM
     * (the map container is wire:ignore), so a marker click can't use
     * wire:click directly — it dispatches this browser event instead.
     */
    #[On('landmark-clicked')]
    public function onLandmarkClicked($id): void
    {
        $this->selectLandmark((int) $id);
    }

    public function closePanel(): void
    {
        $this->panelOpen = false;
        $this->selectedLandmarkId = null;
    }

    /**
     * Called automatically by Livewire when any filter <select> changes
     * (wire:model.live). The map itself is wire:ignore, so instead of
     * re-rendering markers we just tell the JS layer which landmark ids
     * should stay visible.
     */
    public function updatedSelectedProvince(): void
    {
        $this->applyFilters();
    }

    public function updatedSelectedAccessibility(): void
    {
        $this->applyFilters();
    }

    public function updatedSelectedAgeRange(): void
    {
        $this->applyFilters();
    }

    protected function applyFilters(): void
    {
        $this->dispatch('landmarks-filtered', ids: $this->filteredLandmarkIds());
    }

    protected function filteredLandmarkIds(): array
    {
        return $this->landmarks
            ->when($this->selectedProvince !== 'all', fn ($items) => $items->where('province', $this->selectedProvince))
            ->when($this->selectedAccessibility !== 'all', fn ($items) => $items->where('lsfb_accessible', $this->selectedAccessibility === 'yes'))
            ->when($this->selectedAgeRange !== 'all', fn ($items) => $items->where('age_range', $this->selectedAgeRange))
            ->pluck('id')
            ->all();
    }

    #[Computed]
    public function provinces(): array
    {
        return $this->landmarks->pluck('province')->filter()->unique()->sort()->values()->all();
    }

    #[Computed]
    public function ageRanges(): array
    {
        return $this->landmarks->pluck('age_range')->filter()->unique()->values()->all();
    }

    #[Computed]
    public function selectedLandmark(): ?Landmark
    {
        if (! $this->selectedLandmarkId) {
            return null;
        }

        return $this->landmarks->firstWhere('id', $this->selectedLandmarkId);
    }

    public function render()
    {
        return view('livewire.interactive-map');
    }
}