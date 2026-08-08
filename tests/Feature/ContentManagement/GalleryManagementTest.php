<?php

use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('admin can view the gallery list', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $item = GalleryItem::factory()->create(['title' => 'Portfolio Piece']);

    $this->actingAs($admin)
        ->get(route('content-management.gallery'))
        ->assertOk()
        ->assertSee($item->title);
});

test('admin can create a gallery item', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('create')
        ->set('title', 'New Gallery Item')
        ->set('description', 'A nice project')
        ->set('category', 'Web App')
        ->set('sortOrder', 1)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('gallery_items', [
        'title' => 'New Gallery Item',
        'category' => 'Web App',
    ]);
});

test('admin can edit a gallery item', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $item = GalleryItem::factory()->create(['title' => 'Original Item']);

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('edit', $item->id)
        ->set('title', 'Updated Item')
        ->set('category', 'Mobile App')
        ->call('save')
        ->assertHasNoErrors();

    expect($item->fresh()->title)->toBe('Updated Item');
    expect($item->fresh()->category)->toBe('Mobile App');
});

test('admin can delete a gallery item', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $item = GalleryItem::factory()->create();

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('delete', $item->id);

    $this->assertDatabaseMissing('gallery_items', ['id' => $item->id]);
});

test('gallery item requires title', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('create')
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title']);
});

test('uploading a gallery image creates media with conversions', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('create')
        ->set('title', 'Item with image')
        ->set('sortOrder', 0)
        ->set('galleryImageUpload', UploadedFile::fake()->image('photo.jpg', 800, 600))
        ->call('save')
        ->assertHasNoErrors();

    $item = GalleryItem::query()->where('title', 'Item with image')->firstOrFail();

    expect($item->getFirstMedia('gallery_image'))->not->toBeNull();
    expect($item->getFirstMediaUrl('gallery_image', 'thumb'))->not->toBe('');
    expect($item->getFirstMediaUrl('gallery_image', 'card'))->not->toBe('');
});

test('deleting a gallery item also deletes its media', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $item = GalleryItem::factory()->create();
    $item->addMedia(UploadedFile::fake()->image('photo.jpg'))->toMediaCollection('gallery_image');
    $mediaId = $item->getFirstMedia('gallery_image')->id;

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.gallery')
        ->call('delete', $item->id);

    $this->assertDatabaseMissing('media', ['id' => $mediaId]);
});
