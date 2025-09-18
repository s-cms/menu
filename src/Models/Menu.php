<?php

namespace SmartCms\Menu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use SmartCms\Menu\MenuRegistry;
use Spatie\Translatable\HasTranslations;

/**
 * Class Menu
 *
 * @property int $id The unique identifier for the model.
 * @property string $name The name of the menu.
 * @property array|null $items The menu items configuration.
 * @property-read Collection $links The menu links.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 */
class Menu extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];

    protected $translatable = ['items'];

    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('menu.menus_table_name', 'menus');
    }

    public function links(): Attribute
    {
        return Attribute::make(
            get: function (): Collection {
                return collect($this->items)->map(function ($item) {
                    $data = [
                        'title' => $item['title'] ?? 'Untitled',
                    ];
                    $url = app(MenuRegistry::class)->getLinkByType($item);
                    if (is_array($url)) {
                        $data = array_merge($data, $url);
                    } else {
                        $data['url'] = $url;
                    }
                    $data['is_external'] = $item['open_in_new_tab'] ?? false;
                    $data['status'] = !!$item['status'];

                    return $data;
                })->filter(function ($item) {
                    return $item['status'] === true;
                });
            }
        );
    }
}
