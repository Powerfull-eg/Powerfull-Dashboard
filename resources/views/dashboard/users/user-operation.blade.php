<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    
    <div class="header-data my-3">
        <h2 class="fw-bold text-center d-block">{{__("Customer Name: "). $user->fullName }}</h2>
    </div>
    <div id="data">
        <livewire:user-operations-table :user="$user->id" :startDate="$startDate" :endDate="$endDate"/>
            <table class="table table-responsive">
                <tr><td>{{__("Total Amount")}}</td><td colspan="3">{{number_format($amountTimeData[0],2)}}</td></tr>
                <tr><td>{{__("Total Hours")}}</td><td colspan="3">{{number_format($amountTimeData[1],4)}}</td></tr>
                <tr><td>{{__("Amount Per Selected Date")}}</td><td colspan="3">{{number_format($amountTimeData[2],2)}}</td></tr>
                <tr><td>{{__("Hours Per Selected Date")}}</td><td colspan="3">{{number_format($amountTimeData[3],4)}}</td></tr>
                <tr><td>{{__("Amount For Last Month")}}</td><td colspan="3">{{number_format($amountTimeData[4],2)}}</td></tr>
                <tr><td>{{__("Hours For Last Month")}}</td><td colspan="3">{{number_format($amountTimeData[5],4)}}</td></tr>
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