<?php

namespace App\Livewire;

use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class UserOperationsTable extends Datatable
{

    public $user;
    public $date;

    /**
     * Create a new component instance.
     */
    
    public function __construct()
    {
        // 
    }

    public function mount($user,$startDate=null, $endDate=null)
    {
        $this->user = $user;
        $this->date = [$startDate,$endDate];
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        if($this->date[0] && $this->date[1])
        {
            return Operation::query()->where("user_id",$this->user)->whereBetween("created_at",$this->date)->orderByDesc("updated_at"); 
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return Operation::query()->where("user_id",$this->user)->where("created_at",">=",$this->date[0])->orderByDesc("updated_at"); 
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return Operation::query()->where("user_id",$this->user)->where("created_at","<=",$this->date[1])->orderByDesc("updated_at"); 
        }
        
        return Operation::where("user_id",$this->user)->orderByDesc("updated_at");
    }
    /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            Column::make(__("Customer Phone"),'user.phone')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('phone', 'like', "%$search%");
                    });
                })
                ->format(fn ($phone) => $phone ? "+20" . $phone : ''),
            Column::make(__("Shop"),'device.shop.name'),
            Column::make(__("Device"),'device.device_id')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('device', function($query) use ($search){
                        $query->where('device_id', 'like', "%$search%");
                    });
                }),
            Column::make(__("Borrow Time"),'borrowTime')
                ->sortable()    
                ->searchable()
                ->format(fn ($time) => $time ? chineseToCairoTime($time) : '-'),
            Column::make(__("Return Time"),'returnTime')
                ->sortable()    
                ->searchable()
                ->format(fn ($time) => $time ? chineseToCairoTime($time) : '-'),
            Column::make(__("Amount"),'amount')
                ->sortable()    
                ->searchable()
                ->format(fn ($amount) => $amount ? $amount .' '. __("EGP") : __("Free Oreder")),
            Column::make(__("Operation Date"),'borrowTime')
                ->sortable()    
                ->searchable()
                ->format(fn ($time) => $time ? chineseToCairoTime($time) : '-'),
            Column::make(__("Gifts"),''),
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [
        ];
    }
}
