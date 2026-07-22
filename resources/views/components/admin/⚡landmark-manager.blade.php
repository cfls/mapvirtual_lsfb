<?php

use App\Models\Landmark;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // ---- list state ----
    public string $search = '';

    // ---- form state ----
    public bool $formOpen = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $slug = '';
    public string $region = '';
    public string $province = '';
    public string $age_range = '';
    public bool $lsfb_accessible = false;
    public bool $qr_accessible = false;
    public string $excerpt = '';
    public string $description = '';
    public string $image_url = '';
    public string $website_url = '';
    public string $cloudinary_public_id = '';
    public string $cloudinary_cloud_name = 'dmhdsjmzf';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?float $pos_x = 0;
    public ?float $pos_y = 0;
    public int $sort_order = 0;

    /**
     * Values offered in the province dropdown — the 10 Belgian provinces
     * plus Bruxelles-Capitale.
     */
    public const PROVINCES = [
        'Anvers',
        'Brabant flamand',
        'Brabant wallon',
        'Bruxelles-Capitale',
        'Flandre occidentale',
        'Flandre orientale',
        'Hainaut',
        'Liège',
        'Limbourg',
        'Luxembourg',
        'Namur',
    ];

    public const REGIONS = [
        'Bruxelles',
        'Flandre',
        'Wallonie',
    ];


    public const AGE_RANGES = [
        'Tous ages',
        'Enfants (-12 ans)',
        'Ados (12-17 ans)',
        'Adultes (18+)',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landmarks,slug,'.$this->editingId,
            'region' => 'required|string|in:'.implode(',', self::REGIONS),
            'province' => 'required|string|max:255',
            'age_range' => 'nullable|string|max:255',
            'lsfb_accessible' => 'boolean',
            'qr_accessible' => 'boolean',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'website_url' => 'nullable|url|max:2048',
            'cloudinary_public_id' => 'nullable|string|max:255',
            'cloudinary_cloud_name' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'pos_x' => 'nullable|numeric',
            'pos_y' => 'nullable|numeric',
            'sort_order' => 'integer|min:0',
        ];
    }



    /**
     * The paginated, searchable list consumed by the template
     * as $this->landmarks.
     */
    #[Computed]
    public function landmarks()
    {
        return Landmark::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('province', 'like', "%{$this->search}%")))
            ->orderBy('sort_order')
            ->paginate(10);
    }

    /**
     * Back to page 1 whenever the search term changes, otherwise the
     * user can end up stranded on a page that no longer exists.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Auto-generate the slug from the name while creating (not while
     * editing, so existing URLs never change unexpectedly).
     */
    public function updatedName(string $value): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->sort_order = (int) (Landmark::max('sort_order') + 1);
        $this->formOpen = true;
    }

    public function openEdit(int $id): void
    {
        $landmark = Landmark::findOrFail($id);

        $this->editingId = $landmark->id;
        $this->name = $landmark->name;
        $this->slug = $landmark->slug;
        $this->region = $landmark->region ?? '';
        $this->province = $landmark->province ?? '';
        $this->age_range = $landmark->age_range ?? '';
        $this->lsfb_accessible = (bool) $landmark->lsfb_accessible;
        $this->qr_accessible = (bool) $landmark->qr_accessible;
        $this->excerpt = $landmark->excerpt ?? '';
        $this->description = $landmark->description ?? '';
        $this->image_url = $landmark->image_url ?? '';
        $this->website_url = $landmark->website_url ?? '';
        $this->cloudinary_public_id = $landmark->cloudinary_public_id ?? '';
        $this->cloudinary_cloud_name = $landmark->cloudinary_cloud_name ?? '';
        $this->latitude = $landmark->latitude;
        $this->longitude = $landmark->longitude;
        $this->pos_x = $landmark->pos_x;
        $this->pos_y = $landmark->pos_y;
        $this->sort_order = $landmark->sort_order;

        $this->formOpen = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        // pos_x / pos_y are legacy fields (percentage positions from the
        // old SVG map). Default them to 0 so inserts don't fail while the
        // columns still exist.
        $data['pos_x'] = $data['pos_x'] ?? 0;
        $data['pos_y'] = $data['pos_y'] ?? 0;

        Landmark::updateOrCreate(['id' => $this->editingId], $data);

        $this->formOpen = false;
        $this->resetForm();
        session()->flash('status', 'Lieu enregistré avec succès.');
    }

    public function delete(int $id): void
    {
        Landmark::findOrFail($id)->delete();
        session()->flash('status', 'Lieu supprimé.');
    }

    public function cancel(): void
    {
        $this->formOpen = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingId',
            'name',
            'slug',
            'region',
            'province',
            'age_range',
            'lsfb_accessible',
            'qr_accessible',
            'excerpt',
            'description',
            'image_url',
            'website_url',
            'cloudinary_public_id',
            'cloudinary_cloud_name',
            'latitude',
            'longitude',
            'pos_x',
            'pos_y',
            'sort_order',
        ]);
        $this->resetValidation();
    }

    public function updateOrder(array $items): void
    {
        foreach ($items as $item) {
            Landmark::where('id', $item['value'])->update(['sort_order' => $item['order']]);
        }
    }
};
?>

@php
    /**
     * Shared field styles — defined once so all 15 fields stay
     * consistent and easy to retheme later.
     *
     * Labels: gold, uppercase, mono-spaced — clearly visible.
     * Inputs: thicker slate border, gold on hover, gold ring on focus.
     */
    $label = 'mb-1 block text-[11px] font-semibold uppercase tracking-wider text-amber-600';
    $input = 'w-full rounded-lg border-2 border-slate-300 bg-white text-sm text-gray-900 shadow-sm transition
              hover:border-amber-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none';
    $error = 'mt-1 text-xs font-medium text-rose-600';
@endphp

<div class="mx-auto max-w-6xl space-y-6 p-6">

    {{-- Flash --}}
    @if (session('status'))
        <div class="rounded-lg border-2 border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-baseline gap-3">
            <h1 class="text-xl font-semibold text-gray-900">Lieux</h1>
            <span class="text-xs text-gray-400">Connecté : {{ auth()->user()->name }}</span>
        </div>

        <div class="flex items-center gap-3">
            <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par nom ou province…"
                    class="w-64 rounded-lg border-2 border-slate-300 text-sm shadow-sm transition hover:border-amber-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none"
            />
            <button
                    type="button"
                    wire:click="openCreate"
                    class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
            >
                + Nouveau lieu
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-medium text-gray-500 hover:text-red-600 whitespace-nowrap">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-amber-50 text-left text-xs font-semibold uppercase tracking-wide text-amber-700">
            <tr>
                <th class="px-4 py-3">Ordre</th>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Province</th>
                <th class="px-4 py-3">Région</th>
                <th class="px-4 py-3">LSFB</th>
                <th  class="px-4 py-3">QR</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" wire:sortable="updateOrder">
            @forelse ($this->landmarks as $landmark)
                <tr
                        wire:key="landmark-{{ $landmark->id }}"
                        wire:sortable.item="{{ $landmark->id }}"
                        class="hover:bg-amber-50/40"
                >
                    <td class="px-4 py-3 text-gray-500">
                        <span wire:sortable.handle class="cursor-grab select-none inline-flex items-center gap-2" title="Glisser pour réordonner">
                            <span class="text-slate-400">⠿</span>
                            {{ $landmark->sort_order }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $landmark->name }}</div>
                        <div class="text-xs text-gray-500">{{ $landmark->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $landmark->province }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $landmark->region }}</td>
                    <td class="px-4 py-3">
                        @if ($landmark->lsfb_accessible)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Oui</span>
                        @else
                            <span class="inline-flex rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700">Non</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($landmark->qr_accessible)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Oui</span>
                        @else
                            <span class="inline-flex rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700">Non</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button
                                type="button"
                                wire:click="openEdit({{ $landmark->id }})"
                                class="font-medium text-amber-600 hover:text-amber-500"
                        >
                            Modifier
                        </button>
                        <button
                                type="button"
                                wire:click="delete({{ $landmark->id }})"
                                wire:confirm="Supprimer « {{ $landmark->name }} » ? Cette action est irréversible."
                                class="ml-3 font-medium text-red-600 hover:text-red-500"
                        >
                            Supprimer
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                        Aucun lieu trouvé.
                        @if ($search !== '')
                            Essayez un autre terme de recherche.
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $this->landmarks->links() }}
    </div>

    {{-- Form modal --}}
    @if ($formOpen)
        <div
                class="fixed inset-0 z-50 overflow-y-auto"
                role="dialog"
                aria-modal="true"
                x-data
                x-on:keydown.escape.window="$wire.cancel()"
        >
            {{-- backdrop: puramente visual ahora, ya NO cierra al hacer clic --}}
            <div class="fixed inset-0 bg-gray-900/50"></div>

            <div class="relative mx-auto my-8 w-full max-w-3xl rounded-xl bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center justify-between border-b-2 border-amber-400 pb-2">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $editingId ? 'Modifier le lieu' : 'Nouveau lieu' }}
                    </h2>
                    <button
                            type="button"
                            wire:click="cancel"
                            class="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700"
                            aria-label="Fermer"
                    >
                        &times;
                    </button>
                </div>

                <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <label class="{{ $label }}">Nom *</label>
                        <input type="text" wire:model.live.debounce.300ms="name" class="{{ $input }}" />
                        @error('name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Slug *</label>
                        <input type="text" wire:model="slug" class="{{ $input }}" />
                        @error('slug') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Région *</label>
                        <select wire:model="region" class="{{ $input }}">
                            <option value="">— Choisir —</option>
                            @foreach (self::REGIONS as $regionOption)
                                <option value="{{ $regionOption }}">{{ $regionOption }}</option>
                            @endforeach
                        </select>
                        @error('region') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Province *</label>
                        <select wire:model="province" class="{{ $input }}">
                            <option value="">— Choisir —</option>
                            @foreach (self::PROVINCES as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                        @error('province') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Tranche d'âge</label>
                        <select wire:model="age_range" class="{{ $input }}">
                            <option value="">— Aucune —</option>
                            @foreach (self::AGE_RANGES as $range)
                                <option value="{{ $range }}">{{ $range }}</option>
                            @endforeach
                        </select>
                        @error('age_range') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap items-end gap-3 pb-2">
                        <label class="inline-flex items-center gap-2 rounded-lg border-2 border-slate-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-amber-500">
                            <input type="checkbox" wire:model="lsfb_accessible"
                                   class="rounded border-slate-400 text-emerald-600 focus:ring-emerald-500" />
                            Accessible en LSFB (interprète/guide)
                        </label>

                        <label class="inline-flex items-center gap-2 rounded-lg border-2 border-slate-300 px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-amber-500">
                            <input type="checkbox" wire:model="qr_accessible"
                                   class="rounded border-slate-400 text-emerald-600 focus:ring-emerald-500" />
                            Accessible via QR code
                        </label>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="{{ $label }}">Extrait</label>
                        <textarea wire:model="excerpt" rows="2" maxlength="500" class="{{ $input }}"></textarea>
                        @error('excerpt') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="{{ $label }}">Description</label>
                        <textarea wire:model="description" rows="4" class="{{ $input }}"></textarea>
                        @error('description') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">URL de l'image</label>
                        <input type="url" wire:model="image_url" class="{{ $input }}" />
                        @error('image_url') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Site web</label>
                        <input type="url" wire:model="website_url" class="{{ $input }}" />
                        @error('website_url') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Cloudinary public ID</label>
                        <input type="text" wire:model="cloudinary_public_id" class="{{ $input }}" value="{{ old('cloudinary_public_id') }}" />
                        @error('cloudinary_public_id') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Cloudinary cloud name</label>
                        <input type="text" wire:model="cloudinary_cloud_name" class="{{ $input }}" />
                        @error('cloudinary_cloud_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Latitude *</label>
                        <input type="number" step="any" wire:model="latitude" class="{{ $input }}" />
                        @error('latitude') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="{{ $label }}">Longitude *</label>
                        <input type="number" step="any" wire:model="longitude" class="{{ $input }}" />
                        @error('longitude') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 rounded-lg border-2 border-amber-200 bg-amber-50 px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700 mb-1">
                            💡 Comment trouver latitude / longitude ?
                        </p>
                        <p class="text-sm text-gray-700">
                            Cherchez le lieu sur <a href="https://maps.google.com" target="_blank" rel="noopener" class="font-medium text-amber-700 underline">Google Maps</a>,
                            faites un <b>clic droit</b> sur le point exact (l'entrée du musée, le monument) —
                            les coordonnées apparaissent en première ligne (ex. <code class="rounded bg-white px-1">50.89497, 4.34158</code>).
                            Cliquez dessus pour les copier : le premier nombre est la <b>latitude</b>, le second la <b>longitude</b>.
                            Utilisez le point décimal (50.8467), pas la virgule. Le lien "Itinéraire" du site est généré automatiquement à partir de ces coordonnées.
                        </p>
                    </div>

                    <div>
                        <label class="{{ $label }}">Ordre d'affichage</label>
                        <input type="number" min="0" wire:model="sort_order" class="{{ $input }}" />
                        @error('sort_order') <p class="{{ $error }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-2 flex items-center justify-end gap-3 sm:col-span-2">
                        <button type="button" wire:click="cancel"
                                class="rounded-lg border-2 border-slate-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-amber-500 hover:bg-amber-50">
                            Annuler
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-amber-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-400"
                                wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Enregistrer</span>
                            <span wire:loading wire:target="save">Enregistrement…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>