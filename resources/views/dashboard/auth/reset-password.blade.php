<x-layouts::dashboard.auth :title="__('Reset password')">

    <x-components::status />

    <div class="card card-md">
        <form class="card-body" method="POST" action="{{ route('dashboard.password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <h2 class="card-title text-center mb-4">{{ __('Reset password') }}</h2>

            <p class="text-muted mb-4">{{ __('Make sure to use a strong password to keep your account secure.') }}</p>

            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')"
                    value="{{ old('email', $request->email) }}" placeholder="your@email.com" required />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="password" name="password" :title="__('Password')"
                    placeholder="{{ __('Password') }}" required />
            </div>

            <div class="mb-3">
                <x-components::forms.input type="password" name="password_confirmation" :title="__('Confirm Password')"
                    placeholder="{{ __('Confirm Password') }}" required />
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Reset password') }}</button>
            </div>
        </form>
    </div>
</x-layouts::dashboard.auth>
