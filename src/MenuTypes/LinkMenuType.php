<?php

namespace SmartCms\Menu\MenuTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use SmartCms\Menu\MenuTypeInterface;

class LinkMenuType implements MenuTypeInterface
{
    public function getType(): string
    {
        return 'link';
    }

    public function getLabel(): string
    {
        return __('menu::admin.link');
    }

    public function getSchema(?string $language = null): Field
    {
        return TextInput::make('url')->label(__('menu::admin.url'));
    }

    public function getLinkFromItem(mixed $item): string | array
    {
        return asset($item['url'] ?? '#');
    }
}
