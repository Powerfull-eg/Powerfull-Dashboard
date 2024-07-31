<x-layouts::dashboard>
    <x-components::status />
    <div class="mx-auto text-center fs-1 alert alert-primary"> {{__('Shop')}}: <span class="fw-bold">{{'# ' . $shop->name }}</span></div>
    <livewire:gifts-usage-table :shop="$shop->id" />
</x-layouts::dashboard>