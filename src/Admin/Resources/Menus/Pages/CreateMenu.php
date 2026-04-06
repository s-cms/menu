<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Pages;

use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
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
            ActionGroup::make([
                SaveAction::make($this),
            ])->link()->label(__('support::admin.actions'))
                ->icon(Heroicon::ChevronDown)
                ->size(Size::Small)
                ->iconPosition(IconPosition::After)
                ->color('primary'),
        ];
    }
}
