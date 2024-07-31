<?php

namespace App\Livewire;

use App\Models\Gift;
use App\Models\GiftUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class GiftsUsageTable extends Datatable
{
    public $giftId;
    public $shop;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function mount($id=null,$shop=null){
        $this->giftId = $id ?? null;
        $this->shop = $shop ?? null;
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
        $gifts = GiftUser::query();
        $gifts = $this->giftId ? $gifts->where('gift_id',$this->giftId) : $gifts;
        $gifts = $this->shop ? $gifts->where("shop_id",$this->shop) : $gifts;
        
        return $gifts->orderByDesc("created_at");
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('#','id'),

            Column::make(__('User'), 'user')
                ->format(fn($user) => $user->fullName )
                ->searchable()
                ->sortable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
                    });
                }),

            Column::make(__('User Phone'), 'user')
                ->format(fn($user) => "0" . $user->phone )
                ->searchable()
                ->sortable()
                ->searchUsing(function ($query, $search){
                    $query->whereHas('user', function($query) use ($search){
                        $query->where('phone', 'like', "%0$search%");
                    });
                }),
            Column::make(__('Shop Name'), 'shop_name')
                ->searchable()
                ->sortable(),
            Column::make(__('Code'), 'code')
                ->searchable()
                ->sortable(),
            Column::make(__('Used At'), 'used_at')
                ->format(fn ($date) => $date ?: null)
                ->sortable(),
            Column::make(__('Mark As Delivered'), 'id')
                ->format(fn ($id) => GiftUser::find($id)->used_at ? null : view('components.icon', ['icon' => "<a href='" . route("dashboard.gifts-deliver",$id) . "' class='btn btn-danger' style='width:50px;'><i class='fs-2 ti ti-checks'></i></a>"]))
                ->searchable()
                ->sortable(),
            Column::make(__('Expiration Date'), 'expire')
                ->searchable()
                ->sortable(),
            Column::make(__('Created At'), 'created_at')
                ->searchable()
                ->sortable(),
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [];
    }
}

