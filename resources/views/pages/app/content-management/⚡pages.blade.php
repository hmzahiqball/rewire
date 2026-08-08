<?php

use App\Concerns\AuthorizesContentManagement;
use App\Models\Page;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Pages')] class extends Component
{
    use AuthorizesContentManagement, WithFileUploads, WithPagination;

    public string $search = '';

    public ?Page $editingPage = null;

    public string $sectionKey = '';

    public string $title = '';

    public string $subtitle = '';

    public string $body = '';

    public string $extraDataJson = '';

    public bool $isPublished = true;

    public int $sortOrder = 0;

    public $pageImageUpload = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorizeCmsAction();

        $this->resetForm();

        Flux::modal('page-form')->show();
    }

    public function edit(int $pageId): void
    {
        $this->authorizeCmsAction();

        $page = Page::query()->findOrFail($pageId);

        $this->editingPage = $page;
        $this->sectionKey = $page->section_key;
        $this->title = $page->title;
        $this->subtitle = $page->subtitle ?? '';
        $this->body = $page->body ?? '';
        $this->extraDataJson = $page->extra_data ? json_encode($page->extra_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $this->isPublished = $page->is_published;
        $this->sortOrder = $page->sort_order;
        $this->pageImageUpload = null;

        Flux::modal('page-form')->show();
    }

    public function removePageImage(): void
    {
        $this->authorizeCmsAction();

        $this->editingPage?->clearMediaCollection('page_image');
    }

    public function save(): void
    {
        $this->authorizeCmsAction();

        $rules = [
            'sectionKey' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'extraDataJson' => ['nullable', 'string'],
            'sortOrder' => ['required', 'integer', 'min:0'],
            'pageImageUpload' => ['nullable', 'image', 'max:2048'],
        ];

        $this->validate($rules);

        $extraData = null;
        if ($this->extraDataJson) {
            $decoded = json_decode($this->extraDataJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('extraDataJson', 'Invalid JSON format.');

                return;
            }
            $extraData = $decoded;
        }

        $data = [
            'section_key' => $this->sectionKey,
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'body' => $this->body ?: null,
            'extra_data' => $extraData,
            'is_published' => $this->isPublished,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->editingPage === null) {
            $page = Page::create($data);
        } else {
            $page = $this->editingPage;
            $page->update($data);
        }

        if ($this->pageImageUpload) {
            $page->addMedia($this->pageImageUpload)->toMediaCollection('page_image');
        }

        Flux::toast(variant: 'success', text: 'Page section saved.');
        Flux::modal('page-form')->close();

        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingPage = null;
        $this->reset('sectionKey', 'title', 'subtitle', 'body', 'extraDataJson', 'isPublished', 'sortOrder', 'pageImageUpload');
        $this->isPublished = true;
    }

    public function delete(int $pageId): void
    {
        $this->authorizeCmsAction();

        $page = Page::query()->findOrFail($pageId);

        $title = $page->title;

        $page->delete();

        Flux::toast(variant: 'success', text: "\"{$title}\" was deleted.");
    }

    #[Computed]
    public function pages()
    {
        return Page::query()
            ->when($this->search, fn ($query) => $query->where('title', 'like', "%{$this->search}%")->orWhere('section_key', 'like', "%{$this->search}%"))
            ->orderBy('sort_order')
            ->paginate(10);
    }
}; ?>

<div class="w-full space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">Page Sections</flux:heading>
            <flux:subheading>Manage content sections displayed on the public site (hero, about, etc.).</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">New section</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by title or section key" icon="magnifying-glass" class="max-w-sm" />

    <flux:card class="w-full">
        <flux:table :paginate="$this->pages">
            <flux:table.columns>
                <flux:table.column>Section Key</flux:table.column>
                <flux:table.column>Title</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Order</flux:table.column>
                <flux:table.column>Updated</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->pages as $page)
                    <flux:table.row :key="$page->id">
                        <flux:table.cell>
                            <flux:badge color="zinc" size="sm">{{ $page->section_key }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $page->title }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($page->is_published)
                                <flux:badge color="lime" size="sm">Published</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">Draft</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $page->sort_order }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $page->updated_at->diffForHumans() }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <div class="flex items-center gap-2">
                                <flux:button type="button" variant="outline" size="sm" icon="pencil" wire:click="edit({{ $page->id }})" />

                                <flux:modal.trigger name="delete-page-{{ $page->id }}">
                                    <flux:button type="button" variant="danger" size="sm" icon="trash" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-page-{{ $page->id }}" class="max-w-md" focusable>
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Delete "{{ $page->title }}"?</flux:heading>
                                            <flux:subheading>This permanently removes this page section. This cannot be undone.</flux:subheading>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <flux:modal.close>
                                                <flux:button variant="filled">Cancel</flux:button>
                                            </flux:modal.close>

                                            <flux:button variant="danger" wire:click="delete({{ $page->id }})">
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

    <flux:modal flyout name="page-form" class="w-3xl" focusable>
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingPage ? 'Edit page section' : 'Create page section' }}</flux:heading>

            <flux:input wire:model="sectionKey" label="Section key" description="Unique identifier (e.g. hero, about, vision)" :disabled="$editingPage !== null" />

            <flux:input wire:model="title" label="Title" />

            <flux:input wire:model="subtitle" label="Subtitle" />

            <flux:textarea wire:model="body" label="Body" rows="8" />

            <flux:textarea wire:model="extraDataJson" label="Extra data (JSON)" rows="8" description="Additional structured data in JSON format (stats, steps, etc.)" />

            <x-media-upload
                wire:model="pageImageUpload"
                label="Page image"
                :current-url="$editingPage?->getFirstMediaUrl('page_image')"
                :new-upload="$pageImageUpload"
                remove-action="removePageImage"
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
