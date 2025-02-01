<x-layouts::dashboard :title="ucfirst($shop->name) . ' ' . __('Reports')">
    <x-components::status />
    <x-components::forms.customDatePicker />
    
    <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
        <div class=" d-flex gap-2 justify-content-between">
            <div class="d-flex align-items-center justify-content-center logo">
                <img src="{{$shop->logo}}" width="100" class="d-block mx-3" style="border-radius: 50%; border: 2px solid var(--background-color)" alt="{{$shop->name}} Logo">
                <h1>{{$shop->name}}</h1>
            </div>
        </div>
        {{-- Exports --}}
        <div class="d-flex gap-2 justify-content-end">
            <form id="pdf-form" class="export-form" action="{{route('dashboard.reports.shop.pdf', $shop->id)}}" method="POST">
                @csrf
                <input type="hidden" name="startDate" value="{{$startDate}}">
                <input type="hidden" name="endDate" value="{{$endDate}}">
                <button type="submit" class="btn export">{{__("Export PDF")}}</button>
            </form>
            <form id="excel-form" class="export-form ignore-loader" action="{{route('dashboard.reports.shop.excel', $shop->id)}}" method="POST">
                @csrf
                <input type="hidden" name="startDate" value="{{$startDate}}">
                <input type="hidden" name="endDate" value="{{$endDate}}">
                <button type="submit" class="btn export">{{__("Export Excel")}}</button>
            </form>
        </div>
    </div>
    <div class="container">

        {{-- Summary Data --}}
        <div class="summary-container row justify-content-center mt-5">
            @foreach ($shop->summary as $title => $number)           
            <div class="summary-card {{ in_array($loop->index, [1,4]) ? 'middle' : ''}} col-3">
                <div class="number-card">
                    <span class="background"></span>
                    <span class="number" style="{{$number > 99 ? 'left: -5px' : ($number < 10 ? 'left: 5px' : '')}}">{{$number}}</span>
                </div>
                <div class="title-card">{{ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $title, -1, PREG_SPLIT_NO_EMPTY)))}}</div>
                <hr>
            </div>
            @endforeach
        </div>

        {{-- Shop Operations --}}
        <livewire:shop-operations-table :device="$shop->device->device_id" :startDate="$startDate" :endDate="$endDate"/>
    </div>
    @push('styles')
        <style>
            .btn.export {
                background-color: var(--background-color);
                color: var(--text-color);
                padding: .5rem 1rem;
                border-radius: 10px; 
                justify-content: center;
                max-height: fit-content;
            }
        </style>
    @endpush
</x-layouts::dashboard>