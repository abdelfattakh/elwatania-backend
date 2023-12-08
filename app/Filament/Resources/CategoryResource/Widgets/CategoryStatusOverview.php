<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use App\Models\Category;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CategoryStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('AlL Categories', Category::query()->count())
                ->icon('heroicon-o-collection')->label(__('admin.all_categories')),

            Card::make('Active Categories', Category::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_categories')),

            Card::make('InActive Categories', Category::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.inactive_categories')),
        ];
    }
}
