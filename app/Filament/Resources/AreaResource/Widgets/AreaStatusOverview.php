<?php

namespace App\Filament\Resources\AreaResource\Widgets;

use App\Models\Area;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AreaStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('AlL Areas', Area::query()->count())
                ->icon('heroicon-o-ticket')->label(__('admin.all_areas')),

            Card::make('Active Areas', Area::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_areas')),

            Card::make('InActive Areas', Area::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.inactive_areas')),
        ];
    }
}
