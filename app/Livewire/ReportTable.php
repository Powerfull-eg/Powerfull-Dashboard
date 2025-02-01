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
            Column::make(__('Shop'),"shop.id")
            ->format(fn ($id) => view('components.icon', ['icon' => "<a href='" . route("dashboard.reports.shop",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"])),
            Column::make(__('Service Number'),"sim_number")
                ->searchable()
                ->sortable(),
            Column::make(__('Total Operations Orders'),"operations")
                ->format(fn ($operations) => $operations->count())
                ->sortable(),
            Column::make(__('Total Operating Hours'),"operations")
                ->format(function($operations) {
                    $hours = 0;
                    foreach ($operations as $operation) {
                        $hours += $operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0;
                    }
                    return intval($hours);
                })
                ->sortable(),
            Column::make(__('Total Amount'),"operations")
                ->format(fn($operations) => $operations->sum('amount'))
                ->sortable(),
            Column::make(__('Partnership Share'),"shop.share_percentage")
                ->sortable()
                ->format(fn ($share) => $share . "%"),
            Column::make(__('Total Partner Share'),"shop")
                ->sortable()
                ->format(fn ($shop) => intval(($shop->share_percentage / 100) * $shop->operations->sum('amount')) . " EGP"),
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
