<x-layouts::dashboard>
    <x-components::status />
    <x-components::chart :data-labels="$operationsperDevice" />
</x-layouts::dashboard>