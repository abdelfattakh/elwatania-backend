<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\CategoryResource\Widgets\CategoryStatusOverview;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\Widgets\OrderStatusOverview;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use DragonCode\Contracts\Cashier\Config\Payment;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Coupons;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('user_id')
                            ->options(User::all()->pluck('full_name', 'id')->toArray())
                            ->required()
                            ->reactive()
                            ->label(__('admin.user_name'))
                            ->afterStateUpdated(fn (callable $set) => $set('address_id', null)),



                        Select::make('address_id')
                            ->required()
                            ->options(function (callable $get) {
                                $user = User::find($get('user_id'));
                                if (!$user) {
                                    return Address::all()->pluck('address_name', 'id');
                                }
                                return $user->addresses->pluck('address_name', 'id');
                            })->label(__('admin.address_name')),

                        Select::make('payment_method_id')
                            ->required()
                            ->options(PaymentMethod::all()->pluck('name','id'))
                            ->label(__('admin.payment_method_name')),

                        Select::make('products')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->multiple()
                            ->label(__('admin.product')),


                        Select::make('coupon_id')
                            ->options(Coupon::all()->pluck('code','id'))
                            ->label(__('admin.coupon')),

                        Select::make('status')
                            ->options(OrderStatusEnum::toArray())
                            ->label(__('admin.status')),

                        TextInput::make('delivery_fees')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label(__('admin.delivery_fees'))




                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('admin.id')),

                TextColumn::make('payment_method.name')
                    ->url(fn ($record) => filled($record?->payment_method_id) ? PaymentMethodResource::getUrl('edit', ['record' => $record->payment_method_id]) : null)
                    ->sortable()->searchable()->label(__('admin.payment_method_name')),

                TextColumn::make('coupon.code')
                    ->url(fn ($record) => filled($record?->coupon_id) ? CouponResource::getUrl('edit', ['record' => $record->coupon_id]) : null)
                    ->sortable()->searchable()->label(__('admin.coupon')),
                TextColumn::make('address_name')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(10)
                    ->sortable()->searchable()->label(__('admin.address_name')),

                TextColumn::make('address_details')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(10)
                    ->sortable()->searchable()->label(__('admin.address_details')),

                TextColumn::make('address_phone')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(10)
                    ->sortable()->searchable()->label(__('admin.address_phone')),

                TextColumn::make('address_country_name')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(10)
                    ->sortable()->searchable()->label(__('admin.address_details')),

                TextColumn::make('address_city_name')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(10)
                    ->sortable()->searchable()->label(__('admin.city_name')),

                TextColumn::make('total')
                    ->sortable()->searchable()->label(__('admin.total')),

                TextColumn::make('sub_total')
                    ->sortable()->searchable()->label(__('admin.sub_total')),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => static fn ($state): bool => $state === 'inProgress',
                        'success' => static fn ($state): bool => $state === 'completed',
                        'danger' => static fn ($state): bool => $state === 'cancelled',

                    ])->label(__('admin.status')),

                TextColumn::make('discount_value')
                    ->label(__('admin.discount_value')),

                TextColumn::make('tax_price')
                    ->label(__('admin.tax_price')),

                TextColumn::make('delivery_fees')
                    ->label(__('admin.delivery_fees'))

            ])
            ->filters([
                SelectFilter::make('status')
                ->options(OrderStatusEnum::toArray())

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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function getLabel(): string
    {
        return __('admin.singleOrder');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.order');
    }
    public static function getWidgets(): array
    {
        return [
            OrderStatusOverview::class,
        ];
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.order');
    }
}
