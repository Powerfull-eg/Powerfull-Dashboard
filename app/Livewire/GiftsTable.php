<?php

namespace App\Livewire;

use App\Models\Gift;
use App\Models\GiftUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class GiftsTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // if (static::can('dashboard.gifts.create')) {
        //     $this->create = 'dashboard.gifts.create';
        // }
        if (static::can('dashboard.gifts.edit')) {
            $this->edit = 'dashboard.gifts.edit';
        }
    }

    private function getUser(string $id)
    {
        $user = User::find($id);
        return $user;
    }

    private function getGift(string $id)
    {
        $gift = Gift::find($id);
        return $gift;
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Gift::query();
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('#','id'),
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make('Usage',"id")
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.gifts.show",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-2 ti ti-zoom-exclamation'></i></a>"]) : ''),
            Column::make(__('English Title'), 'title_en')
                ->searchable()
                ->sortable(),
            Column::make(__('English Message'), 'message_en')
                ->searchable()
                ->sortable(),
            Column::make(__('Arabic Title'), 'title_ar')
                ->searchable()
                ->sortable(),
            Column::make(__('Arabic Message'), 'message_ar')
                ->searchable()
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
            Action::edit('dashboard.gifts.edit')->can('dashboard.gifts.edit'),
            Action::delete('dashboard.gifts.destroy')->can('dashboard.gifts.destroy'),
        ];
    }
}
