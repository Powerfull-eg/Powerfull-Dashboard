<x-layouts::website.auth :title="__('Verify your email address')">

    <form class="d-none" method="POST" action="{{ route('website.verification.send') }}" id="resend-verification-email-form">
        @csrf
    </form>

    <x-components::status />

    <div class="card card-md">
        <div class="card-body text-center">
            <h2 class="h1 mb-3">{{ __('Check your inbox') }}</h2>
            <p class="text-muted">{{ __('We have sent you an email with a link to verify your email address and complete your registration.') }}</p>

            <p class="text-muted mb-0">{{ __('If you did not receive the email') }}, <a href="#" onclick="event.preventDefault(); document.getElementById('resend-verification-email-form').submit();">{{ __('click here to request another') }}</a>.</p>
        </div>
    </div>
</x-layouts::website.auth>
