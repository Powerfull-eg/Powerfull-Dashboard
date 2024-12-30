<?php

namespace App\Livewire;

use App\Models\Device;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class ReportTable extends Datatable
{
    private $startDate;
    private $endDate;

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
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    } 
    /**
     * Query builder.
     */
    public function query(): Builder
    {
        $devices = $this->startDate ? Device::where('created_at','>=',$this->startDate) : new Device;
        $devices = $this->endDate ? $devices->where('created_at','<=',$this->endDate) : $devices;
        
        return ($this->startDate === null && $this->endDate === null ? $devices->query() : $devices);
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            Column::make(__('Powerfull ID'),"powerfull_id")
                ->width('50px')
                ->searchable()
                ->sortable(),
            Column::make(__('Device ID'),"device_id")
                ->searchable()
                ->sortable(),
            Column::make(__('Shop'),"shop.name")
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('shop', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
                }),
            Column::make(__('Service Number'),"sim_number")
                ->searchable()
                ->sortable(),
            Column::make(__('Total Operations Orders'),"operations_count")
                ->sortable(),
            Column::make(__('Total Operating Hours'),"operations_time")
                ->sortable(),
            Column::make(__('Total Amount'),"operations_amount")
                ->sortable(),
            Column::make(__('Partnership Share'),"shop.id")
                ->sortable()
                ->format(fn ($share) => 0 . "%"),
            Column::make(__('Total Partner Share'),"shop.id")
                ->sortable()
                ->format(fn ($share) => 0 . "%"),
            Column::make(__('Export'),"shop.phone"),
            Column::make(__('Send Whatsapp'),"shop.phone")
      ];
    }

    /**
     * Data table actions.
     */
    // public function actions()
    // {
    //     //
    // }
}
