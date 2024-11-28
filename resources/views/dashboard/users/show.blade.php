<x-layouts::dashboard>
    <x-components::status />
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
                        <i class="ti fs-2 ti-pencil"></i>
                        <a href="{{ route('dashboard.users.edit',$user->id) }}">{{ __("Edit")}}</a>
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
                            <td class="text-truncate"> {{'actions'}} </td>
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
                    <div class="btn btn-warning" style="background: #b9890f">
                        <i class="ti fs-2 ti-lock"></i>
                        <a href="#">{{ __("Block Account")}}</a>
                    </div>
                    {{-- Delete Account --}}
                    <div class="btn btn-danger">
                        <i class="ti fs-2 ti-trash"></i>
                        <a href="#">{{ __("Delete Account")}}</a>
                    </div>
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
                    <a href="#">{{ __("Add") . " " . __("Gift")}}</a>
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
                    </tr>
                </thead>
                <tbody>
                    @if ($user->operations->isEmpty())
                        <tr><td colspan="9">{{__("No Operations Exists")}}</td></tr>
                    @else
                        @foreach ($user->operations->sortByDesc('updated_at')->take(5) as $operation)
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
                            </tr>
                        @endforeach
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
        .subtitle {
            display: flex;
            color: var(--text-color);
            gap: .5rem;
            background-color: var(--background-color);
            width: fit-content;
            padding: .25rem;
            align-items: center;
            border-radius: 10px;
            min-width: 25%; 
        }
        .subtitle i.ti {
            font-size: 30px;
            font-weight: 600;
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
        table.content-table {
            width: 100%;
        }
        table.content-table tr {
            border: 3px solid var(--background-color);
            padding: 5px;
            margin-top: 10px;
            display: flex;
            max-width: 70vw;
            gap: 10px;
            align-items: center;
            border-radius: 7px;
        }
        table.content-table tr td {
            width: fit-content;
        }
        table.content-table tr td.title{
            min-width: fit-content;
            font-size: 15px;
            font-weight: bold;
        }
        .content-table tr >td:not(.title) {
            max-width: 63vw;
            color: rgb(117, 117, 117);
        }
        .content-table .text-truncate {
            white-space: unset !important;
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
        </style>
    @endpush
</x-layouts::dashboard>