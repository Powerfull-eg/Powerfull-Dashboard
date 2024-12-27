<x-layouts::dashboard>
    <x-components::status />
    <div class="header d-flex gap-2 justify-content-between">
        <div class="d-flex align-items-end justify-content-center gap-2 logo">
            <i style="font-size: 4rem; color: var(--background-color)" class="ti ti-user-cog"></i>
            <h1 class="text-truncate">{{__("Customer") . ' '}} <span style="color: var(--background-color)"> "{{$user->full_name}}"</span>{{' ' .__("Control")}}</h1>
        </div>
    </div>
    {{-- Page Container --}}
    <div class="container my-3">
        {{-- Customer Data --}}
        <div class="customer-data row py-3 align-items-center justify-content-start">
            {{-- data table --}}
            <div class="data-table col-md-6 col-12">
                <div class="d-flex justify-content-between">
                    <div class="subtitle">
                        <i class="ti ti-user"></i>
                        <span>{{__("Customer") . " " . __("Information")}}</span>
                    </div>
                    {{-- Edit Button --}}
                    <div class="controls">
                        <a href="{{ route('dashboard.users.edit',$user->id) }}"><i class="ti fs-2 ti-pencil"></i>{{ __("Edit")}}</a>
                    </div>
                </div>
                {{-- Table --}}
                <div class="table">
                    <table class="content-table">
                        <tr>
                            <td class="title">{{__("Customer Name")}}:</td>
                            <td class="text-truncate"> {{$user->full_name}} </td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Customer Phone")}}:</td>
                            <td class="text-truncate"> {{$user->phone}} </td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Customer Email")}}:</td>
                            <td class="text-truncate"> {{$user->email ?? '-'}} </td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Password")}}:</td>
                            <td>
                                <div class="d-flex gap-1 text-white align-items-center px-2" style="background: rgb(151, 151, 151); border-radius: 10px;">
                                    <div onclick="resetPassword({{$user->id}},'{{$user->fullName}}')" class="btn me-2 bg-transparent" style="border: 0;" type="submit">{{__("Reset password")}}</div>  
                                    @foreach ($resetPasswordChannels as $channel)
                                        <label style="padding-right: 2px;">
                                            <input type="checkbox" name="resetPasswordChannels" value="{{$channel}}"> 
                                            <span class="custom-checkbox">{{ucfirst($channel)}}</span>
                                        </label>
                                    @endforeach
                                    <form action="{{ url('/') . '/dashboard/users/reset-password' }}" class="d-none" id="reset-password" method="POST"> @csrf</form>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Register On App")}}:</td>
                            <td class="text-truncate"> {{$user->created_at->format('Y-m-d')}} </td>
                        </tr>
                    </table>
                </div>
                {{-- Account Actions --}}
                <div class="account-actions d-flex justify-content-between">
                    {{-- Block Account --}}
                    <div type="submit" class="btn btn-warning" style="background: {{$user->blocked ? '#1665a5' : '#b9890f'}}" onclick="blockAccount({{$user->id}},'{{$user->blocked ? 'unblock' : 'block'}}')">
                        <i class="ti fs-2 ti-lock"></i>
                        <span >{{$user->blocked ? __("Unblock Account") : __("Block Account")}}</span>
                    </div>
                    {{-- Delete Account --}}
                    <div class="btn btn-danger" onclick="deleteAccount()">
                        <i class="ti fs-2 ti-trash"></i>
                        <span href="#">{{ __("Delete Account")}}</span>
                    </div>
                    {{-- Forms --}}
                    <form action="{{ url('/') . '/dashboard/users/' . ($user->blocked ? 'unblock' : 'block') }}" class="d-none" id="block-account" method="POST"> @csrf</form>
                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" class="d-none" id="delete-account" method="POST"> @csrf @method('DELETE')</form>
                </div>
            </div>
            {{-- Avatar --}}
            <div class="avatar col-2 mx-auto" style="height: fit-content; width: fit-content;">
                <i class="ti ti-id" style="font-size: 20rem"></i>
            </div>
        </div>

        {{-- Customer Gifts --}}
        <div class="customer-gifts">
            <div class="d-flex justify-content-between">
                <div class="subtitle">
                    <i class="ti ti-gift"></i>
                    <span>{{__("Customer") . " " . __("Gifts")}}</span>
                </div>
                <div class="text-white btn btn-success">
                    <i class="ti fs-2 ti-circle-plus"></i>
                    <a href="{{ route('dashboard.users.gifts.create',$user->id) }}">{{ __("Add") . " " . __("Gift")}}</a>
                </div>
            </div>
            {{-- Table --}}
            <table class="table table-vcenter card-table text-nowrap mt-3 data-table" id="gifts-table">
                <thead class="sticky-top">
                    <tr>
                    <th>#</th>
                    <th>{{__("Gift Name")}}</th>
                    <th>{{__("Merchant")}}</th>
                    <th>{{__("Device")}}</th>
                    <th>{{__("Date Of Gift")}}</th>
                    <th>{{__("Amount")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($user->gifts->isEmpty())
                        <tr><td colspan="6">{{__("No Gifts Exists")}}</td></tr>
                    @else
                        @foreach ($user->gifts as $gift)
                            <tr>
                            <td>{{$loop->iteration}}</td>
                            <th scope="row">{{$gift->gift->name}}</th>
                            <td>{{$gift->shop->name ?? '-'}}</td>
                            <td>{{$gift->operation ? $gift->operation->device->device_id : '-'}}</td>
                            <td>{{$gift->created_at->format('Y-m-d H:i:s')}}</td>
                            <td>{{$gift->gift && app()->getLocale() == 'ar' ? $gift->gift->title_ar : ($gift->gift->title_en ?? '-')}}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Customer Operations --}}
        <div class="customer-operations py-3">
            <div class="subtitle">
                <i class="ti ti-battery-charging"></i>
                <span>{{__("Customer") . " " . __("Operations")}}</span>
            </div>
            {{-- Table --}}
            <table class="table table-vcenter card-table text-nowrap mt-3 data-table" id="operations-table">
                <thead class="sticky-top">
                    <tr>
                        <th>#</th>
                        <th>{{__("Customer Name")}}</th>
                        <th>{{__("Merchant")}}</th>
                        <th>{{__("Device")}}</th>
                        <th>{{__("Borrow Time")}}</th>
                        <th>{{__("Return Time")}}</th>
                        <th>{{__("Renting Time")}}</th>
                        <th>{{__("Amount")}}</th>
                        <th>{{__("Operation Status")}}</th>
                        <th>{{__("Actions")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($operations->isEmpty())
                        <tr><td colspan="10">{{__("No Operations Exists")}}</td></tr>
                    @else
                        @foreach ($operations->sortByDesc('updated_at')->take(5) as $operation)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <th scope="row">{{$operation->user->fullName}}</th>
                                <td>{{$operation->device->shop->name ?? '-'}}</td>
                                <td>{{$operation->device->device_id ?? '-'}}</td>
                                <td>{{$operation->borrowTime ? chineseToCairoTime($operation->borrowTime) : '-'}}</td>
                                <td>{{$operation->returnTime ? chineseToCairoTime($operation->returnTime) : '-'}}</td>
                                <td>{{secondsToTimeString($operation->returnTime ? Carbon\Carbon::parse($operation->returnTime)->getTimestamp() - Carbon\Carbon::parse($operation->borrowTime)->getTimestamp() : 0) ?? '-'}}</td>
                                <td>{{$operation->incompleteOperation ? ($operation->incompleteOperation->final_amount ?? $operation->incompleteOperation->original_amount) . ' ' . __('EGP') : ($operation->amount . ' ' . __('EGP') ?? '-')}}</td>
                                <td>{{__($statusStrings[$operation->status])}}</td>
                                <td>
                                {{-- Actions --}}
                                <div class="d-flex align-items-end mx-2 gap-2">
                                    {{-- Check order status --}}
                                    @if ($operation->status == 3 && $operation->amount > 0)
                                        <div onclick="refundOrder({{$operation->id}},{{$operation->amount}})" class="text-dark btn btn-success">
                                            <span>{{ __("Refund")}}</span>
                                        </div>
                                    @elseif($operation->status == 1)
                                        <div onclick="closeOrder({{$operation->id}})" class="text-dark btn btn-warning">
                                            <span>{{ __("Close")}}</span>
                                        </div>
                                    @endif
                                    {{-- Delete & Restore order --}}
                                        <div onclick="deleteOrder({{$operation->id}},{{$operation->deleted_at ? true : false}})" class="text-dark btn {{$operation->deleted_at ? 'btn-warning' : 'btn-danger'}}">
                                            <span>{{ $operation->deleted_at ? __("Restore") : __("Delete")}}</span>
                                        </div>
                                </div>
                                </td>
                            </tr>
                        @endforeach
                        <form action="{{ url('/') . '/dashboard/operations' }}" class="d-none" id="delete-order" method="POST"> @csrf @method('DELETE')</form>
                        <form action="{{ url('/') . '/dashboard/operations/restore' }}" class="d-none" id="restore-order" method="POST"> @csrf</form>
                        <form action="{{ url('/') . '/dashboard/operations/close' }}" class="d-none" id="close-order" method="POST"> @csrf</form>
                        <form action="{{ route('dashboard.payments.refund') }}" class="d-none" id="refund-order" method="POST"> @csrf</form>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Styles --}}
    @push('styles')
        <style>
        a {
            text-decoration: none;
            color: unset;
        }
        div.controls{
            padding: 5px;
            background-color: var(--background-color);
            color: var(--text-color-2);
            font-weight: bold;
            font-size: 12px;
            border-radius: 30px;
            cursor: pointer;
            margin: 0 5px;
            padding: 5px 10px;
        }
        /* Tables style */
        table.data-table  tr td, table.data-table tr th
        {
            border: 2px solid #000 !important;
            padding: 5px 20px !important;
        }
        table.data-table thead tr th{
            background-color: var(--background-color) !important;
            color: var(--text-color2) !important;
            font-weight: 600 !important;
            text-align: center !important;
        }

        input[type="checkbox"],input[type="radio"] {
         display: none;
        }

        /* Style the custom checkbox */
        .custom-checkbox,.custom-radio {
            background-color: transparent;
            display: inline-block;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Checked state */
        input[type="checkbox"]:checked + .custom-checkbox {
            background-color: var(--background-color); 
        }

        input[type="radio"]:checked + .custom-radio {
            background-color: #8d8d8d; 
        }

        /* Show the checkmark when checked */
        input[type="checkbox"]:checked + .custom-checkbox::after , input[type="radio"]:checked + .custom-radio::after {
            display: block;
        }
        </style>
    @endpush
    {{-- Scripts --}}
    @push('scripts')
        <script src="{{ asset('assets/js/user.js') }}"></script>
    @endpush
</x-layouts::dashboard>