<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    <livewire:shops-table :startDate="$startDate" :endDate="$endDate" />
</x-layouts::dashboard>