<?php

use App\Concerns\AuthorizesContentManagement;
use App\Models\GalleryItem;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Gallery')] class extends Component
{
    use AuthorizesContentManagement, WithFileUploads, WithPagination;

    public string $search = '';

    public ?GalleryItem $editingItem = null;

    public string $title = '';

    public string $description = '';

    public string $category = '';

    public bool $isPublished = true;

    public int $sortOrder = 0;

    public $galleryImageUpload = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorizeCmsAction();

        $this->resetForm();

        Flux::modal('gallery-form')->show();
    }

    public function edit(int $itemId): void
    {
        $this->authorizeCmsAction();

        $item = GalleryItem::query()->findOrFail($itemId);

        $this->editingItem = $item;
        $this->title = $item->title;
        $this->description = $item->description ?? '';
        $this->category = $item->category ?? '';
        $this->isPublished = $item->is_published;
        $this->sortOrder = $item->sort_order;
        $this->galleryImageUpload = null;

        Flux::modal('gallery-form')->show();
    }

    public function removeGalleryImage(): void
    {
        $this->authorizeCmsAction();

        $this->editingItem?->clearMediaCollection('gallery_image');
    }

    public function save(): void
    {
        $this->authorizeCmsAction();

        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:100'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'galleryImageUpload' => ['nullable', 'image', 'max:4096'],
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'category' => $this->category ?: null,
            'is_published' => $this->isPublished,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingItem === null) {
            $item = GalleryItem::create($data);
        } else {
            $item = $this->editingItem;
            $item->update($data);
        }

        if ($this->galleryImageUpload) {
            $item->addMedia($this->galleryImageUpload)->toMediaCollection('gallery_image');
        }

        Flux::toast(variant: 'success', text: 'Gallery item saved.');
        Flux::modal('gallery-form')->close();

        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingItem = null;
        $this->reset('title', 'description', 'category', 'isPublished', 'sortOrder', 'galleryImageUpload');
        $this->isPublished = true;
    }

    public function delete(int $itemId): void
    {
        $this->authorizeCmsAction();

        $item = GalleryItem::query()->findOrFail($itemId);

        $title = $item->title;

        $item->delete();

        Flux::toast(variant: 'success', text: "\"{$title}\" was deleted.");
    }

    #[Computed]
    public function items()
    {
        return GalleryItem::query()
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%")->orWhere('category', 'like', "%{$this->search}%"))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);
    }

    #[Computed]
    public function categories(): array
    {
        return GalleryItem::query()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->all();
    }
}; ?>

<div class="w-full space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">Gallery</flux:heading>
            <flux:subheading>Manage portfolio and gallery items.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">New item</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by title or category" icon="magnifying-glass" class="max-w-sm" />

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @foreach ($this->items as $item)
            <div wire:key="gallery-{{ $item->id }}" class="group relative overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm transition hover:shadow-md">
                @if ($item->getFirstMediaUrl('gallery_image', 'card'))
                    <img src="{{ $item->getFirstMediaUrl('gallery_image', 'card') }}" alt="{{ $item->title }}" class="aspect-square w-full object-cover">
                @else
                    <div class="flex aspect-square w-full items-center justify-center bg-zinc-100 text-zinc-300">
                        <flux:icon icon="photo" class="size-12" />
                    </div>
                @endif

                <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 transition group-hover:opacity-100">
                    <div class="w-full p-4">
                        <p class="text-sm font-medium text-white">{{ $item->title }}</p>
                        @if ($item->category)
                            <p class="mt-1 text-xs text-white/70">{{ $item->category }}</p>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <flux:button type="button" variant="filled" size="sm" icon="pencil" wire:click="edit({{ $item->id }})" />
                            <flux:modal.trigger name="delete-gallery-{{ $item->id }}">
                                <flux:button type="button" variant="danger" size="sm" icon="trash" />
                            </flux:modal.trigger>
                        </div>
                    </div>
                </div>

                @unless ($item->is_published)
                    <div class="absolute top-2 left-2">
                        <flux:badge color="zinc" size="sm">Draft</flux:badge>
                    </div>
                @endunless

                <flux:modal name="delete-gallery-{{ $item->id }}" class="max-w-md" focusable>
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Delete "{{ $item->title }}"?</flux:heading>
                            <flux:subheading>This permanently removes the gallery item. This cannot be undone.</flux:subheading>
                        </div>

                        <div class="flex justify-end gap-2">
                            <flux:modal.close>
                                <flux:button variant="filled">Cancel</flux:button>
                            </flux:modal.close>

                            <flux:button variant="danger" wire:click="delete({{ $item->id }})">
                                Delete
                            </flux:button>
                        </div>
                    </div>
                </flux:modal>
            </div>
        @endforeach
    </div>

    <div>
        {{ $this->items->links() }}
    </div>

    <flux:modal flyout name="gallery-form" class="w-3xl" focusable>
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingItem ? 'Edit gallery item' : 'Add gallery item' }}</flux:heading>

            <flux:input wire:model="title" label="Title" />

            <flux:textarea wire:model="description" label="Description" rows="3" />

            <flux:input wire:model="category" label="Category" description="E.g. Web App, Mobile App, UI/UX Design" />

            <x-media-upload
                wire:model="galleryImageUpload"
                label="Gallery image"
                :current-url="$editingItem?->getFirstMediaUrl('gallery_image')"
                :new-upload="$galleryImageUpload"
                remove-action="removeGalleryImage"
            />

            <flux:input wire:model="sortOrder" label="Sort order" type="number" min="0" />

            <flux:switch wire:model="isPublished" label="Published" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
