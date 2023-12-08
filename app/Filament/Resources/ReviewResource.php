<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Filament\Resources\ReviewResource\Widgets\ReviewStatusOverview;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Yepsua\Filament\Forms\Components\Rating;


class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Card::make()
                    ->columnSpan(fn($record) => filled($record) ? 2 : 'full')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(__('admin.user_id'))
                            ->searchable(['id', 'first_name', 'last_name', 'email'])
                            ->relationship('user', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn(User $record): string => "#{$record->getKey()} - {$record->first_name} {$record->last_name}")
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $set('user_name', User::query()->select('first_name')->find($state)->value('first_name'));
                            }),

                        Forms\Components\Hidden::make('user_name')
                            ->label(__('admin.user_name'))
                            ->required(),

                        Forms\Components\MorphToSelect::make('reviewable')
                            ->searchable()
                            ->label(__('admin.reviewable'))
                            ->required()
                            ->types([
                                Forms\Components\MorphToSelect\Type::make(Product::class)
                                    ->searchColumns(['id', 'name', 'price', 'category_id'])
                                    ->titleColumnName('id')
                                    ->getOptionLabelFromRecordUsing(fn(Product $record): string => "#{$record->getKey()} - {$record->name}")
                                    ->label(__('admin.products')),
                            ]),

                        Rating::make('stars')
                            ->default(1)
                            ->required()
                            ->label(__('admin.stars')),


                        Forms\Components\Textarea::make('comment')
                            ->required()
                            ->label(__('admin.comment'))
                        ,
                    ]),

                Forms\Components\Card::make()
                    ->visible(fn($record) => filled($record))
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('admin.created_at'))
                            ->content(fn($record) => $record->created_at?->translatedFormat('d M Y, h:i a')),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label(__('admin.updated_at'))
                            ->content(fn($record) => $record->updated_at?->translatedFormat('d M Y, h:i a')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable()->label(__('admin.id')),

                Tables\Columns\TextColumn::make('user')
                    ->getStateUsing(fn(Review $record): string => "{$record->user?->first_name} {$record->user?->last_name}")
                    ->limit(20)
                    ->url(fn($record) => filled($record?->user_id) ? UserResource::getUrl('edit', ['record' => $record->user_id]) : null)
                    ->label(__('admin.user_name')),

                Tables\Columns\TextColumn::make('reviewable')
                    ->getStateUsing(fn(Review $record): string => "#{$record->reviewable?->getKey()} - {$record->reviewable?->name}") //  {$record->reviewable?->last_name}
                    ->limit(20)
                    ->label(__('admin.reviewable'))
                    ->description(fn(Review $record) => class_basename($record->reviewable_type), 'above'),
                //      ->url(fn(Review $record) => $record->reviewable ? OrderResource::get.....),
                // TODO: When we have order resource, we should link this to it.

                Tables\Columns\TextColumn::make('stars')
                    ->getStateUsing(fn(Review $record) => str_repeat('⭐', $record->stars))
                    ->sortable()->label(__('admin.stars')),

                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->limit(30)
                    ->label(__('admin.comment')),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->dateTime()->label(__('admin.created_at')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable()
                    ->dateTime()->label(__('admin.updated_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stars')
                    ->options([
                        1 => str_repeat('⭐', 1),
                        2 => str_repeat('⭐', 2),
                        3 => str_repeat('⭐', 3),
                        4 => str_repeat('⭐', 4),
                        5 => str_repeat('⭐', 5),
                    ]),
                Tables\Filters\SelectFilter::make('User')
                    ->relationship('user', 'first_name')
                    ->searchable(),


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

    public static function getWidgets(): array
    {
        return [
            ReviewStatusOverview::class,
        ];
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('admin.Review');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.Reviews');
    }


    protected static function getNavigationGroup(): ?string
    {
        return __('admin.product');
    }
}
