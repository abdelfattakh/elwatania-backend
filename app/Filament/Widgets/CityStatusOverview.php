<?php

namespace App\Filament\Widgets;

use App\Models\City;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CityStatusOverview extends BaseWidget
{

    /**
     * Polling Interval, null means no polling.
     *
     * @var string|null
     */
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            Card::make('All Cities', City::query()->count())
                ->icon('heroicon-o-location-marker')->label(__('admin.all_cities')),

            Card::make('Active Cities', City::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_cities')),

            Card::make('InActive Cities', City::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.Inactive_cities')),
        ];
    }
}
