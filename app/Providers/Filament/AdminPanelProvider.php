<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('SIFCARGO TONIGHT')
            ->brandLogo(asset('app-assets/img/logo/sifcargo.png'))
            ->brandLogoHeight('2.6rem')
            ->favicon(asset('app-assets/img/logo/sifcargo-icon.png'))
            ->font('Plus Jakarta Sans')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16.5rem')
            ->collapsedSidebarWidth('4.5rem')
            ->maxContentWidth(\Filament\Support\Enums\MaxWidth::Full)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->darkMode(true)
            ->colors([
                'primary' => Color::hex('#ed1c24'),
                'gray' => Color::Slate,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::hex('#b91c1c'),
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <style>
                        /* Touch smooth scrolling for tables */
                        .fi-ta-content {
                            -webkit-overflow-scrolling: touch;
                            overflow-x: auto;
                        }
                        /* Responsive adjustments for mobile / small screens */
                        @media (max-width: 768px) {
                            .fi-topbar {
                                position: sticky;
                                top: 0;
                                z-index: 20;
                            }
                            .fi-modal-window {
                                width: 95vw !important;
                                max-width: 95vw !important;
                                margin: 0.5rem auto !important;
                            }
                            .fi-ta-header-actions {
                                flex-wrap: wrap !important;
                                gap: 0.5rem !important;
                            }
                        }
                    </style>
                ')
            )
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Operations & Freight')
                    ->icon('heroicon-o-truck'),
                \Filament\Navigation\NavigationGroup::make('Finance & Accounting')
                    ->icon('heroicon-o-banknotes'),
                \Filament\Navigation\NavigationGroup::make('Partners & CRM')
                    ->icon('heroicon-o-user-group'),
                \Filament\Navigation\NavigationGroup::make('System & Access')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\GreetingWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\RevenueExpenseChart::class,
                \App\Filament\Widgets\InvoiceAgingChart::class,
                \App\Filament\Widgets\ContainerProfitabilityChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
