<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\Widgets\CityOverview;
use App\Filament\Resources\CityResource\Widgets\CityStatusOverview;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Resources\Concerns\Translatable;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CityResource extends Resource
{
    use Translatable;

    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()->label(__('admin.city_name')),

                    Select::make('country_id')
                        ->relationship('country', 'name', fn($query) => $query->active())->label(__('admin.country_id')),

                    Forms\Components\TextInput::make('delivery_fees')
                        ->required()
                        ->numeric()
                        ->minValue('1')
                        ->label(__('admin.delivery_fees')),

                    Forms\Components\Toggle::make('is_active')->label(__('admin.is_active'))

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('admin.id'))->toggledHiddenByDefault()->toggleable(),

                TextColumn::make('country.name')
                    ->url(fn($record) => filled($record?->country_id) ? CountryResource::getUrl('edit', ['record' => $record->country_id]) : null)
                    ->sortable()->searchable()->label(__('admin.country_name')),

                TextColumn::make('delivery_fees')
                    ->searchable()
                    ->sortable()
                    ->label(__('admin.delivery_fees')),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label(__('admin.city_name')),
                ToggleColumn::make('is_active')->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(__('admin.created_at')),


                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(__('admin.updated_at')),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active only?')
                    ->indicator('Active'),
                SelectFilter::make('country')->relationship('country', 'name')
            ])
            ->actions([
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('admin.city');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.cities');
    }

    public static function getWidgets(): array
    {
        return [
            CityStatusOverview::class,
        ];
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.location');
    }
}
