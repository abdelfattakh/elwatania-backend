<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerModelResource\Pages;
use App\Filament\Resources\BannerModelResource\RelationManagers;
use App\Models\BannerModel;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerModelResource extends Resource
{
    protected static ?string $model = BannerModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label(__('admin.name')),


                         SpatieMediaLibraryFileUpload::make('images')
                             ->collection('banner_images')
                             ->multiple()
                             ->label(__('admin.banner_images')),

                        SpatieMediaLibraryFileUpload::make('Web images')
                            ->collection('banner_images_main')
                            ->label(__('admin.banner_images_main')),

                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->label(__('admin.is_active')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->label(__('admin.id')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.name')),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),

                SpatieMediaLibraryImageColumn::make('banner_images')
                    ->collection('banner_images')
                    ->circular()
                    ->label(__('admin.banner_images')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBannerModels::route('/'),
            'create' => Pages\CreateBannerModel::route('/create'),
            'edit' => Pages\EditBannerModel::route('/{record}/edit'),
        ];
    }
    public static function getModelLabel(): string
    {
        return __('admin.banner');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.banners');
    }
    protected static function getNavigationGroup(): ?string
    {
        return __('admin.general');
    }
}
