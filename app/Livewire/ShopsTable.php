<?php

namespace App\Livewire;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class ShopsTable extends Datatable
{
    public $date;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
       //
    }

    //Mount Data
    public function mount($startDate=null, $endDate=null)
    {
        $this->date = [$startDate,$endDate];
    } 
    /**
     * Query builder.
     */
    public function query(): Builder
    {
        if($this->date[0] && $this->date[1])
        {
            return Shop::query()->whereBetween("created_at",$this->date)->orderByDesc("updated_at"); 
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return Shop::query()->where("created_at",">=",$this->date[0])->orderByDesc("updated_at"); 
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return Shop::query()->where("created_at","<=",$this->date[1])->orderByDesc("updated_at"); 
        }

        return Shop::query()->orderByDesc("updated_at");
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
            Column::make('Show',"id")
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.shops.show",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make(__("Name"),'name')
                ->sortable()
                ->searchable(),
            Column::make(__("Phone"),'phone')
                ->sortable()
                ->searchable(),
            Column::make(__("Shop ID"),'provider_id')
                ->sortable()
                ->searchable(),
            Column::make(__("Logo"),'logo')
                ->format(fn ($logo) => $logo ? view('components.icon', ['icon' => "<img style='width:50px;' src='" . $logo . "' />"]) : ''),
            // Column::make(__('Address'), 'address')
            //     ->class("ellipsis")->width("40px")
            //     ->searchable(),
            Column::make(__('City'), 'city')
                ->searchable(),
            Column::make(__('Governorate'), 'governorate')
                ->searchable(),
            Column::make(__('Created At'), 'created_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i'))
                ->searchable()
                ->sortable(),
            Column::make(__('Last Update'), 'updated_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i'))
                ->searchable()
                ->sortable(),
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [];
    }
}
