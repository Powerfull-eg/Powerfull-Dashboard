<x-layouts::dashboard>
    <x-components::status />
    <div class="d-flex justify-content-center mx-auto gap-3 fs-1 fw-bold page-navs">
        <div class="navigator active" onclick="navigator(0)">{{__('Vouchers')}}</div>
        <div class="navigator" onclick="navigator(1)">{{__('Campaigns')}}</div>
    </div>

    <form action="{{route('dashboard.vouchers.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="navigator" value="0">
    {{-- Campaign Data --}}
    <div class="mb-3" navigator=1>
        <x-components::forms.input name="campaign_name" :placeholder="__('Campaign Name')" :title="__('Name')" :value="old('campaign_name')" />
    </div>
    <div class="mb-3" navigator=1>
        <x-components::forms.textarea name="campaign_description" :title="__('Description')" :placeholder="__('Campaign Description')" :value="old('campaign_description')" />
    </div>
    <div class="mb-3" navigator=1>
        <x-components::forms.input name="vouchers_count" type='number' :title="__('Vouchers Count')" :placeholder="__('Add amount of vouchers')" :value="old('vouchers_count')" />
    </div>

    <div class="mb-3" navigator=0>
        <label class="form-label">{{__('Code')}}</label>
        <input type="text" class="form-control" name="code"  value="{{old('code')}}" placeholder="Enter {{__('Code')}}" />
        <div class="btn btn-success my-2" role="" onclick="generatecode()">{{__('Generate Code')}}</div>
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('type')}}</label>
        <div class="form-selectgroup">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="type" value="0" required class="form-selectgroup-input" {{ old('type') == 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Percentage') }}</span>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="type" value="1" required class="form-selectgroup-input" {{old('type') == 1 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Amount') }}</span>
            </label>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Value')}}</label>
        <input type="number" class="form-control" name="value" required value="{{old('value')}}" placeholder="Enter {{__('Value')}}" />
    </div>
    {{-- Users --}}
    <div class="mb-3" navigator=0>
        <label class="d-flex justify-content-start form-label">{{__('Target User')}}</label>
        <div class="form-selectgroup">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="user_id" onclick="toggleShow('hide','.users')" value="0" class="form-selectgroup-input select-user" {{old('user_id') == 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('All Users') }}</span>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="user_id" custom-user onclick="toggleShow('show','.users')" value="{{old('user_id') != 0 ? old('user_id')  : ''}}" class="form-selectgroup-input select-user" {{old('user_id') != 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Specific User') }}</span>
            </label>
            <div class="mx-2 {{old('user_id') == 0 ? 'd-none' : ''}} users">
                <select name="user_selector" class="form-select" aria-label="{{__("Choose User")}}" onchange="selectUser(this)" id="user_id">
                    <option value="">{{__("Choose User")}}</option>
                    @foreach ($users->all() as $user)
                        <option value="{{$user->id}}" {{old('user_id') == $user->id ? 'selected' : ''}}>{{$user->fullName}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- Multiple Usage --}}
    <div class="mb-3">
        <x-components::forms.switch-checkbox required name="multiple_usage" :title="__('Multiple Usage')" :value="old('multiple_usage')" />
    </div>
    <div class="mb-3 d-none usage_count">
        <x-components::forms.input name="usage_count" :placeholder="__('Add No of usage voucher')" :title="__('Usage Count')" :value="old('usage_count')" />
    </div>

    <div class="mb-3">
        <label class="form-label">{{__('Min Amount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="min_amount" required value="{{old('min_amount')}}" placeholder="Enter {{__('Min Amount')}}" />
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Max Discount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="max_discount" required value="{{old('max_discount')}}" placeholder="Enter {{__('Max Discount')}}" />
    </div>
    <div class="mb-3 d-flex justify-content-evenly">
        <div class="d-flex">
            <label class="form-label m-2">{{__('Starts From')}}</label>
            <input type="datetime-local" name="from" required value="{{old('from')}}" />
        </div>
        <div class="d-flex align-content-center flex-wrap">
            <label class="form-label m-2">{{__('Ends Before')}}</label>
            <input type="datetime-local" name="to" required value="{{old('to')}}" />
        </div>
    </div>
    <button role="submit" class="my-5 mx-auto d-block btn btn-primary">{{__('Submit')}}</button>
    </form>
    @push('scripts')
        <script>
            // add current date
            const inputs = document.querySelectorAll('input[type=date]');
            inputs.forEach(input => input.value = (new Date()).toLocaleDateString());

            // parse user_id 
            const selectUser = (select) => {
                userId = select.value;
                document.querySelector("[custom-user]").value = userId;
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

            $(document).ready(() => {
                @if (old('navigator') == 1)
                    navigator(1);
                @else
                    navigator(0);
                @endif

                $("[name=multiple_usage]").on("change",() => {
                    if ($("[name=multiple_usage]").is(':checked')) {
                        $(".usage_count").removeClass('d-none');
                    } else {
                        $(".usage_count").addClass('d-none');
                    }
                });
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
