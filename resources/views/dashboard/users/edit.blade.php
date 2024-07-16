<x-layouts::dashboard>
    <form class="card" action="{{ route('dashboard.users.update',$user->id) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="card-header">
            <p class="card-title">{{ __('Create') . " " . __("New Merchant") }}</p>
        </div>

        <div class="card-body row">
            <div class="row">
                <div class="mb-3 col col-6">
                    <x-components::forms.input name="first_name" :title="__('First Name')" :value="$user->first_name" required />
                </div>
                <div class="mb-3 col col-6">
                    <x-components::forms.input name="last_name" :title="__('Last Name')" :value="$user->last_name" required />
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col col-6">
                    <x-components::forms.input type="file" id="image-input" accept="image/*" onchange="previewImage(event)" name="avatar" :title="__('Profile Picture')" :value="$user->avatar" />
                </div>
                <div class="mb-3 col col-6">
                    <img id="image-preview" src="/storage/users/{{ $user->avatar ?: 'default.png' }}" alt="User Image">
                </div>
            </div>

            <div class="mb-3 col col-6">
                <x-components::forms.input name="email" type="email" :title="__('Email')" :value="$user->email" />
            </div>
            <div class="mb-3 col col-6">
                <x-components::forms.input name="password" type="password" :title="__('Password')" />
                <span>Leave field empyt if you won't change it</span>
            </div>
            <div class="mb-3 row">
                <div class="col col-3">
                    <x-components::forms.input  name="code" :title="__('Code')" :value="$user->code" required/>
                </div>
                <div class="col col-9">
                    <x-components::forms.input name="phone" :title="__('Phone')" type="number" :value='(str_starts_with($user->phone,"0") ? $user->phone : "0" . $user->phone)' required/>
                </div>
                <input type="hidden" name="updated_by" value="{{Auth::user()->id}}">
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.users.index') }}" class="btn">{{ __('Cancel') }}</a>
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
                preview.src = "/storage/users/default.png";
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
