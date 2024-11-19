<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;
use Illuminate\Support\Facades\DB;

class UsersTable extends Datatable
{
    public $date;

    // Mount Data
    public function mount($startDate=null, $endDate=null)
    {
        $this->date = [$startDate,$endDate];
    }
    /**
     * Query builder.
     */
    public function query(): Builder
    {
        if($this->date[0] && $this->date[1])
        {
            return User::query()->whereBetween("created_at",$this->date)->orderByDesc("updated_at"); 
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return User::query()->where("created_at",">=",$this->date[0])->orderByDesc("updated_at"); 
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return User::query()->where("created_at","<=",$this->date[1])->orderByDesc("updated_at"); 
        }
        
        return User::query()->orderByDesc("created_at");
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        $this->fixedHeader = true;

        return [
            Column::make(__("Powerfull ID"),'id')
                ->sortable(),
            Column::make(__("Name"),'fullName')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    return $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"),"like","%{$search}%");
                }),
            Column::make(__("Customer Phone"),'phone')
                ->searchable()
                ->format(fn ($phone) => $phone ? "0" . $phone : '')
                ->searchUsing(function ($query, $search){
                    $search = $search[0] == "0" ? substr($search, 1) : $search;
                    return $query->where("phone","like","%$search%");
                }),
            Column::make(__('Register On'), 'created_at')
                ->sortable()
                ->format(fn ($date) => ($date ? $date->format('d/m/Y') : '' )),
            Column::make(__('Last Update'), 'operations')
                ->sortable()
                ->format(fn ($operations) => ($operations->count() ? $operations->last()->created_at->format('d/m/Y') : '-' )),
            Column::make(__('Operations Log'), 'id')
                ->format(fn ($id) => $id ? "<a href='" . route("dashboard.users.operations",$id) . "' style='color: var(--background-color); display: block; text-align: center;'><i style='font-size: 3rem !important;' class='ti ti-battery-charging'></i></a>": ''),
            Column::make(__('Email'), 'email')
                ->searchable()  
                ->format(fn ($email) => $email ? view('components.icon', ['icon' => "<a href=mailto:'" . $email . "' class='btn btn-primary' style='width:50px;'><i class='fs-1 ti ti-mail'></i></a>"]) . " " . $email: ''),
            // Column::make(__('Edit'), 'id')
            // ->format(fn ($id) => $id ? "<a href='" . route("dashboard.users.edit",$id) . "' style='color: var(--background-color); display: block; text-align: center;'><i style='font-size: 2rem !important;' class='ti ti-user-edit'></i></a>": ''),
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [
        ];
    }
}
