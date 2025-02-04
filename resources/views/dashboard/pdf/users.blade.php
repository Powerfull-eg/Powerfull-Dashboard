<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <meta charset="UTF-8"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Users Report')}}</title>
    <style>
        @font-face {
            font-family: 'Noto Sans Arabic';
            src: url('{{public_path("assets/fonts/NotoSansArabic.ttf")}}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'Noto Sans Arabic';
            margin: 20px;
        }
        header {
            height: 50px;
            background-color: #f4f4f4;
            text-align: center;
            line-height: 50px;
            font-size: 18px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px;
        }
        
        table, th, td {
            border: 1px solid black !important;
        }
        th {
            text-align: center;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .table-section {
            /* margin-top: 50px;/ */
        }
        .page-break {
            page-break-after: always;
        }
        .elipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        /* Summary Section */

        .title-card {
            background: #ddd;
            color: var(--text-color-2);
            font-weight: 700;
            width: 300px;
        }
    </style>
</head>
<body>
    <header style="text-align: center; display: flex; justify-content: space-between">
        <span>
            {{__('Users Report')}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
        </span>
        <br>
        <span >
            {{__("Gernerated By")}} {{ auth()->user()->name }} . {{__("at ")}} {{ now()->format('Y-m-d H:i:s') }}
        </span>
    </header>

    {{-- Summary Data --}}
    <h2 style="text-align: center; margin-top: 20px">{{__('Summary Data')}}</h2>
    <div>
        @foreach ($data->summary as $title => $number)           
        <table>
            <tr>
                <td class="title-card">{{ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $title, -1, PREG_SPLIT_NO_EMPTY)))}}</td>
                <td>{{$number}}</td>
            </tr>
        </table>
        @endforeach
    </div>
    <div class="page-break"></div>
    
    {{-- Table --}}
    <div class="table-section">
        <header style="text-align: center; display: flex; justify-content: space-between">
            <span>
                {{__('Users Report')}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
            </span>
            <br>
            <span >
                {{__("Gernerated By")}} {{ auth()->user()->name }} {{__("at ")}} {{ now()->format('Y-m-d H:i:s') }}
            </span>
        </header>
        <h2 style="text-align: center">{{__('Users Data')}}</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('ID')}}</th>
                    <th style="width: 30%">{{__('Name')}}</th>
                    <th>{{__('Phone')}}</th>
                    <th>{{__('Registered At')}}</th>
                    <th>{{__('Last Operation')}}</th>
                    <th>{{__('Deleted At')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->id }}</td>
                        <td class="elipsis">{{ $user->first_name . ' ' . $user->last_name }}</td>
                        <td>{{ 0 . $user->phone }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>{{ $user->operations->count() ? $user->operations->last()->created_at->format('Y-m-d') : '', }}</td>
                        <td>{{ $user->deleted_at ? $user->deleted_at->format('Y-m-d') : '' }}</td>
                    </tr>
                    @if (($index + 1) % 23 == 0)
                        </tbody>
                    </table>
                    <div class="page-break"></div>
                    <header style="text-align: center; display: flex; justify-content: space-between">
                        <span>
                            {{__('Users Report')}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
                        </span>
                        <br>
                        <span >
                            {{__("Gernerated By")}} {{ auth()->user()->name }} . {{__("at ")}} {{ now()->format('Y-m-d H:i:s') }}
                        </span>
                    </header>
                    <h2 style="text-align: center">{{__('Users Data')}}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('ID')}}</th>
                                <th style="width: 30%">{{__('Name')}}</th>
                                <th>{{__('Phone')}}</th>
                                <th>{{__('Registered At')}}</th>
                                <th>{{__('Last Operation')}}</th>
                                <th>{{__('Deleted At')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>