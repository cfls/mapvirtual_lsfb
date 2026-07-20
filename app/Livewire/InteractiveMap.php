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
