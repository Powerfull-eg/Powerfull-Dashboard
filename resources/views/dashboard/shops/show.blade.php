<x-layouts::dashboard>
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center logo">
            <img src="{{asset('assets/images/machine.png')}}" width="50" class="d-block mb-2 mx-3" alt="contol powerbank">
            <h1>{{__("Merchant Control")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div>
                <i class="ti fs-2 ti-circle-plus"></i>
                <a href="{{-- route('dashboard.shops.create') --}}#">{{__("Add")." ". __("Shop")}}</a>
            </div>
            <div>
                <i class="ti fs-2 ti-pencil"></i>
                <a href="{{-- route('dashboard.shops.edit',$shop->id) --}}#">{{ __("Edit") ." ". __("Shop")}}</a>
            </div>
        </div>
    </div>
    {{-- Loader --}}
    <div id="main-loader" class="mx-auto d-none">
        <div class="spinner-grow" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>
    {{-- Content --}}
    <div class="container shop-container">
        <div class="row">
            {{-- Section 1 => Shop Data --}}
            <div id="shop-data" class="col col-7 col-sm-12">
                {{-- Contract Data --}}
                <div id="contract">
                    <div class="subtitle">
                        <i class="ti ti-building-store"></i>
                        <span>{{__("Merchant") . " ". __("Contract")}}</span>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("Company Name")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Commercial Register")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("Tax Card")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Company Head Office")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Branches")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Signing Contract In Name")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("Job Title")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Section 2 => Shop Images --}}
            <div id="shop-images" class="col col-4 col-sm-12">

            </div>
        </div>
    </div>
@push('styles')
<style>
        a:focus
        {
            text-decoration: none;
        }
        a {
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
        .shop-container {
            padding: 1rem;
            border: 2px solid var(--background-color);
        }
        .subtitle {
            display: flex;
            color: var(--text-color);
            gap: .5rem;
            background-color: var(--background-color);
            width: fit-content;
            padding: .75rem;
            align-items: center;
            border-radius: 10px;
        }
        table.content-table tr {
            border: 3px solid var(--background-color);
            padding: 5px;
            margin-top: 10px;
            display: flex;
            max-width: 70vw;
            gap: 10px;
            align-items: center;
            border-radius: 7px;
        }
        table.content-table tr td {
            width: fit-content;
        }
        table.content-table tr td.title{
            min-width: fit-content;
            font-size: 15px;
            font-weight: bold;
        }
        .content-table tr >td:not(.title) {
            max-width: 63vw;
            color: rgb(117, 117, 117);
        }
</style>
@endpush
@push('scripts')
<script>
</script>
@endpush
</x-layouts::dashboard>