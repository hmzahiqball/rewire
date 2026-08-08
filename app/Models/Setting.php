<?php

namespace App\Models;

use App\Enums\SettingKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    use LogsActivity;

    public static function get(string|SettingKey $key, ?string $default = null): ?string
    {
        $keyString = $key instanceof SettingKey ? $key->value : $key;

        return self::cached()[$keyString] ?? $default;
    }

    public static function put(string|SettingKey $key, ?string $value): void
    {
        $keyString = $key instanceof SettingKey ? $key->value : $key;

        static::query()->updateOrCreate(['key' => $keyString], ['value' => $value]);

        Cache::forget('settings');
    }

    /**
     * Cached as a plain array, not a Collection -- Laravel's default cache config
     * (config/cache.php: serializable_classes => false) refuses to unserialize
     * objects from cache, so caching an Eloquent Collection silently comes back
     * as __PHP_Incomplete_Class. Arrays are unaffected by that restriction.
     *
     * @return array<string, string|null>
     */
    private static function cached(): array
    {
        return Cache::rememberForever('settings', fn () => static::query()->pluck('value', 'key')->all());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('settings')
            ->setDescriptionForEvent(fn (string $eventName) => self::label($this->key)." was {$eventName}");
    }

    private static function label(string $key): string
    {
        return match ($key) {
            'seo_description' => 'SEO meta description',
            'analytics_id' => 'Google Analytics measurement ID',
            'social_linkedin' => 'LinkedIn URL',
            'social_twitter' => 'Twitter / X URL',
            'social_github' => 'GitHub URL',
            'social_instagram' => 'Instagram URL',
            'contact_address' => 'Contact address',
            'contact_email' => 'Contact email',
            'contact_phone' => 'Contact phone',
            default => "Setting \"{$key}\"",
        };
    }
}
