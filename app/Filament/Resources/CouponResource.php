<?php

namespace App\Filament\Resources;

use App\Enums\CouponTypeEnum;
use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
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

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('code')
                    ->required()
                    ->label(__('admin.code')),

                    Select::make('type')
                    ->options(CouponTypeEnum::toArray())
                    ->label(__('admin.type')),

                    TextInput::make('no_uses')
                    ->required()
                    ->numeric()
                    ->label(__('admin.no_uses')),


                    TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->label(__('admin.value')),

                  DateTimePicker::make('starts_at')
                  ->required()
                  ->label(__('admin.starts_at')),

                  DateTimePicker::make('ends_at')
                  ->required()
                  ->after('starts_at')
                  ->label(__('admin.finishes_at')),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('id')
              ->label(__('admin.id')),

              TextColumn::make('code')
              ->searchable()
              ->label(__('admin.code')),

              TextColumn::make('type')
              ->label(__('admin.type')),

              TextColumn::make('no_uses')
              ->label(__('admin.no_uses')),

              TextColumn::make('value')
              ->label(__('admin.value')),

              TextColumn::make('starts_at')

              ->label(__('admin.starts_at')),


              TextColumn::make('ends_at')
              ->label(__('admin.finishes_at')),


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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view' => Pages\ViewCoupon::route('/{record}'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
    public static function getLabel(): string
    {
        return __('admin.coupon');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.coupons');
    }
  
    protected static function getNavigationGroup(): ?string
    {
        return __('admin.product');
    }

}
