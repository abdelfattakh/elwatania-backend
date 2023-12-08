<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Filament\Resources\ProductResource\Widgets\ProductStatusOverview;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class BrandResource extends Resource
{
    use Translatable;

    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin.name')),

                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('brand_image')
                            ->label(__('admin.images')),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('admin.is_active'))

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.id')),

                SpatieMediaLibraryImageColumn::make('brand_image')->collection('brand_image')
                    ->circular()
                    ->label(__('admin.images')),


                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.name')),


                Tables\Columns\ToggleColumn::make('is_active')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.is_active')),


            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active only?')
                    ->indicator('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()

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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'view' => Pages\ViewBrand::route('/{record}'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('admin.brand');
    }

//
    public static function getPluralLabel(): string
    {
        return __('admin.brands');
    }

    public static function getWidgets(): array
    {
        return [
            ProductStatusOverview::class,
        ];
    }


    protected static function getNavigationGroup(): ?string
    {
        return __('admin.product');
    }
}
