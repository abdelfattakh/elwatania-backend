<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),

        ];
    }
}