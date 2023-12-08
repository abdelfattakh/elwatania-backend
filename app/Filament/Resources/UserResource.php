<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Widgets\AdminStatusOverview;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\UserResource\Widgets\UserStatusOverview;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Nette\NotImplementedException;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('first_name')
                        ->required()->label(__('admin.first_name')),

                    TextInput::make('last_name')
                        ->required()->label(__('admin.last_name')),


                    TextInput::make('email')->label(__('admin.email'))
                        ->disabled(fn(?User $record): bool => filled($record))
                        ->hint(fn(?User $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'Verified' : 'Un-Verified') : null)
                        ->hintIcon(fn(?User $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation') : null)
                        ->hintColor(fn(?User $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'success' : 'danger') : null)
                        ->hintAction(fn(?User $record): ?Action => filled($record) && !$record->hasVerifiedEmail() ? self::getEmailSendAction($record) : null)
                        // TODO: Passing $set as argument is a workaround, find another solution.
                        ->suffixAction(fn(?User $record, Closure $set): ?Action => filled($record) ? self::getEmailEditAction($record, $set) : null)
                        ->email()
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->label(__('admin.password'))
                        ->dehydrated(fn($state) => filled($state))
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->required(fn(string $context): bool => $context === 'create')
                        ->confirmed()
                        ->password()
                        ->minLength(8)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->dehydrated(fn($state) => filled($state))
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->required(fn(string $context): bool => $context === 'create')
                        ->password()
                        ->minLength(8)
                        ->maxLength(255)
                        ->label(__('admin.password_confirmation')),

                ]),


            ]);
    }

    public static function getEmailSendAction(?User $record): ?Action
    {
        if (!filled($record)) {
            return null;
        }

        return Action::make('send_verify_email')
            ->icon('heroicon-o-at-symbol')
            ->action(function () use ($record) {
                $record->sendEmailVerificationNotification();
                Notification::make('send_verify_email')
                    ->success()
                    ->icon('heroicon-o-shield-check')
                    ->title('Verification Email')
                    ->body('new Verification email sent to: ' . $record->email)
                    ->send();
            });
    }

    public static function getEmailEditAction(?User $record, Closure $set): ?Action
    {
        if (!filled($record)) {
            return null;
        }

        return Action::make('edit_email')
            ->icon('heroicon-o-pencil-alt')
            ->form([
                TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default($record->email),
                Select::make('verification')
                    ->required()
                    ->default('send_verification_email')
                    ->options([
                        'mark_as_verified' => 'Change and Mark as Verified Now',
                        'send_verification_email' => 'Change and Send Verification Email',
                        'send_new_email_change_request' => 'Send new Email Change Request',
                    ]),
            ])
            ->action(function ($data) use ($record, $set) {
                if ($data['verification'] != 'send_new_email_change_request') {
                    $record->update(['email' => $data['email'], 'email_verified_at' => null]);
                    $set('email', $data['email']);
                } else {
                    // TODO: 'send_new_email_change_request' using sendNewEmail();
//                    $record->newEmail($data['email']);
                    throw new NotImplementedException();
                }

                match ($data['verification']) {
                    'mark_as_verified' => $record->markEmailAsVerified(),
                    'send_verification_email' => $record->sendEmailVerificationNotification(),
                    'send_new_email_change_request' => throw new NotImplementedException(),
                    default => throw ValidationException::withMessages(['verification' => __('validation.required', ['attribute' => 'verification'])]),
                };

                Notification::make('email_changed')
                    ->title('Email Alert')
                    ->body('Your email has been ' . ($data['verification'] != 'send_new_email_change_request' ? 'changed' : 'requested to change') . ' to: ' . $data['email'])
                    ->sendToDatabase($record);

                Notification::make('email_changed')
                    ->title('Email Alert')
                    ->body('email has been ' . ($data['verification'] != 'send_new_email_change_request' ? 'changed' : 'requested to change') . ' to: ' . $data['email'])
                    ->send();
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label(__('admin.id')),
                TextColumn::make('first_name')->sortable()->searchable()->label(__('admin.first_name')),
                TextColumn::make('last_name')->sortable()->searchable()->label(__('admin.last_name')),
                IconColumn::make('email_verified_at')
                    ->options([
                        'heroicon-o-check-circle'=>fn ($state): bool =>filled($state),
                        'heroicon-o-x-circle'=>fn ($state): bool =>!filled($state),
                    ])  ->colors([
                        'success'=>fn ($state): bool => filled($state) ,
                        'danger'=>fn ($state): bool =>!filled($state),

                    ])->label(__('admin.email_verified_at')),
                TextColumn::make('email')->sortable()->searchable()->label(__('admin.email')),

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
                ExportBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ReviewsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getLabel(): string
    {
        return __('admin.User');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.Users');

    }
    public static function getWidgets(): array
    {
        return [
            UserStatusOverview::class,
        ];
    }


    protected static function getNavigationGroup(): ?string
    {
        return __('admin.users');
    }


    
}
