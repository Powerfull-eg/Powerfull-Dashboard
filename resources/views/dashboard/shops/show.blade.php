<x-layouts::dashboard>
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center logo">
            <img src="{{asset('assets/images/machine.png')}}" width="50" class="d-block mb-2 mx-3" alt="contol powerbank">
            <h1>{{__("Merchant Control")}}</h1>
        </div>
        <div class="d-flex align-items-center controls gap-3">
            <div>
                <i class="ti fs-2 ti-circle-plus"></i>
                <a onclick="$('#note').focus()" href="{{-- route('dashboard.shops.create') --}}#">{{__("Add")." ". __("Note")}}</a>
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
            <div id="shop-data" class="col col-md-9">
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
                {{-- Merchant Data --}}
                <div id="merchant-data">
                    <div class="subtitle">
                        <i class="ti ti-circle-plus"></i>
                        <span>{{__("Merchant") . " ". __("Data")}}</span>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("Merchant Name")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Service Phone")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Merchant Type")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Merchant Location --}}
                <div id="location">
                    <div class="subtitle">
                        <i class="ti ti-map-pin"></i>
                        <span>{{__("Merchant") . " ". __("Location")}}</span>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("Latitude")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("Longitude")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Governorate")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("City")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Address")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Merchant Timing --}}
                <div id="timing">
                    <div class="subtitle">
                        <i class="ti ti-clock-hour-4"></i>
                        <span>{{__("Merchant") . " ". __("Timing")}}</span>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("Opens At")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("Closes At")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Closes") . " " . __("After") . " " . __("Midnight")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Merchant Price --}}
                <div id="price">
                    <div class="subtitle">
                        <i class="ti ti-coin"></i>
                        <span>{{__("Merchant") . " ". __("Price")}}</span>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("Amoun")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                                <td class="title">{{__("For Time")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Merchant Menu --}}
                <div id="menu">
                    <div class="subtitle">
                        <i class="ti ti-tools-kitchen-2"></i>
                        <span>{{__("Merchant") . " ". __("Menu")}}</span>
                    </div>
                    <div class="table">
                        <table class="menu-table">
                        </table>
                    </div>
                </div>
                {{-- Device Info --}}
                <div id="device-info">
                    <div class="d-flex justify-content-between">
                        <div class="subtitle">
                            <i class="ti ti-zoom-exclamation"></i>
                            <span>{{__("Device") . " ". __("Info")}}</span>
                        </div>
                        <a href="#" style="text-decoration: none;">
                            <div class="subtitle">
                                <i class="ti ti-player-track-next"></i>
                                <span>{{__("Go To") ." ".__("Device") . " ". __("Control")}}</span>
                            </div>
                        </a>
                    </div>
                    <div class="table">
                        <table class="content-table">
                            <tr>
                                <td class="title">{{__("ID Bajie")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("SIM")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                            <tr>
                                <td class="title">{{__("Battery")}}:</td>
                                <td class="text-truncate"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex ad assumenda corporis corrupti doloribus, reprehenderit dicta nisi? Voluptate voluptatem omnis obcaecati sed voluptates, illo fuga, consectetur, natus porro tempora praesentium.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Section 2 => Shop Images --}}
            <div id="shop-images" class="col col-md-3 mx-auto text-center d-flex flex-column">
                {{-- Shop Name And Logo --}}
                <div>
                    <span class="d-block fs-1 fw-block text-truncate"> {{ $shop->name }}</span>
                    <div class="shop-image">
                        <img src="{{$shop->data && $shop->data->logo ? $shop->data->logo : $shop->logo}}" class="img-fluid" alt="{{$shop->name}} Logo" style="width: 200px; border-radius: 50%;">
                    </div>
                    <a href="{{route('dashboard.shops.edit',$shop->id)}}">
                        <div class="controls">
                            <div class="w-50 mx-auto mt-3">{{__("Edit" . " ". __("Logo"))}}</div>
                        </div>
                    </a>
                </div>
                {{-- Merchant Qr code --}}
                <div class="qr-code d-flex justify-content-center align-items-center flex-column gap-2 my-3">
                    <div class="qr-image" id="qr-code"></div>
                    <div class="fs-2 fw-bold ">QRcode</div>
                    <div class="controls">
                        <div class="">
                            <i class="ti ti-wand"></i>
                            <span btn-save>{{__("Generate")}} </span>
                        </div>
                    </div>
                    <textarea id="qr-code-text" class="d-none">{{"https://www.powerfull-eg.com?device=" . $shop->device->device_id}}</textarea>
                </div>
                {{-- Device Image --}}
                <div class="device-image d-flex flex-column h-100 justify-content-end align-items-center">
                    <img src="{{asset('assets/images/device.png')}}" width="200" class="img-fluid" alt="contol powerbank">
                    {{-- device status --}}
                    <div class="device-status w-50 my-3" >
                        {{-- Online --}}
                        <div class="online font-weight-bold d-none" style="padding:3px; background-color: #8ac78a; display: flex; width: 100%; border-radius: 20px"> 
                            <span style="border-radius:50%;width: 15px;display: block;margin-right: 3px; background-color: #004324;"></span>
                            <span class="fw-bold">{{ __('Online') }}</span>
                        </div>
                        {{-- Offline --}}
                        <div class="offline font-weight-bold" style="padding:3px; background-color: #fff;  display: flex; width: 100%; border-radius: 20px"> 
                            <span style="border-radius:50%; width: 15px;height: 15px; display: block;margin-right: 3px; background-color: #ff0000;"></span>
                            <span>{{ __('Offline') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Section 3 => Notes Section --}}
        <div class="notes" id="notes">
            <div class="subtitle mb-3">
                <i class="ti ti-file-description"></i>
                <span>{{__("Notes")}}</span>
            </div>
            <div class="form">
                <form method="GET" onclick="addNote(this)">
                    <div class="form-floating">
                        <textarea required name="note" class="form-control" placeholder="{{__("Add Note Here")}}" id="note" maxlength="200" style="height: 70px; background: transparent; border: 2px solid var(--background-color)"></textarea>
                        <label for="note">{{__("Add Note Here")}}</label>
                      </div>
                </form>
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
            min-width: 25%; 
        }
        .subtitle i.ti {
            font-size: 30px;
            font-weight: 600;
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
        .content-table .text-truncate {
            white-space: unset !important;
        }
        .form-control:focus{
            box-shadow: unset;
        }
        .qr-code .qr-image { 
            width: 160px;
            height: 160px;
            border: var(--background-color) 2px solid;
            border-radius: 5px;
        }

        #qr-code img {
            margin: 10px auto;
        }
</style>
@endpush
@push('scripts')
<script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>

<script>
    $(document).ready(async () => { 
        qrCodeGenerate();
        
        const setDeviceConnection = () => {
            const online = checkDeviceConnection("{{$shop->device->device_id}}");
            document.querySelector(".device-status ." + (online ? "offline" : "online")).classList.add("d-none");
            document.querySelector(".device-status ." + (online ? "online" : "offline")).classList.remove("d-none");
        }

        setDeviceConnection();

        setInterval(() => {
            setDeviceConnection();
        }, 30000);
     });
    
</script>
@endpush
</x-layouts::dashboard>