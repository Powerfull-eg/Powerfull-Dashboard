<x-layouts::dashboard>
    <x-components::status />
    <div class="container">
        <div class="shops row d-flex justify-content-center">
            @foreach ($shops as $shop)
                <div class="shop col-md-4 card text-center" onclick="addAction(this)" data-device="{{ $shop->device->device_id }}">
                    <div class="card-body">
                        <img src="{{$shop->logo}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                        <span class="fs-3 fw-bold d-block" > {{ $shop->name }} </span>
                        <span class="fs-5" > Device: {{ $shop->device->device_id }} </span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="alert alert-danger d-none device-error"></div>

        <div class="actions" >
            <div class="loader mx-auto text text-warning" style="display: none;"></div>
            <form style="display: none;">
                <div class="my-4">
                    <label class="form-label">{{ __('Device')}}</label>
                    <input type="text" class="form-control text-center" name="device_name" disabled/>
                </div>
                <label class="form-selectgroup-item d-none col-md-4" id="label">
                    <input type="radio" required name="powerbank" value="" class="form-selectgroup-input" />
                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                      <div>
                        <span class="content"></span>
                      </div>
                    </div>
                </label>
                <div class="labels-container row">
                </div>
                <button type="submit" class="btn btn-danger my-3"> {{__("Eject Powerbank")}} </button>
            </form>
        </div>
    </div>
    @push('styles')
    <style>
        .shop {
            border-radius: 2rem;
            margin: 1rem;
            cursor: pointer;
        }
        .actions {
            width: 100%;
            margin: 30px auto;
            text-align: center;
        }
    </style>   
    @endpush
    @push('scripts')
    <script>
        const device = {};
        const addAction = (shop) => {
            !$('.device-error').hasClass('d-none') ? $('.device-error').addClass('d-none') : '';
            const device = shop.getAttribute('data-device');
            document.querySelector('.actions .loader').style.display = 'block';

            $.ajax({
                url : "/dashboard/devices/data/"+device,
                success: function(data){
                    if(data.code == 0){
                        populateDeviceData(device,JSON.parse(data).data);
                        return;
                    }
                    $('.device-error').removeClass('d-none').html('{{__("Failed to get device data, Please try again.")}}');
                    $('.actions .loader').css('display','none');
                }
                
            })
        }

        const populateDeviceData = (ele, device = {}) =>{
            document.querySelector('.actions input[name="device_name"]').value = device.cabinet.id;
            const form = document.querySelector('.actions form');
            form.querySelector('div.labels-container').innerHTML = '';
            device.batteries.push({slotNum: 0, batteryId: 'All Devices'});
            device.batteries.forEach(powerbank => {
                let label = document.querySelector("#label").cloneNode(true);
                label.classList.remove('d-none');
                label.setAttribute('id','');
                label.querySelector('input[name=powerbank]').value = powerbank.slotNum;
                label.querySelector('span.content').innerHTML = `Slot ${powerbank.slotNum}: ${powerbank.batteryId}`;
                form.querySelector('div.labels-container').append(label);
                // powerBankOptions.push(`<option vlaue="${powerbank.slotNum}">Slot ${powerbank.slotNum}: ${powerbank.batteryId}</option>`);
            });
            document.querySelector('.actions .loader').style.display = 'none';
            form.style.display = 'block';

        }
    </script>
    @endpush
</x-layouts::dashboard>