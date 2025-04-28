<x-layouts::dashboard>
    <x-components::status />
    <h2 class="text-center fw-bold mx-auto">{{__('Edit Campaign')}} {{$campaign->name}}</h2>
    <form action="{{route('dashboard.vouchers.campaigns.update', $campaign->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("POST")
    <div class="mb-3">
        <x-components::forms.input name="campaign_name" :placeholder="__('Enter Campaign Name')" :title="__('Name')" :value="old('campaign_name',$campaign->name)" />
    </div>
    <div class="mb-3">
        <x-components::forms.textarea name="campaign_description" :placeholder="__('Campaign Description')" :title="__('Campaign Description')" :value="old('campaign_description',$campaign->description)" />
    </div>
    <div class="mb-3">
        <x-components::forms.input name="vouchers_count" :placeholder="__('Add Vouchers Count')" :title="__('Vouchers Count')" :value="old('vouchers_count',$campaign->vouchers->count())" />
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('type')}}</label>
        <div class="form-selectgroup">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="type" value="0" class="form-selectgroup-input" {{$campaign->vouchers->first()->type == 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Percentage') }}</span>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="type" value="1" class="form-selectgroup-input" {{$campaign->vouchers->first()->type == 1 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Amount') }}</span>
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Value')}}</label>
        <input type="number" class="form-control" name="value" required value="{{$campaign->vouchers->first()->value}}" placeholder="Enter {{__('Value')}}" />
    </div>

    {{-- Multiple Usage --}}
    <div class="mb-3">
        <x-components::forms.switch-checkbox required name="multiple_usage" :title="__('Multiple Usage')" :value="old('multiple_usage',$campaign->vouchers->first()->multiple_usage)" />
    </div>
    <div class="mb-3 d-none usage_count">
        <x-components::forms.input name="usage_count" :placeholder="__('Add No of usage voucher')" :title="__('Usage Count')" :value="old('usage_count',$campaign->vouchers->first()->usage_count)" />
    </div>

    <div class="mb-3">
        <label class="form-label">{{__('Min Amount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="min_amount" required value="{{$campaign->vouchers->first()->min_amount}}" placeholder="Enter {{__('Min Amount')}}" />
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Max Discount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="max_discount" required value="{{$campaign->vouchers->first()->max_discount}}" placeholder="Enter {{__('Max Discount')}}" />
    </div>
    <div class="mb-3 d-flex justify-content-evenly">
        <div class="d-flex">
            <label class="form-label m-2">{{__('Starts From')}}</label>
            <input type="datetime-local" name="from" required value="{{$campaign->vouchers->first()->from}}" />
        </div>
        <div class="d-flex align-content-center flex-wrap">
            <label class="form-label m-2">{{__('Ends Before')}}</label>
            <input type="datetime-local" name="to" required value="{{$campaign->vouchers->first()->to}}" />
        </div>
    </div>
    <button role="submit" class="my-5 mx-auto d-block btn btn-primary">{{__('Submit')}}</button>
    </form>
    @push('scripts')
        <script>
            // add current date
            const inputs = document.querySelectorAll('input[type=date]');
            inputs.forEach(input => input.value = (new Date(input.getAttribute('value'))).toLocaleDateString());

            // parse user_id 
            const selectUser = (select) => {
                userId = select.value;
                document.querySelector("[custom-user]").value = userId;
            }
            // hide specific user selection on un select
            const toggleUserSelect = (selector) => {
                const usersContainer = document.querySelector('.users');
                usersContainer.classList.toggle('d-none');
            }
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

            $("[name=multiple_usage]").on("change",() => {
                    if ($("[name=multiple_usage]").is(':checked')) {
                        $(".usage_count").removeClass('d-none');
                    } else {
                        $(".usage_count").addClass('d-none');
                    }
                });
        </script>
    @endpush
    @push('styles')
        <style>
            /* Removes the clear button from date inputs */
            input[type="datetime-local"]::-webkit-clear-button {
                display: none;
            }

            /* Removes the spin button */
            input[type="datetime-local"]::-webkit-inner-spin-button { 
                display: none;
            }

            /* Always display the drop down caret */
            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                color: #2c3e50;
            }

            /* A few custom styles for date inputs */
            input[type="datetime-local"] {
                appearance: none;
                -webkit-appearance: none;
                color: #95a5a6;
                font-family: "Helvetica", arial, sans-serif;
                font-size: 18px;
                border:1px solid #ecf0f1;
                background:#ecf0f1;
                padding:5px;
                display: inline-block !important;
                visibility: visible !important;
            }

            input[type="datetime-local"], focus {
                color: #95a5a6;
                box-shadow: none;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
            }
        </style>
    @endpush
</x-layouts::dashboard>
