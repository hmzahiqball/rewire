<?php

use App\Models\GalleryItem;
use App\Models\Service;

test('gallery page returns 200 and shows published items', function () {
    $item = GalleryItem::factory()->create(['title' => 'Published Portfolio']);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee($item->title);
});

test('gallery page does not show draft items', function () {
    $published = GalleryItem::factory()->create(['title' => 'Visible Item']);
    $draft = GalleryItem::factory()->unpublished()->create(['title' => 'Hidden Draft']);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee($published->title)
        ->assertDontSee($draft->title);
});

test('gallery page displays category filter buttons when categories exist', function () {
    GalleryItem::factory()->create(['category' => 'Web App']);
    GalleryItem::factory()->create(['category' => 'Mobile App']);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('Web App')
        ->assertSee('Mobile App')
        ->assertSee('Semua');
});

test('service detail page returns 200 for published service', function () {
    $service = Service::factory()->create([
        'title' => 'Cloud Architecture',
        'slug' => 'cloud-architecture',
        'is_published' => true,
    ]);

    $this->get(route('service.detail', 'cloud-architecture'))
        ->assertOk()
        ->assertSee('Cloud Architecture');
});

test('service detail page returns 404 for draft service', function () {
    Service::factory()->unpublished()->create(['slug' => 'draft-service']);

    $this->get(route('service.detail', 'draft-service'))
        ->assertNotFound();
});

test('service detail page returns 404 for non-existent slug', function () {
    $this->get(route('service.detail', 'non-existent-service'))
        ->assertNotFound();
});

test('service detail page shows other services in sidebar', function () {
    $main = Service::factory()->create([
        'title' => 'Main Service',
        'slug' => 'main-service',
        'is_published' => true,
    ]);

    $other = Service::factory()->create([
        'title' => 'Other Service',
        'is_published' => true,
    ]);

    $this->get(route('service.detail', 'main-service'))
        ->assertOk()
        ->assertSee('Other Service');
});
