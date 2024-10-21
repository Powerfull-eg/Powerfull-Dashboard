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

{{-- Content --}}
<div class="container container-fluid">
    <div class="row gap-1">
        {{-- Section 1 => device data --}}
        <div class="device-data col-7">
            {{-- Names --}}
            <div class="device-names mb-5">
                <span class="d-block">{{__("ID") . " " . __(env('APP_NAME')) . ": " . $device->id }}</span>
                <span class="d-block">{{__("ID") . " " . __($device->provider->name) . ": " . $device->device_id }}</span>
            </div>

            {{-- SIM --}}
            <div class="device-sim mb-5">
                <span class="d-block">{{__("SIM") . ": " . $device->sim }}</span>
            </div>

            {{-- Batteries --}}
            <div class="batteries-data d-flex px-0 pt-2 gap-1" style="font-size: .6rem;width: max-content">
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
            <div class="w-100 d-flex flex-wrap flex-row">
                {{-- Battry Slots --}}
                <div class="w-50 d-flex flex-nowrap justify-content-evenly">
                    @for($i=0; $i < $device->slots; $i++)
                    <div class="d-flex flex-column align-items-center gap-3">
                        <div class="battery slot">
                            <span>&nbsp;</span>
                        </div>
                        <span class="d-block">{{($i+1)}}</span>
                        <input type="radio" value="{{$i+1}}" name="slot">
                    </div>
                    @endfor
                </div>
                {{-- Public Actions --}}
                <div style="width: 45%">
                    <div class="d-flex flex-column buttons gap-1">
                        <div class="btn btn-success">{{__("Eject all batteries")}}</div>
                        <div class="btn btn-warning">{{__("Eject all charged batteries")}}</div>
                        <div class="btn btn-danger">{{__("Eject all batteries not charge")}}</div>
                        <div class="btn btn-warning">{{__("Force heartbeat")}}</div>
                        <div class="btn btn-primary">{{__("Reporting states")}}</div>
                        <div class="btn btn-success">{{__("Refresh")}}</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="w-50">
                <span class="text-center fs-3 fw-bold d-block my-3">{{__("Actions")}}</span>
                <div class="row buttons gap-1">
                    <div class="btn btn-primary col-5">{{__("Details")}}</div>
                    <div class="btn btn-danger col-5">{{__("Prohibit Charging")}}</div>
                    <div class="btn btn-success col-5">{{__("Eject")}}</div>
                    <div class="btn btn-warning col-5">{{__("Locking")}}</div>
                </div>
            </div>
        </div>

        {{-- Section 2 => device images & qr code --}}
        <div class="device-images col-4"></div>
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

    input[type="radio"]:checked {
      border-color: var(--background-color); /* Change border color when checked */
      background-color: var(--background-color); /* Change background color when checked */
    }

    label {
      margin-right: 15px;
      font-size: 16px;
    }
</style>
@endpush
</x-layouts::dashboard>