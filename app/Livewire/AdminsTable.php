<?php

namespace App\Livewire;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class AdminsTable extends Datatable
{ 
    public $type;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->perPage = 3;
    }

    //mount data
    public function mount($type=null)
    {
        $this->type = $type;
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        $admins = new Admin();
        $admins = $this->type == 'shop' ? $admins->whereHas('shops') : $admins->whereDoesntHave('shops');
        return $admins->whereNotCurrentAdmin();
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        $columns =  [
            Column::make('')
                ->width('50px')
                ->resolve(fn ($admin) => view('components.avatar', ['user' => $admin])),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Role'))
                ->resolve(fn ($admin) => $admin->getRoleNames()->first()),
            Column::make(__('Email'), 'email')
                ->format(fn ($email) => "<a href=\"mailto:$email\">$email</a>")
                ->searchable()
                ->sortable(),
            Column::make(__('Created At'), 'created_at')
                ->format(fn ($date) => $date->format('d M Y'))
                ->searchable()
                ->sortable(),
        ];
        if($this->type == 'shop'){
            array_splice($columns, 3, 0, [
                Column::make('Shop','shops.name')
                    ->searchable()
            ]);
        }
        return $columns;
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [
            Action::edit('dashboard.admins.edit')->can('dashboard.admins.edit'),
            Action::delete('dashboard.admins.destroy')->can('dashboard.admins.destroy'),
        ];
    }
}
