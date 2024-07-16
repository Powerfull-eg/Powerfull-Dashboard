<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.impersonate.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <div class="card-title">{{ __('Impersonate') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <x-components::forms.select name="admin_id" title="{{ __('Admin') }}" :options="$admins" required />
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">{{ __('Impersonate') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
