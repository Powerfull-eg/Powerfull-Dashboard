<x-layouts::dashboard>
    <x-components::status />
    <form class="card" action="{{ route('dashboard.shop-types.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card-header">
            <p class="card-title">{{ __('Create') . " " . __("Shop") . " " . __("Type") }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="mb-3 col col-md-4">
                    <x-components::forms.input name="type_ar_name" old="type_ar_name" :title="__('Arabic') .' '.__('Name')" required />
                </div>
                <div class="mb-3 col col-md-4">
                    <x-components::forms.input name="type_en_name" old="type_en_name" :title="__('English') .' '.__('Name')" required />
                </div>
                <div class="mb-3 col col-md-3 img-uploader">
                    <img class="image-preview img-fluid"  src="{{'/assets/images/upload.png'}}" :alt="'type icon'">
                    <x-components::forms.input class="image-input d-none" type="file" name="type_icon" required />
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col col-md-4">
                    <x-components::forms.input name="access_ar_name" old="access_ar_name" :title="__('Arabic') .' '. __('Access') .' '.__('Name')" required />
                </div>
                <div class="mb-3 col col-md-4">
                    <x-components::forms.input name="access_en_name" old="access_en_name" :title="__('English') .' '. __('Access') .' '.__('Name')" required />
                </div>
                <div class="mb-3 col col-md-3 img-uploader">
                    <img class="image-preview img-fluid"  src="{{'/assets/images/upload.png'}}" :alt="'type access icon'">
                    <x-components::forms.input class="image-input d-none" type="file" name="access_icon" required />
                </div>
            </div>
        </div>

        {{-- footer --}}
        <div class="card-footer text-end">
        <a href="{{ route('dashboard.shop-types.index') }}" class="btn">{{ __('Cancel') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
        {{--extra data --}}
    </form>
@push('scripts')
    <script>
        // image uploader
        const uploaders = document.querySelectorAll('.img-uploader');
        uploaders.forEach(uploader => {
            let imageInput = uploader.querySelector('.image-input');
            let imagePreview = uploader.querySelector('.image-preview');

            // Change Image Preview on input change
            imageInput.addEventListener('change', (event) => { 
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
            // open input on click
            imagePreview.addEventListener('click', () => { imageInput.click(); });
        });

    </script>
@endpush

     <style>
        .image-preview {
          max-width: 50px;
          max-height: 50px;
          margin-top: 20px;
          cursor: pointer;
        }
      </style>
</x-layouts::dashboard>
