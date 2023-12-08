<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets\ProductStatusOverview;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Filters\TernaryFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';


    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Card::make()
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()->label(__('admin.product_name')),


                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label(__('admin.product_price')),


                        Forms\Components\TextInput::make('model_name')
                            ->required()
                            ->label(__('admin.model_name')),

                        Forms\Components\TextInput::make('stock_code')
                            ->required()
                            ->label(__('admin.stock_code')),

                        Forms\Components\TextInput::make('barcode')
                            ->label(__('admin.barcode')),

                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name', fn ($query) => $query->active())
                            ->label(__('admin.category_id')),

                        Forms\Components\Toggle::make('is_available')
                            ->required()
                            ->label(__('admin.is_available')),
                    ])->columns(2),

                Forms\Components\Card::make()
                    ->visible()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\TextInput::make('discount_value')
                            ->numeric()
                            ->minValue(1)
                            ->label(__('admin.discount_value')),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name', fn ($query) => $query->active())
                            ->label(__('admin.brand_id')),

                        Forms\Components\DateTimePicker::make('discount_expiration_date')
                            ->nullable()
                            ->label(__('admin.discount_expiration_date')),

                    ])->columns(2),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('general_description')
                            ->required()
                            ->label(__('admin.general_description')),

                        Forms\Components\RichEditor::make('technical_description')
                            ->required()
                            ->label(__('admin.technical_description')),

                        Forms\Components\RichEditor::make('shipping_time')
                            ->label(__('admin.shipping_time')),

                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('product_images')
                            ->collection('product_images')
                            ->multiple()
                            ->label(__('admin.product_images')),

                        SpatieMediaLibraryFileUpload::make('cover_images')
                            ->collection('cover_image')
                            ->label(__('admin.cover_images')),

                        SpatieMediaLibraryFileUpload::make('product_guide')
                            ->collection('product_guide')
                            ->appendFiles()
                            ->label(__('admin.product_guide')),

                        Forms\Components\Toggle::make('is_active')->label(__('admin.is_active')),
                        Forms\Components\Toggle::make('is_exclusive')->label(__('admin.is_exclusive'))

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.id')),


                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.product_name')),


                TextColumn::make('category.name')
                    ->url(fn ($record) => filled($record?->category_id) ? CategoryResource::getUrl('edit', ['record' => $record->category_id]) : null)
                    ->sortable()->searchable()->label(__('admin.category_id')),

                TextColumn::make('brand.name')
                    ->url(fn ($record) => filled($record?->brand_id) ? BrandResource::getUrl('edit', ['record' => $record->brand_id]) : null)
                    ->sortable()->searchable()->label(__('admin.brand_id')),

                TextColumn::make('price')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.product_price')),
                TextColumn::make('final_price')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.final_price')),

                Tables\Columns\ToggleColumn::make('is_available')
                    ->label(__('admin.is_available')),

                TextColumn::make('model_name')
                    ->label(__('admin.model_name')),

                TextColumn::make('stock_code')
                    ->label(__('admin.stock_code')),

                TextColumn::make('barcode')
                    ->label(__('admin.barcode')),

                TextColumn::make('discount_value')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.discount_value')),

                TextColumn::make('discount_expiration_date')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.expiration_date')),


                TextColumn::make('shipping_time')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.shipping_time')),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.is_active')),

                Tables\Columns\ToggleColumn::make('is_exclusive')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.is_exclusive')),




            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active only?')
                    ->indicator('Active'),
                //
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('admin.product');
    }

    //
    public static function getPluralLabel(): string
    {
        return __('admin.products');
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
