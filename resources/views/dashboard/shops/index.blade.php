<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />


    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center logo">
            <i style="font-size: 5rem; color: var(--background-color)" class="ti ti-building-store"></i>
            <h1>{{__("Merchant Control")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div>
                <i style="font-size: 1rem" class="ti ti-circle-plus"></i>
                <a href="{{-- route('dashboard.shops.create') --}}#">{{__("Add Shop") . " (Soon)"}}</a>
            </div>
        </div>
    </div>
    <div class="my-3 gap-3 container d-flex justify-content-start">

        {{-- Fetch Shops From Providers  --}}
        <form action="{{ route('dashboard.shops.sync') }}" method="POST">
            @csrf
            <div>
                <button type="submit" id="fetch" class="fetch btn btn-warning">{{__("Fetch") ." " . __("Shops") }}</button>
            </div>
        </form>

        {{-- Shops Types --}}
        <div>
            <a href="{{ route('dashboard.shop-types.index') }}" class="btn btn-warning">{{ __('Shop') ." " . __("Types")}}</a>
        </div>

        {{-- Filter --}}
        <div class="input-group flex-nowrap w-50" style="border: 2px solid var(--background-color)">
            <span class="input-group-text" id="addon-wrapping"><i class="ti ti-home-search" style="font-size: 2rem"></i></span>
            <input type="text" oninput="filter(this.value)" class="form-control" placeholder="Search ..." aria-label="Search" aria-describedby="addon-wrapping">
          </div>
    </div>
    <hr class="mx-5">

    {{-- Shops --}}
    <div id="data">
                {{-- Devices --}}
                <div class="devices justify-content-evenly row px-2 gap-1 mb-5">
                    @foreach ($shops as $shop)
                    <div class="device col col-12 col-xl-5 me-2 mb-5 d-flex flex-column text-center" style="min-height: 150px" attr-filter="{{$shop->name}}" attr-shop="{{$shop->id}}" attr-device="{{$shop->device ? $shop->device->device_id : ''}}">
                        <div class="data d-none" shop-data="{{json_encode(["open" => $shop->data->opens_at, "close" => $shop->data->closes_at, "afterMid" => $shop->data->closes_after_midnight ?? 0])}}" ></div>
                            <a href="{{route('dashboard.shops.show', $shop->id)}}" class="text-decoration-none">
                            <div class="d-flex justify-content-between gap-1 p-2 position-relative">
                                <div class="device-data d-flex flex-column">
                                    <span class="title fs-1 w-100 text-start text-truncate" style="font-weight: 900; text-decoration: underline">{{$shop->name}}</span>
                                    <div class="info d-flex flex-row">
                                        <div class="shop-logo me-3 d-flex align-items-end">
                                            <img src="{{asset('assets/images/device.png')}}" width="100" class="img-fluid" alt="contol powerbank">
                                        </div>
                                        <div class="shop-info d-flex flex-column w-50">
                                            {{-- device name and open status --}}
                                            <div class="d-flex gap-2">
                                                <span class="shop-name text-start" style="font-size: 1rem;font-weight: 800">{{ $shop->device ? $shop->device->device_id : '' }}</span>
                                                {{-- Open status --}}
                                                <div class="status open-status" style="min-width: fit-content">
                                                    <i style="color: var(--background-color)" class="ti ti-clock-hour-9"></i>
                                                    <span class="text text-success">Open</span>
                                                    <span class="fs-6" style="min-width: fit-content">Closing: {{$shop->data->closes_at}}</span>
                                                </div>
                                            </div>
                                            <!-- device status -->
                                            <div class="device-status w-50" >
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
                                                    <img src="{{asset("assets/images/empty-battery.png")}}" style="width: 1.5rem;" alt="Empty Battery">
                                                    <span>{{__('empty slots')}}</span>
                                                    ( <span class="num">{{6}}</span> )
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                {{-- Device Image --}}
                                <div class="device-image" style="width: 120px; min-width: 120px; position: absolute; right: 5px; top: 2px">
                                    <img src="{{$shop->data->logo ?? $shop->logo}}" width="120" style="border-radius: 50%; min-height: 120px" alt="contol powerbank">
                                </div>
        
                                {{-- Edit Device Icon --}}
                                <a href="{{route('dashboard.shops.edit', $shop->id)}}" class="edit-device"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg></a>
                            </div>
                        </a>
                    </div>
                    @endforeach 
                </div>
    </div>
@push("styles")
    <style>
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
        .device  a {
            text-decoration: none !important;
            color: var(--text-color-2) !important;
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

            // Set open or closed status
            const shops = document.querySelectorAll("[attr-shop]");
            shops.forEach(shop => {
                const data = JSON.parse(shop.querySelector("[shop-data]").getAttribute("shop-data"));                
                const closed = isClosed(data.close ?? "11:59:59",data.open ?? "00:00:00",data.afterMid ?? 0);
                if(closed){
                    const text = shop.querySelector(".open-status > span.text");
                    text.innerHTML = "Closed";
                    text.classList.remove('text-success');
                    text.classList.add('text-danger');
                }
            });
        });
        
    function isClosed(closingTime,openingTime = null,closeAfterMidnight = 0) {
        let closed;
        // Split the time string into hours, minutes, and seconds
        const [closingHours, closingMinutes, closingSeconds] = closingTime.split(":").map(Number);
        let now = new Date();
        now.setHours(closingHours, closingMinutes, closingSeconds, 0);
        const closeAt = now.getTime();

        // Check if closes after midnight
        if (closeAfterMidnight == 1) {
            closeAt += (1000 * 24 * 60 * 60);
            const [openingHours, openingMinutes, openingSeconds] = openingTime.split(":").map(Number);
            now.setHours(openingHours, openingMinutes, openingSeconds, 0);
            const openAt = now.getTime() + (1000 * 24 * 60 * 60);
        }
        now = new Date();
        return closeAfterMidnight == 1 ? (now > closeAt && now < openAt) : now > closeAt; 
    }
    </script>
@endpush
</x-layouts::dashboard>