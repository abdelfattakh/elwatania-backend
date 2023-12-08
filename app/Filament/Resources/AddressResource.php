<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('area_id')
                            ->relationship('area', 'name', fn ($query) => $query->active())
                            ->required()
                            ->label(__('admin.area_name')),

                        Select::make('country_id')
                            ->relationship('country', 'name', fn ($query) => $query->active())
                            ->required()
                            ->label(__('admin.country_name')),

                        Select::make('city_id')
                            ->relationship('city', 'name', fn ($query) => $query->active())
                            ->required()
                            ->label(__('admin.city_name')),

                        Select::make('user_id')
                            ->relationship('user', 'first_name')
                            ->required()
                            ->label(__('admin.user_name')),

                    ])->columns(2),
                Card::make()
                    ->schema([
                        TextInput::make('phone')
                            ->required()
                            ->label('phone'),
                        TextInput::make('building_no')
                            ->required('building_no')
                            ->label(__('admin.building_no')),

                        TextInput::make('level')
                            ->required()
                            ->label(__('admin.level')),

                        TextInput::make('flat_no')
                            ->required()
                            ->label(__('admin.flat_no')),

                    ])->columns(2),
                Card::make()
                    ->schema([
                        RichEditor::make('street_name')
                            ->required()
                            ->label(__('admin.address_name')),

                        RichEditor::make('address_details')
                            ->required()
                            ->label(__('admin.address_details'))


                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.name')
                    ->url(fn ($record) => filled($record?->country_id) ? CountryResource::getUrl('edit', ['record' => $record->country_id]) : null)
                    ->sortable()->searchable()->label(__('admin.country_name')),
                TextColumn::make('city.name')
                    ->url(fn ($record) => filled($record?->city_id) ? CityResource::getUrl('edit', ['record' => $record->city_id]) : null)
                    ->sortable()
                    ->searchable()->label(__('admin.city_name')),

                TextColumn::make('area.name')
                    ->url(fn ($record) => filled($record?->area_id) ? AreaResource::getUrl('edit', ['record' => $record->area_id]) : null)
                    ->sortable()
                    ->searchable()->label(__('admin.area_name')),

                TextColumn::make('street_name')
                    ->searchable()
                    ->label(__('admin.address_name')),

                TextColumn::make('address_details')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->label(__('admin.address_details'))
                    ->limit(20),

                TextColumn::make('phone')
                    ->searchable()
                    ->label(__('admin.phone')),



                TextColumn::make('building_no')
                    ->label(__('admin.building_no')),

                TextColumn::make('level')
                    ->label(__('admin.level')),

                TextColumn::make('flat_no')
                    ->label(__('admin.flat_no')),



            ])
            ->filters([
                //
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
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'view' => Pages\ViewAddress::route('/{record}'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('admin.address');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.addresses');
    }
    protected static function getNavigationGroup(): ?string
    {
        return __('admin.location');
    }
}
