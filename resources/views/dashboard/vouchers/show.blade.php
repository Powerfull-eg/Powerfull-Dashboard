<x-layouts::dashboard>
    <x-components::status />
    <div class="mx-auto text-center fs-1 alert alert-primary"> {{__('Voucher Code')}}: <span class="fw-bold">{{$voucher->code}}</span></div>
    <livewire:vouchers-usage-table :id="$voucher->id" />
</x-layouts::dashboard>