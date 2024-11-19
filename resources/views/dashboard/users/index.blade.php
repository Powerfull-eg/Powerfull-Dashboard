<x-layouts::dashboard>
    <x-components::status />
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center gap-2 logo">
            <i style="font-size: 4rem; color: var(--background-color)" class="ti ti-user-cog"></i>
            <h1>{{__("Customer Control")}}</h1>
        </div>
    </div>
    {{-- Page Container --}}
    <div class="container my-3">
        {{-- Customer Conclusion --}}
        <div id="customer-conclusion" class="py-3">
            <div class="subtitle">
                <i class="ti ti-users"></i>
                <span>{{__("Cutomer") . " ". __("Conclusion")}}</span>
            </div>
            {{-- Conclusion Table --}}
            <div class="conclusion-table">
                {{-- Total Downloads --}}
                <div>
                    <span>{{__("Total") . " " . __("Downloads")}}</span>
                    <i class="ti ti-cloud-download" style="font-size: 3rem"></i>
                    <span>1,053</span>
                </div>
                {{-- Registered Customers --}}
                <div>
                    <span>{{__("Registered") . " " . __("Customers")}}</span>
                    <i class="ti ti-user-minus" style="font-size: 3rem"></i>
                    <span>1,001</span>
                </div>
                {{-- Active Customers --}}
                <div>
                    <span>{{__("Active") . " " . __("Customers")}}</span>
                    <i class="ti ti-user-check" style="font-size: 3rem"></i>
                    <span>1,001</span>
                </div>
            </div>
        </div>

        {{-- Customer Table --}}
        <div class="customer-table">
            <div class="subtitle m-3">
                <i class="ti ti-search"></i>
                <span>{{__("Search")}}</span>
            </div>
            <livewire:users-table />
        </div>
        {{-- Incomplete Payments --}}
        <div class="incomplete-payments">
            {{-- Header --}}
            <div class="d-flex flex-row justify-content-between align-items-center">
                <div class="subtitle my-3 fw-bold">
                    <i class="ti ti-credit-card"></i>
                    <span>{{__("Incomplete") . " " . __("Payments")}}</span>
                </div>
                <span class="fw-bold">{{__("No. of Incompleted Payments: ") . $incompleteOperations->count()}} </span>
            </div>
            {{-- Auto request --}}
            <div class="request d-flex justify-content-start gap-1 align-items-center">
                <div class="subtitle">
                    <i class="ti ti-wand"></i>
                    <span>{{__("Auto Request")}}</span>
                </div>
                <form method="POST" class="d-flex gap-1" action="{{route('dashboard.payments.incomplete.settings')}}">
                    @csrf
                    <x-components::forms.select name="duration" :options="$incompleteAutoRequestDurations" :selected="$incompleteDuration->value"/>
                    <button type="submit" class="btn" style="background-color: var(--background-color); color: var(--text-color); border-radius: 10px">{{__("Save")}}</button>
                </form>
            </div>
            {{-- Incomplete Payments --}}
            <div class="operations mt-3 d-flex gap-1 flex-column">
                {{-- Operation --}}
                @foreach ($incompleteOperations as $operation)
                <div class="operation row my-2">
                    {{-- Operation Data --}}
                    <div class="operation-data d-flex col-sm-12 col-md-12 col-lg-7">
                        <div class="shop d-flex gap-2 flex-nowrap justify-content-between fw-bold fs-5 align-items-center">
                            <img src="{{$operation->device->shop->data->logo ?? $operation->device->shop->logo}}" style="border-radius: 20px" width="40" height="40" alt="{{$operation->device->shop->name}} Logo">
                            <span class="fw-bold text-truncate">{{$operation->device->shop->name}}</span>
                            <span class="text-truncate">{{__("Customer: ") . $operation->user->fullName}}</span>
                            <span>{{__("Amount: ") . $operation->amount}}</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4 d-flex gap-2 actions flex-nowrap justify-content-between fw-bold align-items-center my-3">
                        {{-- Manual requests --}}
                        <label>
                            <input type="checkbox" name="incomplete" value="{{ $operation->id }}"> 
                            <span class="custom-checkbox"></span>
                        </label>
                        {{-- Details Action --}}
                        <div class="btn" style="background-color: #e1bc41;">
                            <i class="ti ti-battery-charging"></i>
                            <span>{{__("Details")}}</span>
                        </div>
                        {{-- Edit Amount Action --}}
                        <div class="btn" style="background-color: orange;">
                            <i class="ti ti-edit"></i>
                            <span>{{__("Edit Amount")}}</span>
                        </div>
                        {{-- Delete Action --}}
                        <div class="btn" style="background-color: rgb(253, 36, 72);">
                            <i class="ti ti-square-x"></i>
                            <span>{{__("Delete")}}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                {{-- Request Button --}}
                <form action="{{route('dashboard.payments.request-multiple-payments')}}" id="incomplete-manual" method="POST">
                @csrf
                    <div class="d-flex mt-2">
                        <div class="subtitle" type="submit">
                            <i class="ti ti-hand-click fw-normal"></i>
                            <span>{{__("Manual Request")}}</span>
                        </div>
                        <input type="hidden" name="orders">
                        <div class="d-flex gap-1  mx-2 operation-data justify-content-between">
                            <label>
                                <input type="radio" name="incomplete-request" value="1"> 
                                <span style="padding: 5px; border-radius: 7px;" class="custom-radio">{{__("Request Selected")}}</span>
                            </label>
                            <label>
                                <input type="radio" name="incomplete-request" value="2">
                                <span style="padding: 5px; border-radius: 7px;" class="custom-radio">{{__("Request All")}}</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            {{-- Incomplete Payments History --}}
            <div>
                <div class="subtitle mt-3">
                    <i class="ti ti-clipboard-text fw-normal"></i>
                    <span>{{__("History") . " " . __("Incomplete Payments")}}</span>
                </div>
                {{-- Table --}}
                <livewire:incomplete-history-table />
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
        .conclusion-table {
            border: 2px solid var(--background-color);
            padding: 2rem;
            border-radius: 20px;
            margin-top: 2rem;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
        .conclusion-table > div {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap; 
            justify-content: center;
            gap: .5rem;
            align-items: center;
        }
        .conclusion-table i{
            color: var(--background-color)
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
        
        div.livewire-datatable > .table-responsive  tr td, div.livewire-datatable > .table-responsive thead tr th {
            border: 2px solid;
            padding: 5px 20px !important;
        }
        div.livewire-datatable > .table-responsive thead tr th {
            background-color: var(--background-color) !important;
            color: var(--text-color2) !important;
            font-weight: 600 !important;
            text-align: center !important;
        }

        div.livewire-datatable > .table-responsive thead a {
            color: var(--text-color2) !important;
        }
        .operation-data {
            border: 2px solid var(--background-color);
            padding: .5rem;
            border-radius: 10px;
        }
        .operation .actions input[type="checkbox"] {
            border-radius: 50%;
            border: 2px solid var(--background-color);
        }
        
        .operation .actions a {
            text-decoration: none;
        }

        .operation .actions .btn i {
            font-size: 20px;
            margin: 0 5px;
        }

        input[type="checkbox"],input[type="radio"] {
         display: none;
        }

        /* Style the custom checkbox */
        .custom-checkbox,.custom-radio {
            background-color: transparent;
            display: inline-block;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .custom-checkbox{
            border-radius: 50%; /* Makes it circular */
            border: 3px solid #8d8d8d;
            width: 30px;
            height: 30px;
        }
        .custom-checkbox,.custom-radio {
            background-color: transparent;
            display: inline-block;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Checked state */
        input[type="checkbox"]:checked + .custom-checkbox {
            background-color: var(--background-color); 
        }

        input[type="radio"]:checked + .custom-radio {
            background-color: #8d8d8d; 
        }

        /* Show the checkmark when checked */
        input[type="checkbox"]:checked + .custom-checkbox::after , input[type="radio"]:checked + .custom-radio::after {
            display: block;
        }
        </style>
    @endpush
    @push('scripts')
        <script>
            $('form#incomplete-manual').on('beforeSubmit',function() {
                const selectedValues = $('input[name="incomplete"]:checked').map(function() {
                    return $(this).val();
                }).get();

                if($("input[name='incomplete-request']:checked").val() == 1){
                    $('input[name="orders"]').val(selectedValues);
                    return;
                }

                $('form#incomplete-manual').attr("action", "{{route('dashboard.payments.store')}}");
            });
            
        </script>
    @endpush
</x-layouts::dashboard>