<?php

namespace App\Traits\Attributes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Mark;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;

/**
 * @method static Builder whereHasMark(Mark $mark, Model $user, ?string $value = null)
 */
trait FavouredByCurrentUser
{
    use Markable;

    public static string $favGuard = 'users';

    /**
     * Marks using maize-tech/laravel-markable
     * @var Mark[]
     */
    protected static array $marks = [
//        \Maize\Markable\Models\Like::class,
        Favorite::class,
//        \Maize\Markable\Models\Bookmark::class,
//        \Maize\Markable\Models\Reaction::class,
    ];

    /**
     * Is Favourite.
     *
     * @return Attribute
     */
    public function isFavourite(): Attribute
    {
        return Attribute::get(fn () => auth(self::$favGuard)->check() && Favorite::has($this, auth(self::$favGuard)->user()));
    }
}
