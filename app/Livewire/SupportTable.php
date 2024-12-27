<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class SupportTable extends Datatable{
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
            return Ticket::query()->whereBetween("created_at",$this->date)->orderByDesc("updated_at"); 
        }
        elseif($this->date[0] && !$this->date[1])
        {
            return Ticket::query()->where("created_at",">=",$this->date[0])->orderByDesc("updated_at"); 
        }
        elseif(!$this->date[0] && $this->date[1])
        {
            return Ticket::query()->where("created_at","<=",$this->date[1])->orderByDesc("updated_at"); 
        }
        
        return Ticket::query()->orderByDesc("updated_at");
    }

    /**
     * Data table columns.
    */
    public function columns(): array
    {
        $this->fixedHeader = true;
        return [
            Column::make(__("Customer Name"),'user.fullName')
                ->searchable()
                ->searchUsing(function ($query, $search){
                        $query->whereHas('user', function($query) use ($search){
                            $query->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%");
                        });
                }),
                Column::make(__("Customer Phone"),'user.phone')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('phone', 'like', "%$search%");
                    });
                })
                ->format(fn ($phone) => $phone ? "+20" . $phone : ''),
            Column::make(__("Last Message"),'lastMessage.0.message')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('lastMessage', function($query) use ($search){
                        $query->where('message', 'like', "%$search%");
                    });
                })->format(fn ($message) => strlen($message) > 100 ? mb_substr($message, 0, 100, "UTF-8") . "..." : $message),
            Column::make(__("Add Reply"),'id')
                ->format(fn ($id) => view('components.icon', ['icon' => "<a href='". route("dashboard.support.edit", $id)."' class='btn btn-warning' style='width:50px;'><i class='fs-1 ti ti-message-forward'></i></a>"])),
            Column::make(__("Need Reply"),'lastMessage.0.sender')
                ->format(fn($message) =>  view('components.icon', ['icon' => "<span class='text text-". ($message == "1" ? 'danger': 'success') . "' style='width:50px;'>  <i class='fs-2 ti ti-". ($message == "1" ? 'bell-ringing': 'check') . "'></i></span>"])),
            Column::make(__('Email'), 'user.email')
                ->searchable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('email', 'like', "%$search%");
                    });
                })
                ->format(fn ($email) => $email ? view('components.icon', ['icon' => "<a href=mailto:'" . $email . "' class='btn btn-primary' style='width:50px;'><i class='fs-1 ti ti-mail'></i></a>"]) . " " . $email: ''),
            Column::make(__("Subject"),'subject')
                ->searchable(),
            Column::make(__('Operations'), 'user.id')
                ->format(fn ($id) => $id ? view('components.icon', ['icon' => "<a href='" . route("dashboard.users.operations",$id) . "' class='btn btn-primary' style='width:50px;'><i class='fs-1 ti ti-battery-charging'></i></a>"]) : ''),
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