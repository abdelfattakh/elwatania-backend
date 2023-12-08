<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCity extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = CityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
}
