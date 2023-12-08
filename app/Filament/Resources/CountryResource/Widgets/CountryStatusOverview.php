<?php

namespace App\Filament\Resources\CountryResource\Widgets;

use App\Models\Country;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class CountryStatusOverview extends BaseWidget
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
            Card::make('All countries', Country::query()->count())
                ->icon('heroicon-o-flag')->label(__('admin.all_countries')),

            Card::make('Active Countries', Country::query()->active()->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_countries')),

            Card::make('InActive Countries', Country::query()->active(false)->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.Inactive_countries')),
        ];
    }
}
