<x-layouts::dashboard>
    <x-components::status />
    <form class="card" action="{{ route('dashboard.users.gifts.store',$user->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
        <div class="card-header">
            <p class="card-title">{{ __('Add') . " " . __("Gift") }}</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 mb-3">
                    <x-components::forms.select name="gift_id" title="{{ __('Gift') }}" :options="$gifts" required />
                </div>
                <div class="col-6 mb-3">
                    <x-components::forms.select name="shop_id" title="{{ __('Shop') }}" :options="$shops" required />
                </div>
            </div>
            <div class="row align-items-end">
                <div class="col-6 mb-3">
                    <x-components::forms.input name="code" :title="__('Code')" required />
                </div>
                <div class="col-6 mb-3 ">
                    <div class="btn btn-success" onclick="generatecode()">{{__("Generate Code")}}</div>
                </div>
            </div>
            <div class="mb-3">
                <x-components::forms.date name="expire" :title="__('Expiration Date')" required />
            </div>
            <div class="row">
                <div class="mb-3 col col-md-6">
                    <x-components::forms.select onchange="toggleUsed(this)" name="used" :title="__('Used')" :options='[0 => "No",1 => "Yes"]' :selected="1" />
                </div>
                <div class="mb-3 col col-md-6 used_at">
                    <x-components::forms.date name="used_at" :title="__('Used At')" required/>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
        </div>
    </form>
    @push('scripts')
        <script>
        // generate code
        const generatecode = () => {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let code = '';
            for (let i = 0; i < 6; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                code += characters.charAt(randomIndex);
            }
            document.querySelector('input[name=code]').value = code;
        }

        // show used at field based on used status
        const toggleUsed = (selector) => {
            const usedAt = document.querySelector('.used_at');
            usedAt.classList.toggle('d-none');
            usedAt.querySelector('input').required = selector.value == 1;
        }
        </script>
    @endpush
</x-layouts::dashboard>
