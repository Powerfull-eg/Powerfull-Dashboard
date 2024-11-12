<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    {{-- <x-components::chart :title="__('Devices')" :dataLabels="array_keys($operationsPerDevice)" :dataValues="$countPerDevice" /> --}}
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center logo">
            <img src="{{asset('assets/images/machine.png')}}" width="50" class="d-block mb-2 mx-3" alt="contol powerbank">
            <h1>{{__("Device Control")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-plus-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z" fill="currentColor" stroke-width="0" />
                </svg>
                <a href="{{route('dashboard.devices.create')}}">{{__("Add Device")}}</a>
            </div>
        </div>
    </div>
    {{-- Filter --}}
    <div class="input-group flex-nowrap w-50 mx-auto" style="border: 2px solid var(--background-color)">
        <span class="input-group-text" id="addon-wrapping"><i class="ti ti-home-search" style="font-size: 1.5rem"></i></span>
        <input type="text" oninput="filter(this.value)" class="form-control" placeholder="Search ..." aria-label="Search" aria-describedby="addon-wrapping">
    </div>
    <hr class="mx-5">

    <div id="data">
        {{-- Devices --}}
        <div class="devices justify-content-evenly row px-2 gap-1 mb-5">
            @foreach ($devices as $device)
            <div class="device col col-12 col-xl-5 me-2 mb-5 d-flex flex-column text-center" attr-filter="{{$device->device_id}} {{$device->shop->name}} {{$device->powerfull_id}}" attr-device="{{$device->device_id}}">
                    <a href="{{route('dashboard.devices.show', $device->id)}}" class="text-decoration-none">
                    <div class="d-flex justify-content-between gap-1 p-2 position-relative">
                        <div class="device-data d-flex flex-column">
                            <span class="title fs-1 w-100 text-start" style="font-weight: 900; text-decoration: underline; min-height: 20px;">{{$device->powerfull_id ?? ''}}</span>
                            <div class="info d-flex flex-row">
                                <div class="shop-logo me-3 d-flex align-items-end">
                                    <img src="{{$device->shop->data->logo ?? $device->shop->logo}}" width="50" style="border-radius: 50%; min-height: 50px" alt="contol powerbank">
                                </div>
                                <div class="shop-info d-flex flex-column w-50">
                                    <span class="shop-name text-truncate" style="font-size: 1rem;font-weight: 800">{{ $device->shop->name }}</span>
                                    <!-- device status -->
                                    <div class="device-status" >
                                        {{-- Loader --}}
                                        <div class="spinner-grow text-dark" role="status"></div>
                                        {{-- Online --}}
                                        <div class="online font-weight-bold" style="padding:3px; background-color: #8ac78a; display: flex; width: 100%; display: none;"> 
                                            <span style="border-radius:50%;width: 15px;display: block;margin-right: 3px; background-color: #004324;"></span>
                                            <span class="fw-bold">{{ __('Online') }}</span>
                                        </div>
                                        {{-- Offline --}}
                                        <div class="offline font-weight-bold" style="padding:3px; background-color: #fff;  display: flex; width: 100%; display: none;"> 
                                            <span style="border-radius:50%; width: 15px;height: 15px; display: block;margin-right: 3px; background-color: #ff0000;"></span>
                                            <span>{{ __('Offline') }}</span>
                                        </div>
                                    </div>
                                    <!-- Batteries Data-->
                                    <div class="batteries-data d-flex px-0 pt-2 gap-1" style="font-size: 1rem;width: max-content">
                                        {{-- Loader --}}
                                        <div class="spinner-grow text-dark" role="status"></div>
                                        <!-- Filled Batteries -->
                                        <div class="filled-batteries d-flex align-items-center">

                                            <img src="{{asset("assets/images/full-battery.png")}}" style="width: 1.5rem;" alt="Filled Battery">
                                            <span>{{__('full slots')}}</span>
                                            ( <span class="num">0</span> )
                                        </div>
                                        <!-- Empty Batteries -->
                                        <div class="empty-batteries d-flex align-items-center">
                                            <img src="{{asset("assets/images/empty-battery.png")}}" style="width: 1rem;" alt="Empty Battery">
                                            <span>{{__('empty slots')}}</span>
                                            ( <span class="num">{{6}}</span> )
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Device Image --}}
                        <div class="device-image" style="width: 110px; min-width: 110px; position: absolute; right: 5px; top: 20px">
                            <img src="{{asset('assets/images/device.png')}}" class="img-fluid" alt="contol powerbank">
                        </div>

                        {{-- Edit Device Icon --}}
                        <a href="{{route('dashboard.devices.edit', $device->id)}}" class="edit-device"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg></a>
                    </div>
                </a>
            </div>
            @endforeach 
        </div>
        {{-- <livewire:devices-table :startDate="$startDate" :endDate="$endDate"/> --}}
        <div class="table-responsive">
            <table class="table table-vcenter table-nowrap w-50">
                <tr>
                    <td>{{__("Total Devices")}}</td>
                    <td>{{$allDevices->count()}}</td>
                </tr>  
                <tr>
                    <td>{{__("Devices In Selected Date")}}</td>
                    <td>{{$devices->count()}}</td>
                </tr>  
                <tr>
                    <td>{{__("Devices In Last 30 Days")}}</td>
                    <td>{{$allDevices->where('created_at','>=',now()->previous("Month") )->count()}}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="footer-data">
        <div class="excel">
            <button onclick="excel.export()" class="btn btn-success"> {{ __("Export Excel") }} </button>
        </div>
    </div>
@push("styles")
    <style>
        .device-status > div {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            font-size: .6rem;
            max-width: 100% !important;
        }
        .device {
            border-radius: 5px;
            border: var(--background-color) 1px solid;
        }
        .spinner-grow {
            background: var(--background-color)
        }

        .edit-device {
            position: absolute;
            right: -25px;
            top: 3px;
            color: var(--text-color-2);
            cursor: pointer;
            background: var(--background-color);
            border-radius: 30px;
            padding: 5px;
            display: none;
            transition: ease-out 2s;
        }

        .device  a {
            text-decoration: none !important;
            color: var(--text-color-2) !important;
        }

        .device:hover .edit-device {
           display: block; 
        }

        @media only screen and (max-width: 650px) {
            .device-image {
                display: none;
            }
        }
    </style>
@endpush
@push("scripts")
<script>
        document.addEventListener("DOMContentLoaded", () => {
            
        const devices = document.querySelectorAll(".device");
        devices.forEach(device => {
            bindDeviceSelector(device)  
        }); 
    });
</script>
@endpush
</x-layouts::dashboard>
<script>
    const excel = new Table2Excel("#data");
</script>