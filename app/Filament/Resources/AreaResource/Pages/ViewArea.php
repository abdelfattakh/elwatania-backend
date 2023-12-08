<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArea extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = AreaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
}
