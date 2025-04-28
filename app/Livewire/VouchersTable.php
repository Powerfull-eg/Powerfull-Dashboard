<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\VoucherOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class VouchersTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (static::can('dashboard.vouchers.create')) {
            $this->create = 'dashboard.vouchers.create';
        }
        if (static::can('dashboard.vouchers.edit')) {
            $this->edit = 'dashboard.vouchers.edit';
        }
    }

    private function getVocuher(string $id)
    {
        $voucher = Voucher::find($id);
        return $voucher;
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Voucher::where('campaign_id',null)->orderByDesc('id');
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('ID','id'),
            Column::make(__('Code'), 'code')
                ->searchable()
                ->sortable(),
                Column::make(__('User'), 'user.fullName')
                ->searchable()
                ->sortable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                    });
                }),
            Column::make('Usage',"id")
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.vouchers.show",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make(__('Type'), 'type')
                ->format(fn ($type) => ($type == 0 ? __('Percentage'): __('Amount')) . ' ' . view('components.icon', ['icon' => "<i class='fs-2 ti ti-" .($type == 0 ? "percentage":"cash") ."'></i>"]))
                ->sortable(),
            Column::make(__('Value'), 'id')
                ->format(fn ($id) => $this->getVocuher($id)->value . ' ' . view('components.icon', ['icon' => "<i class='fs-2 ti ti-" .($this->getVocuher($id)->type == 0 ? "percentage":"cash") ."'></i>"]))
                ->sortable(),
            Column::make(__('Min Amount'), 'min_amount')
                ->format(fn ($amount) => $amount . " EGP")
                ->sortable(),
            Column::make(__('Max Discount'), 'max_discount')
                ->format(fn ($amount) => $amount . " EGP")
                ->sortable(),
            Column::make(__('Starts At'), 'from')
                ->format(fn ($date) => $date ? date($date) : null)
                ->sortable(),
            Column::make(__('Expires At'), 'to')
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
