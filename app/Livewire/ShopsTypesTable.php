<?php

namespace App\Livewire;

use App\Models\Shop;
use App\Models\ShopsType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class ShopsTypesTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
               if (static::can('dashboard.shop-types.create')) {
            $this->create = 'dashboard.shop-types.create';
        }
    }

       /**
     * Query builder.
     */
    public function query(): Builder
    {
        return ShopsType::query()->orderByDesc("updated_at");
    }

        /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            Column::make('#',"id")
                ->width('50px'),
            Column::make(__("Arabic") ." ". __("Name"),'type_ar_name'),
            Column::make(__("English") ." ". __("Name"),'type_en_name'),
            Column::make( __("type")." ". __("icon"),'type_icon')
                ->format(fn ($icon) => $icon ? view('components.icon', ['icon' => "<img style='width:50px;' src='" . $icon . "' />"]) : ''),
            Column::make(__ ("Arabic") ." ". __("Access"),'type_ar_name'),
            Column::make(__ ("English") ." ". __("Access"),'type_en_name'),             
            Column::make(__("access")." ". __("icon"),'access_icon')
                ->format(fn ($icon) => $icon ? view('components.icon', ['icon' => "<img style='width:50px;' src='" . $icon . "' />"]) : ''),
            Column::make(__("Created At"),'created_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i')),
            Column::make(__("Updated At"),'updated_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i')),
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [
                    Action::edit('dashboard.shop-types.edit')->can('dashboard.shop-types.edit'),
                    Action::delete('dashboard.shop-types.destroy')->can('dashboard.shop-types.destroy'),
                ];
    }
}
