<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use SmartCms\Menu\Admin\Resources\Menus\MenuResource;
use SmartCms\Menu\MenuPlugin;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    public static function getCluster(): ?string
    {
        return MenuResource::getCluster();
    }

    public function getSubNavigation(): array
    {
        $additionalItems = [];
        if (MenuPlugin::$cluster) {
            foreach (MenuPlugin::$cluster::getClusteredComponents() as $component) {
                $additionalItems = array_merge($additionalItems, $component::getNavigationItems());
            }
        }

        return array_merge(parent::getSubNavigation(), $additionalItems);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                SaveAction::make($this),
                SaveAndClose::make($this, ListMenus::getUrl()),
                DeleteAction::make(),
            ])->link()->label(__('support::admin.actions'))
                ->icon(\Filament\Support\Icons\Heroicon::ChevronDown)
                ->size(\Filament\Support\Enums\Size::Small)
                ->iconPosition(\Filament\Support\Enums\IconPosition::After)
                ->color('primary'),
        ];
    }
}
