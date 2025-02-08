<x-layouts::dashboard>
    <x-components::status />
    <div class="header d-flex gap-2 justify-content-between mb-3">
        <div class="d-flex align-items-end justify-content-center logo">
            <i style="font-size: 5rem; color: var(--background-color)" class="ti ti-building-store"></i>
            <h1>{{__("Merchant Operation Detail")}}</h1>
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
                                    <td class="text-truncate"> {{$shop->name}} </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Commercial Register")}}:</td>
                                    <td class="text-truncate"> (Soon)</td>
                                    <td class="title">{{__("Tax Card")}}:</td>
                                    <td class="text-truncate"> (Soon)</td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Company Head Office")}}:</td>
                                    <td class="text-truncate">(Soon) </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Branches")}}:</td>
                                    <td class="text-truncate">(Soon) </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Signing Contract In Name")}}:</td>
                                    <td class="text-truncate">(Soon) </td>
                                    <td class="title">{{__("Job Title")}}:</td>
                                    <td class="text-truncate"> (Soon)</td>
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
                                    <td class="text-truncate"> {{$shop->name}} </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Service Phone")}}:</td>
                                    <td class="text-truncate"> {{$shop->phone}}</td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Merchant Type")}}:</td>
                                    <td class="text-truncate">{{$shop->data->type ? (app()->getLocale() == 'ar' ? $shop->data->type->type_ar_name : $shop->data->type->type_en_name) :  __("Not Set") }} </td>
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
                                    <td class="text-truncate"> {{$shop->data && $shop->data->lat ? $shop->data->lat : $shop->location_latitude}}</td>
                                    <td class="title">{{__("Longitude")}}:</td>
                                    <td class="text-truncate"> {{$shop->data && $shop->data->lng ? $shop->data->lng : $shop->location_longitude}}</td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Governorate")}}:</td>
                                    <td class="text-truncate"> {{$shop->governorate}} </td>
                                    <td class="title">{{__("City")}}:</td>
                                    <td class="text-truncate"> {{$shop->city}} </td>
                                </tr>
                                <tr>
                                    <td class="title">{{__("Address")}}:</td>
                                    <td style="white-space: wrap !important" class="text-truncate">{{$shop->address}} </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Merchant Conclusion --}}
                    <div id="conclusion">
                        <div class="d-flex justify-content-between">
                            <div class="subtitle" style="max-height: 50px;">
                                <i class="ti ti-currency-dollar"></i>
                                <span>{{__("Conclusion")}}</span>
                            </div>
                            {{-- date form --}}
                            <div class="Date my-3">
                                <x-components::forms.customDatePicker :startDate="$startDate" :endDate="$endDate" />
                            </div>
                        </div>
                        {{-- Conclusion --}}
                        <div class="table">
                            <table class="content-table">

                        @foreach ($shop->summary as $title => $number)           
                            <tr>
                                <td class="title">{{ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $title, -1, PREG_SPLIT_NO_EMPTY)))}}:</td>
                                <td class="text-truncate"> {{$number}} </td>
                            </tr>
                        @endforeach
                            </table>
                        </div>
                        
                    </div>
                </div>
                
                {{-- Section 2 => Shop Images --}}
                <div id="shop-images" class="col col-md-3 mx-auto text-center d-flex flex-column">
                    {{-- Shop Name And Logo --}}
                    <div>
                        <span class="d-block fs-4 fw-block text-truncate my-3"> {{ $shop->name }}</span>
                        <div class="shop-image">
                            <img src="{{$shop->data && $shop->data->logo ? $shop->data->logo : $shop->logo}}" class="img-fluid" alt="{{$shop->name}} Logo" style="width: 200px; border-radius: 50%;">
                        </div>
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
            {{-- Operations --}}
            <div class="operations">
                <livewire:shop-operations-table :device="$shop->device->device_id" :startDate="$startDate" :endDate="$endDate"/>
            </div>

            {{-- Exports --}}
            <div class="d-flex gap-2 justify-content-start my-3">
                <form id="pdf-form" class="export-form" action="{{route('dashboard.shops.shop.pdf', $shop->id)}}" method="GET">
                    @csrf
                    <input type="hidden" name="startDate" value="{{$startDate}}">
                    <input type="hidden" name="endDate" value="{{$endDate}}">
                    <button type="submit" class="btn export">{{__("Export PDF")}}</button>
                </form>
                <form id="excel-form" class="export-form ignore-loader" action="{{route('dashboard.shops.shop.excel', $shop->id)}}" method="GET">
                    @csrf
                    <input type="hidden" name="startDate" value="{{$startDate}}">
                    <input type="hidden" name="endDate" value="{{$endDate}}">
                    <button type="submit" class="btn export">{{__("Export Excel")}}</button>
                </form>
                <form id="send-form" class="export-form justify-self-end" action="{{route('dashboard.reports.send-report', $shop->id)}}" method="GET">
                    @csrf
                    <input type="hidden" name="startDate" value="{{$startDate}}">
                    <input type="hidden" name="endDate" value="{{$endDate}}">
                    <button type="submit" class="btn export">{{__("Send Report")}} &nbsp;<i class="ti ti-brand-whatsapp fs-2"></i></button>
                </form>
            </div>
        </div>
        @push('styles')
        <style>
            .shop-container {
                padding: 1rem;
                border: 2px solid var(--background-color);
            }
            form#date label > input:valid {
                /* border: unset ;
                box-shadow: unset; */
            }
            form#date input[type="date"] {
                border-bottom: unset !important;
                border: 4px solid var(--background-color);
                padding: 0 10px;
            }
            form#date input[type="submit"] {
                background-color: var(--background-color);
            }
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