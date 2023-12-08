<?php

namespace App\Filament\Resources\BannerModelResource\Pages;

use App\Filament\Resources\BannerModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBannerModel extends CreateRecord
{
    protected static string $resource = BannerModelResource::class;
}
