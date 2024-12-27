<x-layouts::dashboard>
    <x-components::status />
    <div class="header fs-1 fw-bold w-100 mx-auto text-center"> Edit Price #{{$price->id}}</div>
    <form action="{{ route('dashboard.prices.update',$price->id) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="row m-3">
            <div class="mb-3 col">
                <label class="form-label">{{__('Name')}}</label>
                <input type="text" class="form-control" name="name" value="{{$price->name}}" placeholder="{{__('Enter'). " " .__('Name')}}" />
            </div>
        </div>
        <div class="row m-3">    
            <div class="mb-3 col">
                <label class="form-label">{{__('Arabic App Description')}}</label>
                <input type="text" class="form-control" name="app_description_ar" value="{{$price->app_description_ar}}" placeholder="{{__('Enter')}} {{__('Arabic App Description')}}" />
            </div>
            <div class="mb-3 col">
                <label class="form-label">{{__('English App Description')}}</label>
                <input type="text" class="form-control" name="app_description_en" value="{{$price->app_description_en}}" placeholder="{{__('Enter')}} {{__('English App Description')}}" />
            </div>
        </div>
        </div>
        <div class="row m-3">
            <div class="mb-3 col">
                <label class="form-label">{{__('Arabic App Description Detailed')}}</label>
                <textarea type="text" class="form-control" name="app_description_detailed_ar" value="{{$price->app_description_detailed_ar}}" placeholder="{{__('Enter')}} {{__('Arabic App Description Detailed')}}">{{$price->app_description_detailed_ar}}</textarea>
            </div>
            <div class="mb-3 col">
                <label class="form-label">{{__('English App Description Detailed')}}</label>
                <textarea type="text" class="form-control" name="app_description_detailed_en" placeholder="{{__('Enter')}} {{__('English App Description Detailed')}}">{{$price->app_description_detailed_en}}</textarea>
            </div>
        </div>
        <div class="mb-3 mx-2">
            <label class="form-label">{{__('Free Time')}}</label>
            <input type="number" class="form-control" name="free_time" required value="{{$price->free_time}}" placeholder="{{__('Enter')}} {{__('Free Time')}}" />
        </div>
        <div class="mb-3 mx-2">
            <label class="form-label">{{__('Max Hours')}}</label>
            <input type="number" class="form-control" name="max_hours" required value="{{$price->max_hours}}" placeholder="{{__('Enter')}} {{__('Max Hours')}}" />
        </div>
        <div class="mb-3 mx-2">
            <label class="form-label">{{__('Insurance Amount')}}</label>
            <input type="number" class="form-control" name="insurance" required value="{{$price->insurance}}" placeholder="{{__('Enter')}} {{__('Insurance Amount')}}" />
        </div>
        {{-- Price details --}}
        <div class="mb-3 mx-2">
            <label class="form-label fs-2 w-100 d-block text-center">{{__('Price Details')}}</label>
            {{-- <input type="hidden" name="prices" required value="{{$price->prices}}"/> --}}
            @foreach (json_decode($price->prices,true) as $type => $prices)
            <div id="{{__($type)}}">      
                <span class="fs-3 fw-bold d-block my-2">{{ __(ucfirst($type)) . " Prices" }}</span>
                @foreach($prices as $index => $price)
                <div class="prices-container">
                    <table class="ms-3">
                        <tr><td><span>Description</span></td><td> <input class="mx-3 form-control" type="text" name="prices[{{$type}}][][description]" value="{{$price['description']}}"></td></tr>
                        <tr><td><span>Price</span></td><td> <input class="mx-3 form-control" type="number" name="prices[{{$type}}][][price]"  value="{{$price['price']}}"></td></tr>
                        <tr><td><span>From (Hours)</span></td><td> <input class="mx-3 form-control" type="number" name="prices[{{$type}}][][from]"  value="{{$price['from']}}"></td></tr>
                        <tr><td><span>To (Hours)</span></td><td> <input class="mx-3 form-control" type="number" name="prices[{{$type}}][][to]"  value="{{$price['to']}}"></td></tr>
                    </table>
                </div>
                
                <div onclick="addPriceDetail(this)" class="btn btn-success add-price">+</div>
                @if($index != 0)
                    <div onclick="removePriceDetail(this)" class="btn btn-danger remove-price">-</div>
                @endif
                <hr>
                @endforeach
            </div>
            @endforeach
        </div>
        <div>
            <div class="mb-3 w-100 text-center mx-auto">
                <button class="btn btn-primary">{{__("Submit")}}</button>
            </div>
    </form>
    @push('scripts')
        <script>
            const addPriceDetail = (element) => {
                const clone = element.previousElementSibling.cloneNode(true);
                clone.querySelectorAll('input').forEach(input => input.setAttribute('value',''));
                const hr = document.createElement('hr');
                const addBtn = element.cloneNode(true);
                const removeBtn = document.querySelector('.remove-price').cloneNode(true);
                const addAfter = (element.nextElementSibling.classList.contains('remove-price') ? element.nextElementSibling : element);
                [removeBtn,addBtn,clone,hr].forEach(ele => addAfter.after(ele));
            }

            const removePriceDetail = (element) => {
                const price = element.previousElementSibling.previousElementSibling;
                const addBtn = element.previousElementSibling;
                const hr = element.nextElementSibling;;
                [hr,element,addBtn,price].forEach(ele => ele.remove());
            }
        </script>
    @endpush
</x-layouts::dashboard>