<div class="wrapper">
    <div class="support" keep-alive="">
        <div class="header__right d-flex align-center pds15">
        <div class="chat__online d-flex align-center">
            <div class="gx-con blue">
                <div class="icon lg"><svg class="icon">
                        <use xlink:href="/images/symbols.svg#support"></use>
                    </svg></div>
                <div class="title">
                    <div class="">
                        <h4 style="font-size: 14px;">Техническая поддержка</h4>
                        
                    </div>
                </div>
            </div>
        </div>
            <a aria-current="page" onclick="load('support')" class="router-link-active router-link-exact-active support__header-back" type="button">Назад</a>
        </div>
        <hr>
        <div class="support__container">
            <div class="support__sidebar display-none">
                <div class="support__sidebar-body">
                    @foreach(\App\Ticket::where('user_id', Auth::id())->get() as $ticket)
                    <a onclick="load('support/view/{{ $ticket->id  }}')" class="ticket-row">
                    <img class="ticket-row_icons" src="/assets/images/ticket_row.svg">
                        <div class="toclet-row-content">
                        <div class="ticket-row__title">{{ $ticket->subject }} </div>
                        <div class="ticket-row__subtitle">{{ date("d.m.Y в H:i", strtotime($ticket->created_at)) }}</div> 
                        </div> 
                        @if($ticket->status == 0)
                        <div class="ticket-row__status" style="background-color: rgba(84, 180, 102, 0.15)">
                        <span class="ticket-row_text-active"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
<path fill-rule="evenodd" clip-rule="evenodd" d="M13.4714 4.19526C13.7317 4.45561 13.7317 4.87772 13.4714 5.13807L6.47136 12.1381C6.21101 12.3984 5.7889 12.3984 5.52855 12.1381L2.86189 9.4714C2.60154 9.21105 2.60154 8.78894 2.86189 8.5286C3.12224 8.26825 3.54435 8.26825 3.8047 8.5286L5.99996 10.7239L12.5286 4.19526C12.7889 3.93491 13.211 3.93491 13.4714 4.19526Z" fill="#54B466"/>
</svg> Активный</span>
                        </div>
                        @else
                        <div class="ticket-row__status" style="background-color: rgba(210, 78, 73, 0.15)">
                        <span class="ticket-row_text-closed"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path style="gap: 5px;" d="M5.02426 4.17574C4.78995 3.94142 4.41005 3.94142 4.17574 4.17574C3.94142 4.41005 3.94142 4.78995 4.17574 5.02426L7.15147 8L4.17574 10.9757C3.94142 11.2101 3.94142 11.5899 4.17574 11.8243C4.41005 12.0586 4.78995 12.0586 5.02426 11.8243L8 8.84853L10.9757 11.8243C11.2101 12.0586 11.5899 12.0586 11.8243 11.8243C12.0586 11.5899 12.0586 11.2101 11.8243 10.9757L8.84853 8L11.8243 5.02426C12.0586 4.78995 12.0586 4.41005 11.8243 4.17574C11.5899 3.94142 11.2101 3.94142 10.9757 4.17574L8 7.15147L5.02426 4.17574Z" fill="#D24E49"/>
                        </svg> Закрытый</span>
                        </div>
                        @endif
                     
                    </a>
                    @endforeach
                </div>
                <div class="support__sidebar-footer">
                    <a  class="newbuttonsupp is-ripples flare d-flex align-center has-ripple" onclick="load('support/create')" type="button">
                        Создать новый запрос <svg xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4C12.5523 4 13 4.44772 13 5V11L19 11C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13L13 13L13 19C13 19.5523 12.5523 20 12 20C11.4477 20 11 19.5523 11 19L10.999 13L5 13C4.44772 13 4 12.5523 4 12C4 11.4477 4.44772 11 5 11L10.999 11L11 5C11 4.44772 11.4477 4 12 4Z" fill="#3B7BE6"/>
                        </svg></a>
                </div>
            </div>
            <div class="support__content">
                @yield('support')
            </div>
        </div>
    </div>
</div>

<script>
    function createTicket() {
        $.post('/support/create', {
                subject: $('#ticket_subject').val(),
                body: $('#ticket_body').val()
            })
            .then((e) => {
                if (!e.success) return notification('error', e.error);

                return load('support/view/' + e.ticket.id);
            });
    }

    function sendMessage(id) {
        $.post('/support/send', {
                id,
                body: $('#send_message').val()
            })
            .then((e) => {
                if (!e.success) return notification('error', e.error);

                return load('support/view/' + e.ticket.id);
            });
    }

    function closeTicket(id) {
        $.post('/support/close', {
            id: id
        }).then((e) => {
            if (!e.success) return notification('error', e.error);

            return load('support');
        });
    }
</script>
