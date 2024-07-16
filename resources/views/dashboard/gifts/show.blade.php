<x-layouts::dashboard>
    <x-components::status />
    <x-components::forms.customDatePicker />

    
    <table class="table table-responsive">
        @php
        $counter = 0;
        @endphp
        <thead>
            <tr>
                <td>#</td>
                <td>Gift Name</td>
                <td>Code</td>
                <td>Created At</td>
                <td>Updated At</td>
            </tr>
        </thead>
        <tbody>
            @foreach($gifts as $gift)
            <tr>
                <td>{{++$counter}}</td>
                <td>{{$gift->gift->name}}</td>
                <td>{{$gift->code}}</td>
                <td>{{$gift->created_at}}</td>
                <td>{{$gift->updated_at}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layouts::dashboard>
