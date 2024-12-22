<x-layouts::dashboard>
    <x-components::status />
    {{-- Header --}}
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center gap-2 logo">
            <i style="font-size: 4rem; color: var(--background-color)" class="ti ti-user-shield"></i>
            <h1 class="fw-bold">{{__("Access Users")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-plus-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z" fill="currentColor" stroke-width="0" /></svg>
                <a href="{{route('dashboard.admins.create')}}">{{__("Add User")}}</a>
            </div>
        </div>
    </div>

    {{-- Page Container --}}
    <div class="container m-3" style="border: 2px solid var(--background-color); border-radius: 10px">
        {{-- Active Admins --}}
        <div class="active">
            <div class="subtitle m-3">
                <i class="ti ti-building-store"></i>
                <span>{{__("Active Admins")}}</span>
            </div>
            <livewire:admins-table />
        </div>
        {{-- Add New --}}
        <div class="new">
            <div class="subtitle m-3">
                <i class="ti ti-circle-plus"></i>
                <span>{{__("Add New Admins")}}</span>
            </div>
        </div>
    </div>

@push('styles')
    <style>
        .subtitle {
            display: flex;
            color: var(--text-color);
            gap: .5rem;
            background-color: var(--background-color);
            width: fit-content;
            padding: .25rem;
            align-items: center;
            border-radius: 10px;
            min-width: 25%; 
        }
        .subtitle i.ti {
            font-size: 30px;
            font-weight: 600;
        }
        .controls > div > a :focus
        {
            text-decoration: none;
        }
        .controls > div > a {
            text-decoration: none;
            color: var(--text-color-2);
        }
        .controls > div{
            padding: 5px;
            background-color: var(--background-color);
            color: var(--text-color-2);
            font-weight: bold;
            font-size: 12px;
            border-radius: 30px;
            cursor: pointer;
            margin: 0 5px;
            padding: 5px 10px;

        }
        /* Edit Livewire styles */
        div.livewire-datatable {
            background: transparent !important;
        }

        div.livewire-datatable > .card-header {
            flex-direction: row-reverse;
            flex-wrap: wrap !important;
        }
        div.livewire-datatable > .card-header > .input-icon {
            flex-grow: 1;
        }
        div.livewire-datatable > .card-header > .input-icon > input {
            border-color: var(--background-color) !important;
        }
        
        div.livewire-datatable > .table-responsive  tr td, div.livewire-datatable > .table-responsive thead tr th
        ,#history-table tr td, #history-table tr th
        {
            border: 2px solid #000 !important;
            padding: 5px 20px !important;
        }
        div.livewire-datatable > .table-responsive thead tr th,#history-table > thead tr th{
            background-color: var(--background-color) !important;
            color: var(--text-color2) !important;
            font-weight: 600 !important;
            text-align: center !important;
        }
        /* End Edit Livewire styles */
    </style>
@endpush
</x-layouts::dashboard>
