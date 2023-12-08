<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Country;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('All Orders', Order::query()->count())
                ->icon('heroicon-o-shopping-bag')->label(__('admin.all_orders')),

            Card::make('Active Countries', Order::query()->where('status','cancelled')->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.cancelled_orders')),

            Card::make('InActive Countries', Order::query()->where('status','completed')->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.completed_orders')),

            Card::make('InActive Countries', Order::query()->where('status','inProgress')->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.inProgress_orders')),
        ];
    }
}
