<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.admins.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-6">
                    <x-components::forms.input name="first_name" :title="__('First Name')" :value="old('first_name')" required />
                </div>
                <div class="col-6">
                    <x-components::forms.input name="last_name" :title="__('Last Name')" :value="old('last_name')" required />
                </div>
            </div>

            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')" :value="old('email')" required />
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <x-components::forms.input type="password" name="password" :title="__('Password')" :value="old('password')" required />
                </div>
                <div class="col-6">
                    <x-components::forms.input type="password" name="password_confirmation" :title="__('Confirm Password')" :value="old('password_confirmation')" required />
                </div>
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
