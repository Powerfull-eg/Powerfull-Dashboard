<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.admins.update', $admin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-title">{{ __('Edit') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-components::forms.input :title="__('Admin')" :value="$admin->name" disabled />
            </div>
            <div class="mb-3">
                <x-components::forms.select name="role" title="{{ __('Role') }}" :options="$roles" :selected="$admin->roles()->first()?->id"
                    required />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.admins.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
