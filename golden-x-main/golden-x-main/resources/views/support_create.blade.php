@extends('layouts.support')

@section('support')
<div class="support-create__wrapper">
<div class="support-chat__header">
        <div class="support-chat__left">

            <div class="support_tittles">
            <img src="/assets/images/support_head.svg" class="icon_support_head"> <span style="
  display: flex;
  width: 550px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  "> Создание нового обращения </span>
            </div>

        </div>
                    </div>
	<div class="support-create">

        <input minlength="5" maxlength="255" id="ticket_subject" class="support-create__input" required type="text" placeholder="Тема обращения" autocomplete="off" />
        <textarea id="ticket_body" class="support-create__input support-create__textarea" required type="text" placeholder="Расскажите о проблеме чуть-подробнее..." autocomplete="off"></textarea>
        <div class="support-create__footer">
   
            <button onclick="createTicket()" class="is-ripples flare d-flex align-center has-ripple create sp">Создать обращение <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="btn_create_sp">
<path d="M12 3.99999C12.5523 3.99999 13 4.44771 13 5V11L19 11C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13L13 13L13 19C13 19.5523 12.5523 20 12 20C11.4477 20 11 19.5523 11 19L10.999 13L5 13C4.44772 13 4 12.5523 4 12C4 11.4477 4.44772 11 5 11L10.999 11L11 5C11 4.44771 11.4477 3.99999 12 3.99999Z" fill="white"/>
</svg>
</button>
        </div>
    </div>
</div>
@endsection