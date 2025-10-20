<?php

namespace SmartCms\Menu\MenuTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SmartCms\Menu\MenuTypeInterface;
use SmartCms\Menu\Models\Menu;

class NestedMenuType implements MenuTypeInterface
{
    public function getType(): string
    {
        return 'nested';
    }

    public function getLabel(): string
    {
        return __('menu::admin.nested_menu');
    }

    public function getSchema(): Field
    {
        return Select::make('url')
            ->label(__('menu::admin.select_menu'))
            ->options(fn (?Model $record) => Menu::query()
                ->when($record && $record instanceof Menu, fn (Builder $query) => $query->where('id', '!=', $record->id))
                ->pluck('name', 'id'))
            ->searchable()
            ->required();
    }

    public function getLinkFromItem(mixed $item): string | array
    {
        // if (! isset($item['menu_id'])) {
        //     return '#';
        // }

        $nestedMenu = Menu::find($item['url']);
        if (! $nestedMenu) {
            return '#';
        }

        return [
            'url' => '',
            'children' => $nestedMenu->links,
        ];
    }
}
