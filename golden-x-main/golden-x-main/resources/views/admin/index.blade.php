@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection


@section('content')

<div class="page-header">
        <div class="row align-items-center">
          <div class="col">
            <h1 class="page-header-title">Статистика</h1>
          </div>
          <!-- End Col -->

          <div class="col-auto">
            <a class="btn btn-primary" href="javascript:;" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
              <i class="bi-person-plus-fill me-1"></i> Подробная информация статистики
            </a>
          </div>
          <!-- End Col -->
        </div>
        <!-- End Row -->
      </div>
      
<div class="row">
        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
          <!-- Card -->
          <a class="card card-hover-shadow h-100" href="#">
            <div class="card-body">
              <h6 class="card-subtitle"><i class="bi bi-cash-stack nav-icon"></i> Баланс RubPay</h6>

              <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                  <h2 class="card-title text-inherit" id="rubpay_bal">... RUB</h2>
                </div>

              </div>
              <!-- End Row -->

            
              <span class="badge bg-soft-danger text-danger">Баланс обновляется сразу</span>
            </div>
          </a>
          <!-- End Card -->
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
          <!-- Card -->
          <a class="card card-hover-shadow h-100" href="#">
            <div class="card-body">
              <h6 class="card-subtitle"><i class="bi bi-cash-stack nav-icon"></i> Баланс FreeKassa</h6>

              <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                  <h2 class="card-title text-inherit" id="fk_bal">... RUB</h2>
                </div>
     
              </div>
              <!-- End Row -->

              <span class="badge bg-soft-danger text-danger">Баланс обновляется сразу</span>
            </div>
          </a>
          <!-- End Card -->
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
          <!-- Card -->
          <a class="card card-hover-shadow h-100" href="#">
            <div class="card-body">
              <h6 class="card-subtitle"><i class="bi bi-back nav-icon"></i> Пополнений за сегодня</h6>

              <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                  <h2 class="card-title text-inherit" id="deposits">...</h2>
                </div>
                <!-- End Col -->
              </div>
              <!-- End Row -->

             
              <span class="badge bg-soft-danger text-danger">Всего пополнений за сегодня</span>
            </div>
          </a>
          <!-- End Card -->
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
          <!-- Card -->
          <a class="card card-hover-shadow h-100" href="#">
            <div class="card-body">
              <h6 class="card-subtitle"><i class="bi bi-wallet nav-icon"></i> Выводов за сегодня</h6>

              <div class="row align-items-center gx-2 mb-1">
                <div class="col-6">
                  <h2 class="card-title text-inherit" id="withdraws">...</h2>
                </div>
                <!-- End Col -->


              </div>
              <!-- End Row -->

              <span class="badge bg-soft-danger text-danger">Всего выводов за сегодня</span>
            </div>
          </a>
          <!-- End Card -->
        </div>
      </div>

<div class="row">
    <div class="col-xl-4">
    <div class="col-12 mb-3">
                  <div class="card bg-body-tertiary dark__bg-opacity-50 shadow-none">
                    <div class="bg-holder bg-card d-none d-sm-block" style="background-image:url(https://prium.github.io/falcon/v3.18.0/assets/img/illustrations/ticket-bg.png);"></div><!--/.bg-holder-->
                    <div class="d-flex align-items-center z-1 p-0"><img src="https://prium.github.io/falcon/v3.18.0/assets/img/illustrations/ticket-welcome.png" alt="" width="96">
                      <div class="ms-n3">
                        <h6 class="mb-1 text-primary">Добро пожаловать, {{ucfirst(Auth::user()->name)}} </h6>
                        <h4 class="mb-0 text-primary fw-bold">GoldenX<span class="text-info fw-medium"> admin panel</span></h4>
                      </div>
                    </div>
                  </div>
                </div>
        <div class="card">
            <div class="card-body">

                <div class="d-flex align-start">
                    <div class="flex-grow-1">
                        <h4 class="card-title ">Последние депозиты</h4>
                    </div>
                    <a href="/admin/deps/1">Смотреть все</a>
                </div>

                <div class="card-body card-body-height">
                            @php
                            $deps = \App\Payment::orderBy('id', 'desc')->limit(8)->get();
                            @endphp

                            @foreach($deps as $d)
                            @php
                            $u = \App\User::where('id', $d->user_id)->first();
                            @endphp
              <ul class="list-group list-group-flush list-group-no-gutters">
            
                <!-- List Item -->
                <li class="list-group-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                    <img class="avatar avatar-sm avatar-circle" src="{{$u->avatar}}" alt="Image Description">
                    </div>

                    <div class="flex-grow-1 ms-3">
                      <div class="row">
                        <div class="col-7 col-md-5 order-md-1">
                          <h5 class="mb-0"><a href="/admin/user/{{$u->id}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a></h5>
                          <span class="fs-6 text-body">Айди пользователя: {{$u->id}}</span>
                        </div>

                        <div class="col-5 col-md-4 order-md-3 text-end mt-2 mt-md-0">
                          <h5 class="mb-0">{{number_format($d->sum, 2, ',', ' ')}}₽</h5>
                          <span class="fs-6 text-body">{{$d->data}}</span>
                        </div>

                        <div class="col-auto col-md-3 order-md-2">
                        @if($d->status == 0)
                          <span class="badge bg-soft-warning text-warning rounded-pill">Ожидание</span>
                        @else
                        <span class="badge bg-soft-success text-success rounded-pill">Успешно</span>
                        @endif
                        </div>
                        
               
                      </div>
                      <!-- End Row -->
                    </div>
                  </div>
                </li>
      
              </ul>
              @endforeach
            </div>

            </div>
        </div>
        <br>

        <div class="card">
            <div class="card-body">
                <div class="flex-grow-1">
                    <h4 class="card-title ">Топ 13 богатых</h4>
                </div>
                <hr>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Пользователь</th>
                                <th class="align-middle">Монет</th>
                            </tr>
                        </thead>
                        @php
                        $user_bogat = \App\User::orderBy('balance', 'desc')->where('admin', '!=', 1)->limit(13)->get();
                        @endphp
                        @foreach($user_bogat as $u)
                        <tbody>
                            <tr>
                                <td><img src="{{$u->avatar}}" style="width:30px;height:30px;border-radius: 100%" class="me-3">
                                    <a href="/admin/user/{{$u->id}}" target="_blank" @if($u->admin == 1) class="text-danger" @endif>{{$u->name}}</a>
                                </td>
                                <td>{{$u->balance}} <img src="img/coin.svg?v=5" style="position: relative;transform: translate(1px, 3px);"></td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="col-xl-8">
    <div class="row">
                                  
                                  
                               
                                </div>
        <div class="row">
    



         
            @php
            $deps = \App\Payment::where('status', 1)->sum('sum');
            $withdraws = \App\Withdraw::where('status', 1)->sum('sum');
            $profit = round($deps - $withdraws ,2);

            $deps_today = \App\Payment::where('status', 1)->whereDate('created_at', \Carbon\Carbon::today())->sum('sum');
            $withdraws_today = \App\Withdraw::where('status', 1)->whereDate('created_at', \Carbon\Carbon::today())->sum('sum');
            $profit_today = round($deps_today - $withdraws_today ,2);

            $deps_yesterday = \App\Payment::where('status', 1)->whereDate('created_at', \Carbon\Carbon::yesterday())->sum('sum');
            $withdraws_yesterday = \App\Withdraw::where('status', 1)->whereDate('created_at', \Carbon\Carbon::yesterday())->sum('sum');
            $profit_yesterday = round($deps_yesterday - $withdraws_yesterday ,2);


            $deps_week = \App\Payment::where('status', 1)->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->sum('sum');
            $withdraws_week = \App\Withdraw::where('status', 1)->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->sum('sum');
            $profit_week = round($deps_week - $withdraws_week ,2);

            $deps_month = \App\Payment::where('status', 1)->whereMonth('created_at', \Carbon\Carbon::now()->month)->sum('sum');
            $withdraws_month = \App\Withdraw::where('status', 1)->whereMonth('created_at', \Carbon\Carbon::now()->month)->sum('sum');
            $profit_month = round($deps_month - $withdraws_month ,2);

            $deps_year = \App\Payment::where('status', 1)->whereYear('created_at', \Carbon\Carbon::now()->year)->sum('sum');
            $withdraws_year = \App\Withdraw::where('status', 1)->whereYear('created_at', \Carbon\Carbon::now()->year)->sum('sum');
            $profit_year = round($deps_year - $withdraws_year ,2);

            @endphp
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><i class="bi bi-cash-stack nav-icon"></i> Профит</p>
                                <h4 class="mb-0">{{$profit}}₽</h4>
                            </div>

                     
                        </div>
                    </div>
                </div>
            </div>
            <br>
           
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><i class="bi bi-browser-firefox nav-icon"></i> Онлайн сайта</p>
                                <h4 class="mb-0 online">0</h4>
                            </div>

                       
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium"><i class="bi bi-cash-stack nav-icon"></i> Выводов всего</p>
                                <h4 class="mb-0">{{$withdraws}} ₽</h4>
                            </div>

                          
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <!-- end row -->
    <br>
        <div class="card">
            <div class="card-body" style="position: relative;">
                <div class="card-body chartAdmin1">


                    <div class="d-sm-flex flex-wrap">
                        <h4 class="card-title mb-4">Статистика</h4>
                        <div class="ms-auto">
                            <ul class="nav nav-pills stat-pills">
                                <li class="nav-item">
                                    <a class="nav-link active first" onclick="statUpdate(1, this)">За день</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="statUpdate(2, this)">За неделю</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="statUpdate(3, this)">За месяц</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" onclick="statUpdate(4, this)">За год</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="chart1" class="apex-charts" dir="ltr"></div>

            </div>
        </div>

<br>
        <div class="card">
            <div class="card-body" style="position: relative;">
                <div class="card-body chartAdmin2">
                    <div class="d-sm-flex flex-wrap">
                        <h4 class="card-title mb-4">Статистика профита</h4>
                    </div>

                    <div id="chart2" class="apex-charts" dir="ltr"></div>
                </div>



            </div>
        </div>



    </div>
    <!-- end modal -->

    @endsection
    @section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- dashboard init -->
    <script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.post('/admin/getBalance').then((res) => {
                $('#fk_bal').html(res.fk_bal + '₽')
                $('#rubpay_bal').html(res.rubpay_bal + '₽')
            })
        })

        $('#deposits').html('')
        $('#withdraws').html('')
        $('#profit').html('')
        statUpdate(1, '.first')
    </script>

    <script type="text/javascript">
        new Chart(document.getElementById("today-profit-chart"), {
            type: 'line',
            data: {
                labels: ['00:00', '01:00', '02:00', 1750, 1800, 1850, 1900, 1950, 1999, 2050],
                datasets: [{
                    data: [0, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                    label: "Пополнения",
                    borderColor: "#73ff4f",
                    fill: false
                }, {
                    data: [0, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                    label: "Выводы",
                    borderColor: "#ff523b",
                    fill: false
                }, {
                    data: [0, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                    label: "Профит",
                    borderColor: "#3cba9f",
                    fill: false
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Статистика за сегодня'
                }
            }
        });


        new Chart(document.getElementById("yesterday-profit-chart"), {
            type: 'line',
            data: {
                labels: ['00:00', '01:00', '02:00', 1750, 1800, 1850, 1900, 1950, 1999, 2050],
                datasets: [{
                    data: [0, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                    label: "Пополнения",
                    borderColor: "#73ff4f",
                    fill: false
                }, {
                    data: [0, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                    label: "Выводы",
                    borderColor: "#ff523b",
                    fill: false
                }, {
                    data: [0, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                    label: "Профит",
                    borderColor: "#3cba9f",
                    fill: false
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Статистика за вчерв'
                }
            }
        });


        new Chart(document.getElementById("week-profit-chart"), {
            type: 'line',
            data: {
                labels: ['00:00', '01:00', '02:00', 1750, 1800, 1850, 1900, 1950, 1999, 2050],
                datasets: [{
                    data: [0, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                    label: "Пополнения",
                    borderColor: "#73ff4f",
                    fill: false
                }, {
                    data: [0, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                    label: "Выводы",
                    borderColor: "#ff523b",
                    fill: false
                }, {
                    data: [0, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                    label: "Профит",
                    borderColor: "#3cba9f",
                    fill: false
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Статистика за неделю'
                }
            }
        });


        new Chart(document.getElementById("month-profit-chart"), {
            type: 'line',
            data: {
                labels: ['00:00', '01:00', '02:00', 1750, 1800, 1850, 1900, 1950, 1999, 2050],
                datasets: [{
                    data: [0, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                    label: "Пополнения",
                    borderColor: "#73ff4f",
                    fill: false
                }, {
                    data: [0, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                    label: "Выводы",
                    borderColor: "#ff523b",
                    fill: false
                }, {
                    data: [0, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                    label: "Профит",
                    borderColor: "#3cba9f",
                    fill: false
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Статистика за месяц'
                }
            }
        });
    </script>
    <div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteUserModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="inviteUserModalLabel">Статистика сайта</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <!-- Form -->
          <div class="mb-4">
          <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Пользователей всего</label>
                          <input type="text" class="form-control" value="{{\App\User::count()}}" disabled aria-label="Clarice">
                        </div>
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Забаненных</label>
                          <input type="text" class="form-control" value="{{\App\User::where('ban', 1)->count()}}" disabled aria-label="Clarice">
                        </div>
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Профит</label>
                          <input type="text" class="form-control" value="{{$profit}}₽" disabled aria-label="Clarice">
                        </div>
                     
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Пополнений всего</label>
                          <input type="text" class="form-control" value="{{$deps}}₽" disabled aria-label="Clarice">
                        </div>
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Выводов всего</label>
                          <input type="text" class="form-control" value="{{$withdraws}} ₽" disabled aria-label="Clarice">
                        </div>
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Профит за сегодня</label>
                          <input type="text" class="form-control" value="{{$withdraws_today}}₽" disabled aria-label="Clarice">
                        </div>
                     
            
          </div>
          <!-- End Form -->

       
       
        <!-- End Body -->


      </div>
    </div>
  </div>
    @endsection