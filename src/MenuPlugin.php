<?php

namespace SmartCms\Menu;

use Filament\Contracts\Plugin;
use Filament\Panel;
use SmartCms\Menu\Admin\Resources\Menus\MenuResource;

class MenuPlugin implements Plugin
{
    public static ?string $navigationGroup = null;

    public static ?string $cluster = null;

    public function getId(): string
    {
        return 'menu';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            MenuResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(?string $navigationGroup = null, ?string $cluster = null): static
    {
        static::$navigationGroup = $navigationGroup;

        static::$cluster = $cluster;

        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
