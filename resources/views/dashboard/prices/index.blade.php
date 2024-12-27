<x-layouts::dashboard>
    <x-components::status />
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center align-items-center logo">
            <i style="font-size: 4rem; color: var(--background-color);" class="ti ti-coin px-3"></i>
            <h1>{{__("Prices") . " " . __("Control")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div class="d-flex align-items-center gap-1">
                <i style="font-size: 1.2rem" class="ti ti-circle-plus"></i>
                <a href="{{route('dashboard.prices.create')}}">{{__("Add Price")}}</a>
            </div>
        </div>
    </div>
    <livewire:prices-table>
</x-layouts::dashboard>