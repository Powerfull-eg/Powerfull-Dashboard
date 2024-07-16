<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.roles.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body border-bottom">
            <div class="d-flex gap-2">
                <x-components::forms.input name="name" :value="old('name')" :placeholder="__('Name')" required />
                <a class="btn btn-outline-primary" onclick="togglePermissions()">{{ __('Toggle All') }}</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th style="width: 25%">{{ __('Category') }}</th>
                        <th>{{ __('Permissions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($permissions as $key => $values)
                        <tr>
                            <td>{{ Str::title($key) }}</td>
                            <td>
                                <div class="form-selectgroup">
                                    @foreach ($values as $permission)
                                        <label class="form-selectgroup-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="form-selectgroup-input" @checked(in_array($permission->id, old('permissions', [])))>
                                            <span class="form-selectgroup-label">
                                                {{ Str::title(Arr::last(explode('.', $permission->name))) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.roles.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>

    @push('scripts')
        <script>
            function togglePermissions() {
                $('input[type="checkbox"]').each(function() {
                    $(this).prop('checked', !$(this).prop('checked'));
                });
            }
        </script>
    @endpush
</x-layouts::dashboard>
