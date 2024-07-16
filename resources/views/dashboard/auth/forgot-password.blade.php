<x-layouts::dashboard.auth :title="__('Forgot password')">

    <x-components::status />

    <div class="card card-md">
        <form class="card-body" method="POST" action="{{ route('dashboard.password.email') }}">
            @csrf

            <h2 class="card-title text-center mb-4">{{ __('Forgot password') }}</h2>

            <p class="text-muted mb-4">
                {{ __('Enter your email address and your reset password link will be emailed to you.') }}
            </p>

            <div class="mb-3">
                <x-components::forms.input type="email" name="email" :title="__('Email address')" value="{{ old('email') }}"
                    placeholder="your@email.com" required />
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Send password reset link') }}</button>
            </div>
        </form>
    </div>
</x-layouts::dashboard.auth>
