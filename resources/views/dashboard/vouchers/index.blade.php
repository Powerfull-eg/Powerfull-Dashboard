<x-layouts::dashboard>
    <x-components::status />
    <div class="d-flex justify-content-center mx-auto gap-3 fs-1 fw-bold page-navigator">
        <div class="active" onclick="navigator(0)">{{__('Vouchers')}}</div>
        <div onclick="navigator(1)">{{__('Campaigns')}}</div>
    </div>
    <div> <livewire:vouchers-table navigator="0"/></div>
    <div class="d-none"> <livewire:campaings-table navigator="1"/></div>

    @push('styles')
        <style>
            .page-navigator .active {
                align-items: center;
                background-color: var(--background-color);
                color: var(--text-color);
                padding: .3rem 3rem;
                border-radius: 1rem;
                max-width: 90%; 
            }
        </style>
    @endpush
</x-layouts::dashboard>