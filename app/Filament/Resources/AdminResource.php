<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\Widgets\AdminStatusOverview;
use App\Models\Admin;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Card;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Validation\ValidationException;
use Nette\NotImplementedException;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

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

                        Forms\Components\TextInput::make('email')
                            ->unique(ignoreRecord: true)
                            ->label(__('admin.email'))
                            ->disabled(fn (?Admin $record): bool => filled($record))
                            ->hint(fn (?Admin $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'Verified' : 'Un-Verified') : null)
                            ->hintIcon(fn (?Admin $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'heroicon-o-shield-check' : 'heroicon-o-shield-exclamation') : null)
                            ->hintColor(fn (?Admin $record): ?string => filled($record) ? ($record->hasVerifiedEmail() ? 'success' : 'danger') : null)
                            ->hintAction(fn (?Admin $record): ?Action => filled($record) && !$record->hasVerifiedEmail() ? self::getEmailSendAction($record) : null)
                            // TODO: Passing $set as argument is a workaround, find another solution.
                            ->suffixAction(fn (?Admin $record, Closure $set): ?Action => filled($record) ? self::getEmailEditAction($record, $set) : null)
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('password')
                            ->label(__('admin.password'))
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->confirmed()
                            ->password()
                            ->minLength(8)
                            ->maxLength(255),

                            
                        Forms\Components\TextInput::make('password_confirmation')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->label(__('admin.password_confirmation')),

                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.id'))
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('admin.name')),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable()
                    ->label(__('admin.email')),
                IconColumn::make('email_verified_at')
                    ->options([
                        'heroicon-o-check-circle' => fn ($state): bool => filled($state),
                        'heroicon-o-x-circle' => fn ($state): bool => !filled($state),
                    ])->colors([
                        'success' => fn ($state): bool => filled($state),
                        'danger' => fn ($state): bool => !filled($state),

                    ])->label(__('admin.email_verified_at')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()
                    ->label(__('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(__('admin.updated_at'))
                    ->toggleable()

            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getEmailSendAction(?Admin $record): ?Action
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


    public static function getEmailEditAction(?Admin $record, Closure $set): ?Action
    {
        if (!filled($record)) {
            return null;
        }

        return Action::make('edit_email')
            ->icon('heroicon-o-pencil-alt')
            ->form([
                Forms\Components\TextInput::make('email')
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

    public static function getLabel(): string
    {
        return __('admin.admin');
    }

    public static function getPluralLabel(): string
    {
        return __('admin.admins');
    }

    public static function getWidgets(): array
    {
        return [
            AdminStatusOverview::class,
        ];
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('admin.users');
    }
}
