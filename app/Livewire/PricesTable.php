<?php

namespace App\Livewire;

use App\Http\Controllers\PriceController;
use App\Models\Price;
use Illuminate\Database\Eloquent\Builder;
use Redot\LivewireDatatable\Action;
use Redot\LivewireDatatable\Column;
use Redot\LivewireDatatable\Datatable;

class PricesTable extends Datatable
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    // Delete Record
    private function delete($id)
    {
        if($id == 1){
            return redirect()->action([PriceController::class, 'index'])->with('success',__("This Price Can't be Removed"));
        }
        
        $price = Price::findOrfail($id);
        $price->delete();
        return redirect()->action([PriceController::class, 'index'])->with('success',__("Price Deleted Successfully"));
    }

    // Format Price
    public function formatPrice($json){
        $prices = json_decode($json,true);
        $output = '';
        foreach($prices as $index => $types){
            $output .= __(ucfirst($index)) . " : <br>";
                foreach($types as $prices){
                    $output .= "&nbsp;&nbsp;&nbsp;&nbsp;". $prices['description'] . " => ". __("Price") . ": " . $prices['price']." EGP, " . __("From") . ": " . $prices['from']." Hours, " . __("To") . ": " . $prices['to'] . " Hours<br>";
            }
            $output .= "<br>";
        }
        return $output;
    }

    /**
     * Query builder.
     */
    public function query(): Builder
    {
        return Price::query();
    }

    /**
     * Data table columns.
     */
    public function columns(): array
    {
        return [
            Column::make('#','id'),
            Column::make('name','name')->sortable()->searchable(),
            Column::make(__('Prices'),'prices')
                ->format(fn ($price) => $this->formatPrice($price)),
            Column::make(__('Free Time'),'free_time')
                ->format(fn ($time) => $time ." Minutes"),
            Column::make(__('Insurance Amount'),'insurance')
                ->format(fn ($amount) => $amount ." EGP"),
            Column::make(__('Max Hours'),'max_hours')
                ->format(fn ($time) => $time ." Hours"),
            Column::make(__('Created At'), 'created_at')
                ->format(fn ($date) => $date->format('d M Y'))
                ->sortable(),
            Column::make(__('Updated At'), 'updated_at')
                ->format(fn ($date) => $date->format('d M Y'))
                ->sortable(),
            // Column::make(__('Delete'), 'id')
            //     ->format(fn ($id) => $this->delete($id))
        ];
    }

    /**
     * Data table actions.
     */
    public function actions(): array
    {
        return [
            Action::edit('dashboard.prices.edit')->can('dashboard.prices.edit'),
            // Action::delete('dashboard.admins.destroy')->can('dashboard.admins.destroy'),
        ];
    }
}
?>