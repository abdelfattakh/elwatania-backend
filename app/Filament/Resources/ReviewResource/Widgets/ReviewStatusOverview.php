<?php

namespace App\Filament\Resources\ReviewResource\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ReviewStatusOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            Card::make('All Reviews', Review::count())
                ->icon('heroicon-o-annotation')->label(__('admin.all_reviews')),

            Card::make('reviews with 1 stars', Review::stars(1)->count())
                ->icon('heroicon-o-annotation')->label(__('admin.first_star')),

            Card::make('reviews with 2 stars', Review::stars(2)->count())
                ->icon('heroicon-o-annotation')->label(__('admin.second_star')),

            Card::make('reviews with 3 stars', Review::stars(3)->count())
                ->icon('heroicon-o-annotation')->label(__('admin.third_star')),

            Card::make('reviews with 4 stars', Review::stars(4)->count())
                ->icon('heroicon-o-annotation')->label(__('admin.fourth_star')),

            Card::make('reviews with 5 stars', Review::stars(5)->count())
                ->icon('heroicon-o-annotation')->label(__('admin.fifth_star')),
        ];
    }
}
