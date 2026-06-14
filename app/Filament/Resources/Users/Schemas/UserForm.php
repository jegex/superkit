<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Settings\System\MailSettings;
use DateTimeZone;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Callout::make('User info')
                    ->description(function (?User $record): HtmlString {
                        if (! $record) {
                            return new HtmlString('<span class="text-sm text-gray-500">Save to see user details! 😊</span>');
                        }
                        $name = trim(($record->firstname ?? '').' '.($record->lastname ?? ''));
                        $joined = $record->created_at?->format('M j, Y \a\t g:i A') ?? 'just now';
                        $updated = $record->updated_at?->diffForHumans() ?? 'never';
                        $updatedExact = $record->updated_at?->format('M j, Y \a\t g:i A') ?? '';
                        if ($record->email_verified_at) {
                            $verified = $record->email_verified_at->format('M j, Y \a\t g:i A');
                            $sentence = "<span class='font-semibold'>$name</span> joined $joined and has been <span class='font-semibold' title='Verified on $verified'>verified</span> since then. Last updated <span class='font-semibold' title='Updated on $updatedExact'>$updated</span>.";
                        } else {
                            $sentence = "<span class='font-semibold'>$name</span> joined $joined and hasn't verified their email yet. Last updated <span class='font-semibold' title='Updated on $updatedExact'>$updated</span>.";
                        }

                        return new HtmlString($sentence);
                    })
                    ->columnSpanFull()
                    ->hidden(fn (string $operation): bool => $operation === 'create'),
                Group::make([
                    SpatieMediaLibraryFileUpload::make('media')
                        ->helperText('Upload a clear, square image. Max size: 1MB.')
                        ->hiddenLabel()
                        ->avatar()
                        ->collection('avatars')
                        ->alignCenter()
                        ->columnSpanFull()
                        ->image()
                        ->imagePreviewHeight('80')
                        ->automaticallyCropImagesToAspectRatio('1:1')
                        ->automaticallyResizeImagesMode('cover')
                        ->automaticallyResizeImagesToWidth('256')
                        ->automaticallyResizeImagesToHeight('256')
                        ->maxSize(1024)
                        ->maxFiles(1),
                    Section::make()
                        ->schema([
                            TextInput::make('password')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->revealable()
                                ->required(),
                            TextInput::make('passwordConfirmation')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->revealable()
                                ->same('password')
                                ->required(),
                        ])
                        ->compact()
                        ->hidden(fn (string $operation): bool => $operation === 'edit'),
                ])->columnSpan(1),

                Tabs::make()
                    ->schema([
                        Tab::make('Details')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->columns()
                            ->schema([
                                TextInput::make('username')
                                    ->required()
                                    ->maxLength(255)
                                    ->live()
                                    ->rules(function ($record) {
                                        $userId = $record?->id;

                                        return $userId
                                            ? ['unique:users,username,'.$userId]
                                            : ['unique:users,username'];
                                    }),
                                TextInput::make('email')
                                    ->helperText('Changing the email may require re-verification.')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->rules(function ($record) {
                                        $userId = $record?->id;

                                        return $userId
                                            ? ['unique:users,email,'.$userId]
                                            : ['unique:users,email'];
                                    })
                                    ->disabled(fn (string $operation) => $operation === 'edit'),
                                TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('lastname')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('timezone')
                                    ->options(fn () => collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                                    ->searchable()
                                    ->default(config('app.timezone')),
                            ]),
                        Tab::make('Roles')
                            ->icon(Heroicon::OutlinedShieldCheck)
                            ->schema([
                                Select::make('roles')
                                    ->hiddenLabel()
                                    ->relationship('roles', 'name')
                                    ->helperText('Select one or more roles for this user. Roles determine access and permissions.')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->optionsLimit(5)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(2),
            ]);
    }

    private static function doResendEmailVerification(Model|User $record, MailSettings $settings) {}
}
