<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\System\MailSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Mail extends SettingsPage
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 7;

    protected static string $settings = MailSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Mail Settings')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('General')
                            ->icon(Heroicon::OutlinedCog)
                            ->schema([
                                Select::make('driver')
                                    ->label('Mail Driver')
                                    ->options([
                                        'smtp' => 'SMTP (Standard)',
                                        'mailgun' => 'Mailgun',
                                        'ses' => 'Amazon SES',
                                        'postmark' => 'Postmark',
                                    ])
                                    ->native(false)
                                    ->live()
                                    ->columnSpanFull(),

                                Section::make('SMTP Configuration')
                                    ->visible(fn ($get) => $get('driver') === 'smtp')
                                    ->columns()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('host')
                                            ->label('SMTP Host')
                                            ->required(fn ($get) => $get('driver') === 'smtp'),
                                        TextInput::make('port')
                                            ->label('SMTP Port')
                                            ->numeric()
                                            ->required(fn ($get) => $get('driver') === 'smtp'),
                                        Select::make('encryption')
                                            ->label('Encryption')
                                            ->options([
                                                'tls' => 'TLS',
                                                'ssl' => 'SSL',
                                            ])
                                            ->native(false),
                                        TextInput::make('username')
                                            ->label('SMTP Username')
                                            ->required(fn ($get) => $get('driver') === 'smtp'),
                                        TextInput::make('password')
                                            ->label('SMTP Password')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($get) => $get('driver') === 'smtp'),
                                        TextInput::make('timeout')
                                            ->label('Timeout (seconds)')
                                            ->numeric(),
                                        TextInput::make('local_domain')
                                            ->label('Local Domain')
                                            ->helperText('Optional domain for SMTP HELO command'),
                                    ]),

                                Section::make('Mailgun')
                                    ->visible(fn ($get) => $get('driver') === 'mailgun')
                                    ->columns(2)
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('providers.mailgun.domain')
                                            ->label('Domain')
                                            ->required(fn ($get) => $get('driver') === 'mailgun'),
                                        TextInput::make('providers.mailgun.secret')
                                            ->label('Secret')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($get) => $get('driver') === 'mailgun'),
                                        TextInput::make('providers.mailgun.endpoint')
                                            ->label('Endpoint')
                                            ->default('api.mailgun.net'),
                                    ]),

                                Section::make('Postmark')
                                    ->visible(fn ($get) => $get('driver') === 'postmark')
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('providers.postmark.token')
                                            ->label('Token')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($get) => $get('driver') === 'postmark'),
                                    ]),
                                Section::make('Amazon SES')
                                    ->visible(fn ($get) => $get('driver') === 'ses')
                                    ->columns()
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('providers.ses.key')
                                            ->label('Access Key')
                                            ->required(fn ($get) => $get('driver') === 'ses'),
                                        TextInput::make('providers.ses.secret')
                                            ->label('Secret Key')
                                            ->password()
                                            ->revealable()
                                            ->required(fn ($get) => $get('driver') === 'ses'),
                                        Select::make('providers.ses.region')
                                            ->label('Region')
                                            ->options([
                                                'us-east-1' => 'US East (N. Virginia)',
                                                'us-east-2' => 'US East (Ohio)',
                                                'us-west-1' => 'US West (N. California)',
                                                'us-west-2' => 'US West (Oregon)',
                                                'eu-central-1' => 'EU (Frankfurt)',
                                                'eu-west-1' => 'EU (Ireland)',
                                                'eu-west-2' => 'EU (London)',
                                                'ap-southeast-1' => 'Asia Pacific (Singapore)',
                                                'ap-northeast-1' => 'Asia Pacific (Tokyo)',
                                                'ap-south-1' => 'Asia Pacific (Mumbai)',
                                            ])
                                            ->default('us-east-1')
                                            ->native(false),
                                    ]),

                                Section::make('Email Identity')
                                    ->description('Sender details for outgoing emails.')
                                    ->icon(Heroicon::OutlinedIdentification)
                                    ->collapsible()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('from_address')
                                            ->label('From Email Address')
                                            ->email()
                                            ->required(),
                                        TextInput::make('from_name')
                                            ->label('From Name')
                                            ->required(),
                                        TextInput::make('reply_to_address')
                                            ->label('Reply-To Address')
                                            ->email(),
                                        TextInput::make('reply_to_name')
                                            ->label('Reply-To Name'),
                                    ]),
                            ]),

                        Tab::make('Advanced')
                            ->icon(Heroicon::OutlinedCog8Tooth)
                            ->schema([
                                Section::make('Queue Settings')
                                    ->description('Process emails in the background.')
                                    ->icon(Heroicon::OutlinedQueueList)
                                    ->columns()
                                    ->schema([
                                        Toggle::make('queue_emails')
                                            ->label('Queue Emails')
                                            ->helperText('Process emails asynchronously.')
                                            ->live()
                                            ->columnSpanFull(),
                                        TextInput::make('queue_name')
                                            ->label('Queue Name')
                                            ->required(fn ($get) => $get('queue_emails'))
                                            ->visible(fn ($get) => $get('queue_emails')),
                                        Select::make('queue_connection')
                                            ->label('Queue Connection')
                                            ->options([
                                                'database' => 'Database',
                                                'redis' => 'Redis',
                                                'sqs' => 'Amazon SQS',
                                            ])
                                            ->native(false)
                                            ->required(fn ($get) => $get('queue_emails'))
                                            ->visible(fn ($get) => $get('queue_emails')),
                                    ]),

                                Section::make('Rate Limiting')
                                    ->description('Prevent abuse by limiting send rates.')
                                    ->icon(Heroicon::OutlinedShieldExclamation)
                                    ->columns()
                                    ->schema([
                                        Toggle::make('rate_limiting.enabled')
                                            ->label('Enable Rate Limiting')
                                            ->live()
                                            ->columnSpanFull(),
                                        TextInput::make('rate_limiting.attempts')
                                            ->label('Max Attempts')
                                            ->numeric()
                                            ->default(5)
                                            ->visible(fn ($get) => $get('rate_limiting.enabled')),
                                        TextInput::make('rate_limiting.per_minutes')
                                            ->label('Per Minutes')
                                            ->numeric()
                                            ->default(1)
                                            ->visible(fn ($get) => $get('rate_limiting.enabled')),
                                    ]),

                                Section::make('Notification Types')
                                    ->description('Control which email notifications are sent.')
                                    ->icon(Heroicon::OutlinedBell)
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('notifications_enabled')
                                            ->label('Enable All Notifications')
                                            ->live()
                                            ->columnSpanFull(),
                                        Toggle::make('notification_types.account')
                                            ->label('Account Notifications')
                                            ->helperText('Welcome emails, password resets.')
                                            ->visible(fn ($get) => $get('notifications_enabled')),
                                        Toggle::make('notification_types.system')
                                            ->label('System Notifications')
                                            ->helperText('Alerts and critical information.')
                                            ->visible(fn ($get) => $get('notifications_enabled')),
                                        Toggle::make('notification_types.marketing')
                                            ->label('Marketing Emails')
                                            ->helperText('Promotions and newsletters.')
                                            ->visible(fn ($get) => $get('notifications_enabled')),
                                        Toggle::make('notification_types.blog')
                                            ->label('Blog Updates')
                                            ->helperText('New content notifications.')
                                            ->visible(fn ($get) => $get('notifications_enabled')),
                                    ]),

                                Section::make('Debug Options')
                                    ->description('Troubleshooting and testing settings.')
                                    ->icon(Heroicon::OutlinedBugAnt)
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('test_mode')
                                            ->label('Test Mode')
                                            ->helperText('Redirect all emails to the test address.')
                                            ->live(),
                                        TextInput::make('test_to_address')
                                            ->label('Test Recipient')
                                            ->email()
                                            ->required(fn ($get) => $get('test_mode'))
                                            ->visible(fn ($get) => $get('test_mode')),
                                        Select::make('log_channel')
                                            ->label('Log Channel')
                                            ->options([
                                                'stack' => 'Default Stack',
                                                'single' => 'Single File',
                                                'daily' => 'Daily Files',
                                                'slack' => 'Slack',
                                                'null' => 'No Logging',
                                            ])
                                            ->native(false),
                                    ]),
                            ]),

                        Tab::make('Template')
                            ->icon(Heroicon::OutlinedPaintBrush)
                            ->schema([
                                Section::make('Email Appearance')
                                    ->description('Customize the look and feel of your emails.')
                                    ->icon(Heroicon::OutlinedSwatch)
                                    ->columns(2)
                                    ->schema([
                                        Select::make('template_theme')
                                            ->label('Theme')
                                            ->options([
                                                'default' => 'Default',
                                                'minimal' => 'Minimal',
                                                'corporate' => 'Corporate',
                                                'modern' => 'Modern',
                                                'dark' => 'Dark',
                                            ])
                                            ->native(false),
                                        ColorPicker::make('primary_color')
                                            ->label('Primary Color'),
                                        ColorPicker::make('secondary_color')
                                            ->label('Secondary Color'),
                                        FileUpload::make('logo_path')
                                            ->label('Email Logo')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('sites')
                                            ->columnSpan(2),
                                        Textarea::make('footer_text')
                                            ->label('Footer Text')
                                            ->rows(2)
                                            ->columnSpan(2),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
