<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    @php
     foreach($operationsPerDevice as $deviceOperations){
        $countPerDevice[] = count($deviceOperations);
     }
    @endphp
    <x-components::chart :title="__('Device Operations')" :dataLabels="array_keys($operationsPerDevice)" :dataValues="$countPerDevice" />
    <livewire:operations-table :startDate="$startDate" :endDate="$endDate" />
</x-layouts::dashboard>