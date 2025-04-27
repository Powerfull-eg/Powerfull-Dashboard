<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class CampaingsTable extends Datatable
{
        /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Campaign::with('vouchers');
    }

    private function getVocuherValue(string $id)
    {
        $voucher = Voucher::where('id', $id)->first('value');
        return $voucher;
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('ID','id'),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Description'), 'description')
                ->searchable()
                ->sortable()
                ->format(fn ($description) => html_entity_decode(strlen($description) > 20 ? substr($description, 0, 20) . '...' : $description)),
            Column::make('Usage',"id")
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.vouchers.campaign.show",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make(__('Type'), 'type')
                ->format(fn ($type) => ($type == 0 ? __('Percentage'): __('Amount')) . ' ' . view('components.icon', ['icon' => "<i class='fs-2 ti ti-" .($type == 0 ? "percentage":"cash") ."'></i>"]))
                    ->sortable(),
            Column::make(__('Value'), 'id')
                ->format(fn ($id) => $this->getVocuherValue($id)->value . ' ' . view('components.icon', ['icon' => "<i class='fs-2 ti ti-" .($this->getVocuherValue($id)->type == 0 ? "percentage":"cash") ."'></i>"]))
                ->sortable(),
            Column::make(__('Min Amount'), 'min_amount')
                ->format(fn ($amount) => $amount ?? 0 . " EGP")
                ->sortable(),
            Column::make(__('Max Discount'), 'max_discount')
                ->format(fn ($amount) => $amount ?? 0 . " EGP")
                ->sortable(),
            Column::make(__('Starts At'), 'start_date')
                ->format(fn ($date) => $date ? date($date) : null)
                ->sortable(),
            Column::make(__('Expires At'), 'end_date')
                ->format(fn ($date) => $date ? date($date) : null)
                ->sortable(),
            Column::make(__('Created At'), 'created_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i'))
                ->searchable()
                ->sortable(),
            Column::make(__('Updated At'), 'updated_at')
                ->format(fn ($date) => $date->format('d M Y h:m:i'))
                ->searchable()
                ->sortable(),
            Column::make(__('Export'), 'id')
            ->format(fn ($id) => view('components.icon', ['icon' => "<a href='" . route("dashboard.vouchers.campaign.excel",$id) . "' class='btn btn-primary ignore-loader' style='width:50px;'><i class='fs-2 ti ti-download'></i></a>"]))
        ];
    }
}
