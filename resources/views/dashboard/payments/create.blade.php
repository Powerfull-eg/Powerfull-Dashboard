<x-layouts::dashboard>
    <x-components::status />
    <div class="container">
        <h3 class="text-center my-3">{{ __('Incomplete Payments') }}</h3>
        <div class="loader mx-auto text text-warning" style="display: none;"></div>
        <div class="data">

            <div class="my-3">
                <form action="{{ route('dashboard.payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order" value="">
                    <a class="btn btn-danger w-25" onclick="this.closest('form').submit();" >{{__("Request All")}}</a>
                </form>
            </div>
            <span class="alert alert-warning d-block">{{__("To complete specific payment, please click on the required order")}}</span>
            <span class="d-block">{{__("No. of Incompleted Payments")}}: <span class="text text-danger">{{$orders->count()}}</span></span>
            <div class="services  row d-flex justify-content-center">
                @foreach ($orders as $order)
                <form class="col-md-3 service card" action="{{ route('dashboard.payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <a class="text-center" onclick="this.closest('form').submit();">
                        <div class="card-body">
                            <div class="shop-data row">
                                <img src="{{ $order->device->shop->logo }}" width="100" class="d-block mx-auto mb-2 col" alt="Logo">
                                <span class="fw-bold col" > {{ $order->device->shop->name }} </span>
                            </div>
                            <span class="user d-block text-start">{{ __("UserName") ." : ". $order->user->fullName }}</span>
                            <span class="amount d-block text-start">{{ __("Amount") ." : ". $order->amount }}</span>
                        </div>
                    </a>
                </form>
                @endforeach
            </div>
        </div>
    </div>
    @push('styles')
    <style>
        .service {
            border-radius: 2rem;
            margin: 1rem;
            
        }
        .service:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            transition: 0.5s;
            cursor: pointer;
        }
        .service a{
            text-decoration: none;
        }
        .shop-data {
            display: flex;
            align-items: center;
            flex-direction: row-reverse;
            flex-wrap: nowrap;
            border-bottom: 1px solid;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .shop-data img {
            max-width: 30%;
            border-radius: 50%;
        }
    </style>
    @endpush
    {{-- @push('scripts')
    <script>
        $('form').on("submit", function(e) {
            e.preventDefault(); // Prevent the default form submission

            $('.loader').show();
            $('.data').hide();
            e.submit();
            // Perform any additional actions or AJAX requests here
        });
    </script>
    @endpush --}}
</x-layouts::dashboard>