<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\VoucherOrder;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class CampaingVouchersTable extends Datatable
{
    private $campaign;

    public function mount($campaign)
    {
        $this->campaign = $campaign;
    }

    public function getUsageCount($id)
    {
        return VoucherOrder::where('voucher_id', $id)->count();
    }

    public function query() : Builder
    {
        return Voucher::where('campaign_id', $this->campaign->id);
    }

    public function columns() : array
    {
        return [
            Column::make('ID','id'),
            Column::make(__('Code'), 'code')
                ->sortable(),
            Column::make(__('Used'),"voucherOrder.count"),
            Column::make(__('Usage'),"id")
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.vouchers.show",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make(__('Value'), 'value')
                ->sortable(),
            Column::make(__('Min Amount'), 'min_amount')
                ->sortable(),
            Column::make(__('Max Discount'), 'max_discount')
                ->sortable(),
            Column::make(__('Starts At'), 'from')
                ->sortable(),
            Column::make(__('Expires At'), 'to'),
            Column::make(__('Created At'), 'created_at'),
            Column::make(__('Updated At'), 'updated_at'),  
        ];
    }
}
