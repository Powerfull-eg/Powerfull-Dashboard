<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.stations.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <p class="card-title">{{ __('Create') . " " . __("New Station") }}</p>
        </div>

        <div class="card-body row">
            <div class="mb-3 col col-4">
                <x-components::forms.input name="inet_id" :title="__('Inet ID')" :value="old('inet_id')" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.select name="status" :title="__('Status')" :options='["Online" => "Online","Offline" => "Offline"]' :selected="'Online'" :value="old('status')" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="signal_value" :title="__('Signal Value')" type="number" :value="old('signal_value')" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.select name="type" :title="__('Type')" :options='["6-slot Cabinet","8-slot Cabinet"]' :selected="'6-slot Cabinet'" :value="old('type')" required />
            </div>
            <div class="mb-3 col col-8">
                <x-components::forms.select name="slots" :title="__('Slots')" :options='[1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8]' :selected="6" :value="old('slots')" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="return_slots" :title="__('Empty Slots')" :options='[0,1,2,3,4,5,6,7,8]' :selected="0" :value="old('return_slots')" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="fault_slots" :title="__('Corrupted Slots')" :options='[0,1,2,3,4,5,6,7,8]' :selected="0" :value="old('fault_slots')" required />
            </div>
            <div class="mb-3">
                <x-components::forms.select name="merchant_id" :title="__('Merchant')" :options="$merchants" :value="old('merchant_id')" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="internet_card" :title="__('Internet Card')" type="number" :value="old('internet_card')"/>
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="device_ip" :title="__('Device IP')" :value="old('device_ip')" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="server_ip" :title="__('Server IP')" :value="old('server_ip')" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.input name="port" :title="__('Port')" type="number" :value="old('port')" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="authorize" :title="__('Authorization')" :options='["authorized" => "Authorized","notAuthorized"=>"Not Authorized"]' :selected="'notAuthorized'" :value="old('authorize')" required />
            </div>
            <input type="hidden" name="created_by" value="{{Auth::user()->id}}">
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.stations.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
