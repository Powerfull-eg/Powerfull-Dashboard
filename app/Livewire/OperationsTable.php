<?php

namespace App\Livewire;

use App\Models\Operation;
use App\Models\User;
use App\Models\VoucherOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class OperationsTable extends Datatable
{    

    public $date;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // 
    }
    private function voucherAmount($id)
    {
        $voucher = VoucherOrder::where("order_id",$id)->first();
        $amount = ($voucher ? $voucher->voucher->value . ($voucher->voucher->type ? ' EGP' : '%') : null);
        return $amount;
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
            return Operation::query()->whereBetween("created_at",$this->date)->orderByDesc("updated_at");
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return Operation::query()->where("created_at",">=",$this->date[0])->orderByDesc("updated_at");
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return Operation::query()->where("created_at","<=",$this->date[1])->orderByDesc("updated_at");
        }
        return Operation::query()->orderByDesc("updated_at");
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            Column::make('#',"id")
                ->width('50px') 
                ->sortable(),
            Column::make('User',"user")
                ->format(fn($user) => $user ? $user->fullName : null)
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%");
                    });
                }),
            Column::make('Device',"station_id")
                ->searchable(),
            Column::make('Powerbank',"powerbank_id")
                ->searchable(),
            Column::make('Borrow Time',"borrowTime")
                ->searchable()
                ->format(fn ($time) => $time ? chineseToCairoTime($time) : '-'),
            Column::make('Return Time',"returnTime")
                ->searchable()
                ->format(fn ($time) => $time ? chineseToCairoTime($time) : '-'),
            Column::make('Borrow Slot',"borrowSlot"),
            Column::make('Shop',"device.shop_id")
                ->searchUsing(function ($query, $search){
                    $query->whereHas('device', function($query) use ($search){
                        $query->where('shop_id', 'like', "%$search%");
                    });
                })
            ->format(fn ($shop) => $shop ? view('components.icon', ['icon' => "<a href='" . route("dashboard.shops.show",$shop) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make('Has Voucher',"id")
                ->format(fn($id) => $this->voucherAmount($id)),
            Column::make('Net Amount',"amount")
                ->format(fn($amount) => $amount  ? $amount . " EGP" : '0 EGP'),
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
