@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')



@php
$user = $data['user'];
@endphp
<div class="content container-fluid">
    <div class="row justify-content-lg-center">
        <div class="col-lg-10">
            <!-- Profile Cover -->
            <div class="profile-cover">
                <div class="profile-cover-img-wrapper">
                    <img class="profile-cover-img" src="https://sun1-15.userapi.com/impf/IgQUQhwVPUVLUGVD9XGf05Xkdi0W7sEdg7ykcQ/9j7AHFJarqo.jpg?size=1920x768&quality=95&crop=132,0,1325,529&sign=4315ce0d5f0c6183c0358d7fcf9f5c33&type=cover_group" alt="Image Description">
                </div>
            </div>
            <!-- End Profile Cover -->

            <!-- Profile Header -->
            <div class="text-center mb-5">
                <!-- Avatar -->
                <div class="avatar avatar-xxl avatar-circle profile-cover-avatar">
                    <img class="avatar-img" src="{{$user->avatar}}" alt="Image Description">
                    <span class="avatar-status avatar-status-success"></span>
                </div>
                <!-- End Avatar -->

                <h1 class="page-header-title">{{$user->name}} #{{$user->id}}</h1> 
                <span class="badge bg-danger rounded-pill">Баланс: {{$user->balance}}₽</span>
              
                <hr>

                <!-- List -->
                <ul class="list-inline list-px-2">
                    <li class="list-inline-item">
                        <i class="bi-building me-1"></i>
                        <span>{{$user->status == 0 ? 'Новичек' : ($user->status == 1 ? 'Волк' : ($user->status == 2 ? 'Хищник' : ($user->status == 3 ? 'Премиум' : ($user->status == 4 ? 'Альфа' : ($user->status == 5 ? 'Вип' : ($user->status == 6 ? 'Профи' : 'Легенда'))))))}}</span>
                    </li>

                    <li class="list-inline-item">
                        <i class="bi-geo-alt me-1"></i>
                        <a href="#">{{$user->ip}}</a>

                    </li>

                    <li class="list-inline-item">
                        <i class="bi-calendar-week me-1"></i>
                        <span>{{date('d.m.y в H:i:s', strtotime($user->created_at))}}</span>
                    </li>
                </ul>
                <!-- End List -->
            </div>
            <!-- End Profile Header -->

            <!-- Nav -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
                <span class="hs-nav-scroller-arrow-prev" style="display: none;">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="bi-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next" style="display: none;">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="bi-chevron-right"></i>
                    </a>
                </span>

                <ul class="nav nav-tabs align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Профиль</a>
                    </li>


                    <li class="nav-item ms-auto">
                        <div class="d-flex gap-2">


                            <!-- Dropdown -->
                            <a data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl" class="btn btn-soft-danger">Управление аккаунтом <i class="mdi mdi-arrow-right ms-1"></i></a>
                            <!-- End Dropdown -->
                        </div>
                    </li>
                </ul>
            </div>
            <!-- End Nav -->

            <div class="row">

                <div class="col-lg-4">

                    <div id="accountSidebarNav"></div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Персональная информация</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>

                                        <th class="ps-0" scope="row">Соц.сеть :</th>
                                        <td class="text-muted"><a href="{{$user->social}}">{{$user->social}}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Имя :</th>
                                            <td><a href="/admin/user/{{$user->id}}" @if($user->admin == 1) class="text-danger" @endif>{{$user->name}}</a></td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Айпи :</th>
                                            <td class="text-muted">{{$user->ip}}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Дата регистрации:</th>
                                            <td class="text-muted">{{date('d.m.y в H:i:s', strtotime($user->created_at))}}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Статус:</th>
                                            <td class="text-muted">{{$user->status == 0 ? 'Новичек' : ($user->status == 1 ? 'Волк' : ($user->status == 2 ? 'Хищник' : ($user->status == 3 ? 'Премиум' : ($user->status == 4 ? 'Альфа' : ($user->status == 5 ? 'Вип' : ($user->status == 6 ? 'Профи' : 'Легенда'))))))}}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Локация :</th>
                                            <td class="text-muted">Неизвестно
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->

              
                    <!-- End Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">Мульти-аккаунты</h5>
                                </div>
                            </div>
                            <div>
                                @foreach($data['accounts'] as $acc)
                                <div class="d-flex align-items-center py-3">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <img src="{{$acc->avatar}}" alt="" class="img-fluid rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div>
                                            <h5 class="fs-14 mb-1"><a href="/admin/user/{{$acc->id}}" @if($acc->admin == 1) class="text-danger" @endif>{{$acc->name}}</a></h5>
                                            <p class="fs-13 text-muted mb-0">Date register: {{date('d.m.y в H:i:s', strtotime($acc->created_at))}}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        @if($acc->ban == 0)<button onclick="changeBan({{$acc->id}}, 1)" class="btn btn-soft-danger">Заблокировать</button> @else<button onclick="changeBan({{$acc->id}}, 0)" class="btn btn-info btn-sm">Разблокировать</button> @endif
                                    </div>
                                </div>
                                @endforeach

                                <div style="margin-bottom: 5px;">
                                    {{ $data['accounts']->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="d-grid gap-3 gap-lg-5">
                        <!-- Card -->
                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between align-items-center flex-grow-1">
                                    <div class="col-md">
                                        <h4 class="card-header-title">Игровые логи</h4>
                                    </div>

                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive datatable-custom">
                                <table id="exportDatatable" class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                    "dom": "Bfrtip",
                    "buttons": [
                      {
                        "extend": "copy",
                        "className": "d-none"
                      },
                      {
                        "extend": "excel",
                        "className": "d-none"
                      },
                      {
                        "extend": "csv",
                        "className": "d-none"
                      },
                      {
                        "extend": "pdf",
                        "className": "d-none"
                      },
                      {
                        "extend": "print",
                        "className": "d-none"
                      }
                   ],
                   "order": []
                 }'>
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Действие</th>
                                            <th>Баланс до</th>
                                            <th>Баланс после</th>
                                            <th>Изменение баланса</th>
                                            <th>Дата</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($data['history'] as $h)
                                        <tr>
                                            <td>
                                                <span class="badge bg-soft-success text-success">{{$h->type}}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-warning text-warning">{{number_format($h->balance_before, 2, ',', ' ')}} RUB</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-info text-info">{{number_format($h->balance_after, 2, ',', ' ')}} RUB</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-soft-success text-success">{{number_format(($h->balance_before - $h->balance_after), 2, ',', ' ')}} RUB</span>
                                            </td>
                                            <td>{{date('d.m.y в H:i:s', strtotime($h->date))}}</td>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                </table>
                                <div style="margin-bottom: 5px;">
                                    {{ $data['history']->links() }}
                                </div>
                            </div>
                            <!-- End Table -->
                        </div>



                    </div>

                    <!-- Sticky Block End Point -->
                    <div id="stickyBlockEndPoint"></div>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Col -->
    </div>
    <!-- End Row -->
</div>
<div class="container-fluid">
    <div class="row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="col-sm-8">
                            <div class="pt-4">
                                <div class="modal fade bs-example-modal-xl" tabindex="-1" aria-labelledby="myExtraLargeModalLabel" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myExtraLargeModalLabel">Управление аккаунтом</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="nav nav-pills nav-justified" role="tablist">
                                                    <li class="nav-item waves-effect waves-light" role="presentation">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#home-1" role="tab" aria-selected="true">
                                                            <span class="d-block d-sm-none"><i class="bi bi-person"></i></span>
                                                            <span class="d-none d-sm-block">Аккаунт</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light" role="presentation">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#profile-1" role="tab" aria-selected="false" tabindex="-1">
                                                            <span class="d-block d-sm-none"><i class="bi bi-info-circle"></i></span>
                                                            <span class="d-none d-sm-block">Информация баланса</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light" role="presentation">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#messages-1" role="tab" aria-selected="false" tabindex="-1">
                                                            <span class="d-block d-sm-none"><i class="bi bi-wallet"></i></span>
                                                            <span class="d-none d-sm-block">Пополнения</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light" role="presentation">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#settings-1" role="tab" aria-selected="false" tabindex="-1">
                                                            <span class="d-block d-sm-none"><i class="bi bi-bank"></i></span>
                                                            <span class="d-none d-sm-block">Выводы</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content p-3 text-muted">
                                                    <div class="tab-pane active show" id="home-1" role="tabpanel">
                                                        <form>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Баланс</label>
                                                                        <input class="form-control" type="text" value="{{$user->balance}}" id="balance" placeholder="Баланс">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Демо баланс</label>
                                                                        <input class="form-control" type="text" value="{{$user->demo_balance}}" id="demo_balance" placeholder="Баланс">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Роль</label>
                                                                        <select class="form-control" id="admin" value="{{$user->admin}}">
                                                                            <option value="0" {{ $user->admin == 0 ? 'selected' : ''}}>Пользователь</option>
                                                                            <option value="1" {{ $user->admin == 1 ? 'selected' : ''}}>Администратор</option>
                                                                            <option value="2" {{ $user->admin == 2 ? 'selected' : ''}}>Модератор</option>
                                                                            <option value="3" {{ $user->admin == 3 ? 'selected' : ''}}>Ютубер</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer text-end">
                                                                @if($user->ban == 1)<button class="btn btn-danger btn-success" onclick="changeBan({{$user->id}}, 0)" type="button">Разблокировать</button>@else<button type="button" onclick="changeBan({{$user->id}}, 1)" class="btn btn-danger">Заблокировать</button>@endif
                                                                <button class="btn btn-danger" onclick="deleteUser({{$user->id}})" type="button">Удалить аккаунт</button>
                                                                <button class="btn btn-primary" onclick="saveUser({{$user->id}})" type="button">Сохранить</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="profile-1" role="tabpanel">
                                                    <div class="card-body">
                           

                            <div class="row">
                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-extension"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Пополнено средств :</p>
                                            <h6 class="text-truncate mb-0">{{$user->deps}} ₽</h6>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-pie-chart-alt-2"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Выводов на :</p>
                                            <h6 class="text-truncate mb-0">{{$user->withdraws}} ₽</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-down-arrow-alt"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Рефералов :</p>
                                            <h6 class="text-truncate mb-0">{{$user->refs}}</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-slideshow"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Демо баланс :</p>
                                            <h6 class="text-truncate mb-0">{{$user->demo_balance}} ₽</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-message-rounded-dots"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Всего побед :</p>
                                            <h6 class="text-truncate mb-0">{{$user->win_games}}</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-wallet-alt"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Всего поражений :</p>
                                            <h6 class="text-truncate mb-0">{{$user->lose_games}} ₽</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-buildings"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Макс.выигрыш :</p>
                                            <h6 class="text-truncate mb-0">{{$user->max_win}} ₽</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-sort-up"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Общий выигрыш :</p>
                                            <h6 class="text-truncate mb-0">{{$user->sum_win}} ₽</h6>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-6 col-md-4">
                                    <div class="d-flex mt-4">
                                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                <i class="bx bx-rocket"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-1">Сумма поставленных</p>
                                            <h6 class="text-truncate mb-0">{{$user->sum_bet}} ₽</h6>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="messages-1" role="tabpanel">
                                                        <table class="table " style="margin-bottom: 20px;">

                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Пользователь</th>
                                                                    <th scope="col">Система</th>
                                                                    <th scope="col">Сумма</th>

                                                                    <th scope="col">Дата</th>

                                                                    <th scope="col">Действия</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($data['deps'] as $d)
                                                                @php
                                                                $u = \App\User::where('id', $d->user_id)->first();
                                                                @endphp
                                                                <tr>
                                                                    <th scope="row">{{$d->id}}</th>
                                                                    <td><img src="{{$u->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3"><a href="{{$u->social}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a></td>
                                                                    <td><img src="../{{$d->img_system}}" style="width: 30px;"></td>
                                                                    <td>{{number_format($d->sum, 2, ',', ' ')}}</td>

                                                                    <td>{{date('d.m.y в H:i:s', strtotime($d->created_at))}}</td>

                                                                    <th scope="col">@if($d['status'] == 0)<button onclick="changePay({{$d->id}})" class="btn btn-info btn-sm">Зачислить депозит</button>@endif</th>

                                                                </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>

                                                        <div style="margin-bottom: 5px;">
                                                            {{ $data['deps']->links() }}
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="settings-1" role="tabpanel">
                                                        <div class="table-responsive add-project">

                                                            <table class="table " style="margin-bottom: 20px;">

                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Пользователь</th>
                                                                        <th scope="col">Система</th>
                                                                        <th scope="col">Сумма</th>
                                                                        <th scope="col">Кошелек</th>
                                                                        <th scope="col">Дата</th>
                                                                        <th scope="col">Действия</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($data['withdraws'] as $w)
                                                                    @php
                                                                    $u = \App\User::where('id', $w->user_id)->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <th scope="row">{{$w->id}}</th>
                                                                        <td><img src="{{$u->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3"><a href="{{$u->social}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a></td>
                                                                        <th scope="row">{{$w->ps}}</th>
                                                                        <td>{{number_format($w->sum, 2, ',', ' ')}}</td>
                                                                        <th scope="row">{{$w->wallet}}</th>
                                                                        <td>{{date('d.m.y в H:i:s', strtotime($w->created_at))}}</td>

                                                                        <th scope="col">@if($w['status'] == 0)<button onclick="changeWithdraw({{$w->id}}, 1)" class="btn btn-info btn-sm">Вывести</button>@endif</th>

                                                                    </tr>
                                                                    @endforeach

                                                                </tbody>
                                                            </table>

                                                       
                                                        </div>
                                                        <div style="margin-bottom: 5px;">
                                                                {{ $data['withdraws']->links() }}
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card -->



        </div>

    </div>
    <!-- end row -->

</div>





</div>
@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection