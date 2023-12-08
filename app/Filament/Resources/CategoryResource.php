<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\Widgets\CategoryStatusOverview;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    use Translatable;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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

                        Forms\Components\Select::make('parent_id')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->label(__('admin.parent_id')),

                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('category_image')
                            ->multiple()
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

                Tables\Columns\ToggleColumn::make('is_active')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.name')),

                TextColumn::make('parent.name')
                    ->url(fn($record) => filled($record?->parent_id) ? CategoryResource::getUrl('edit', ['record' => $record->parent_id]) : null)
                    ->sortable()->searchable()
                    ->label(__('admin.category_id')),



          SpatieMediaLibraryImageColumn::make('category_image')
              ->collection('category_image')
              ->circular()
              ->label(__('admin.images')),

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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    public static function getLabel(): string
    {
        return __('admin.category');
    }

//
    public static function getPluralLabel(): string
    {
        return __('admin.categories');
    }

    public static function getWidgets(): array
    {
        return [
            CategoryStatusOverview::class,
        ];
    }


    protected static function getNavigationGroup(): ?string
    {
        return __('admin.general');
    }



}
