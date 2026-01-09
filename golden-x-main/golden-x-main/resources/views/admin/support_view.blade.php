@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')
<div class="row">
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold">Информация о пользователе</h5>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="col">Статус</th>
                                <td scope="col"><span class="badge badge-soft-info">{{$user->status == 0 ? 'Новичек' : ($user->status == 1 ? 'Волк' : ($user->status == 2 ? 'Хищник' : ($user->status == 3 ? 'Премиум' : ($user->status == 4 ? 'Альфа' : ($user->status == 5 ? 'Вип' : ($user->status == 6 ? 'Профи' : 'Легенда'))))))}}</span></td>
                            </tr>
                            <tr>
                                <th scope="row">Рефералов:</th>
                                <td>{{$user->refs}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Регистрация:</th>
                                <td>{{date('d.m.y в H:i:s', strtotime($user->created_at))}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Баланс</th>
                                <td>{{$user->balance}}₽</td>
                            </tr>
                            <tr>
                                <th scope="row">Соц.сеть</th>
                                <td><a href="{{$user->social}}">{{$user->social}}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">Реф.баланс</th>
                                <td>{{$user->balance_ref}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Депозитов</th>
                                <td>{{$user->deps}}₽</td>
                            </tr>
                            <tr>
                                <th scope="row">Выводов всего</th>
                                <td>{{$user->withdraws}}₽</td>
                            </tr>
                            <tr>
                                <th scope="row">Демо баланс</th>
                                <td>{{$user->demo_balance}}₽</td>
                            </tr>
                            <tr>
                                <th scope="row">Всего побед</th>
                                <td>{{$user->win_games}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Всего лузов</th>
                                <td>{{$user->lose_games}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Роль</th>
                                <td><span class="text-muted"><span class="badge bg-soft-warning text-warning">@if($user->admin == 1) Администратор @else Пользователь @endif</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="hstack gap-2">
                    @if($ticket->status == 0)
                    <button type="button" class="btn btn-outline-danger w-100 waves-effect waves-light" onclick="location.href = '/admin/support/close/{{$ticket->id}}'">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Закрыть обращение</font>
                        </font>
                    </button>
                    @endif
                    @if($ticket->status == 1)
                    <button type="button" class="btn btn-outline-danger w-100 waves-effect waves-light">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">Тикет был закрыт</font>
                        </font>
                    </button>
                    @endif
                </div>
            </div>
        </div>


    </div><!--end col-->
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="d-flex">
                    <img src="{{ $user->avatar }}" alt="" style="border-radius:15px" height="50">
                    <div class="flex-grow-1 ms-3">
                        <h5 class="fw-semibold">{{ $user->name }}</h5>
                        <ul class="list-unstyled hstack gap-2 mb-0">
                            <li><i class="bx bx-shield-alt"></i> <span class="text-muted">Название темы: <span class="badge bg-soft-danger text-danger"">{{ $ticket->subject }}</span></span></li>
                            <li>
                                <i class="bx bx-user"></i> <span class="text-muted">Роль: <span class="badge bg-soft-warning text-warning">@if($user->admin == 1) Администратор @else Пользователь @endif</span></span>
                            </li>
                            <li>
                                @if($ticket->status == 1)

                                <i class="bx bxs-flame"></i> <span class="text-muted">Закрытие тикета: <span style="color: #f46a6a;">{{ $ticket->date }}</span></span>
                                @else
                                <i class="bx bxs-flame"></i> <span class="text-muted">Тикет: <span class="badge bg-soft-warning text-warning">Активно в настоящее время</span></span>
                                @endif

                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <ul class="vstack gap-3">
                    <div>
                        <div class="chat-conversation p-3">
                            <ul class="list-unstyled mb-0" data-simplebar="init" style="max-height: 486px;">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: -17px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    @foreach($messages as $message)
                                                    <li class="{{ $message->answer ? 'right' : ''}}">
                                                        <div class="conversation-list">
                                                            <div class="ctext-wrap">
                                                                <div class="d-flex py-3">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <div class="avatar-xs">
                                                                            <div class="avatar-title rounded-circle bg-light text-primary">
                                                                                <img src="{{ $user->avatar }}" style="height: 45px; border-radius: 100px;">
                                                                            </div>
                                                                          
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h5 class="font-size-14 mb-1">
                                                                            <font style="vertical-align: inherit; color: {{ $message->answer ? '#f46a6a' : '#f1b44c' }}"> 
                                                                            <a href="/admin/user/{{ $user->id }}">{{ $message->answer ? 'Агент поддержки' : $user->name }}</a> 
                                                                            <a href="/admin/user/{{ $user->id }}"> #{{$message->answer ? $message->admin_id : $user->id }}</a></font>
                                                                        </h5>
                                                                        <p class="text-muted">{{ $message->body }}</p>
                                                                        <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i>
                                                                            <font style="vertical-align: inherit;">
                                                                                <font style="vertical-align: inherit;">{{ $message->date }}</font>
                                                                            </font>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </li>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: auto; height: 645px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar" style="height: 366px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                                </div>
                            </ul>
                        </div>
                        <div class="p-3 chat-input-section">
                            <form class="row" method="GET" action="/admin/support/send/{{$ticket->id}}">
                                <div class="col">
                                    <div class="position-relative">
                                        <input type="text" name="message" class="form-control chat-input" placeholder="Введите сообщение...">
                                        <div class="chat-input-links" id="tooltip-container">
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item"><a href="javascript: void(0);" title="Быстрые ответы"><i class="mdi mdi-file-document-outline"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light"><span class="d-none d-sm-inline-block me-2">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">Отправить</font>
                                            </font>
                                        </span> <i class="mdi mdi-send"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                        <hr>
                        <h5 class="fw-semibold">Список быстрых ответов</h5>
                        <p class="text-muted">Чтобы быстро ответить, нажмите на любой ответ, и ответ перенесётся в поле для ввода.</p>

                        <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                                <th scope="col" onclick="insert(' Здравствуйте! Сейчас я ознакомлюсь с вашим вопросом, пожалуйста, ожидайте. ')">Нажмите чтобы вставить</th>
                                <td scope="col">Здравствуйте! Сейчас я ознакомлюсь с вашим вопросом, пожалуйста, ожидайте.</td>
                            </tr>
                            <tr>
                                <th scope="col" onclick="insert(' Здравствуйте, {{$user->name}}, подскажите что у вас конкретно случилось? ')">Нажмите чтобы вставить</th>
                                <td scope="col">Здравствуйте, {{$user->name}}, подскажите что у вас конкретно случилось?</td>
                            </tr>
                            <tr>
                                <th scope="row" onclick="insert(' {{$user->name}}, вывод средств зачисляется от 5 минут до 24-х часов. ')">Нажмите чтобы вставить</th>
                                <td>{{$user->name}}, вывод средств зачисляется от 5 минут до 24-х часов. </td>
                            </tr>
                            <tr>
                                <th scope="row"  onclick="insert(' {{$user->name}}, подскажите, могу я ли вам ещё чем-то помочь? ')">Нажмите чтобы вставить</th>
                                <td>{{$user->name}}, подскажите, могу я ли вам ещё чем-то помочь?</td>
                            </tr>
                            <tr>
                                <th scope="row" onclick="insert(' В таком случае, чат закрываю. Если появятся дополнительные вопросы - напишите нам снова. Всего Вам доброго! ')">Нажмите чтобы вставить</th>
                                <td>Хорошо. Рад был Вам помочь!</td>
                            </tr>
                            <tr>
                                <th scope="row" onclick="insert(' Вот ваш промокод:  ')">Нажмите чтобы вставить</th>
                                <td>Вот ваш промокод: </td>
                            </tr>
                            <tr>
                                <th scope="row" onclick="insert(' Данную информацию уточним, пожалуйста ожидайте! ')">Нажмите чтобы вставить</th>
                                <td>Данную информацию уточним, пожалуйста ожидайте!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div><!--end col-->
</div>
<script>
    function insert (word) {
  let inp = document.querySelector('input');
  let start = inp.selectionStart;
  inp.value = inp.value.substring(0, start) + word +
    inp.value.substring(inp.selectionEnd, inp.value.length) 
    inp.focus();
    inp.setSelectionRange(start, start + word.length)
}
</script>
    @endsection