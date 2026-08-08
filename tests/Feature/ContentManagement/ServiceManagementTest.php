<?php

use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

test('admin can view the services list', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $service = Service::factory()->create(['title' => 'Cloud Architecture']);

    $this->actingAs($admin)
        ->get(route('content-management.services'))
        ->assertOk()
        ->assertSee($service->title);
});

test('admin can create a service', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.services')
        ->call('create')
        ->set('title', 'New Service')
        ->set('shortDescription', 'A short description')
        ->set('body', 'Service body content')
        ->set('sortOrder', 1)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('services', [
        'title' => 'New Service',
        'short_description' => 'A short description',
    ]);
});

test('admin can edit a service', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $service = Service::factory()->create(['title' => 'Original Service']);

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.services')
        ->call('edit', $service->id)
        ->set('title', 'Updated Service')
        ->call('save')
        ->assertHasNoErrors();

    expect($service->fresh()->title)->toBe('Updated Service');
});

test('admin can delete a service', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $service = Service::factory()->create();

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.services')
        ->call('delete', $service->id);

    $this->assertDatabaseMissing('services', ['id' => $service->id]);
});

test('service generates slug from title on create', function () {
    $service = Service::create([
        'title' => 'Cloud Architecture Solutions',
        'short_description' => 'We build cloud solutions',
        'body' => 'Full body description',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    expect($service->slug)->toBe('cloud-architecture-solutions');
});

test('service slug does not change on update', function () {
    $service = Service::factory()->create([
        'title' => 'Original Title',
        'slug' => 'original-title',
    ]);

    $service->update(['title' => 'Completely New Title']);

    expect($service->fresh()->slug)->toBe('original-title');
});

test('service requires title, short description, and body', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.content-management.services')
        ->call('create')
        ->set('title', '')
        ->set('shortDescription', '')
        ->set('body', '')
        ->call('save')
        ->assertHasErrors(['title', 'shortDescription', 'body']);
});

test('editing a service logs activity', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $service = Service::factory()->create(['is_published' => false]);

    $this->actingAs($admin);

    $countBefore = Activity::count();

    Livewire::test('pages::app.content-management.services')
        ->call('edit', $service->id)
        ->set('isPublished', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(Activity::count())->toBeGreaterThan($countBefore);
});
