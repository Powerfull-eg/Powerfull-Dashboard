<x-layouts::dashboard>
<form class="card" action="{{ route('dashboard.stations.update', $station->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-title">{{ __('Edit') . " " . __("Station")}}</div>
        </div>

        <div class="card-body row">
            <div class="mb-3 col col-4">
                <x-components::forms.input name="inet_id" :title="__('Inet ID')" :value="$station->inet_id" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.select name="status" :title="__('Status')" :options='["Online" => "Online","Offline" => "Offline"]' :selected="$station->status" :value="$station->status" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="signal_value" :title="__('Signal Value')" type="number" :value="$station->signal_value" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.select name="type" :title="__('Type')" :options='["6-slot Cabinet","8-slot Cabinet"]' :selected="$station->type" :value="$station->type" required />
            </div>
            <div class="mb-3 col col-8">
                <x-components::forms.select name="slots" :title="__('Slots')" :options='[1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8]' :selected="$station->slots" :value="$station->slots" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="return_slots" :title="__('Empty Slots')" :options='[0,1,2,3,4,5,6,7,8]' :selected="$station->return_slots" :value="$station->return_slots" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="fault_slots" :title="__('Corrupted Slots')" :options='[0,1,2,3,4,5,6,7,8]' :selected="$station->fault_slots" :value="$station->fault_slots" required />
            </div>
            <div class="mb-3">
                <x-components::forms.select name="merchant" :title="__('Merchant')" :options="$merchants" :selected="$station->merchant->id" :value="$station->merchant->id" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="internet_card" :title="__('Internet Card')" type="number" :value="$station->internet_card"/>
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="device_ip" :title="__('Device IP')" :value="$station->device_ip" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="server_ip" :title="__('Server IP')" :value="$station->server_ip" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.input name="port" :title="__('Port')" type="number" :value="$station->port" required />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.select name="authorize" :title="__('Authorization')" :options='["authorized" => "Authorized","notAuthorized"=>"Not Authorized"]' :selected="$station->authorize" :value="$station->authorize" required />
            </div>
            <input type="hidden" name="updated_by" value="{{Auth::user()->id}}">

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.stations.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
