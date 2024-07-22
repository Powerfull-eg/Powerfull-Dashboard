<x-layouts::dashboard>
    <x-components::status />
    <h2 class="text-center fw-bold mx-auto">{{__('Edit Gift')}} #{{$gift->id}}</h2>
    <form action="{{route('dashboard.gifts.update', $gift->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <div class="mb-3">
        <label class="form-label">{{__('Name')}}</label>
        <input type="text" class="form-control" name="code" required value="{{$gift->name}}" placeholder="Enter {{__('Name')}}" />
    </div>
    <div class="d-flex">
        <div class="mb-3 mx-3">
            <label class="form-label">{{__('English Title')}}</label>
            <input type="text" class="form-control" name="title_en" required value="{{$gift->title_en}}" placeholder="Enter {{__('English Title')}}" />
        </div>
        <div class="mb-3 mx-3">
            <label class="form-label">{{__('English Message')}}</label>
            <textarea type="text" class="form-control" name="message_en" required value="{{$gift->message_en}}" placeholder="Enter {{__('English Message')}}">{{$gift->message_en}}</textarea>
        </div>
    </div>
<div class="d-flex">
    <div class="mb-3 mx-3">
        <label class="form-label">{{__('Arabic Title')}}</label>
        <input type="text" class="form-control" name="title_ar" required value="{{$gift->title_ar}}" placeholder="Enter {{__('Arabic Title')}}" />
    </div>
    <div class="mb-3 mx-3">
        <label class="form-label">{{__('Arabic Message')}}</label>
        <textarea type="text" class="form-control" name="message_ar" required value="{{$gift->message_ar}}" placeholder="Enter {{__('Arabic Message')}}">{{$gift->message_ar}}</textarea>
    </div>
</div>
    {{-- Users --}}
    {{-- <div class="mb-3">
        <label class="d-flex justify-content-evenly form-label">{{__('Target User')}}</label>
        <div class="form-selectgroup">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="user_id" value="0" class="form-selectgroup-input select-user" {{$voucher->user_id == 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('All Users') }}</span>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="user_id" custom-user onclick="toggleUserSelect(this)" value="{{$voucher->user_id != 0 ? $voucher->user_id  : ''}}" class="form-selectgroup-input select-user" {{$voucher->user_id != 0 ? 'checked' : ''}} />
                <span class="form-selectgroup-label">{{ __('Specific User') }}</span>
            </label>
            <div class="mx-2 {{$voucher->user_id == 0 ? 'd-none' : ''}} users">
                <select name="user_selector" class="form-select" aria-label="{{__("Choose User")}}" onchange="selectUser(this)" id="user_id">
                    <option value="">{{__("Choose User")}}</option>
                    @foreach ($users->all() as $user)
                        <option value="{{$user->id}}" {{$voucher->user_id == $user->id ? 'selected' : ''}}>{{$user->fullName}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Min Amount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="min_amount" required value="{{$voucher->min_amount}}" placeholder="Enter {{__('Min Amount')}}" />
    </div>
    <div class="mb-3">
        <label class="form-label">{{__('Max Discount')}} ({{__('EGP')}})</label>
        <input type="number" class="form-control" name="max_discount" required value="{{$voucher->max_discount}}" placeholder="Enter {{__('Max Discount')}}" />
    </div>
    <div class="mb-3 d-flex justify-content-evenly">
        <div class="d-flex">
            <label class="form-label m-2">{{__('Starts From')}}</label>
            <input type="datetime-local" name="from" required value="{{$voucher->from}}" />
        </div>
        <div class="d-flex align-content-center flex-wrap">
            <label class="form-label m-2">{{__('Ends Before')}}</label>
            <input type="datetime-local" name="to" required value="{{$voucher->to}}" />
        </div>
    </div> --}}
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
