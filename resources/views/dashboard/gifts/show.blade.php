<x-layouts::dashboard>
    <x-components::status />
    <div class="mx-auto text-center fs-1 alert alert-primary"> {{__('Gift')}}: <span class="fw-bold">{{'#' . $gift->id . ' - '.$gift->name}}</span></div>
    <livewire:gifts-usage-table :id="$gift->id" />
</x-layouts::dashboard>