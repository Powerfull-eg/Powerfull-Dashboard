<x-layouts::dashboard>
    <x-components::status />

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Supported Languages') }}</h3>
        </div>

        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>{{ __('Language') }}</th>
                    <th style="width: 1%"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($languages as $language)
                    <tr>
                        <td>{{ __('languages.' . $language) }}</td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="{{ route('dashboard.language.sync', $language) }}" class="btn btn-icon"
                                    title="{{ __('Sync') }}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <i class="ti ti-reload"></i>
                                </a>

                                <a href="{{ route('dashboard.language.edit', $language) }}" class="btn btn-icon"
                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <i class="ti ti-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts::dashboard>
