<x-layouts::dashboard>
    <x-components::status />

    <form class="card" action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-title">{{ __('Settings') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-components::forms.input name="app_name" :title="__('App name')" value="{{ setting('app_name') }}" required />
            </div>

            <div class="mb-3">
                <x-components::forms.input name="google_analytics_property_id" :title="__('Google Analytics property ID')" value="{{ setting('google_analytics_property_id') }}" />
            </div>

            <div class="mb-3">
                <x-components::forms.input name="facebook_pixel_id" :title="__('Facebook Pixel ID')" value="{{ setting('facebook_pixel_id') }}" />
            </div>

            <div class="mb-3">
                <div class="form-label">{{ __('Page loader') }}</div>
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="page_loader_enabled" {{ setting('page_loader_enabled') ? 'checked' : '' }}>
                    <span class="form-check-label">{{ __('Enabled') }}</span>
                </label>
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="reset" class="btn">{{ __('Reset') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
