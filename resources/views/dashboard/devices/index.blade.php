<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    {{-- <x-components::chart :title="__('Devices')" :dataLabels="array_keys($operationsPerDevice)" :dataValues="$countPerDevice" /> --}}
    <div id="data">
        <livewire:devices-table :startDate="$startDate" :endDate="$endDate"/>
        <div class="table-responsive">
            <table class="table table-vcenter table-nowrap w-50">
                <tr>
                    <td>{{__("Total Devices")}}</td>
                    <td>{{$allDevices->count()}}</td>
                </tr>  
                <tr>
                    <td>{{__("Devices In Selected Date")}}</td>
                    <td>{{$devices->count()}}</td>
                </tr>  
                <tr>
                    <td>{{__("Devices In Last 30 Days")}}</td>
                    <td>{{$allDevices->where('created_at','>=',now()->previous("Month") )->count()}}</td>
                </tr>
            </table>
        </div>
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