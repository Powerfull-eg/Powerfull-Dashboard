@extends('dashboard.pdf.base')

@section('title', __('Customers Report'))

<h2 class="text-center mb-5 fw-bold w-100">{{__('Customers') . ' ' .__('Data')}}</h2>
@if(isset($data->startDate) || isset($data->endDate))
    <h3 class="text-center mb-5 fw-bold w-100">{{__('Report') . ' ' . ($data->startDate ? __('From') . ' ' . $data->startDate . ' - ' : '' ). __('Until') . ' ' . $data->endDate}}</h3>
@endif
@section('content')
    @if(isset($data->summary))
        <div class="container w-100 mx-auto summary-container row  justify-content-center mt-5">
            @foreach ($data->summary as $title => $number)
                <div class="summary-card {{ in_array($loop->index, [1,4]) ? 'middle' : ''}} col-3">
                    <div class="number-card">
                        <span class="background"></span>
                        <span class="number" style="{{$number > 99 ? 'left: -5px' : ($number < 10 ? 'left: 5px' : '')}}">{{$number}}</span>
                    </div>
                    <div class="title-card">{{ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $title, -1, PREG_SPLIT_NO_EMPTY)))}}</div>
                    <hr>
                </div>
            @endforeach
        </div>
    @endif
    {{-- Temporary !!!! --}}
    <div class="col-12">
        <livewire:users-table :startDate="$data->startDate" :endDate="$data->endDate"/>        
    </div>
    <style>
        * {
            font-size: 15px;
        }
        /* Summary Card */
        .summary-card {
                position: relative;
                margin: 20px;
                margin-top: 25px;
                display: inline-block;
            }
            .number-card {
                position: relative;
            }
            .number-card .background {
                position: absolute;
                top: -10px;
                left: -10px;
                width: 50px;
                height: 50px;
                background-color: var(--background-color);
                border-radius: 10px;
                transform: rotate(45deg);
            }
            .summary-card.middle .number-card .background {
                background: #eca51c;
            }
            .number-card .number {
                position: absolute;
                font-size: 20px;
                font-weight: 700;
                color: var(--text-color);
            }
            .title-card {
                background: #ddd;
                color: var(--text-color-2);
                padding: 1rem;
                margin-top: 30px;
                border-radius: 50px;
                padding-inline-start: 30px;
                font-weight: 600;
            }
            .summary-card hr {
                background: var(--background-color);
                height: 3px;
                opacity: 0.6;
            }
            .summary-card.middle hr {
                background: #ddd !important;
            }
    </style>
@endsection


