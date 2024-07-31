<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    <div id="data">
        <livewire:shops-table :startDate="$startDate" :endDate="$endDate" />
    </div>
    <div class="footer-data">
        <div class="excel">
            <button onclick="excel.export()" class="btn btn-success"> {{ __("Export Excel") }} </button>
        </div>
    </div>
</x-layouts::dashboard>
<script>
    const excel = new Table2Excel("#data");
</script>