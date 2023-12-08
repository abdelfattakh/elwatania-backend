<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use App\Filament\Resources\CityResource\Widgets\CityStatusOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = CityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CityStatusOverview::class,
        ];
    }
}
