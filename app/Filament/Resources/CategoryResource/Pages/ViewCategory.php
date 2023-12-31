<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;
    use ViewRecord\Concerns\Translatable;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
}
