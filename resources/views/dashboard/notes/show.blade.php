<x-layouts::dashboard>
    <x-components::status />
    <div class="header d-flex gap-2 justify-content-between align-items-center">
        <div class="d-flex align-items-end justify-content-center logo">
            <i style="font-size: 5rem; color: var(--background-color)" class="ti ti-clipboard-text"></i>
            <h1>{{__("Notes For") . " " . ucfirst($note->type) . " #" . $note->type_id}}</h1>
        </div>
    </div>
    {{-- Notes --}}
    <div class="notes">
        <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">{{__("Type")}}</th>
                <th scope="col">{{__("Name")}}</th>
                <th scope="col">{{__("Note")}}</th>
                <th scope="col">{{__("Added By")}}</th>
                <th scope="col">{{__("Added At")}}</th>
                <th scope="col">{{__("Updated At")}}</th>
              </tr>
            </thead>
            <tbody>
            
            @foreach ($notes as $note)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$note->type}}</td>
                    <td>{{$type->name ?? ($type->fullName ?? __("Not Defined"))}}</td>
                    <td class="text text-red">{{$note->note}}</td>
                    <td>{{$note->admin->name}}</td>
                    <td>{{$note->created_at}}</td>
                    <td>{{$note->updated_at}}</td>
                </tr>
            @endforeach
            </tbody>
          </table>
    </div>
    @push('styles')
        <style>
            .note {
                border: 2px solid var(--background-color);
                padding: 1rem;
                border-radius: 10px;
                margin-top: 1rem;
            }
            .note a {
            }
            table.content-table {
                border-collapse: collapse;
                width: 100%;
                border: 3px solid var(--background-color);
                border-radius: 10px;
                text-align: center;
            }
            table.content-table tr {
                border: 3px solid var(--background-color);
                padding: 5px;
                margin-top: 10px;
                display: flex;
                gap: 10px;
                align-items: center;
            }
            table.content-table tr td.title{
                font-size: 15px;
                font-weight: bold;
            }
            table.content-table tr >td:not(.title) {
                max-width: 63vw;
                color: #000;
                font-weight: 500;
            }
            table.content-table thead{
                background-color: var(--background-color);
                color: var(--text-color);
            }
            table.content-table .text-truncate {
                white-space: unset !important;
            }
        </style>
    @endpush
</x-layouts::dashboard>