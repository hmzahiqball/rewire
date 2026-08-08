<?php

use App\Models\Page;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('admin can view the pages list', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $page = Page::create([
        'section_key' => 'hero',
        'title' => 'Hero Section',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('content-management.pages'))
        ->assertOk()
        ->assertSee($page->title);
});

test('admin can create a page section', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('create')
        ->set('sectionKey', 'test_section')
        ->set('title', 'Test Section')
        ->set('subtitle', 'A subtitle')
        ->set('body', 'Section body content')
        ->set('sortOrder', 5)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pages', [
        'section_key' => 'test_section',
        'title' => 'Test Section',
        'subtitle' => 'A subtitle',
    ]);
});

test('admin can edit a page section', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $page = Page::create([
        'section_key' => 'about',
        'title' => 'Original Title',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('edit', $page->id)
        ->set('title', 'Updated Title')
        ->call('save')
        ->assertHasNoErrors();

    expect($page->fresh()->title)->toBe('Updated Title');
});

test('admin can delete a page section', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $page = Page::create([
        'section_key' => 'deleteme',
        'title' => 'Delete Me',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('delete', $page->id);

    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});

test('page section requires title and section_key', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('create')
        ->set('sectionKey', '')
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['sectionKey', 'title']);
});

test('extra data JSON validation rejects invalid JSON', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('create')
        ->set('sectionKey', 'test')
        ->set('title', 'Test')
        ->set('extraDataJson', '{invalid json}')
        ->call('save')
        ->assertHasErrors(['extraDataJson']);
});

test('extra data JSON validation accepts valid JSON', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.pages')
        ->call('create')
        ->set('sectionKey', 'stats')
        ->set('title', 'Stats Section')
        ->set('extraDataJson', '{"items": [{"value": "100+", "label": "Clients"}]}')
        ->call('save')
        ->assertHasNoErrors();

    $page = Page::query()->where('section_key', 'stats')->firstOrFail();

    expect($page->extra_data)->toBeArray();
    expect($page->extra_data['items'])->toHaveCount(1);
});
