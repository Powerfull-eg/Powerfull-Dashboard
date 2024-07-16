<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.merchants.update',$merchant->id) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="card-header">
            <p class="card-title">{{ __('Create') . " " . __("New Merchant") }}</p>
        </div>

        <div class="card-body row">
            <div class="mb-3 col col-4">
                <x-components::forms.input name="name" :title="__('Name')" :value="$merchant->name" required />
            </div>
            <div class="col col-8 row">
                <div class="mb-3 col col-9">
                    <x-components::forms.input type="file" id="image-input" accept="image/*" onchange="previewImage(event)" name="logo" :title="__('Logo')" :value="$merchant->logo" />
                </div>
                <div class="mb-3 col col-3">
                    <img id="image-preview" src="/storage/merchants/{{$merchant->logo ?? "default.png"}}" alt="Image Preview">
                </div>
            </div>
            <div class="mb-3">
                <x-components::forms.input name="images" :title="__('Images')" type="number" :value="old('images')" />
            </div>

            <div class="mb-3 col col-4">
                <x-components::forms.select name="governorate" :title="__('Governorate')" :options='[1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8]' :selected="$merchant->governorate" :value="$merchant->governorate" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.select name="city" :title="__('City')" :options='[1 => 1,2 => 2,3 => 3,4 => 4,5 => 5,6 => 6,7 => 7,8 => 8]' :selected="$merchant->city" :value="$merchant->city" required />
            </div>
            <div class="mb-3 col col-4">
                <x-components::forms.input name="address" :title="__('Address')" :value="$merchant->address" />
            </div>
            <div class="mb-3 row">
                <h4 >Location</h4>
                <div class="col col-6">
                    <x-components::forms.input  name="latitude" :title="__('Latitude')" :value="json_decode($merchant->location,true)['lat']" required/>
                </div>
                <div class="col col-6">
                    <x-components::forms.input name="longitude" :title="__('Longitude')" :value="json_decode($merchant->location,true)['lng']" required/>
                </div>
            <input type="hidden" name="updated_by" value="{{Auth::user()->id}}">
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.merchants.index') }}" class="btn">{{ __('Cancel') }}</a>
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
                preview.src = "/storage/merchants/default.png";
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
