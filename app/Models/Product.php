<?php

namespace App\Models;

use App\Traits\Attributes\FavouredByCurrentUser;
use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use HasActiveScope;
    use FavouredByCurrentUser;
    use HasSEO;


    /**
     * Primary Media Collection
     */
    public static string $mediaCollection = 'product_images';

    /**
     * Single Cover Image Media Collection
     */
    public static string $coverMediaCollection = 'cover_image';

    /**
     * Single Product Guide File Image Media Collection
     */
    public static string $fileMediaCollection = 'product_guide';

    /**
     * Translation Fields using Spatie\Translatable
     * @var string[]
     */
    public array $translatable = [
        'name',
        'shipping_time',
        'general_description',
        'technical_description', // TODO: Should be key/val.
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'final_price',
        'name',
        'price',
        'discount_value',
        'discount_expiration_date',
        'shipping_time',
        'general_description',
        'technical_description',
        'is_available',
        'model_name',
        'stock_code',
        'barcode',
        'is_active',
        'is_exclusive',
        'is_available'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category_id' => 'int',
        'brand_id' => 'int',
        'price' => 'int',
        'discount_value' => 'int',

        'discount_expiration_date' => 'datetime',

        'is_active' => 'bool',
        'is_available' => 'bool',
        'is_exclusive' => 'bool',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'final_price',
        'is_favourite',
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            $price = $model->getAttribute('price') ?? 0;
            $discount = $model->getAttribute('discount_value') ?? 0;
            $discountAmount = ($discount / 100) * $price;
            $model->final_price = $price - $discountAmount;
        });
        static::updating(function (self $model) {

            $price = $model->getAttribute('price') ?? 0;
            $discount = $model->getAttribute('discount_value') ?? 0;
            $discountAmount = ($discount / 100) * $price;
            $model->final_price = $price - $discountAmount;

        });
    }

    /**
     * Product has many Reviews.
     * @return MorphMany
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Product Belongs to Brand.
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Product Belongs to Category.
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Defining Media Collections for Category Images.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(self::$mediaCollection)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->withResponsiveImages();

        $this
            ->addMediaCollection(self::$coverMediaCollection)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->withResponsiveImages()
            ->singleFile();
        $this
            ->addMediaCollection(self::$fileMediaCollection)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp','application/pdf'])
            ->withResponsiveImages()
            ->singleFile();
    }

    /**
     * Product Images Morph Relation
     *
     * @return MorphMany
     */
    public function image(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model')
            ->where('collection_name', self::$mediaCollection);
    }

    /**
     * Main Image Morph Relation
     *
     * @return MorphOne
     */
    public function coverImage(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', self::$coverMediaCollection);
    }

    /**
     * Main Image Morph Relation
     *
     * @return MorphOne
     */
    public function productGuideFile(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', self::$fileMediaCollection);
    }

    /**
     * Calculating Final Price.
     * @return float
     */
    public function getFinalPriceAttribute(): float
    {
        $price = $this->getAttribute('price') ?? 0;
        $discount = $this->getAttribute('discount_value') ?? 0;

        $discountAmount = ($discount / 100) * $price;
        return $price - $discountAmount;
    }

    public function getDynamicSEOData(): SEOData
    {
        $this->loadMissing('coverImage');
        $media_links = collect($this->getRelation('coverImage')?->getResponsiveImageUrls() ?? []);

        // Override only the properties you want:
        return new SEOData(
            title: $this->getAttribute('name'),
            description: $this->getAttribute('general_description'),
            author: config('app.name'),
            image: str_replace(config('app.url'), '', $media_links->last()),
        );
    }
}
