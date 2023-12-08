<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Brand extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use HasActiveScope;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'is_active'
    ];
    public $translatable = ['name'];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    /**
     * @return void
     * user_image
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('brand_image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
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
            ->where('collection_name', 'brand_image');
    }
}
