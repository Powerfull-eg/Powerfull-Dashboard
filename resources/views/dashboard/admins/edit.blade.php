<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.admins.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-title">{{ __('Edit') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-components::forms.input :title="__('Admin')" :value="old('name', $admin->name)" requuired />
            </div>
            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')" :value="old('email', $admin->email)" required />
            </div>

            <div class="mb-3 row">
                <div class="col-6">
                    <x-components::forms.input type="password" name="password" :title="__('Password')"/>
                </div>
                <div class="col-6">
                    <x-components::forms.input type="password" name="password_confirmation" :title="__('Confirm Password')"/>
                </div>
                <span>{{ __('Leave empty if you don\'t want to change the password') }}</span>
            </div>
            <div class="mb-3">
                <x-components::forms.select name="role" title="{{ __('Role') }}" :options="$roles" :selected="$admin->roles()->first()?->id" required />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.admins.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
