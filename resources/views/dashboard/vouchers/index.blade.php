<x-layouts::dashboard>
    <x-components::status />
    <div class="d-flex justify-content-end my-3">
        <a class="btn btn-primary px-3 py-1" href="{{route('dashboard.vouchers.create')}}">
            {{__("Create")}}&nbsp;
            <i class="ti ti-square-rounded-plus"></i>
        </a>
    </div>

    <div class="d-flex justify-content-center mx-auto gap-3 fs-1 fw-bold page-navs">
        <div class="navigator active" onclick="navigator(0)">{{__('Vouchers')}}</div>
        <div class="navigator" onclick="navigator(1)">{{__('Campaigns')}}</div>
    </div>
    <div navigator=0> <livewire:vouchers-table /></div>
    <div class="d-none" navigator=1> <livewire:campaings-table/></div>
</x-layouts::dashboard>