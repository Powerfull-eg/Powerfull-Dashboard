<x-layouts::dashboard>
    <x-components::status />
    <div id="message-container" style="overflow-x: hidden; overflow-y:scroll; max-height: 70%" class="container">
            {{-- User Data --}}
            <div class="user-data w-50">
                <div class="subtitle">
                    <i class="ti ti-user"></i>
                    <span>{{__("Customer") . " " . __("Data")}}</span>
                </div>
                <table class="content-table">
                    <tr>
                        <td class="title">{{__("Customer Name")}}:</td>
                        <td class="text-truncate"> {{$ticket->user->full_name}} </td>
                    </tr>
                    <tr>
                        <td class="title">{{__("Customer Phone")}}:</td>
                        <td class="text-truncate"> {{$ticket->user->phone ? $ticket->user->code . $ticket->user->phone : '-'}} </td>
                    </tr>
                    <tr>
                        <td class="title">{{__("Customer Email")}}:</td>
                        <td class="text-truncate"> {{$ticket->user->email ?? '-'}} </td>
                    </tr>
                </table>
            </div>
            {{-- Ticket Container --}}
            <div class="ticket-container row mt-5">
                <h3 class="subject text-center alert alert-warning w-75 mx-auto">{{ $ticket->subject }}</h3>
                <div class="messages d-flex flex-column" style="margin-bottom: 5rem;">
                    @foreach($ticket->messages as $message)
                        <div class="message w-50 mx-5 mt-2 {{ ($message->sender == 1 ? 'align-self-start text-start' : 'align-self-end text-end') }}">
                            <span class="position-relative {{ ($message->sender == 1 ? 'user' : 'admin') }}">
                                {{ $message->message }}
                                @if ($message->sender == 2 && $message->admin_id == auth()->user()->id)
                                    <span onclick="editMessage(this,{{ $message->id }})" class="edit position-absolute" style="left: -40px; top: 10px; cursor: pointer; display: none;"><i class="ti ti-pencil"></i></span>
                                @endif
                            </span>
                            <span class="time d-none fs-6 text-muted">{{ $message->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="bg-white position-fixed d-flex flex-row w-100" style="bottom:0;left:0;">
                    <div class="form-container mt-1 position-relative w-100">
                        <form style="height: 100px;" action="{{ route('dashboard.support.update',$ticket->id) }}" class="d-flex flex-row  align-items-center w-100" id="reply" method="post" >
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="sender" value="2">
                            <div class="d-flex gap-2 w-100">
                                <div class="form-floating" style="min-width: 70%">
                                    <textarea class="form-control h-100" name="message" placeholder="{{ __('Leave a Message here') }}" id="floatingTextarea2"></textarea>
                                </div>
                                <div class="submit ">
                                    <button type="submit" class="btn btn-"><i class="ti ti-send"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- Closiing Ticket --}}
                @php 
                    $closable = $ticket->status != 2 && $ticket->lastMessage->first()->sender == 2;
                @endphp
                <form class="d-none" id="close-ticket" action="{{ route('dashboard.support.close', $ticket->id) }}" method="post">@csrf</form>
                <a class="floating-btn text {{ $closable ? 'text-danger' : 'disabled' }}" 
                    style="bottom: 220px;" 
                    onclick="{{ $closable ? 'closeTicket()' : ''}}" 
                    href="#"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-title="{{__("Close Ticket")}}"
                    >
                    <i class="ti ti-x"></i>
                </a>
                {{-- Scroll To Top --}}
                <div onclick="$(document).scrollTop(0);" style="bottom: 150px" class="scroll-top floating-btn text text-warning"><span><i class="ti ti-arrow-up"></i></span></div>
            </div>
        </div>
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Scroll to last message
            const messages = document.querySelectorAll('.message');
          messages[messages.length - 1].scrollIntoView({ behavior: 'smooth' });
        });
        
        const editMessage = (element, messageId) => {
            const parent = $(element).parent('.admin');
            const original = $(parent).html();
            const message = $(parent).text();
            const addedHtml = `<div class="form-floating" style="min-width: 70%">
                                    <form action="{{ route('dashboard.support.message.update',$ticket->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="message_id" value="${messageId}">
                                        <textarea class="edit form-control h-100" name="message" placeholder="{{ __('Leave a Message here') }}" id="floatingTextarea2">${message}</textarea>
                                    </form>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <button type="submit" class="text text-success"><i class="ti ti-check"></i></button>
                                    <button type="button" class="text text-danger"><i class="ti ti-circle-x"></i></button>
                                </div>`;
            $(parent).html(addedHtml);

            $(parent).find('button').eq(1).on('click', () => {
                $(parent).html(original);
                $(element).parent('.admin').find('.edit').css('display','block');
            })

            $(parent).find('button').eq(0).on('click', () => {
                $(parent).find('form').submit();
            })
        }

        // Close Ticket
        const closeTicket = () => {
            this.event.preventDefault();
            $.confirm({
                title: '{{__("Close Ticket")}}',
                content: '{{__("Are you sure you want to close this ticket?")}}',
                type: 'red',
                buttons: {
                    confirm: {
                        text: '{{__("Yes")}}',
                        btnClass: 'btn-red',
                        action: () => {
                            $('#close-ticket').submit();
                        }
                    },
                    cancel: {
                        text: '{{__("No")}}',
                        btnClass: 'btn-default',
                    },
                },
            });
        }
    </script>
    @endpush
    @push('styles')
    <style>
    .floating-btn {
        padding: 1rem;
        position: fixed;
        background: white;
        width: 50px;
        height: 50px;
        right: 20px;
        border: 1px solid var(--background-color);
        border-radius: 10%;
        cursor: pointer;
    }
    .floating-btn.disabled {
        cursor: not-allowed;
        border-color: #ddd;
        color: #ddd;
    }
    span.user {
        padding: 13px;
        background: linear-gradient(265deg, rgb(220 115 37) 37%, rgba(229,136,31,1) 74%);
        border-radius: 30px;
        color: #fff;
        font-size: 18px;
        display: inline-block;
    }
    span.admin {
        padding: 13px;
        background: #ddd;
        border-radius: 30px;
        font-size: 18px;
        display: inline-block;
        color: #000;
    }
    .form-container {
        z-index: 100;
        background: inherit;
        margin: 10px 0;
        margin-left: 5px;
        box-sizing: border-box;
    }
    .submit > button{
        padding: 1rem;
        margin-left: 3px;
        font-size: 26px;

    }
    
    .form-container textarea#floatingTextarea2 {
        border-color: #f68d41;
        border-width: 3px;
    }
    @media screen and (min-width:990px){
        .form-container {
            margin-left: 20rem;
        }
    }

    .message:hover span.edit,.message:hover span.time{
        display: block !important;
        padding: 0 20px;
    }
    textarea.edit {
        width: 200px;
        height: 100px;
        border: none;
        resize: none;
        outline: none;
        background: transparent;
        color: var(--background-color);
    }
    .admin button {
        background: transparent;
        border: none;
    }
    </style>
    @endpush
</x-layouts::dashboard>