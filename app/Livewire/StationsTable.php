<?php

namespace App\Livewire;

use App\Models\Station;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class StationsTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (static::can('dashboard.stations.create')) {
            $this->create = 'dashboard.stations.create';
        }
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Station::select("*");
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
            Column::make(__("Inet ID"),'inet_id')
            ->searchable(),
            Column::make(__("Status"),'status')
            ->sortable(),
            Column::make(__('Signal Value'), 'signal_value')
                ->searchable()
                ->sortable(),
            Column::make(__('Merchant Name'), 'merchant')
                ->format(fn ($merchant) => $merchant->name)
                ->searchable()
                ->sortable(),
            Column::make(__('Merchant Logo'), 'merchant')
                ->width('70px')
                ->format(fn ($merchant) => view('components.icon', ['icon' => "<img alt='".$merchant->name."-icon' style='width:50px;' src='" . Storage::url('merchants/'.$merchant->logo) . "' />"]))
                ->searchable()
                ->sortable(),
            Column::make(__('Type'), 'type')
                ->sortable(),
            Column::make(__('Slots'), 'slots')
                ->sortable(),
            Column::make(__('Rentable Slots'), 'rentable_slots')
                ->sortable(),
            Column::make(__('Return Slots'), 'return_slots')
                ->sortable(),
            Column::make(__('Fault Slots'), 'fault_slots')
                ->sortable(),
            Column::make(__('Heartbeat Time'), 'heartbeat')
                ->format(fn ($date) => $date->heartbeat_time ?? null)
                ->searchable()
                ->sortable(),
            Column::make(__('Internet Card'), 'internet_card')
                ->searchable()
                ->sortable(),
            Column::make(__('Device Ip'), 'device_ip')
                ->searchable()
                ->sortable(),
            Column::make(__('Server Ip'), 'server_ip')
                ->searchable()
                ->sortable(),
            Column::make(__('Port'), 'port')
                ->searchable()
                ->sortable(),
            Column::make(__('Authorize'), 'authorize')
                ->sortable(),
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
        return [
            Action::edit('dashboard.stations.edit'),
            Action::delete('dashboard.stations.destroy'),
        ];
    }
}
