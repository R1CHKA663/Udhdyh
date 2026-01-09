@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')
<div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Список обращений</font></font></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Поддержка</font></font></a></li>
                                            <li class="breadcrumb-item active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Список обращений</font></font></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                    <div class="d-flex align-start" style="gap: 5px;">
                    <div class="flex-grow-1">
                    <input type="" id="search_input" placeholder="Найти обращение по ID" class="form-control" style="border-radius: 10px 10px 10px 10px;" name="">
                    </div>
                    <button onclick="searchUser()" class="btn btn-outline-danger waves-effect waves-light" style="border-radius:10px 10px 10px 10px">Найти тикет</button>
                </div>
                
                
                <br>
                <p>Всего обращений: {{ \App\Ticket::count() }}</p>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ID</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Пользователь</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Тема</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Последние сообщение</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Тикет принят</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Взялся за тикет</font></font></th>
                                                        <th scope="col"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Действие</font></font></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($tickets as $ticket)
                                                    <?php
                                                        $lastMessage = \App\TicketMessage::query()->where([['ticket_id', $ticket->id]])->orderBy('created_at', 'desc')->first();

                                                        $user = \App\User::where('id', $ticket->user_id)->first();

                                                        $lastAdmin = \App\TicketMessage::where([['ticket_id', $ticket->id], ['answer', 1]])->orderBy('id', 'desc')->first();
                                                    ?>
                                                    <tr>
                                                        <td>
                                                           {{ $ticket->id }}
                                                        </td>
                                                        <td>
                                                        <div class="d-flex align-items-center">
                                                                <div class="avatar-xs me-3">
                                                                    <span class="avatar-title rounded-circle bg-warning bg-soft text-warning font-size-18">
                                                                        <img class="mdi mdi-bitcoin" src="{{ $user->avatar }}" style="height: 35px; border-radius: 15px;">
                                                                    </span>
                                                                 </div>
                                                                <span><a href="/admin/user/{{$user->id }}"> {{$user->name }}</a></span>
                                                            </div>
                                                        </td>
                                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><span class="badge bg-soft-primary text-primary" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width: 160px;">{{ $ticket->subject }}</span></font></font></td>
                                                        <td><span class="badge bg-soft-danger text-danger" style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;width: 565px;">{{ $lastMessage->body }}</span></td>
                                                        <td>
                                                        <div>
                                                            @if($ticket->status == 0)
                                                                <a href="javascript: void(0);" class="badge bg-soft-success text-success font-size-11 m-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">В процессе</font></font></a>
                                                            @else
                                                                <a href="javascript: void(0);" class="badge bg-soft-danger text-danger font-size-11 m-1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Тикет закрыт</font></font></a>
                                                            @endif
                                                            </div>
                                                        </td>
                                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                            @if(\App\TicketMessage::where([['ticket_id', $ticket->id], ['answer', 1]])->count() >= 1)
                                                                Модератор #{{ $lastAdmin->admin_id }}
                                                            @else
                                                                Тикет без ответа
                                                            @endif
                                                        </font></font></td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                            <button class="btn btn-outline-danger waves-effect waves-light" style="border-radius:10px 10px 10px 10px" onclick="location.href = '/admin/support/{{$ticket->id}}'">Перейти</button>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                {{ $tickets->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection
