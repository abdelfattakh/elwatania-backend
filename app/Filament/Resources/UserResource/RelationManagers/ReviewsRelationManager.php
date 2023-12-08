<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Product;
use App\Models\Review;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Yepsua\Filament\Forms\Components\Rating;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $recordTitleAttribute = 'comment';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Card::make()
                    ->columnSpan('full')
                    ->schema([
                        Forms\Components\MorphToSelect::make('reviewable')
                            ->searchable()
                            ->label(__('admin.reviewable'))
                            ->required()
                            ->types([
                                Forms\Components\MorphToSelect\Type::make(Product::class)
                                    ->searchColumns(['id', 'name', 'price', 'category_id'])
                                    ->titleColumnName('id')
                                    ->getOptionLabelFromRecordUsing(fn (Product $record): string => "#{$record->getKey()} - {$record->name}")
                                    ->label(__('admin.products')),
                            ]),
                        Rating::make('stars')->label(__('admin.stars')),
                        Forms\Components\Textarea::make('comment')
                            ->required()->label(__('admin.comment')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewable')
                    ->getStateUsing(fn (Review $record): string => "#{$record->reviewable?->getKey()} - {$record->reviewable?->name}")
                    ->limit(20)
                    ->description(fn (Review $record) => class_basename($record->reviewable_type), 'above')
                    ->label(__('admin.reviewable')),
                Tables\Columns\TextColumn::make('stars')
                    ->getStateUsing(fn (Review $record) => str_repeat('⭐', $record->stars))
                    ->sortable()
                    ->label(__('admin.stars')),

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
                Tables\Filters\SelectFilter::make('User')
                    ->relationship('user', 'first_name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('stars')
                    ->options([
                        1 => str_repeat('⭐', 1),
                        2 => str_repeat('⭐', 2),
                        3 => str_repeat('⭐', 3),
                        4 => str_repeat('⭐', 4),
                        5 => str_repeat('⭐', 5),
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getTitle(): string
    {
        return __('admin.Reviews');
    }
}
