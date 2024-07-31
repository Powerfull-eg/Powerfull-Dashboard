<x-layouts::dashboard>
    <x-components::status />
    <div class="container">
        <div class="services row d-flex justify-content-center">
            <a class="service col-md-4 card text-center" href="{{ route('dashboard.powerbank.index') }}">
                <div class="card-body">
                    <img src="{{asset('assets/images/powerbank.png')}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                    <span class="fw-bold" > PowerBanks Control </span>
                </div>
            </a>
            <a class="service col-md-4 card text-center" href="{{ route('dashboard.devices.index') }}">
                <div class="card-body">
                    <img src="{{asset('assets/images/machine.png')}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                    <span class="fw-bold" > Device Control </span>
                </div>
            </a>
            <a class="service col-md-4 card text-center" href="{{ route('dashboard.prices.index') }}">
                <div class="card-body">
                    <img src="{{asset('assets/images/price.png')}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                    <span class="fw-bold" > Prices Control </span>
                </div>
            </a>
            <a class="service col-md-4 card text-center" href="{{ route('dashboard.vouchers.index') }}">
                <div class="card-body">
                    <img src="{{asset('assets/images/offer.png')}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                    <span class="fw-bold" > Offers Control </span>
                </div>
            </a>
            <a class="service col-md-4 card text-center" href="{{ route('dashboard.gifts.index') }}">
                <div class="card-body">
                    <img src="{{asset('assets/images/gift.png')}}" width="100" class="d-block mx-auto mb-2" alt="Logo">
                    <span class="fw-bold" > Gifts Control </span>
                </div>
            </a>
        </div>
    </div>
    @push('styles')
    <style>
        .service {
            border-radius: 2rem;
            margin: 1rem;
        }
    </style>   
    @endpush
</x-layouts::dashboard>