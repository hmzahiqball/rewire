<?php

use App\Models\Service;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Services')] class extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public ?Service $editingService = null;

    public string $title = '';

    public string $icon = '';

    public string $shortDescription = '';

    public string $body = '';

    public bool $isFeatured = false;

    public bool $isPublished = true;

    public int $sortOrder = 0;

    public $serviceImageUpload = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();

        Flux::modal('service-form')->show();
    }

    public function edit(int $serviceId): void
    {
        $service = Service::query()->findOrFail($serviceId);

        $this->editingService = $service;
        $this->title = $service->title;
        $this->icon = $service->icon ?? '';
        $this->shortDescription = $service->short_description;
        $this->body = $service->body;
        $this->isFeatured = $service->is_featured;
        $this->isPublished = $service->is_published;
        $this->sortOrder = $service->sort_order;
        $this->serviceImageUpload = null;

        Flux::modal('service-form')->show();
    }

    public function removeServiceImage(): void
    {
        $this->editingService?->clearMediaCollection('service_image');
    }

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'shortDescription' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'serviceImageUpload' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'title' => $this->title,
            'icon' => $this->icon ?: null,
            'short_description' => $this->shortDescription,
            'body' => $this->body,
            'is_featured' => $this->isFeatured,
            'is_published' => $this->isPublished,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingService === null) {
            $service = Service::create($data);
        } else {
            $service = $this->editingService;
            $service->update($data);
        }

        if ($this->serviceImageUpload) {
            $service->addMedia($this->serviceImageUpload)->toMediaCollection('service_image');
        }

        Flux::toast(variant: 'success', text: 'Service saved.');
        Flux::modal('service-form')->close();

        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingService = null;
        $this->reset('title', 'icon', 'shortDescription', 'body', 'isFeatured', 'isPublished', 'sortOrder', 'serviceImageUpload');
        $this->isPublished = true;
    }

    public function delete(int $serviceId): void
    {
        $service = Service::query()->findOrFail($serviceId);

        $title = $service->title;

        $service->delete();

        Flux::toast(variant: 'success', text: "\"{$title}\" was deleted.");
    }

    #[Computed]
    public function services()
    {
        return Service::query()
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);
    }
}; ?>

<div class="w-full space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">Services</flux:heading>
            <flux:subheading>Manage company services displayed on the public site.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">New service</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by title" icon="magnifying-glass" class="max-w-sm" />

    <flux:card class="w-full">
        <flux:table :paginate="$this->services">
            <flux:table.columns>
                <flux:table.column>Image</flux:table.column>
                <flux:table.column>Title</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Featured</flux:table.column>
                <flux:table.column>Order</flux:table.column>
                <flux:table.column>Updated</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->services as $service)
                    <flux:table.row :key="$service->id">
                        <flux:table.cell class="py-2">
                            @if ($service->getFirstMediaUrl('service_image', 'thumb'))
                                <img src="{{ $service->getFirstMediaUrl('service_image', 'thumb') }}" alt="" class="h-10 w-16 rounded-lg object-cover">
                            @else
                                <div class="flex h-10 w-16 items-center justify-center rounded-lg bg-zinc-100 text-zinc-400">
                                    <flux:icon icon="photo" variant="micro" />
                                </div>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $service->title }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($service->is_published)
                                <flux:badge color="lime" size="sm">Published</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">Draft</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($service->is_featured)
                                <flux:badge color="amber" size="sm">Featured</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $service->sort_order }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $service->updated_at->diffForHumans() }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <div class="flex items-center gap-2">
                                <flux:button type="button" variant="outline" size="sm" icon="pencil" wire:click="edit({{ $service->id }})" />

                                <flux:modal.trigger name="delete-service-{{ $service->id }}">
                                    <flux:button type="button" variant="danger" size="sm" icon="trash" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-service-{{ $service->id }}" class="max-w-md" focusable>
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Delete "{{ $service->title }}"?</flux:heading>
                                            <flux:subheading>This permanently removes the service. This cannot be undone.</flux:subheading>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <flux:modal.close>
                                                <flux:button variant="filled">Cancel</flux:button>
                                            </flux:modal.close>

                                            <flux:button variant="danger" wire:click="delete({{ $service->id }})">
                                                Delete
                                            </flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal flyout name="service-form" class="w-3xl" focusable>
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingService ? 'Edit service' : 'Create service' }}</flux:heading>

            <flux:input wire:model="title" label="Title" />

            <flux:input wire:model="icon" label="Icon" description="Icon name (e.g. code, cloud, shield, brain, cog, compass)" />

            <flux:textarea wire:model="shortDescription" label="Short description" rows="3" />

            <flux:textarea wire:model="body" label="Body" rows="10" />

            <x-media-upload
                wire:model="serviceImageUpload"
                label="Service image"
                :current-url="$editingService?->getFirstMediaUrl('service_image')"
                :new-upload="$serviceImageUpload"
                remove-action="removeServiceImage"
            />

            <flux:input wire:model="sortOrder" label="Sort order" type="number" min="0" />

            <flux:switch wire:model="isFeatured" label="Featured" />

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
