<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('AlL products', Product::query()->count())
                ->icon('heroicon-o-lightning-bolt')->label(__('admin.all_products')),

            Card::make('Active products', Product::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_products')),

            Card::make('InActive products', Product::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.inactive_products')),
        ];
    }
}
