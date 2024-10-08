<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.shops.update',$shop->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method("PUT")
        <div class="card-header">
            <p class="card-title">{{ __('Update') . " " . __("Shop") }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="name" :title="__('Name')" :value="$shop->name" required />
                </div>
                <div class="mb-3 mx-2 col col-md-4 img-uploader">
                    <img class="image-preview img-fluid"  src="{{$shop->logo}}" :alt="$shop->name . ' icon'">
                    <x-components::forms.input name="logo" class="image-input d-none" type="file" :value="$shop->logo" />
                </div>
            </div>
            <div class="row">
                <x-components::forms.input name="phone" :title="__('Phone')" :value="$shop->phone" required />
            </div>
            <div class="row">

                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="governorate" :title="__('Governorate')" :value="$shop->governorate"  />
                </div>
                <div class="mb-3 col col-md-6">
                    <x-components::forms.input name="city" :title="__('City')" :value="$shop->city"  />
                </div>
            </div>
            <div class="mb-3 col col-md-6">
                <x-components::forms.textarea name="address" :title="__('Address')" :value="$shop->address" />
            </div>
            <div class="mb-3 row">
                <h4 >Location</h4>
                <div class="col col-6">
                    <x-components::forms.input  name="location_latitude" :title="__('Latitude')" :value="$shop->location_latitude" required/>
                </div>
                <div class="col col-6">
                    <x-components::forms.input name="location_longitude" :title="__('Longitude')" :value="$shop->location_longitude" required/>
                </div>
            </div>
        </div>

        {{--extra data --}}
        <div class="accordion" id="shops-data-accordion">
            <div class="accordion-item">
              <h2 class="accordion-header fw-bold" id="headingOne">
                  <button class="accordion-button fw-bold text-align-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <span class="pe-1 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-databricks" width="52" height="52" viewBox="0 0 24 24" stroke-width="2" stroke="#000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" />
                          </svg>
                    </span>
                  {{__("Shop Data")}}
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                <div class="accordion-body">
                    {{-- Shop Data Form --}}
                    <div class="row mb-3">
                        <div class="mb-3 mx-2 col col-md-4 img-uploader d-flex align-items-center justify-content-center gap-3">
                            <div>
                                <label for="logo">{{__('Logo')}}</label><span class="text-danger">*</span>
                            </div>
                            <img class="image-preview img-fluid"  src="{{$shop->data->logo}}" :alt="$shop->name . ' icon'">
                            <x-components::forms.input id="logo"  name="data_logo" class="image-input d-none" type="file" :value="$shop->data->logo" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col col-6">
                            <label for="opens_at">{{__('Opens At')}}</label>
                            <input type="time" name="opens_at" id="opens_at" value="{{$shop->data->opens_at ?? ''}}" >
                        </div>
                        <div class="col col-6">
                            <label for="closes_at">{{__('Closes At')}}</label>
                            <input type="time" name="closes_at" id="closes_at" value="{{$shop->data->closes_at ?? ''}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-6">
                            <x-components::forms.input name="price" :title="__('Price')" :value="$shop->data->price ?? ''" />
                        </div>
                        <div class="col col-6">
                            <x-components::forms.select name="type_id" :options="\App\Models\ShopsType::pluck('type_ar_name', 'id')" :title="__('Type')" :selected="$shop->data->type_id ?? ''" />
                        </div>
                    </div>

                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header fw-bold" id="headingTwo">
                  <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <span class="pe-1 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-up" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8h.01" />
                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                            <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                        </svg>
                    </span>
                    {{__("Shop Menu")}}
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                  <div class="accordion-body">
                      {{-- Shop Menu Images --}} 
                    <label class="active">Menu Images</label>
                    <div class="menu-images" style="padding-top: .5rem;"></div>
                  </div>
                </div>
              </div>
          </div>
          {{-- footer --}}
        <div class="card-footer text-end">
            <a href="{{ route('dashboard.shops.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
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

        // Menu Images
        let menu = @json($shop->menu ?? [] );
        let images = [];
        menu.forEach((item) => {
            images.push({id: item.id, src: item.image});
        });
        options = {
            label: '{{ __('Upload Shop Menu Images') }}',
            preloaded: images,
            imagesInputName: 'menu_images',
        }
        $('.menu-images').imageUploader(options);
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
