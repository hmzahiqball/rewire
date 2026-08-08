<?php

use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('admin can access the settings page', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin)->get(route('system.settings'))->assertOk();
});

test('staff cannot access the settings page', function () {
    $staff = User::factory()->create();
    $staff->syncRoles(Role::findOrCreate('staff'));

    $this->actingAs($staff)->get(route('system.settings'))->assertForbidden();
});

test('admin can save all 9 settings via Livewire component', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('seoDescription', 'Test SEO description')
        ->set('analyticsId', 'G-ABC123')
        ->set('socialLinkedin', 'https://linkedin.com/company/test')
        ->set('socialTwitter', 'https://x.com/test')
        ->set('socialGithub', 'https://github.com/test')
        ->set('socialInstagram', 'https://instagram.com/test')
        ->set('contactAddress', 'Jakarta, Indonesia')
        ->set('contactEmail', 'hello@example.com')
        ->set('contactPhone', '+62 21 1234 5678')
        ->call('save')
        ->assertHasNoErrors();

    expect(Setting::get('seo_description'))->toBe('Test SEO description');
    expect(Setting::get('analytics_id'))->toBe('G-ABC123');
    expect(Setting::get('social_linkedin'))->toBe('https://linkedin.com/company/test');
    expect(Setting::get('social_twitter'))->toBe('https://x.com/test');
    expect(Setting::get('social_github'))->toBe('https://github.com/test');
    expect(Setting::get('social_instagram'))->toBe('https://instagram.com/test');
    expect(Setting::get('contact_address'))->toBe('Jakarta, Indonesia');
    expect(Setting::get('contact_email'))->toBe('hello@example.com');
    expect(Setting::get('contact_phone'))->toBe('+62 21 1234 5678');
});

test('settings validation rejects invalid email format', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('contactEmail', 'not-an-email')
        ->call('save')
        ->assertHasErrors(['contactEmail']);
});

test('settings validation rejects invalid social URL format', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('socialLinkedin', 'not-a-url')
        ->call('save')
        ->assertHasErrors(['socialLinkedin']);
});

test('settings validation rejects invalid analytics ID format', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('analyticsId', 'INVALID-ID')
        ->call('save')
        ->assertHasErrors(['analyticsId']);
});

test('settings validation accepts valid analytics ID formats', function (string $analyticsId) {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('analyticsId', $analyticsId)
        ->call('save')
        ->assertHasNoErrors(['analyticsId']);
})->with([
    'G-ABC12345',
    'GTM-ABCDE',
    'UA-12345-1',
]);

test('settings allows empty analytics ID to disable tracking', function () {
    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->set('analyticsId', '')
        ->call('save')
        ->assertHasNoErrors(['analyticsId']);
});

test('settings loads existing values on mount', function () {
    Setting::put('seo_description', 'Existing description');
    Setting::put('contact_email', 'existing@test.com');

    $admin = User::factory()->create();
    $admin->syncRoles(Role::findOrCreate('admin'));

    $this->actingAs($admin);

    Livewire::test('pages::app.system.settings')
        ->assertSet('seoDescription', 'Existing description')
        ->assertSet('contactEmail', 'existing@test.com');
});
