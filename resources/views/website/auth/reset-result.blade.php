<x-layouts::website.auth :title="__('Success Reset password')">
    <x-components::status />
    
    <div class="card card-md">
        <div class="card-body text-center">
            <h2 class="h1 mb-3">{{ request('status') === 'success'? __('Success') : __('Error') }}</h2>
            <p :class="'text-muted' (request('status') === 'success'? 'text-success' : 'text-danger')">{{ request('message') }}</p>
        </div>
    </div>
</x-layouts::website.auth>