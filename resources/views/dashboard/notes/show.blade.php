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
        <table class="content-table w-100">
            <thead>
                <tr>
                    <td class="title">#</td>
                    <td class="title" style="width: 50%">{{__("Note")}}</td>
                    <td class="title">{{__("Created By")}}</td>
                    <td class="title">{{__("Created At")}}</td>
                    <td class="title">{{__("Updated At")}}</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($notes as $note)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td class="text-truncate">{{$note->note}}</td>
                    <td class="text-truncate">{{\App\Models\Admin::find($note->admin_id)->name}}</td>
                    <td class="text-truncate">{{$note->created_at}}</td>
                    <td class="text-truncate">{{$note->updated_at}}</td>
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
                color: rgb(117, 117, 117);
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