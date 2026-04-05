<?php

namespace SmartCms\Menu\Admin\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use SmartCms\Lang\Models\Language;
use SmartCms\Menu\MenuRegistry;
use SmartCms\Support\Admin\Components\Forms\StatusField;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->schema([
                    LeftGrid::make()->schema([
                        Section::make()->schema([
                            TextInput::make('name'),
                        ]),
                        ...self::buildMenuLanguageSchema(),
                    ])->columnSpanFull(),

                ])->columnSpanFull(),
            ]);
    }

    protected static function buildMenuItemsForLanguage(Language $lang): array
    {
        return [
            Repeater::make('items.' . $lang->slug)
                ->label('Menu Items')
                ->hiddenLabel()
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->placeholder('Menu item title')
                        ->live(),
                    Select::make('type')
                        ->label(__('menu::admin.type'))
                        ->options(app(MenuRegistry::class)->all())
                        ->reactive()
                        ->default('link')
                        ->required(),
                    Flex::make(function (Get $get) use ($lang) {
                        $type = $get('type');
                        if (! $type) {
                            return [];
                        }
                        $field = app(MenuRegistry::class)->getSchemaByType($type, $lang->slug);
                        if (! $field) {
                            return [];
                        }

                        return [$field];
                    }),
                    Flex::make([
                        StatusField::make('status')->inline(false),
                        Toggle::make('open_in_new_tab')
                            ->label(__('menu::admin.open_in_new_tab'))
                            ->inline(false)
                            ->default(false),
                    ]),
                ])
                ->columns(2)
                ->defaultItems(0)
                ->reorderableWithButtons()
                ->collapsible(),
        ];
    }

    protected static function buildMenuLanguageSchema(): array
    {
        $languages = app('lang')->adminLanguages();

        if ($languages->count() <= 1) {
            $lang = $languages->first();

            return [
                Section::make()->schema(self::buildMenuItemsForLanguage($lang)),
            ];
        }

        return [
            Tabs::make()->schema(
                $languages->map(fn (Language $lang) => Tab::make($lang->name)->schema(
                    self::buildMenuItemsForLanguage($lang)
                ))->toArray()
            ),
        ];
    }
}
