<x-layouts::dashboard>
    <x-components::status />
    <div class="mx-auto text-center fs-1 alert alert-warning"> {{__('Campaign')}}: <span class="fw-bold">{{$campaign->name}}</span></div>
    <div class="mx-auto text-center fs-4 my-3" style="border: 1px solid var(--background-color);"> <span>{!! $campaign->description !!}</span></div>
    <livewire:campaing-vouchers-table :campaign="$campaign" />
</x-layouts::dashboard>