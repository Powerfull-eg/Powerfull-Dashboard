<x-layouts::dashboard>
    <x-components::status />
        {{-- Header --}}
        <div class="header d-flex gap-2 justify-content-between">
            <div class="d-flex align-items-end justify-content-center gap-2 logo">
                <i style="font-size: 4rem; color: var(--background-color)" class="ti ti-user-shield"></i>
                <h1 class="fw-bold">{{__("Access Shops")}}</h1>
            </div>
        </div>
        {{-- Page Container --}}
        <div class="container m-3" style="border: 2px solid var(--background-color); border-radius: 10px">
            {{-- Active Admins --}}
            <div class="active">
                <div class="subtitle m-3">
                    <i class="ti ti-building-store"></i>
                    <span>{{__("Active Admins")}}</span>
                </div>
                <livewire:admins-table :type="'shop'"/>
            </div>
                    {{-- Add New --}}
        <div class="new">
            <div class="subtitle m-3">
                <i class="ti ti-circle-plus"></i>
                <span>{{__("Add New Admins")}}</span>
            </div>
            {{-- Form --}}
            <form action="{{route('dashboard.shop-admins.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Admin inputs --}}
                <div class="table">
                    <table class="content-table">
                        <tr>
                            <td class="title">{{__("FirstName")}}:</td>
                            <td><input type="text" name="first_name" value="{{old('first_name')}}" placeholder="{{__("First Name")}}" required></td>
                            <td class="title">{{__("Last Name")}}:</td>
                            <td><input type="text" name="last_name" value="{{old('last_name')}}" placeholder="{{__("Last Name")}}" required></td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Shop")}}:</td>
                            <td colspan="3"><x-components::forms.select name="shop" :options="$shops" required/></td></td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Email")}}:</td>
                            <td colspan="3" class="w-100"><input type="text" name="email" value="{{old('email')}}" placeholder="{{__("Email")}}" required></td>
                        </tr>
                        <tr>
                            <td class="title">{{__("Password")}}:</td>
                            <td colspan="3" class="d-flex">
                                <input type="password" name="password" placeholder="{{__("Password")}}" required>
                                <button type="button" class="input-group-text" onclick="togglePassword(this, '[name=password]')" tabindex="-1">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                            <td class="title">{{__("Confirm Password")}}:</td>
                            <td colspan="3" class="d-flex">
                                <input type="password" name="password_confirmation" placeholder="{{__("Confirm Password")}}" required>
                                <button type="button" class="input-group-text" onclick="togglePassword(this, '[name=password_confirmation]')" tabindex="-1">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary p-3 px-5 mx-auto my-3 d-block">
                    <i class="ti ti-save"></i>
                    <span>{{__("Submit")}}</span>
                </button>
            </form>
        </div>
        </div>
         @push('styles')
            <style>
                table.content-table td input,table.content-table td input:focus-visible {
                    background: transparent;
                    border: none;
                    outline: none;
                }
            </style>
        @endpush
</x-layouts::dashboard>
