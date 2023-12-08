<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ManageGeneral extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    TextInput::make('tax_price')->label(__('admin.tax')),
                    RichEditor::make('about')->label(__('admin.about')),
                    RichEditor::make('returnPolicy')->label(__('admin.returnPolicy')),

                    SpatieMediaLibraryFileUpload::make('images')
                        ->collection('banners')
                        ->multiple()
                        ->label(__('admin.images')),
                        
                    TextInput::make('phone')
                        ->label(__('admin.phone')),
                    TextInput::make('email')->label(__('web.email')),
                    TextInput::make('tax_price'),

                ])
                ->columns(1),

        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.general_setting');
    }

    protected function getHeading(): string
    {
        return __('admin.general_setting');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.setting');
    }
}
