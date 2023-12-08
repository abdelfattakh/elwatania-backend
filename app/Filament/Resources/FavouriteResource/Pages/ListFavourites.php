<?php

namespace App\Filament\Resources\FavouriteResource\Pages;

use App\Filament\Resources\FavouriteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFavourites extends ListRecords
{
    protected static string $resource = FavouriteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
