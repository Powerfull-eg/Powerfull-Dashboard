<x-layouts::dashboard>
    <x-components::status />
    <form class="card" action="{{ route('dashboard.push-notifications.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="header d-flex gap-2 justify-content-between">
            <div class="d-flex align-items-end justify-content-center logo">
                <i style="font-size: 3rem; color: var(--background-color); margin: 10px" class="ti ti-notification"></i>
                <h1 class="card-title">{{ __('Send') . " " . __("Notification") }}</h1>
            </div>
        </div>
        
        <div class="card-body">
            {{-- Targets --}}
            <div class="mb-3">
                <x-components::forms.select name="target" :title="__('Target Recievers')" onchange="showUsers()" :options='$targets' :value="old('target')" required />

                <x-components::forms.select class="d-none users my-3" multiple name="users[]" :options='$users' placeholder="{{__('Select Users')}}" :value="old('users')" />
            </div>

            {{-- Title --}}
            <div class="mb-3">
                <x-components::forms.input name="title" :title="__('Title')" :value="old('title')" required />
            </div>
            
            {{-- Body --}}
            <div class="mb-3">
                <x-components::forms.input name="body" :title="__('Body')" :value="old('body')" required />
            </div>

            {{-- Image --}}
            <div class="d-flex justify-content-center align-items-center mb-3 img-uploader">
                <img class="image-preview img-fluid mx-3"  src="{{'/assets/images/upload.png'}}">            
                <x-components::forms.input type="file" class="image-input d-none" accept="image/*" name="image" :title="__('Image')" :value="old('image')" />
            </div>

            {{-- URl Image --}}
            <div class="mb-3">
                <x-components::forms.input type="text" before="{{__('This URL will replace the image')}}"  name="url_image" :title="__('Image URL')" :value="old('image')" />
            </div>
            
            {{-- submit --}}
            <div class="card-footer text-end">
                <a href="{{ route('dashboard.push-notifications.index') }}" class="btn">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        prepareImageUploader();

        function showUsers() {
            let target = $("select[name='target']");
            let users = $("select[name='users[]']").siblings("div.form-select.users");
            $(target).val() == '3' ? $(users).removeClass("d-none") : $(users).addClass("d-none");
        }
    </script>
@endpush
<style>
    .image-preview {
      width: 100px;
      height: 100px;
      padding: 10px;
      border: 2px solid var(--background-color);
      border-radius: 50%;
      cursor: pointer;
    }
</style>
</x-layouts::dashboard>
