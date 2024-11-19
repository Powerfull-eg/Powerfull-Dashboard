<?php

namespace App\Livewire;
use App\Models\IncompleteHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class IncompleteHistoryTable extends Datatable
{
    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return IncompleteHistory::query()->orderByDesc("updated_at")->with('operation');
    }

    private function secondsToTimeString($time) {
        // change seconds to string
        $hours = floor($time / 3600);
        $minutes = floor(($time - ($hours * 3600)) / 60);
        $seconds = $time - ($hours * 3600) - ($minutes * 60);
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    /**
     * Get the table columns.
     */
    public function columns(): array
    {
        return [
            Column::make(__("Operation ID"), 'operation.id'),
            Column::make(__("Customer") . " " . __("Name"), 'operation.user.fullName'),
            Column::make(__("Merchant"), 'operation.device.shop.name'),
            Column::make(__("Device"), 'operation.device.device_id'),
            Column::make(__("Borrow Time"), 'operation.borrowTime')
            ->format(fn ($time) => $time ? chineseToCairoTime($time) : "-"),
            Column::make(__("Return Time"), 'operation.returnTime')
            ->format(fn ($time) => $time ? chineseToCairoTime($time) : "-"),
            Column::make(__("Renting Time"), 'operation')
            ->format(fn ($operation) => $operation->returnTime ? $this->secondsToTimeString(Carbon::parse($operation->returnTime)->getTimestamp() - Carbon::parse($operation->borrowTime)->getTimestamp()) : '-'),
            Column::make(__("Amount"), 'original_amount')
            ->format(fn ($amount) => $amount . " EGP"),
            Column::make(__("Final Amount"), 'final_amount')
            ->format(fn ($amount) => $amount . " EGP"),
            Column::make(__("Status"), 'status'),
            Column::make(__("Ended At"), 'ended_at')
            ->format(fn ($date) => $date ? $date->format('D, M j, Y') : "-"),
        ];
    }
}
