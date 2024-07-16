<?php

namespace App\Livewire;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class MerchantsTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (static::can('dashboard.merchants.create')) {
            $this->create = 'dashboard.merchants.create';
        }
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Merchant::select("*");
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
            Column::make(__("Name"),'name')
            ->sortable()
            ->searchable(),
            Column::make(__("Logo"),'logo')
            ->format(fn ($logo) => $logo ? view('components.icon', ['icon' => "<img alt='".$logo."-icon' style='width:50px;' src='" . Storage::url('merchants/'.$logo) . "' />"]) : ''),
            Column::make(__('Images'), 'images'),
            Column::make(__('Address'), 'address')
                ->searchable(),
            Column::make(__('City'), 'city')
                ->searchable(),
            Column::make(__('Governorate'), 'governorate')
                ->searchable(),
            Column::make(__('Location'), 'location')
            ->format(fn ($location) => "<a href='https://maps.google.com/?q=". json_decode($location,true)["lat"] . "," . json_decode($location,true)["lng"] . "' target='_blank'><img width='30' src='/storage/map.png' alt='map icon' /></a>"),
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
            Action::edit('dashboard.merchants.edit'),
            Action::delete('dashboard.merchants.destroy'),
        ];
    }
}
