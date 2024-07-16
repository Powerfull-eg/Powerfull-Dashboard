<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;
use Spatie\Permission\Models\Role;

class RolesTable extends DataTable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (static::can('dashboard.roles.create')) {
            $this->create = 'dashboard.roles.create';
        }
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Role::query();
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')
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
            Action::edit('dashboard.roles.edit')->can('dashboard.roles.edit'),
            Action::delete('dashboard.roles.destroy')->can('dashboard.roles.destroy'),
        ];
    }
}
