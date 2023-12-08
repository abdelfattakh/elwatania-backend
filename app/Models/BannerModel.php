<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BannerModel extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia, HasActiveScope;

//    protected $table='media';
    protected $fillable = [
        'name',
        'is_active'
    ];

    /**
     * @return void
     * user_image
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('banner_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg'])
            ->withResponsiveImages()
            ->singleFile();

        $this
            ->addMediaCollection('banner_images_main')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg'])
            ->withResponsiveImages()
            ->singleFile();
    }

    /**
     * Main Image Morph Relation
     *
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'banner_images');
    }

    /**
     * Main Image Morph Relation
     *
     * @return MorphOne
     */
    public function mainImage(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'banner_images_main');
    }
}
