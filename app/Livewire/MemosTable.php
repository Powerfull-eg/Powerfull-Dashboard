<?php

namespace App\Livewire;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class MemosTable extends DataTable
{
    /**
     * Create button.
     */
    public string|bool $create = 'dashboard.memos.create';

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Memo::query();
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make(__('Title'), 'title')
                ->searchable()
                ->sortable(),
            Column::make(__('Date'), 'date')
                ->format(fn ($date) => $date ? $date->format('D, M j, Y') : null)
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
            Action::view('dashboard.memos.show'),
            Action::edit('dashboard.memos.edit'),
            Action::delete('dashboard.memos.destroy'),
        ];
    }
}
