<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\VoucherOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class VouchersUsageTable extends Datatable
{
    private $voucherId;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }
    
    public function mount($id){
        $this->voucherId = $id;
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return VoucherOrder::where('voucher_id',$this->voucherId);
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('#','id'),
            Column::make(__('User'), 'user.fullName')
                ->searchable()
                ->sortable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                    });
                }),
            Column::make(__('Code'), 'voucher.code')
                ->sortable(),
            Column::make(__('Order'), 'order.id')
                ->sortable(),
            Column::make(__('Used At'), 'added_at')
                ->sortable(),
            Column::make(__('Created At'), 'created_at')
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
        return [
            Action::edit('dashboard.vouchers.edit')->can('dashboard.vouchers.edit'),
            Action::delete('dashboard.vouchers.destroy')->can('dashboard.vouchers.destroy'),
        ];
    }
}
