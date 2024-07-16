<div class="page page-center page-loader">
    <div class="container container-slim text-center py-4">
        <x-components::logo height="24" class="mb-4" />

        <div class="progress progress-sm">
            <div class="progress-bar progress-bar-indeterminate"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(window).on('load', () => $('.page-loader').fadeOut());
    </script>
@endpush
