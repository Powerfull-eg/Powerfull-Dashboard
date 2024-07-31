<x-layouts::dashboard>
    <x-components::status />
    <div class="mx-auto text-center fs-1 alert alert-primary fw-bold"> {{__('Gifts')}}</div>

    <livewire:gifts-table />
</x-layouts::dashboard>