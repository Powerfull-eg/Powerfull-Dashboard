<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />
    @php
    foreach ($top10 as $user) {
        $topusers[$user->first_name . " " .$user->last_name] = $user['operations_count'];
    }   
    @endphp
    <x-components::chart :title="__('Top 10')" :dataLabels="array_keys($topusers)" :dataValues="array_values($topusers)" />

    <div id="data">
        <livewire:users-table :startDate="$startDate" :endDate="$endDate"/>
        <div class="table-responsive">
            <table class="table table-vcenter table-nowrap w-50">
                <tr>
                    <td>{{__("Total Users")}}</td>
                    <td>{{$allUsers->count()}}</td>
                </tr>
                <tr>
                    <td>{{__("Users In Selected Date")}}</td>
                    <td>{{$users->count()}}</td>
                </tr>  
                <tr>
                    <td>{{__("Users In Last 30 Days")}}</td>
                    <td>{{$allUsers->where('created_at','>=',now()->previous("Month") )->count()}}</td>
                </tr>    
            </table>
        </div>
    </div>
    <div class="footer-data">
        <div class="excel">
            <button onclick="excel.export()" class="btn btn-success"> {{ __("Export Excel") }} </button>
        </div>
    </div>
</x-layouts::dashboard>
<script>
    const excel = new Table2Excel("#data");
</script>