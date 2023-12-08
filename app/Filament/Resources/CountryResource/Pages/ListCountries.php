<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Widgets\CountryStatusOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CountryResource::class;

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
            CountryStatusOverview::class,
        ];
    }
}
