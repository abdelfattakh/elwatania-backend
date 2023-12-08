<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use App\Filament\Resources\AreaResource\Widgets\AreaStatusOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreas extends ListRecords
{
    use ListRecords\Concerns\Translatable;


    protected static string $resource = AreaResource::class;

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
            AreaStatusOverview::class,
        ];
    }
}
