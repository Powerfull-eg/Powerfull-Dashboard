<x-layouts::dashboard :title="ucfirst($target) . ' ' . __('Reports')">
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end mb-5 justify-content-center logo">
            <i style="font-size: 5rem; color: var(--background-color)" class="ti ti-clipboard-data"></i>
            <h1>{{__("Comprehensive Report")}}</h1>
        </div>
    </div>
    <x-components::status />
    <x-components::forms.customDatePicker />
    {{-- Navigators & Exports --}}
    <div class="row gap-1">
        {{-- Navigator --}}
        <div class="col-12 col-md-7 d-flex flex-wrap gap-4 justify-content-center page-navigator">
            <span data-name="shops">{{__("Merchants")}}</span>
            <span data-name="devices">{{__("Devices")}}</span>
            <span data-name="customers">{{__("Customers")}}</span>
            <span data-name="financial">{{__("Financials")}}</span>
        </div>
        {{-- Exports --}}
        <div class="col-12 col-md-4 d-flex gap-2 justify-content-center mx-auto">
            {{-- <a href="{{route('dashboard.reports.pdf', $target)}}" class="btn export" onclick="()">{{__("Export All PDF")}}</a> --}}
            <form id="excel-form" class="export-form" action="{{route('dashboard.reports.export.excel', $target)}}" method="POST">
                @csrf
                <input type="hidden" name="startDate" value="{{$startDate}}">
                <input type="hidden" name="endDate" value="{{$endDate}}">
                <button type="submit" class="btn export">{{__("Export All Excel")}}</button>
            </form>
        </div>
    </div>

    <div class="container summary-container row justify-content-center mt-5">
        {{-- Summary Data --}}
        @foreach ($data->summary as $title => $number)           
            <div class="summary-card {{ in_array($loop->index, [1,4]) ? 'middle' : ''}} col-3">
                <div class="number-card">
                    <span class="background"></span>
                    <span class="number" style="{{$number > 99 ? 'left: -5px' : ($number < 10 ? 'left: 5px' : '')}}">{{$number}}</span>
                </div>
                <div class="title-card">{{ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $title, -1, PREG_SPLIT_NO_EMPTY)))}}</div>
                <hr>
            </div>
        @endforeach
        {{-- Table --}}
        {{-- Temporary !!!! --}}
        <div class="col-12">
            @if ($target == 'customers')
            <livewire:users-table :startDate="$startDate" :endDate="$endDate"/>
            @else
                <livewire:report-table :startDate="$startDate" :endDate="$endDate"/>
            @endif
        </div>
    </div>
    @push("styles")
        <style>
            .page-navigator {
                align-items: center;
                background-color: var(--background-color);
                color: var(--text-color);
                padding: .3rem 3rem;
                border-radius: 1rem;
                max-width: 90%;
                margin: auto 1rem;
            }
            .page-navigator span {
                font-size: 1rem;
                font-weight: 700;
                cursor: pointer;
                padding: .5rem 1rem;
            }
            .page-navigator span:hover,.page-navigator span.active {
                background: var(--text-color);
                color: var(--background-color);
                border-radius: 1rem;
            }
            .btn.export {
                background-color: var(--background-color);
                color: var(--text-color);
                padding: .5rem 1rem;
                border-radius: 10px; 
                justify-content: center;
                max-height: fit-content;
            }
            /* Summary Card */
            .summary-card {
                position: relative;
                margin: 20px;
                margin-top: 25px;
            }
            .number-card {
                position: relative;
            }
            .number-card .background {
                position: absolute;
                top: -10px;
                left: -10px;
                width: 50px;
                height: 50px;
                background-color: var(--background-color);
                border-radius: 10px;
                transform: rotate(45deg);
            }
            .summary-card.middle .number-card .background {
                background: #eca51c;
            }
            .number-card .number {
                position: absolute;
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-color);
            }
            .title-card {
                background: #ddd;
                color: var(--text-color-2);
                padding: 1rem;
                margin-top: 30px;
                border-radius: 50px;
                padding-inline-start: 30px;
                font-weight: 700;
            }
            .summary-card hr {
                background: var(--background-color);
                height: 3px;
                opacity: 0.6;
            }
            .summary-card.middle hr {
                background: #ddd !important;
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
            div.livewire-datatable > .table-responsive thead a {
                color: var(--text-color2) !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            const target = '{{request()->target}}';
            $(`[data-name=${target}]`).addClass('active');
            $(".page-navigator span[data-name]:not(.active)").each(function() {
                $(this).on('click',function(){
                    let link = location.origin + location.pathname + "?target=" + $(this).attr("data-name");
                    link += "&startDate={{$startDate}}&endDate={{$endDate}}";
                    showPageLoader();
                    location.href = link;
                });
            });
        </script>
    @endpush
</x-layouts::dashboard>