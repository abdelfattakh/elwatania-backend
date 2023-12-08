<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;
    use HasActiveScope;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'is_active',
        'show',
        'order_column',
    ];

    /**
     * Translation Fields using Spatie\Translatable
     * @var string[]
     */
    public $translatable = [
        'title',
        'description',
        'show'
    ];
    /**
     * @var array[]
     */
    public $casts = [
        'show'=>'array'
    ];


}
