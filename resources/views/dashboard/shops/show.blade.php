<x-layouts::dashboard>
    <x-components::forms.customDatePicker />
    <div id="data">
        <div class="device  d-block mx-auto fw-bold my-2 text-center">
            <span>{{__("Device ID")}}: <span class="text text-danger">{{$shop->device->device_id}}</span></span>
        </div>
        <div class="card-header my-3">
            <p class="card-title text-center fs-2 d-inline-block mx-auto">{{__("Shop Name")}}: <span class="fw-bold">{{ $shop->name }}</span></p>
            <span class="phone d-block mx-auto">
                {{$shop->phone}} 
                <span class="mx-2"onclick="window.location.href ='tel:{{$shop->phone}}'"><i class="fs-2 ti ti-phone mx-2"></i></span>
             </span>
        </div> 
        <livewire:shop-operations-table :device="$shop->device->device_id" :startDate="$startDate" :endDate="$endDate"/>
        <table class="table table-responsive">
            <tr><td>{{__("Total Hours")}}</td><td colspan="3">{{$totalHours}}</td></tr>
            <tr><td>{{__("Total Amount")}}</td><td colspan="3">{{$totalAmount}}</td></tr>
            <tr><td>{{__("Total Gifts")}}</td><td colspan="3"><a class="btn btn-primary" href="{{route('dashboard.gifts-show',$shop->id)}}">{{$totalGifts}}</a></td></tr>
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
@push('scripts')
<script>
    function copyPhone(number) {
        number.select();
        number.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(number)
        toastify()->success(__("Phone Number Copied"));
    };
</script>
@endpush