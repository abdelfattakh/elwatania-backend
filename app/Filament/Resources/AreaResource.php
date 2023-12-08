<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Filament\Resources\AreaResource\Widgets\AreaStatusOverview;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AreaResource extends Resource
{
    use Translatable;
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()->label(__('admin.area_name')),





                    Select::make('city_id')
                        ->relationship('city', 'name', fn ($query) => $query->active())
                        ->label(__('admin.city_name')),

                    Forms\Components\TextInput::make('shipping_time')
                        ->required()->label(__('admin.shipping_time')),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.is_active'))
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('admin.id')),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.area_name')),

                TextColumn::make('shipping_time')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.shipping_time')),

                TextColumn::make('city.name')
                    ->url(fn ($record) => filled($record?->city_id) ? CityResource::getUrl('edit', ['record' => $record->city_id]) : null)
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.city_name')),


                ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active only?')
                    ->indicator('Active'),

                SelectFilter::make('city')->relationship('city', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),

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
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'view' => Pages\ViewArea::route('/{record}'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            AreaStatusOverview::class,
        ];
    }


    public static function getLabel(): string
    {
        return __('admin.area');
    }

//
    public static function getPluralLabel(): string
    {
        return __('admin.areas');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.location');
    }

}
