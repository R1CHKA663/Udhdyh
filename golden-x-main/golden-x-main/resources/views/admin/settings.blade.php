@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')


@php
$setting = \App\Setting::first();
@endphp

<!-- Nav -->
<div class="text-center">
  <ul class="nav nav-segment nav-pills mb-7" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="nav-one-eg1-tab" href="#nav-one-eg1" data-bs-toggle="pill" data-bs-target="#nav-one-eg1" role="tab" aria-controls="nav-one-eg1" aria-selected="true">Настройка сайта</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="nav-two-eg1-tab" href="#nav-two-eg1" data-bs-toggle="pill" data-bs-target="#nav-two-eg1" role="tab" aria-controls="nav-two-eg1" aria-selected="false">Настройка ботов</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="nav-three-eg1-tab" href="#nav-three-eg1" data-bs-toggle="pill" data-bs-target="#nav-three-eg1" role="tab" aria-controls="nav-three-eg1" aria-selected="false">Настройка платёжек</a>
    </li>
  </ul>
</div>
<!-- End Nav -->

<!-- Tab Content -->
<div class="tab-content">
  <div class="tab-pane fade show active" id="nav-one-eg1" role="tabpanel" aria-labelledby="nav-one-eg1-tab">
  <div class="col-lg-12">
            <div id="checkoutStepFormContent">
              <!-- Card -->
              <div id="checkoutStepDelivery" class="active">
                <div class="card mb-3 mb-lg-5">
                  <!-- Header -->
                  <div class="card-header">
                    <h4 class="card-header-title">Настройка сайта</h4>
                  </div>
                  <!-- End Header -->

                  <!-- Body -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <!-- Form -->
                        <div class="mb-4">
                          <label for="firstNameDeliveryAddressLabel" class="form-label">Название сайта</label>
                          <input type="text" class="form-control"  id="name" value="{{$setting->name}}" placeholder="Название сайта" aria-label="Clarice">
                        </div>
                        <!-- End Form -->
                      </div>

                      <div class="col-sm-6">
                        <!-- Form -->
                        <div class="mb-4">
                          <label for="lastNameDeliveryAddressLabel" class="form-label">Бонус за регистрацию</label>
                          <input type="text" class="form-control" id="bonus_reg" value="{{$setting->bonus_reg}}" placeholder="Бонус за регистрацию" aria-label="Boone">
                        </div>
                        <!-- End Form -->
                      </div>
                    </div>
                    <!-- End Row -->

                    <div class="row">
                      <div class="col-sm-6">
                        <!-- Form -->
                        <div class="mb-4">
                          <label for="emailDeliveryAddressLabel" class="form-label">Бонус за подписку на группу ВК и ТГ</label>
                          <input type="text" class="form-control"  id="bonus_group" value="{{$setting->bonus_group}}" placeholder="Бонус за подписку на группу ВК и ТГ">
                        </div>
                        <!-- End Form -->
                      </div>

                       <div class="col-sm-6">
                        <!-- Form -->
                        <div class="mb-4">
                          <label for="lastNameDeliveryAddressLabel" class="form-label">Максимальный вывод с бонуса</label>
                          <input type="text" class="form-control" name="lastNameDeliveryAddress" id="max_withdraw_bonus" value="{{$setting->max_withdraw_bonus}}" placeholder="Максимальный вывод с бонуса" aria-label="Boone">
                        </div>
                        <!-- End Form -->
                      </div>
               
                    </div>
                    <!-- End Row -->

                    <div class="row">
                      <div class="col-sm-8">
                        <!-- Form -->
                        <div class="mb-4">
                          <label for="deliveryStreetAddressLabel" class="form-label">Мета-теги</label>
                          <input type="text" class="form-control" name="deliveryStreetAddress w-100" id="deliveryStreetAddressLabel" value="{{$setting->meta_tags}}" placeholder="Мета-теги" aria-label="470, Lucy Forks Street">
                        </div>
                        <!-- End Form -->
                      </div>
                    </div>
                    <div class="col-lg">
                            
                            <button onclick="saveSetting(1)" class="btn btn-primary w-100">Сохранить</button>
                        </div>
                  </div>
                  <!-- Body -->
                </div>
       

             
              </div>


            </div>
          </div>
  </div>

  <div class="tab-pane fade" id="nav-two-eg1" role="tabpanel" aria-labelledby="nav-two-eg1-tab">
  <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                <h3>Настройки вк</h3>
                <div class="col-lg-12 mb-3">
                            <label>Айди группы вк</label>
                            <input type="" class="form-control" id="group_id" value="{{$setting->group_id}}" name="">
                        </div>
                        <div class="col-lg-12 ">
                            <label>Токен группы вк</label>
                            <input type="" class="form-control" id="group_token" value="{{$setting->group_token}}" name="">
                        </div>
                       <hr>
                       <h3>Настройки ТГ</h3>
                        <div class="col-lg-12 mb-3">
                            <label>Канал тг</label>
                            <input type="" class="form-control" id="tg_id" value="{{$setting->tg_id}}" name="">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Бот тг</label>
                            <input type="" class="form-control" id="tg_bot_id" value="{{$setting->tg_bot_id}}" name="">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Токен бота тг</label>
                            <input type="" class="form-control" id="tg_token" value="{{$setting->tg_token}}" name="">
                        </div>
                        <div class="col-lg">
                            
                            <button onclick="saveSetting(1)" class="btn btn-info btn-block w-100">Сохранить</button>
                        </div>
                </div>
            </div>
        </div>
  </div>

  <div class="tab-pane fade" id="nav-three-eg1" role="tabpanel" aria-labelledby="nav-three-eg1-tab">
  <div class="row">


<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы FreeKassa</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>FK ID</label>
                    <input type="" class="form-control" id="fk_id" value="{{$setting->fk_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>FK SECRET 1</label>
                    <input type="" class="form-control" id="fk_secret_1" value="{{$setting->fk_secret_1}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>FK SECRET 2</label>
                    <input type="" class="form-control" id="fk_secret_2" value="{{$setting->fk_secret_2}}" name="">
                </div>
                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(2)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы Piastrix</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>Piastix ID</label>
                    <input type="" class="form-control" id="piastrix_id" value="{{$setting->piastrix_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Piastix SECRET</label>
                    <input type="" class="form-control" id="piastrix_secret" value="{{$setting->piastrix_secret}}" name="">
                </div>

                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(3)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы Primepayments</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>ID проекта</label>
                    <input type="" class="form-control" id="prime_id" value="{{$setting->prime_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SECRET 1</label>
                    <input type="" class="form-control" id="prime_secret_1" value="{{$setting->prime_secret_1}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SECRET 2</label>
                    <input type="" class="form-control" id="prime_secret_2" value="{{$setting->prime_secret_2}}" name="">
                </div>
                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(4)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы Linepay</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>ID проекта</label>
                    <input type="" class="form-control" id="linepay_id" value="{{$setting->linepay_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SECRET 1</label>
                    <input type="" class="form-control" id="linepay_secret_1" value="{{$setting->linepay_secret_1}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SECRET 2</label>
                    <input type="" class="form-control" id="linepay_secret_2" value="{{$setting->linepay_secret_2}}" name="">
                </div>
                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(5)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы Paypaylych</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>ID проекта</label>
                    <input type="" class="form-control" id="paypaylych_id" value="{{$setting->paypaylych_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Токен</label>
                    <input type="" class="form-control" id="paypaylych_token" value="{{$setting->paypaylych_token}}" name="">
                </div>
                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(6)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>


            </div>
        </div>
    </div>
</div>

<!--<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <h3>Настройки платежной системы AezaPay</h3>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>ID проекта</label>
                    <input type="" class="form-control" id="aezapay_id" value="{{$setting->aezapay_id}}" name="">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Private Key</label>
                    <input type="" class="form-control" id="aezapay_token" value="{{$setting->aezapay_token}}" name="">
                </div>
                <div class="col-lg">
                    <label>Действие</label>
                    <button onclick="saveSetting(7)" class="btn btn-info btn-block w-100">Сохранить</button>
                </div>


            </div>
        </div>
    </div>
</div> -->

</div>
  </div>
</div>
<!-- End Tab Content -->





@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection