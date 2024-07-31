<x-layouts::dashboard>
    <div class="container-xl">
        <div class="row row-deck row-cards">
          <div class="col-sm-6 col-lg-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">{{__('Operations')}}</div>
                  <div class="ms-auto lh-1">
                    <div class="dropdown">
                      <a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Last 7 days')}}</a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item active" href="#">{{__('Last 7 days')}}</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="h1 mb-3">{{ intval($data['operationsPerLastWeek']->count())}}</div>
                <div class="d-flex mb-2">
                  <div>{{__("Conversion Rate")}}</div>
                  <div class="ms-auto">
                    <span class="text-green d-inline-flex align-items-center lh-1">
                        {{ intval($data['operationsPerLastWeek']->count()/$data['allOperations']->count()) * 100}}% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    </span>
                  </div>
                </div>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-primary" style="width: {{ intval($data['operationsPerLastWeek']->count()/$data['allOperations']->count()) * 100}}%" role="progressbar" aria-valuenow="{{ intval($data['operationsPerLastWeek']->count()/$data['allOperations']->count()) * 100}}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ intval($data['operationsPerLastWeek']->count()/$data['allOperations']->count()) * 100}}% Complete">
                    <span class="visually-hidden">{{ intval($data['operationsPerLastWeek']->count()/$data['allOperations']->count()) * 100}}% Complete</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">{{__('Revenue')}}</div>
                  <div class="ms-auto lh-1">
                    <div class="dropdown">
                      <a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                      <div class="dropdown-menu dropdown-menu-end">
                        {{-- <a class="dropdown-item active" href="#">{{__('Last 7 days')}}</a> --}}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-baseline">
                  <div class="h1 mb-0 me-2">{{$data['revenuePerAllOperations']}} {{__('EGP')}}</div>
                  <div class="me-auto">
                    <span class="text-green d-inline-flex align-items-center lh-1 d-block">
                      {{intval($data['revenuePerLastWeek']/$data['revenuePerAllOperations']) * 100}}% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    </span>
                  </div>
                </div>
              </div>
              <div id="chart-revenue-bg" class="chart-sm"></div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">{{__("New Clients")}}</div>
                  <div class="ms-auto lh-1">
                    <div class="dropdown">
                      <a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Last 30 days')}}</a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item active" href="#">{{__('Last 30 days')}}</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-baseline">
                  <div class="h1 mb-3 me-2">{{ $data['usersPerLastMonth']->count() }}</div>
                  <div class="me-auto">
                    <span class="text-yellow d-inline-flex align-items-center lh-1">
                      {{ intval($data['usersPerLastMonth']->count()/$data['allUsers']->count() * 100)  }}% <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    </span>
                  </div>
                </div>
                <div id="chart-new-clients" class="chart-sm"></div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">{{__('Active Users')}}</div>
                  <div class="ms-auto lh-1">
                    <div class="dropdown">
                      <a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Last 7 days')}}</a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item active" href="#">{{__('Last 7 days')}}</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-baseline">
                  <div class="h1 mb-3 me-2">{{$data['activeUsers']->count()}}</div>
                  <div class="me-auto">
                    <span class="text-green d-inline-flex align-items-center lh-1">
                        {{ intval($data['activeUsers']->count()/$data['allUsers']->count() * 100)  }}% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    </span>
                  </div>
                </div>
                <div id="chart-active-users" class="chart-sm"></div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="row row-cards">
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <a href="{{route('dashboard.operations.index')}}" class="text-white">
                            <span class="bg-primary text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" /><path d="M12 3v3m0 12v3" /></svg>
                            </span>
                        </a>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          {{$data['revenuePerAllOperations']}} {{__("EGP")}}
                        </div>
                        <div class="text-muted">
                          {{ $data['inCompletedPaymentOperations']->count() }} {{__('Incompleted Payments')}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <a href="{{route('dashboard.operations.index')}}" class="text-white">
                            <span class="bg-green text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
                            </span>
                        </a>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          {{$data['allOperations']->count()}} {{__('Orders')}}
                        </div>
                        <div class="text-muted">
                          {{$data['inCompletedOperations']->count()}} {{__('Incompleted Order')}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <a href="{{route('dashboard.support.index')}}" class="text-white">
                            <span class="bg-google text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help-circle" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /></svg>
                            </span>
                        </a>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          {{$data['allTickets']->count()}} {{__('Tickets')}}
                        </div>
                        <div class="text-muted">
                            {{$data['newTickets']->count()}} {{__('New Tickets')}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <a href="{{route('dashboard.shops.index')}}" class="text-white">
                            <span class="bg-pink text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-store" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/> <path d="M3 21l18 0" /> <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /> <path d="M5 21l0 -10.15" /> <path d="M19 21l0 -10.15" /> <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                                </svg>
                            </span>
                        </a>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          {{$data['allShops']->count() . ' ' . __('Shops')}}
                        </div>
                        <div class="text-muted">
                            {{$data['newShops']->count() . ' ' . __('Shops')}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">{{__('Top 10')}} {{__('Customers')}}</h3>
                    <a href="{{route('dashboard.users.index')}}" class="btn btn-primary">{{__('View Customers')}}</a>
                </div>

                @php
                foreach ($data['top10'] as $user) {
                    $topusers[$user->first_name . " " .$user->last_name] = $user['operations_count'];
                }
                @endphp
                <x-components::chart :title="__('Top 10')" :dataLabels="array_keys($topusers)" :dataValues="array_values($topusers)" />
                </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h3 class="card-title">{{__('Latest Shops Operations')}}</h3>
                <div class="">
                  <div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <td>-</td>
                                <td>{{__('Shop Name')}}</td>
                                <td>{{__('Borrow Time')}}</td>
                                <td>{{__('Return Time')}}</td>
                                <td>{{__('User')}}</td>
                                <td>{{__('Amount')}}</td>
                            </thead>
                        @foreach($data['latestOperationsShops'] as $operation)
                          <tr>
                            <td>
                                <a href="{{route('dashboard.shops.show',$operation['shop']->id)}}" class="text-reset">
                                    <span class="avatar avatar-sm" style="background-image: url({{$operation['shop']->logo}})"></span>
                                </a>
                            </td>
                            <td class="w-100">
                              <a href="{{route('dashboard.shops.show',$operation['shop']->id)}}" class="text-reset">{{$operation['shop']->name}}</a>
                            </td>
                            <td class="text-nowrap text-muted">
                                {{$operation['operation']->borrowTime}}
                            </td>
                            <td class="text-nowrap">
                                {{$operation['operation']->returnTime ?? "-"}}
                            </td>
                            <td class="text-nowrap">
                                {{$operation['user']}}
                            </td>
                            <td class="text-nowrap">
                                {{$operation['operation']->amount ? $operation['operation']->amount .' ' . __('EGP')  : 'Free Order'}}
                            </td>
                          </tr>
                          @endforeach
                        </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</x-layouts::dashboard>
