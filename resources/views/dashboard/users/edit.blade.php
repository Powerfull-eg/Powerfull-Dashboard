<x-layouts::dashboard>
    <x-components::status />
    <form class="card" action="{{ route('dashboard.users.update',$user->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method("PUT")
        <div class="card-header">
            <p class="card-title">{{ __('Update') . " " . __("User") }}</p>
            <span class="text text-warning"> &nbsp; "{{ $user->full_name }}" </span>
        </div>

        <div class="card-body">
            <a class="btn btn-warning pb-2 mb-3 text-white" href="{{ route('dashboard.users.show', $user->id) }}"><i class="ti ti-arrow-left"></i></a>
            <div class="row">
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="first_name" :title="__('First Name')" :value="$user->first_name" required />
                </div>
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="last_name" :title="__('Last Name')" :value="$user->last_name" required />
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input :type="'email'" name="email" :title="__('Email')" :value="$user->email" />
                </div>
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="phone" :title="__('Phone')" :value="'0'. $user->phone" required />
                    {{-- Phone code --}}
                    <x-components::forms.input name="code" :type="'hidden'" :value="$user->code" required />
                </div>
            </div>
            <div class="mb-3 row">
                <x-components::forms.input :type="'password'" name="password" :title="__('Password')" placeholder="{{ __('Leave blank if you don\'t want to change it') }}" />
            </div>
        </div>
        {{--  --}}
          {{-- footer --}}
        <div class="card-footer text-end">
            <a href="{{ route('dashboard.users.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>
@push('scripts')
    <script>
    </script>
@endpush
<style>
</style>
</x-layouts::dashboard>
