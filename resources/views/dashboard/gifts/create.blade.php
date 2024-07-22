<x-layouts::dashboard>
    <x-components::status />
    <h2 class="text-center fw-bold mx-auto">{{__('Create New Gift')}}</h2>
    <form action="{{route('dashboard.gifts.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{__('Name')}}</label>
        <input type="text" class="form-control" name="code" required value="{{old('name')}}" placeholder="Enter {{__('Name')}}" />
    </div>
    <div class="d-flex">
        <div class="mb-3 mx-3">
            <label class="form-label">{{__('English Title')}}</label>
            <input type="text" class="form-control" name="title_en" required value="{{old('title_en')}}" placeholder="Enter {{__('English Title')}}" />
        </div>
        <div class="mb-3 mx-3">
            <label class="form-label">{{__('English Message')}}</label>
            <textarea type="text" class="form-control" name="message_en" required value="{{old('message_en')}}" placeholder="Enter {{__('English Message')}}">{{old('message_en')}}</textarea>
        </div>
    </div>
<div class="d-flex">
    <div class="mb-3 mx-3">
        <label class="form-label">{{__('Arabic Title')}}</label>
        <input type="text" class="form-control" name="title_ar" required value="{{old('title_ar')}}" placeholder="Enter {{__('Arabic Title')}}" />
    </div>
    <div class="mb-3 mx-3">
        <label class="form-label">{{__('Arabic Message')}}</label>
        <textarea type="text" class="form-control" name="message_ar" required value="{{old('message_ar')}}" placeholder="Enter {{__('Arabic Message')}}">{{old('message_ar')}}</textarea>
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
