<x-layouts::dashboard>
    {{-- Header --}}
<div class="header d-flex gap-2 justify-content-between">
    <div class="d-flex align-items-center justify-content-center logo">
        <img src="{{asset('assets/images/machine.png')}}" width="50" class="d-block mb-2 mx-3" alt="contol powerbank">
        <h1>{{__("Device Control")}}</h1>
    </div>
    <div class="d-flex align-items-center controls gap-3">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-plus-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z" fill="currentColor" stroke-width="0" /></svg>
            <a href="{{route('dashboard.devices.create')}}">{{__("Add Device")}}</a>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
            <a href="{{route('dashboard.devices.edit', $device->id)}}">{{__("Edit Device")}}</a>
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
<div class="container device-container">
    <div class="row">
        {{-- Section 1 => device data --}}
        <div class="device-data col-9">
            {{-- Names --}}
            <div class="device-names mb-4">
                <span class="d-block">{{__("ID") . " " . __(env('APP_NAME')) . ": { " . $device->powerfull_id . " }"}}</span>
                <span class="d-block">{{__("ID") . " " . __($device->provider->name) . ": " . $device->device_id }}</span>
            </div>

            {{-- SIM --}}
            <div class="device-sim mb-4">
                <span class="d-block">{{__("SIM") . ": " . $device->sim_number }}</span>
            </div>

            {{-- Batteries --}}
            <div class="batteries-data d-flex px-0 pt-2 gap-1 mb-3" style="font-size: .6rem;width: max-content">
                <div class="fs-1 fw-bold">{{__("Battery")}}</div>
                {{-- Filled Batteries  --}}
                <div class="filled-batteries d-flex align-items-center">
                    <img src="{{asset("assets/images/full-battery.png")}}" style="width: 1rem;" alt="Filled Battery">
                    <span>{{__('full slots')}}</span>
                    <span style="color: var(--background-color);color: var(--background-color);"> . </span>
                    <span class="num">0</span>
                </div>

                 {{-- Empty Batteries  --}}
                <div class="empty-batteries d-flex align-items-center">
                    <img src="{{asset("assets/images/empty-battery.png")}}" style="width: 1rem;" alt="Empty Battery">
                    <span>{{__('empty slots')}}</span>
                    <span style="color: var(--background-color);color: var(--background);"> . </span>
                    <span class="num">{{6}}</span>
                </div>
            </div>

            {{-- Battery Slots & Actions --}}
            <div class="w-100 d-flex flex-wrap flex-row slots">
                {{-- Battry Slots --}}
                <div class="w-50 d-flex flex-nowrap justify-content-evenly">
                    @for($i=0; $i < $device->slots; $i++)
                    <div id="slot-{{$i+1}}" class="d-flex flex-column align-items-center gap-3 slot">
                        <div class="battery">
                            <span>&nbsp;</span>
                        </div>
                        <span class="d-block">{{($i+1)}}</span>
                        <input type="radio" disabled value="{{$i+1}}" name="slot">
                    </div>
                    @endfor
                </div>
                {{-- Public Actions --}}
                <div style="width: 45%">
                    <div class="d-flex flex-column buttons gap-1">
                        <div class="btn btn-success" onclick="deviceOperation('{{$device->device_id}}','popall',1)">{{__("Eject all batteries")}} (Server Error)</div>
                        <div class="btn btn-warning">{{__("Eject all charged batteries")}}</div>
                        <div class="btn btn-danger">{{__("Eject all batteries not charge")}}</div>
                        <div class="btn btn-warning" onclick="deviceOperation('{{$device->device_id}}','heartbeat',1)">{{__("Force heartbeat")}} (Server Error)</div>
                        <div class="btn btn-primary" onclick="deviceOperation('{{$device->device_id}}','report',1)">{{__("Reporting states")}} (Server Error)</div>
                        <div class="btn btn-success" onclick="refreshDevice('{{$device->device_id}}')">{{__("Refresh")}}</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="w-50">
                <span class="text-center fs-3 fw-bold d-block my-3">{{__("Actions")}}</span>
                <div class="row buttons gap-1">
                    <div class="btn btn-primary col-5">{{__("Details")}}</div>
                    <div class="btn btn-danger col-5">{{__("Prohibit Charging")}}</div>
                    <div class="btn btn-success col-5" onclick="ejectBattery('{{$device->device_id}}',document.querySelector('input[type=radio]:checked').value)">{{__("Eject")}}</div>
                    <div class="btn btn-warning col-5" onclick="lockDevice('{{$device->device_id}}')">{{__("Locking")}}</div>
                </div>
            </div>
        </div>

        {{-- Section 2 => device images & qr code --}}
        <div class="device-images col-3">
            {{-- Device Image --}}
            <div class="device-image" style="width: 150px; min-width: 150px;">
                <img src="{{asset('assets/images/device.png')}}" class="img-fluid" alt="contol powerbank">
            </div>

            {{-- device status --}}
            <div class="device-status w-100 mb-5" >
                {{-- Online --}}
                <div class="online font-weight-bold" style="background-color: #8ac78a;"> 
                    <span style="background-color: #004324;"></span>
                    <span class="fw-bold">{{ __('Online') }}</span>
                </div>
                {{-- Offline --}}
                <div class="offline font-weight-bold" style="background-color: #e2dddd;"> 
                    <span style="background-color: #ff0000;"></span>
                    <span>{{ __('Offline') }}</span>
                </div>
            </div>

            {{-- Qr Code --}}
            <div class="qr-code d-flex justify-content-center align-items-center flex-column gap-2 mb-3">
                <div class="qr-image" id="qr-code"></div>
                <div class="fs-2 fw-bold ">QRcode</div>
                <button btn-save class="btn" style="background: var(--background-color); border-radius:20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2"> <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path> <path d="M13.5 6.5l4 4"></path> <path d="M16 19h6"></path> <path d="M19 16v6"></path></svg>
                    &nbsp;
                    {{__("Generate")}}
                </button>
                <textarea id="qr-code-text" class="d-none">{{"https://www.powerfull-eg.com?device=$device->device_id"}}</textarea>
            </div>

            {{-- Shop Logo & Name --}}
            <div class="shop d-flex flex-column justify-content-center align-items-center gap-2">
                <div class="shop-image">
                    <img src="{{$device->shop->data->logo ?? $device->shop->logo}}" width="150" style="min-width: 150px" alt="{{$device->shop->name . " logo"}}">
                </div>
                <div class="shop-name">
                    <span class="fw-bold fs-3 text-start text-truncate">{{$device->shop->name}}</span>
                </div>
            </div>
        </div>
    </div>
    {{-- Notes Section --}}
    <div class="notes" id="notes">
        <div class="subtitle mb-3">
            <i class="ti ti-file-description"></i>
            <span>{{__("Notes")}}</span>
        </div>
        <div class="form">
            <form method="POST" onclick="addNote(this)" action="{{route('dashboard.notes.store')}}">
                @csrf
                <input type="hidden" name="type" value="devices">
                <input type="hidden" name="type_id" value="{{ $device->id }}">
                <div class="form-floating">
                    <textarea required name="note" class="form-control" placeholder="{{__("Add Note Here")}}" id="note" maxlength="200" style="height: 70px; background: transparent; border: 2px solid var(--background-color)"></textarea>
                    <label for="note">{{__("Add Note Here")}}</label>
                  </div>
            </form>
        </div>
        {{-- Latest Notes --}}
        @if($device->notes->count() > 0)
        <div class="notes-container py-3">
            <ul><li>{{ $device->notes->last()->note}}</li></ul>
            <a href="{{route('dashboard.notes.show',$device->notes->last()->id)}}">{{__("See All Notes")}} <i class="ti ti-arrow-right"></i></a>
        </div>
        @endif
    </div>
</div>
@push('styles')
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

    .battery {
      height: 6rem;
      width: 2rem;
      background: #dadada;
      display: flex;
      align-items: center;
      border-top-right-radius: 10px;
      border-top-left-radius: 10px;
      border-bottom-right-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    .battery span {
      height: 2rem;
      width: 5px;
      display: block;
      text-align: center;
      background: var(--text-color);
      margin: 0 auto;
    }

    input[type="radio"] {
      appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid #ccc;
      border-radius: 50%;
      outline: none;
      cursor: pointer;
    }
    @media only screen and (max-width: 800px) {
        div.slots {
            flex-direction: column !important;
            justify-content: space-evenly;
            gap: 2rem;
            font-size: .6rem;
        }
        div.slots > div {
            width: 75% !important;
        }
    }

    input[type="radio"]:checked {
      border-color: var(--background-color); /* Change border color when checked */
      background-color: var(--background-color); /* Change background color when checked */
    }

    label {
      margin-right: 15px;
      font-size: 16px;
    }

    div.device-status > div {
        padding:0 3px;
        display: flex;
        width: 75%;
        margin: 10px;
        border-radius:20px;
    }

    div.device-status > div > span:first-of-type {
        border-radius:50%;
        width: 15px;
        display: block;
        margin-right: 3px; 
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
    #main-loader > div { 
        background: var(--background-color);
        width: 10rem;
        height: 10rem;
        margin: 0 auto;
        display: block;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>
<script src="{{ asset('assets/js/device.js') }}"></script>

<script>
let deviceData;


$(document).ready(async () => {
    
    qrCodeGenerate();
    
    // Handle device data
    bindDeviceData("{{$device->device_id}}");
});
</script>
@endpush
</x-layouts::dashboard>