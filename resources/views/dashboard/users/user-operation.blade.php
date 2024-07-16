<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />

    <div class="header-data my-3">
        <h2 class="fw-bold text-center d-block">{{__("Customer Name: "). $user->fullName }}</h2>
    </div>
    <div id="data">
        <livewire:user-operations-table :user="$user->id" :startDate="$startDate" :endDate="$endDate"/>
            <table class="table table-responsive">
                <tr><td>{{__("Total Hours")}}</td><td colspan="3">{{$totalHours}}</td></tr>
                <tr><td>{{__("Total Amount")}}</td><td colspan="3">{{$totalAmount}}</td></tr>
            </table>
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