<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $section_key
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $body
 * @property array<string, mixed>|null $extra_data
 * @property int $sort_order
 * @property bool $is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['section_key', 'title', 'subtitle', 'body', 'extra_data', 'sort_order', 'is_published'])]
class Page extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'extra_data' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Retrieve a published page section by its unique key.
     */
    public static function section(string $key): ?self
    {
        return static::query()->where('section_key', $key)->where('is_published', true)->first();
    }

    /**
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 160, 100);
        $this->addMediaConversion('hero')->fit(Fit::Max, 1600, 1600);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'is_published'])
            ->logOnlyDirty()
            ->useLogName('pages')
            ->setDescriptionForEvent(fn (string $eventName) => "The page section \"{$this->section_key}\" was {$eventName}");
    }
}
