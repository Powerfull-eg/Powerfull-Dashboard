<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.language.update', $locale) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-header">
            <h3 class="card-title">
                {{ __('Edit') }}
            </h3>
        </div>

        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th style="width: 50%">{{ __('Original') }}</th>
                    <th style="width: 50%">{{ __('Translation') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($translations as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>
                            <input type="text" class="form-control" name="translations[{{ $key }}]"
                                value="{{ $value }}">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">
                            <p class="py-5 mb-0 text-center">{{ __('No translations found') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="card-footer text-end border-top-0">
            <a href="{{ route('dashboard.language.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
