<?php

namespace App\Filament\Resources\BannerModelResource\Pages;

use App\Filament\Resources\BannerModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBannerModel extends EditRecord
{
    protected static string $resource = BannerModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
