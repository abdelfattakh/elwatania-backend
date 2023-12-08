<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBrand extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
}
