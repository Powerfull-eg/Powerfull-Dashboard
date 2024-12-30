<?php

namespace App\Livewire;

use App\Http\Controllers\Api\BajieController;
use App\Models\Device;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class DevicesTable extends Datatable
{
    public $date;
    public $devices; 
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // $this->getDevices();
    }
    // Mount Data
    public function mount($startDate=null, $endDate=null)
    {
        $this->date = [$startDate,$endDate];
    }

    // Get Devices
    public function getDevices(){
        $devices = new BajieController;
        $devices = $devices->getDevices();
        $devices =  json_decode($devices->getContent(),true);
        $all_devices = [];
        foreach($devices as $device){
            $all_devices[$device["DeviceId"]] = $device;
        }
        $this->devices = $all_devices;
        return $this->devices;
    }
    /**
     * Query builder.
     */
    public function query(): Builder
    {
        if($this->date[0] && $this->date[1])
        {
            return Device::query()->whereBetween("created_at",$this->date)->orderByDesc("updated_at"); 
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return Device::query()->where("created_at",">=",$this->date[0])->orderByDesc("updated_at"); 
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return Device::query()->where("created_at","<=",$this->date[1])->orderByDesc("updated_at"); 
        }
        
        return Device::query()->orderByDesc("updated_at");
    }

        /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            // Column::make('#',"id")
            //     ->width('50px') 
            //     ->sortable(),
            Column::make(__('Device ID'),"device_id")
                ->searchable()
                ->sortable(),
            Column::make('Shop',"shop_id")
                ->searchable()
                ->format(fn ($shop) => $shop ? view('components.icon', ['icon' => "<a href='" . route("dashboard.shops.show",$shop) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            // Column::make('Live',"device_id")
            // ->format(fn($device) => ($this->devices[$device] ? (isset($this->devices[$device]["data"]["cabinet"]["online"]) ? "<i class='text text-success fs-1 ti ti-checkbox'></i>":"<i class='text text-danger fs-1 ti ti-circle-x'></i>" )  : null) ),
            // Column::make('Type',"device_id")
            // ->format(fn($device) => ($this->devices[$device] ? 'Type: ' . (isset($this->devices[$device]["data"]["cabinet"]["online"]) ? $this->devices[$device]["data"]["cabinet"]["type"] : null)  : null) ),
            Column::make('Slots',"slots")
                ->sortable(),
            // Column::make('Empty Slots',"device_id")
            //     ->sortable()
            //     ->format(fn($device) => ($this->devices[$device] ? (isset($this->devices[$device]["data"]["cabinet"]["online"]) ? $this->devices[$device]["data"]["cabinet"]["emptySlots"]: null) : null) ),
            Column::make('Added At',"created_at")
                ->sortable()
                ->format(fn ($date) => ($date ? $date->format('d M Y h:m:i') : '' )),
            Column::make('Updated At',"updated_at")
                ->sortable()
                ->format(fn ($date) => ($date ? $date->format('d M Y h:m:i') : '' )),
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
