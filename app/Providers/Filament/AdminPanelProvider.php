<?php

namespace App\Providers\Filament;

use AchyutN\FilamentLogViewer\FilamentLogViewer;
use App\Filament\Pages\HealthCheckResults;
use App\Livewire\PersonalInfo;
use App\Models\Menu;
use App\Policies\ExceptionPolicy;
use App\Policies\MenuPolicy;
use App\Services\MultiLanguageService;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use BezhanSalleh\FilamentExceptions\Models\Exception;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel;
use Filament\Forms\Components\DateTimePicker;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Livewire\Livewire;
use Relaticle\ActivityLog\Filament\ActivityLogPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                'Blog',
                'Appearance',
                'Settings',
                'System',
            ])
            ->plugins([
                FilamentLogViewer::make()
                    ->authorize(fn (): bool => auth()->user()?->can('View:LogTable') ?? false),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->authorize(fn (): bool => auth()->user()?->can('View:Backups') ?? false),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class)
                    ->authorize(fn (): bool => auth()->user()?->can('View:HealthCheckResults') ?? false),
                SpatieTranslatablePlugin::make()
                    ->defaultLocales(app(MultiLanguageService::class)->getSupportedLocales())
                    ->persist(),
                FilamentExceptionsPlugin::make()
                    ->navigationGroup('System'),
                FilamentShieldPlugin::make()
                    ->navigationGroup('Settings')
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                BreezyCore::make()
                    ->myProfile(
                        hasAvatars: true,
                        navigationGroup: 'Settings'
                    )
                    ->myProfileComponents([
                        'personal_info' => PersonalInfo::class,
                    ])
                    ->enableSanctumTokens()
                    ->enableBrowserSessions(),
                ActivityLogPlugin::make(),
                $this->menuPlugin(),
            ]);
    }

    private function menuPlugin(): FilamentMenuBuilderPlugin
    {
        $plugin = FilamentMenuBuilderPlugin::make()
            ->navigationGroup('Appearance')
            ->usingMenuModel(Menu::class)
            ->addLocations([
                'header' => 'Header',
                'footer' => 'Footer',
                'footer-2' => 'Footer 2',
                'footer-3' => 'Footer 3',
                'footer-4' => 'Footer 4',
            ])
            ->addMenuPanels([
                StaticMenuPanel::make()
                    ->addMany([
                        'Home' => url('/'),
                        'Blog' => url('/blog'),
                        'Contact Us' => url('/contact-us'),
                    ])
                    ->description('Default menus')
                    ->collapsed(true)
                    ->collapsible(true)
                    ->paginate(perPage: 5, condition: true),
            ]);

        $multiLanguage = app(MultiLanguageService::class);

        $locales = $multiLanguage->isEnabled()
            ? $multiLanguage->getSupportedLocales()
            : [$multiLanguage->getDefaultLocale()];

        $plugin->translatable($locales)
            ->translatableMenuItemFields(['title'])
            ->translatableMenuFields(['name']);

        return $plugin;
    }

    public function boot(): void
    {
        Livewire::component('personal_info', PersonalInfo::class);

        DateTimePicker::configureUsing(function (DateTimePicker $field) {
            $field->timezone(auth()->user()->timezone ?? config('app.timezone'));
        });

        TextColumn::configureUsing(function (TextColumn $field) {
            $field->timezone(auth()->user()->timezone ?? config('app.timezone'));
        });

        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(Exception::class, ExceptionPolicy::class);

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            ScheduleCheck::new(),
            CacheCheck::new(),
            UsedDiskSpaceCheck::new(),
            QueueCheck::new(),
        ]);
    }
}
