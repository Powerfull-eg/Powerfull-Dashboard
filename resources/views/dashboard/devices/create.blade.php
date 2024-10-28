<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.devices.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card-header">
            <p class="card-title">{{ __('Create') . " " . __("Device") }}</p>
        </div>
        
        <div class="card-body">
            <div class="row">
                {{-- Device ID --}}
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="device_id" :title="__('Device ID')" :value="old('device_id')" required />
                </div>
                
                {{-- Shop --}}
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="shop_id" :title="__('Shop')" :options="$shops" :selected="old('shop_id')" required />
                </div>
            </div>
            
            {{-- Status & Slots --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="status" :title="__('Status')" :options='[0 => "Offline",1 => "Online"]' :selected="old('status') ?? 1" required />
                </div>
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="slots" :title="__('Slots')" :value="old('slots') ?? 6" required />
                </div>
            </div>
            {{-- Provider --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.select name="provider_id" :title="__('Provider')" :options="$providers" :selected="old('provider_id') ?? 1" required />
                </div>
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="sim_number" :title="__('SIM Number')" :value="old('sim_number')" required />
                </div>
            </div>
            {{-- Powerfull ID --}}
            <div class="row">
                <div class="mb-3 col col-6 col-md-6 col-sm-12">
                    <x-components::forms.input name="powerfull_id" :title="__('Powerfull') . ' ' . __('ID') " :value="old('powerfull_id')" required />
                </div>
            </div>
            {{-- submit --}}
            <div class="card-footer text-end">
                <a href="{{ route('dashboard.devices.index') }}" class="btn">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </div>
        </div>
    </form>
</x-layouts::dashboard>
