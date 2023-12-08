<?php

namespace App\Filament\Widgets;

use App\Models\Admin;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AdminResource extends BaseWidget
{
    /**
     * Polling Interval, null means no polling.
     * @var string|null
     */
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            Card::make('All Administrators', Admin::query()->count())
                ->icon('heroicon-o-user-circle')->label(__('admin.all_administrators')),

            Card::make('Verified Administrators', Admin::query()->verified(false)->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.active_administrators')),

            Card::make('Un-Verified Administrators', Admin::query()->verified()->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.Inactive_administrators')),
        ];
    }
}
