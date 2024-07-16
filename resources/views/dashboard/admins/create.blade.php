<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.admins.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-components::forms.input name="name" :title="__('Name')" :value="old('name')" required />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')" :value="old('email')" required />
            </div>

            <div class="mb-3">
                <x-components::forms.select name="role" title="{{ __('Role') }}" :options="$roles"
                    :selected="old('role', null)" required />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.admins.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
