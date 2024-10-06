<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    <div class="my-3 gap-3 controls container d-flex justify-content-start">

        {{-- Fetch Shops From Providers  --}}
        <form action="{{ route('dashboard.shops.sync') }}" method="POST">
            @csrf
            <div>
                <button type="submit" id="fetch" class="fetch btn btn-warning">{{__("Fetch") ." " . __("Shops") }}</button>
            </div>
        </form>

        {{-- Shops Types --}}
        <div>
            <a href="{{ route('dashboard.shop-types.index') }}" class="btn btn-secondary">{{ __('Shop') ." " . __("Types")}} <i class="ti ti-plus"></i></a>
        </div>
    </div>

    {{-- Table --}}
    <div id="data">
        <livewire:shops-table :startDate="$startDate" :endDate="$endDate" />
    </div>

    {{-- Footer --}}
    <div class="footer-data">
        <div class="excel">
            <button onclick="excel.export()" class="btn btn-success"> {{ __("Export Excel") }} </button>
        </div>
    </div>
</x-layouts::dashboard>
<script>
    const excel = new Table2Excel("#data");
    // Fetch Shops From Providers
</script>