<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.memos.store') }}" method="POST">
        @csrf

        <div class="card-header">
            <p class="card-title">{{ __('Create') }}</p>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <x-components::forms.input name="title" :title="__('Title')" :value="old('title')" required />
                </div>

                <div class="col-12 col-md-6">
                    <x-components::forms.date name="date" :title="__('Date')" :value="old('date')" />
                </div>
            </div>

            <div class="mb-3">
                <x-components::forms.tinymce name="content" :title="__('Content')" :value="old('content')" />
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.memos.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
</x-layouts::dashboard>
