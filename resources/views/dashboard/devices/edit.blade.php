<x-layouts::dashboard>
    <x-components::status />

    <form class="card" action="{{ route('dashboard.devices.update', $device->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="card-header">
            <p class="card-title">{{ __('Edit') . " " . __("Device") }}</p>
        </div>
        
        <div class="card-body">
            <div class="row">
                {{-- Device ID --}}
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="device_id" :title="__('Device ID')" :value="$device->device_id" required />
                </div>
                
                {{-- Shop --}}
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="shop_id" :title="__('Shop')" :options="$shops" :selected="$device->shop_id" required />
                </div>
            </div>
            
            {{-- Status & Slots --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="status" :title="__('Status')" :options='[0 => "Offline",1 => "Online"]' :selected="$device->status ?? 1" required />
                </div>
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="slots" :title="__('Slots')" :value="$device->slots" required />
                </div>
            </div>
            {{-- Shop & SIM --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="provider_id" :title="__('Provider')" :options="$providers" :selected="$device->provider_id" required />
                </div>
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="sim_number" :title="__('SIM Number')" :value="$device->sim_number" required />
                </div>
            </div>
            {{-- Powerfull ID --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="powerfull_id" :title="__('Powerfull') . ' ' . __('ID') " :value="$device->powerfull_id" required />
                </div>
            </div>
            {{-- submit --}}
            <div class="card-footer text-end">
                <a href="{{ route('dashboard.devices.index') }}" class="btn">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </div>
    </form>
</x-layouts::dashboard>
