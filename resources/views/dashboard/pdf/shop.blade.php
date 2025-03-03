<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <meta charset="UTF-8"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="{{$data->name . __(' Report')}}">
    <meta name="description" content="Report for {{$data->name}} shop in specific period">
    <meta property="og:title" content="{{$data->name . __(' Report')}}">
    <meta property="og:description" content="Report for {{$data->name}} shop in specific period">
    <meta property="og:type" content="application/pdf">
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
            {{$data->name . __(' Report')}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
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

    {{-- Table --}}
    <div class="table-section">
        <h2 style="text-align: center">{{__(ucfirst($data->name). " Data")}}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{__('Shop')}}</th>
                    <th>{{__('Shop Phone')}}</th>
                    <th>{{__('Service Number')}}</th>
                    <th>{{__('Total Orders')}}</th>
                    <th>{{__('Total Hours')}}</th>
                    <th>{{__('Total Amount')}}</th>
                    <th>{{__('Partnership Share')}}</th>
                    <th>{{__('Total Partner Share')}}</th>
                </tr>
            </thead>
                    <tr>
                        <td class="elipsis">{{ $data->name }}</td>
                        <td class="elipsis">{{ $data->phone }}</td>
                        <td>{{ $data->device->sim_number }}</td>
                        <td>{{ $data->operations->count()}}</td>
                        <td>{{ intval($data->operations->sum(fn($operation) => $operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0)) }}</td>
                        <td>{{ $data->operations->sum('amount') }}</td>
                        <td>{{ "0 %" }}</td>
                        <td>{{ "0 EGP" }}</td>
                    </tr>
        </table>
    </div>
        <div class="page-break"></div>
    @if ($data->operations)
        
        {{-- Table --}}
        <div class="table-section">
            <header style="text-align: center; display: flex; justify-content: space-between">
                <span>
                    {{$data->name. __('Operations Report')}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
                </span>
                <br>
                <span >
                    {{__("Gernerated By")}} {{ auth()->user()->name }} {{__("at ")}} {{ now()->format('Y-m-d H:i:s') }}
                </span>
            </header>
            <h2 style="text-align: center">{{__('Operations Data')}}</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('User ID')}}</th>
                        <th>{{__('Borrow Time')}}</th>
                        <th>{{__('Return Time')}}</th>
                        <th>{{__('Renting Time')}}</th>
                        <th>{{__('Operation Time')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->operations as $index => $operation)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td >#{{ $operation->user_id}}</td>
                            <td>{{ $operation->borrowTime ? chineseToCairoTime($operation->borrowTime) : '' }}</td>
                            <td>{{ $operation->returnTime ? chineseToCairoTime($operation->returnTime) : '-' }}</td>
                            <td>{{ $operation->returnTime ? secondsToTimeString(Carbon\Carbon::parse($operation->returnTime)->getTimestamp() - Carbon\Carbon::parse($operation->borrowTime)->getTimestamp()) : '-' }}</td>
                            <td>{{ $operation->created_at->format('D, M j, Y') }}</td>
                        </tr>
                        @if (($loop->index + 1) % 23 == 0)
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
                                    <th>{{__('User ID')}}</th>
                                    <th>{{__('Borrow Time')}}</th>
                                    <th>{{__('Return Time')}}</th>
                                    <th>{{__('Renting Time')}}</th>
                                    <th>{{__('Operation Time')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>