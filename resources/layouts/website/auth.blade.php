<x-layouts::scaffold {{ $attributes->merge(['class' => 'd-flex flex-column border-top-wide border-primary']) }}>
    <div class="page page-center">
        <div class="container container-tight py-4">
            {{ $slot }}

            @if (count(config('app.website_locales')) > 1)
                <ul class="list-inline list-inline-dots mt-3 mb-0 text-center">
                    @foreach (config('app.website_locales') as $locale)
                        <li class="list-inline-item">
                            <a href="{{ route('website.language.show', $locale) }}" class="link-secondary text-decoration-none">
                                {{ __('languages.' . $locale) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts::scaffold>
