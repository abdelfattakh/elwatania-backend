<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Filament\Resources\BrandResource\Widgets\BrandStatusOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrands extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = BrandResource::class;

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
            BrandStatusOverview::class,
        ];
    }
}
