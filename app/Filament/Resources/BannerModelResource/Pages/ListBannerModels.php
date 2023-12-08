<?php

namespace App\Filament\Resources\BannerModelResource\Pages;

use App\Filament\Resources\BannerModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBannerModels extends ListRecords
{
    protected static string $resource = BannerModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
