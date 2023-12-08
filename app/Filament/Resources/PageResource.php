<?php

namespace App\Filament\Resources;

use App\Enums\PagesTypeEnum;
use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->label(__('admin.title')),
                    Forms\Components\RichEditor::make('description')
                        ->required()
                        ->label(__('admin.description')),

                    Forms\Components\Select::make('show')
                        ->options([
                            'customer_service' => __('admin.customer_service_part'),
                            'about_watania' => __('admin.about_watania'),
                        ])
                        ->required()
                        ->label(__('admin.show')),

                    Forms\Components\Toggle::make('is_active')
                        ->required()
                        ->label(__('admin.is_active')),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label(__('admin.title')),
                Tables\Columns\TextColumn::make('description')
                    ->limit(20)
                    ->label(__('admin.description')),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('show')
                    ->limit(20)
                    ->label(__('admin.show')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()
                    ->label(__('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->label(__('admin.updated_at'))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort(config('eloquent-sortable.order_column_name'))
            ->reorderable(config('eloquent-sortable.order_column_name'));
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('admin.page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.pages');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.general');
    }
}
