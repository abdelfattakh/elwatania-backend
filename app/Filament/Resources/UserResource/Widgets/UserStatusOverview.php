<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Admin;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserStatusOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('All Users', User::query()->count())
                ->icon('heroicon-o-user-circle')->label(__('admin.all_Users')),

            Card::make('Verified Users', User::query()->verified(false)->count())
                ->icon('heroicon-o-check-circle')->label(__('admin.verified_Users')),

            Card::make('Un-Verified Users', User::query()->verified()->count())
                ->icon('heroicon-o-x-circle')->label(__('admin.unVerified_Users')),
        ];
    }
}
