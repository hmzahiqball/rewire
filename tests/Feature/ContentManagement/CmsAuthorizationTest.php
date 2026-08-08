<?php

use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

// ──────────────────────────────────────────────────────────────
// Route-level: staff cannot access any content-management page
// ──────────────────────────────────────────────────────────────

test('staff cannot access blog management page', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff)->get(route('content-management.blogs'))->assertForbidden();
});

test('staff cannot access services management page', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff)->get(route('content-management.services'))->assertForbidden();
});

test('staff cannot access pages management page', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff)->get(route('content-management.pages'))->assertForbidden();
});

test('staff cannot access gallery management page', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff)->get(route('content-management.gallery'))->assertForbidden();
});

// ──────────────────────────────────────────────────────────────
// Admin can access content-management pages
// ──────────────────────────────────────────────────────────────

test('admin can access blog management page', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin)->get(route('content-management.blogs'))->assertOk();
});

test('admin can access services management page', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin)->get(route('content-management.services'))->assertOk();
});

test('admin can access pages management page', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin)->get(route('content-management.pages'))->assertOk();
});

test('admin can access gallery management page', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin)->get(route('content-management.gallery'))->assertOk();
});

// ──────────────────────────────────────────────────────────────
// Livewire action-level: staff cannot create/edit/delete via Livewire
// ──────────────────────────────────────────────────────────────

test('staff cannot create a blog post via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.blogs')
        ->call('create')
        ->assertForbidden();
});

test('staff cannot save a blog post via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.blogs')
        ->set('title', 'Malicious Post')
        ->set('body', 'Injected content')
        ->call('save')
        ->assertForbidden();
});

test('staff cannot delete a blog post via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $post = Post::factory()->create();

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.blogs')
        ->call('delete', $post->id)
        ->assertForbidden();

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});

test('staff cannot create a service via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.services')
        ->call('create')
        ->assertForbidden();
});

test('staff cannot delete a service via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $service = Service::factory()->create();

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.services')
        ->call('delete', $service->id)
        ->assertForbidden();

    $this->assertDatabaseHas('services', ['id' => $service->id]);
});

test('staff cannot create a page section via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.pages')
        ->call('create')
        ->assertForbidden();
});

test('staff cannot delete a page section via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $page = Page::create([
        'section_key' => 'test_section',
        'title' => 'Test Page',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.pages')
        ->call('delete', $page->id)
        ->assertForbidden();

    $this->assertDatabaseHas('pages', ['id' => $page->id]);
});

test('staff cannot create a gallery item via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.gallery')
        ->call('create')
        ->assertForbidden();
});

test('staff cannot delete a gallery item via Livewire action', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $item = GalleryItem::factory()->create();

    $this->actingAs($staff);

    Livewire::test('pages::app.content-management.gallery')
        ->call('delete', $item->id)
        ->assertForbidden();

    $this->assertDatabaseHas('gallery_items', ['id' => $item->id]);
});

// ──────────────────────────────────────────────────────────────
// User Management: super-admin deletion protection
// ──────────────────────────────────────────────────────────────

test('admin cannot delete a super-admin user', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $superAdmin = User::factory()->create();
    $superAdmin->syncRoles(Role::findOrCreate('super-admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.users')
        ->call('delete', $superAdmin->id);

    $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
});

test('admin cannot change a super-admin role', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $superAdmin = User::factory()->create();
    $superAdmin->syncRoles(Role::findOrCreate('super-admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.users')
        ->call('edit', $superAdmin->id)
        ->set('editingRole', 'staff')
        ->call('updateRole');

    expect($superAdmin->fresh()->hasRole('super-admin'))->toBeTrue();
});
