<?php

namespace SmartCms\Menu\Admin\Resources\Menus;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use SmartCms\Menu\Admin\Resources\Menus\Pages\CreateMenu;
use SmartCms\Menu\Admin\Resources\Menus\Pages\EditMenu;
use SmartCms\Menu\Admin\Resources\Menus\Pages\ListMenus;
use SmartCms\Menu\Admin\Resources\Menus\Schemas\MenuForm;
use SmartCms\Menu\Admin\Resources\Menus\Tables\MenusTable;
use SmartCms\Menu\MenuPlugin;
use SmartCms\Menu\Models\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedNumberedList;

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('menu::admin.menu');
    }

    public static function getNavigationGroup(): ?string
    {
        return MenuPlugin::$navigationGroup ? __(MenuPlugin::$navigationGroup) : null;
    }

    public static function getCluster(): ?string
    {
        return MenuPlugin::$cluster ? __(MenuPlugin::$cluster) : null;
    }

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
