<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\CreateRecord;
use SmartCms\Menu\Admin\Resources\Menus\MenuResource;
use SmartCms\Menu\MenuPlugin;
use SmartCms\Support\Admin\Components\Actions\SaveAction;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;

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
            ])->link()->label(__('support::admin.actions'))
                ->icon(\Filament\Support\Icons\Heroicon::ChevronDown)
                ->size(\Filament\Support\Enums\Size::Small)
                ->iconPosition(\Filament\Support\Enums\IconPosition::After)
                ->color('primary'),
        ];
    }
}
