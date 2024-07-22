<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.shops.update',$shop->id) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="card-header">
            <p class="card-title">{{ __('Update') . " " . __("Shop") }}</p>
        </div>

        <div class="card-body">
            <div class="row">
                <x-components::forms.input name="name" :title="__('Name')" :value="$shop->name" required />
            </div>
            <div class="row">
                <x-components::forms.input name="phone" :title="__('Phone')" :value="$shop->phone" required />
            </div>
            {{-- <div class="col col-8 row">
                <div class="mb-3 col col-9">
                    <x-components::forms.input type="file" id="image-input" accept="image/*" onchange="previewImage(event)" name="logo" :title="__('Logo')" :value="$shop->logo" />
                </div>
                <div class="mb-3 col col-3">
                    <img id="image-preview" src="/storage/shops/{{$shop->logo ?? "default.png"}}" alt="Image Preview">
                </div>
            </div>
            <div class="mb-3">
                <x-components::forms.input name="images" :title="__('Images')" type="number" :value="old('images')" />
            </div> --}}
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

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.shops.index') }}" class="btn">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </form>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
                
            }else{
                preview.src = "/storage/shops/default.png";
            }
        }
    </script>
     <style>
        #image-preview {
          max-width: 100px;
          max-height: 100px;
        }
      </style>
</x-layouts::dashboard>
