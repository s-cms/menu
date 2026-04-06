<?php

namespace SmartCms\Menu;

use Filament\Forms\Components\Field;
use InvalidArgumentException;
use SmartCms\Menu\MenuTypes\LinkMenuType;
use SmartCms\Menu\MenuTypes\NestedMenuType;

class MenuRegistry
{
    protected array $menus = [];

    public function __construct()
    {
        $this->register(LinkMenuType::class);
        $this->register(NestedMenuType::class);
    }

    public function register(string $class): void
    {
        if (! is_subclass_of($class, MenuTypeInterface::class)) {
            throw new InvalidArgumentException("Class $class must implement MenuTypeInterface");
        }
        $this->menus[(new $class)->getType()] = $class;
    }

    public function get(string $type): ?MenuTypeInterface
    {
        if (isset($this->menus[$type])) {
            return new $this->menus[$type];
        }

        return null;
    }

    public function all(): array
    {
        return collect($this->menus)->mapWithKeys(fn (string $class) => [(new $class)->getType() => (new $class)->getLabel()])->toArray();
    }

    public function getSchemaByType(string $type, ?string $language = null): ?Field
    {
        $schema = $this->get($type)?->getSchema($language);
        if (! $schema) {
            return null;
        }

        return $schema->required()->columnSpanFull();
    }

    public function getLinkByType(array $item): string | array
    {
        return $this->get($item['type'])?->getLinkFromItem($item);
    }
}
