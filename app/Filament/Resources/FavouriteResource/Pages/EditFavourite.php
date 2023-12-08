<?php

namespace App\Filament\Resources\FavouriteResource\Pages;

use App\Filament\Resources\FavouriteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFavourite extends EditRecord
{
    protected static string $resource = FavouriteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
