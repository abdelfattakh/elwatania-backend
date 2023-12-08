<?php

namespace App\Filament\Resources\BrandResource\Widgets;

use App\Models\Brand;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class BrandStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('AlL brands', Brand::query()->count())
                ->icon('heroicon-o-ticket')->label(__('admin.all_brands')),

            Card::make('Active brands', Brand::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_brands')),

            Card::make('InActive brands', Brand::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.inactive_brands')),
        ];
    }
}
