<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__(ucfirst($data->target). " Report")}}</title>
    <style>
        * {
            --background-color: #ea711b;
            --text-color: #ffffff;
            --text-color-2: #000;
        }

        body {
            margin: 20px;
        }
        header {
            /* position: fixed; */
            top: -40px;
            left: 0;
            right: 0;
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
            border: 1px solid black;
        }
        th {
            text-align: center;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .summary-section {
            page-break-after: always;
        }
        .table-section {
            /* margin-top: 50px;/ */
        }
        .elipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .page-break {
            page-break-after: always;
        }
        /* Summary Card */
        .title-card {
            background: #ddd;
            color: #000;
            padding: 1rem;
            margin-top: 30px;
            border-radius: 50px;
            padding-inline-start: 30px;
            font-weight: 700;
            width: 200px;
        }
    </style>
</head>
<body>
    <header style="text-align: center; display: flex; justify-content: space-between">
        <span>
            {{__(ucfirst($data->target). " Report")}} - {{$data->startDate ? __('From') : ''}} {{ $data->startDate ?? '' }} {{__('To')}} {{ $data->endDate ?? now()->format('Y-m-d') }}
        </span>
        <br>
        <span >
            {{__("Gernerated By")}} {{ auth()->user()->name }} . {{__("at ")}} {{ now()->format('Y-m-d H:i:s') }}
        </span>
    </header>

    {{-- Summary Data --}}
    <h2 style="text-align: center;  margin-top: 20px">{{__('Summary Data')}}</h2>
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
        <h2 style="text-align: center">{{__(ucfirst($data->target). " Data")}}</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Powerfull ID')}}</th>
                    <th>{{__('Device ID')}}</th>
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
            <tbody>
                @foreach ($data as $index => $device)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $device->powerfull_id }}</td>
                        <td>{{ $device->device_id }}</td>
                        <td class="elipsis">{{ $device->shop->name }}</td>
                        <td class="elipsis">{{ $device->shop->phone }}</td>
                        <td>{{ $device->sim_number }}</td>
                        <td>{{ $device->operations->count()}}</td>
                        <td>{{ intval($device->operations->sum(fn($operation) => $operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0)) }}</td>
                        <td>{{ $device->operations->sum('amount') }}</td>
                        <td>{{ "0 %" }}</td>
                        <td>{{ "0 EGP" }}</td>
                    </tr>
                    @if (($index + 1) % 26 == 0)
                        </tbody>
                    </table>
                    <div class="page-break"></div>
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