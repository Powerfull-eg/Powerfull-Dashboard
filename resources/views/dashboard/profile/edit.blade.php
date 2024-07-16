<x-layouts::dashboard>
    <x-components::status />

    <form class="card" action="{{ route('dashboard.profile.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-header">
            <div class="card-title">{{ __('Edit your profile') }}</div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <div class="row">
                    <div class="col-auto">
                        <x-components::avatar :user="$user" size="md" avatar-preview />
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <x-components::forms.input type="file" name="profile_picture" :title="__('Profile picture')" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <x-components::forms.input name="name" :title="__('Name')" value="{{ $user->name }}" required />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')" value="{{ $user->email }}"
                    required />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="password" name="password" :title="__('Password')" type="password"
                    placeholder="{{ __('Leave blank if you don\'t want to change it') }}" />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="password" name="password_confirmation" :title="__('Confirm Password')"
                    type="password" placeholder="{{ __('Leave blank if you don\'t want to change it') }}" />
            </div>
        </div>

        <div class="card-footer text-end">
            <button type="reset" class="btn">{{ __('Reset') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(() => {
                const avatar = $('input[name="profile_picture"]');

                avatar.on('change', () => {
                    const reader = new FileReader();
                    const avatarPreview = $('[avatar-preview]');

                    reader.addEventListener('load', () => {
                        avatarPreview.css({
                            backgroundImage: `url(${reader.result})`
                        });
                        avatarPreview.empty();
                    });

                    reader.readAsDataURL(avatar.get(0).files[0]);
                });
            });
        </script>
    @endpush
</x-layouts::dashboard>
