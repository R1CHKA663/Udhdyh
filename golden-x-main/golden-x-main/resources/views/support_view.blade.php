@extends('layouts.support')

@section('support')
<div class="support-chat">
    <div class="support-chat__header">
        <div class="support-chat__left">

            <div class="support_tittles">
            <img src="/assets/images/support_head.svg" class="icon_support_head"> <span style="
  display: flex;
  width: 550px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  "> {{ $ticket->subject }} </span>
            </div>

        </div>
        @if($ticket->status == 0)
        <div class="support-chat__right">
            <!-- <button onclick="closeTicket({{ $ticket->id }})" class="support-chat__close">Закрыть обращение</button> -->
            <div class="support_id">
                <span>Ticket ID: #{{$ticket->id}}</span>
            </div>
        </div>
        @endif
        @if($ticket->status == 1)
        <div class="support-chat__right">
            <div class="support_closed">
                <span>Тикет был закрыт #{{$ticket->id}}</span>
            </div>
        </div>
        @endif
    </div>
    <div class="support-chat__body">
        <div class="support-head-date">
    <div class="support-hr-date"> 
        Создан {{ date("d.m.Y в H:i", strtotime($ticket->created_at)) }} 
    </div>
</div>
        @foreach($messages as $message)
        <div class="support-message {{ $message->answer ? 'answer' : '' }}">
            <div class="support-message_globals">
            <div class="support-message__username">{{ $message->username }}</div>
            <div class="support-message__date">{{ $message->date }}</div>
            </div>
            <div class="support-message__text">{{ $message->body }}</div>
        </div>
        @endforeach
    </div>
    <div class="support-chat__footer">
        <input class="support-chat__input support-chat__textarea" id="send_message" required type="text" placeholder="Введите ваше обращение" autocomplete="off" {{ $ticket->status == 1 ? 'disabled' : ''}}></input>
        <div class="support-chat__footer-right">
            <div class="chat__buttons d-flex align-center">
                <a class="is-ripples flare d-flex align-center has-ripple send blues" onclick="sendMessage({{ $ticket->id }})" {{ $ticket->status == 1 ? 'disabled' : ''}}>
                <span>Отправить</span>  
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="support_send">
                <path d="M3.82587 10.5028C3.36877 11.7393 3.08153 12.6081 2.96415 13.1092C2.59526 14.684 2.32703 15.0387 3.70194 14.2903C5.07685 13.5419 11.733 9.83458 13.218 9.01067C15.1538 7.93667 15.1797 8.02058 13.1142 6.88024C11.541 6.01173 4.96288 2.4011 3.70194 1.69216C2.441 0.983214 2.59526 1.29847 2.96415 2.87323C3.08305 3.38081 3.37446 4.25739 3.83838 5.50297C4.16285 6.37413 4.91757 7.01345 5.83024 7.19023L9.67322 7.93462C9.70933 7.94184 9.73274 7.97697 9.72552 8.01307C9.72024 8.03946 9.69961 8.06009 9.67322 8.06537L5.82157 8.80904C4.90595 8.98583 4.1492 9.62808 3.82587 10.5028Z" fill="white"/>
                </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection