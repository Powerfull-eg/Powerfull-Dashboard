<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

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
            Column::make(__("First Name"),'first_name')
                ->sortable()
                ->searchable(),
            Column::make(__("Last Name"),'last_name')
                ->sortable()
                ->searchable(),
            Column::make(__("Customer Phone"),'phone')
                ->searchable(),
                // ->format(fn ($phone) => $phone ? "+20" . $phone : ''),
            Column::make(__('Email'), 'email')
                ->searchable()  
                ->format(fn ($email) => $email ? view('components.icon', ['icon' => "<a href=mailto:'" . $email . "' class='btn btn-primary' style='width:50px;'><i class='fs-1 ti ti-mail'></i></a>"]) . " " . $email: ''),
            Column::make(__('Operations'), 'id')
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.users.operations",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-1 ti ti-battery-charging'></i></a>"]) : ''),
            Column::make(__('Created At'), 'created_at')
                ->sortable()
                ->format(fn ($date) => ($date ? $date->format('d M Y h:m:i') : '' )),
            Column::make(__('Updated At'), 'updated_at')
                ->format(fn ($date) => ($date ? $date->format('d M Y h:m:i') : '' ) )
                ->sortable(),
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
