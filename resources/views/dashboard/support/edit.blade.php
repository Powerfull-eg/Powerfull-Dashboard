<x-layouts::dashboard>
    <x-components::status />
    <div id="message-container" style="overflow-x: hidden; overflow-y:scroll; max-height: 70%" class="container">
            <div class="ticket-container row mt-5">
                <h3 class="subject text-center alert alert-warning w-75 mx-auto">{{ $ticket->subject }}</h3>
                <div class="messages d-flex flex-column" style="margin-bottom: 5rem;">
                    @foreach($ticket->messages as $message)
                        <div class="message w-50 mx-5 mt-2 {{ ($message->sender == 1 ? 'align-self-start text-start' : 'align-self-end text-end') }}">
                            <span class="{{ ($message->sender == 1 ? 'user' : 'admin') }}">{{ $message->message }}</span>
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
                        <div class="form-floating" style="width: 70%">
                            <input class="form-control h-100" name="message" placeholder="{{ __('Leave a Message here') }}" id="floatingTextarea2" />
                        </div>
                        <div class="submit ">
                            <button type="submit" class="btn btn-"><i class="ti ti-send"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Scroll to last message
            const messages = document.querySelectorAll('.message');
          messages[messages.length - 1].scrollIntoView({ behavior: 'smooth' });
        });
    </script>
    @endpush
    @push('styles')
    <style>
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
    
    .form-container input#floatingTextarea2 {
        border-color: #f68d41;
        border-width: 3px;
    }
    @media screen and (min-width:990px){
        .form-container {
            margin-left: 20rem;
        }
    }
    </style>
    @endpush
    </div>
</x-layouts::dashboard>