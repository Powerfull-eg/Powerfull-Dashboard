<x-layouts::dashboard>
    <x-components::status />
    <div class="d-flex justify-content-center mx-auto my-3 gap-3 fw-bold page-navs">
        <div class="navigator active" onclick="navigator(0)">{{__('Dashboard')}}</div>
        <div class="navigator" onclick="navigator(1)">{{__('Mobile App')}}</div>
        {{-- <div class="navigator" onclick="navigator(2)">{{__('Website')}}</div> --}}
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                navigator(1);
            })
        </script>
    @endpush
    <form class="card" action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-header" navigator=0>
            <div class="card-title">{{ __('Dashboard') . " " . __('Settings') }}</div>
        </div>

        <div class="card-header d-none" navigator=1>
            <div class="card-title">{{ __('Application') . " " . __('Settings') }}</div>
        </div>

        <div class="card-header d-none" navigator=2>
            <div class="card-title">{{ __('Website') . " " . __('Settings') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3" navigator=0>
                <x-components::forms.input name="app_name" :title="__('App name')" value="{{ setting('app_name') }}" required />
            </div>

            <div class="mb-3 d-none" navigator=2>
                <x-components::forms.input name="google_analytics_property_id" :title="__('Google Analytics property ID')" value="{{ setting('google_analytics_property_id') }}" />
            </div>

            <div class="mb-3 d-none" navigator=2>
                <x-components::forms.input name="facebook_pixel_id" :title="__('Facebook Pixel ID')" value="{{ setting('facebook_pixel_id') }}" />
            </div>

            <div class="mb-3" navigator=0>
                <x-components::forms.select name="payment_gateway" :title="__('Payment gateway')" :options="$payment_gateways" :selected="setting('payment_gateway')" value="{{ setting('payment_gateway') }}" />
            </div>

            <div class="mb-3" navigator=0>
                <div class="form-label">{{ __('Page loader') }}</div>
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="page_loader_enabled" {{ setting('page_loader_enabled') ? 'checked' : '' }}>
                    <span class="form-check-label">{{ __('Enabled') }}</span>
                </label>
            </div>
            
            {{-- Application Settings --}}

            <div class="mb-3 d-none" navigator=1>
                <x-components::forms.input name="bundle_id" :title="__('Bundle ID')"  value="{{ setting('bundle_id') }}" />
            </div>

            {{-- Map --}}
            <div class="mb-3 d-none" navigator=1>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-label fw-bold">{{__('Map') . " " . __('Settings') }} :</div>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach (json_decode(setting('map'),true) as $key => $value )
                            <div><x-components::forms.input :name="'map.' . $key" :title="__(ucfirst($key))"  value="{{ $value }}" /></div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mb-3 d-none d-flex gap-3 flex-wrap w-100" navigator=1>
                <div class="w-50"><x-components::forms.input name="appAndroidLink" :title="__('Application Android Link')"  value="{{ setting('appAndroidLink') }}" /></div>
                <div class="w-25"><x-components::forms.input name="appAndroidVersion" :title="__('Application Android Version')"  value="{{ setting('appAndroidVersion') }}" /></div>
                <div><x-components::forms.input name="updateAndroidMandatory" :title="__('Mandatory Android Version')"  value="{{ setting('updateAndroidMandatory') }}" /></div>
            </div>

            <div class="mb-3 d-none d-flex gap-3 w-100" navigator=1>
                <div class="w-50"><x-components::forms.input name="appIosLink" :title="__('Application IOS Link')"  value="{{ setting('appIosLink') }}" /></div>
                <div class="w-25"><x-components::forms.input name="appIosVersion" :title="__('Application IOS Version')"  value="{{ setting('appIosVersion') }}" /></div>
                <div><x-components::forms.input name="updateIosMandatory" :title="__('Mandatory IOS Version')"  value="{{ setting('updateIosMandatory') }}" /></div>
            </div>

            <div class="mb-3 d-none">
                <x-components::forms.input name="timezone" :title="__('Timezone')"  value="{{ setting('timezone') }}" />
            </div>

            {{-- Update Settings--}}
            <hr navigator=1>
            <div class="card-title" navigator=1>{{ __("Update") . " " . __("Settings") . ": " }}</div>
            <div class="mx-auto mb-3 d-none d-flex gap-3 w-100" navigator=1>
                <div class="w-25"><x-components::forms.input name="enUpdateTitle" :title="__('Update Title In English')"  value="{{ setting('enUpdateTitle') }}" /></div>
                <div class="w-50"><x-components::forms.input name="enUpdateMessage" :title="__('Update Message In English')"  value="{{ setting('enUpdateMessage') }}" /></div>
            </div>

            <div class="mb-3 d-none d-flex gap-3 w-100" navigator=1>
                <div class="w-25"><x-components::forms.input name="arUpdateTitle" :title="__('Update Title In Arabic')"  value="{{ setting('arUpdateTitle') }}" /></div>
                <div class="w-50"><x-components::forms.input name="arUpdateMessage" :title="__('Update Message In Arabic')"  value="{{ setting('arUpdateMessage') }}" /></div>
            </div>
            <hr navigator=1>
            {{-- Maintenance --}}
            <div class="mb-3 d-none" navigator=1>
                <div class="form-label fw-bold">{{__('Maintenance') . " " . __('Settings') }} :</div>
                <div class="d-flex gap-3 flex-wrap">
                    <x-components::forms.switch-checkbox name="maintenance" :title="__('Maintenance')" value="{{ setting('maintenance') }}" :checked="setting('maintenance')" />
                    <x-components::forms.switch-checkbox name="otp" :title="__('OTP')" value="{{ setting('otp') }}" :checked="setting('otp')" />    
                </div>
            </div>
            
            <hr navigator=1>

            {{-- Oauth --}}
            <div class="mb-3 d-none" navigator=1>
                <div class="form-label fw-bold">{{__('Application Sign In Settings') }} :</div>
                <div><x-components::forms.switch-checkbox name="oauth.active" :title="__('Active')"  value="{{ json_decode(setting('oauth'),true)['active'] }}" :checked="json_decode(setting('oauth'),true)['active']" /></div>
                    <div class="d-flex my-3 gap-3 flex-wrap" >
                        @foreach ($oauthPlatforms['platforms'] as $platform )
                            <div><x-components::forms.switch-checkbox name="oauth.platforms.{{ $platform }}" :title="__(ucfirst($platform))"  value="{{ $oauthPlatforms['active'][$platform] }}" :checked="$oauthPlatforms['active'][$platform]" /></div>
                        @endforeach
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="reset" class="btn">{{ __('Reset') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
