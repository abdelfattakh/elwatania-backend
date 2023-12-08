<?php

namespace App\Filament\Resources\FavouriteResource\Pages;

use App\Filament\Resources\FavouriteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFavourite extends ViewRecord
{
    protected static string $resource = FavouriteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
