<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
* @property-read string $type
* @property-read string $extension
* @property-read string $humanReadableSize
* @property-read string $previewUrl
* @property-read string $originalUrl
* @method static Builder ordered()
*/
class SpatieMedia extends Media
{
    use HasFactory;
    /**
     * The attributes that should be visible in serialization.
     *
     * @var array<string>
     */
    protected $visible = [
        'url',
        'responsive_urls',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'responsive_urls',
    ];

    /**
     * Get Full Url.
     *
     * @return Attribute
     */
    public function url(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->getAttribute('disk') == 's3') {
                return $this->getTemporaryUrl(now()->addMinutes(20));
            }

            return $this->getFullUrl();
        });
    }

    /**
     * Get Responsive Urls.
     *
     * @return Attribute
     */
    public function responsiveUrls(): Attribute
    {
        return Attribute::get(function (): array {
            $images = $this->getAttribute('responsive_watermarked_urls');
            if (filled($images)) {
                return $images;
            }

            $images = $this->getAttribute('responsive_original_urls');
            if (filled($images)) {
                return $images;
            }

            return Arr::wrap($this->getAttribute('url'));
        });
    }

    /**
     * Get Responsive Original.
     *
     * @return Attribute
     */
    public function responsiveOriginalUrls(): Attribute
    {
        return Attribute::get(function (): array {
            return $this->getResponsiveImageUrls();
        });
    }

    /**
     * Get Responsive Watermarked.
     *
     * @return Attribute
     */
    public function responsiveWatermarkedUrls(): Attribute
    {
        return Attribute::get(function (): array {
            return $this->getResponsiveImageUrls('water_marked');
        });
    }
}
