    @php
        $admin = auth('admins')->user();
        $shop = \App\Models\ShopsAdmin::where('admin_id', $admin->id)->with('shop')->first()->shop->first();
        $routes = [
            [
              "title" => __("Operations"),
              "description" => __("Check Shop Operations"),
              "route" => 'dashboard.shops.operations.show',
              "id" => $shop->id,
              "icon" => "ti ti-arrows-sort",
            ],
            [
              "title" => __("Gifts"),
              "description" => __("Gifts that user claimed in shop"),
              "route" => 'dashboard.gifts-show',
              "id" => $shop->id,
              "icon" => "ti ti-arrows-sort",
            ],
            [
              "title" => __("Vouchers"),
              "description" => __("Vouchers that user claimed in shop"),
              "route" => 'dashboard.vouchers.show',
              "id" => $shop->id,
              "icon" => "ti ti-arrows-sort",
            ],
            [
              "title" => __("Shop"),
              "description" => __("Shop Details and Settings"),
              "route" => 'dashboard.shops.show',
              "id" => $shop->id,
              "icon" => "ti ti-arrows-sort",
            ],
        ];
    @endphp
<x-layouts::dashboard sidebar="0" title="{{ html_entity_decode($shop->name) . ' ' . __('Dashboard') }}">
    <div class="container py-5">
        {{-- Shop Title --}}
        <div class="title w-100 text-center">
            <h1>{{__('Hi') . ' ' . $admin->name }}</h1>
            <a href="{{route('dashboard.shops.show',$shop->id)}}">
                <div class="shop-image">
                    <img src="{{$shop->data && $shop->data->logo ? $shop->data->logo : $shop->logo}}" class="img-fluid" alt="{{$shop->name}} Logo" style="width: 200px; border-radius: 50%;">
                </div>
            </a>    
        </div>
      <div class="row g-4 text-center mt-5 cards-contaniner contaniner">
      @foreach ($routes as $route)
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <a href="{{ route($route['route'], $route['id']) }}" class="text-decoration-none">
            <div class="card card-hover text-dark">
              <div class="card-img-top fs-1 mt-2 text-warning" alt="Image"> <i class="{{ $route['icon'] }}"></i></div>
              <div class="card-body">
                <h5 class="card-title">{{ $route['title'] }}</h5>
                <p class="card-text">{{ $route['description'] }}</p>
              </div>
            </div>
          </a>
        </div>
      @endforeach
      </div>
    </div>
@push('styles')
    <style>
        .card{
          border: 1px solid var(--background-color);    
        }
        
        .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
        overflow: hidden;
        }
    
        .card-hover:hover {
        transform: scale(1.03);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        }
    
        .card-img-top {
          transition: transform 0.3s ease;
        }
        .card-img-top i{
          border-radius: 1rem;
          border: 2px solid var(--background-color);
          padding: 5px;
        }

        .card-hover:hover .card-img-top {
        transform: scale(1.05);
        }
    </style>
@endpush
</x-layouts::dashboard>
