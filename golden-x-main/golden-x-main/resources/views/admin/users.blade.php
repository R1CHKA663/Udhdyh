@php
$users = \App\User::paginate(15);
@endphp

@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')
<div class="row">
    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Всего пользователей</h6>

                <div class="row align-items-center gx-2">
                    <div class="col">
                        <span class="js-counter display-4 text-dark" data-value="{{\App\User::count()}}">{{\App\User::count()}}</span>

                    </div>
                    <!-- End Col -->

                    <div class="col-auto">
                        <span class="badge bg-soft-success text-success p-1">
                            <i class="bi-graph-up"></i> 5.0%
                        </span>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Active members</h6>

                <div class="row align-items-center gx-2">
                    <div class="col">
                        <span class="js-counter display-4 text-dark" data-value="12">12</span>
                        <span class="text-body fs-5 ms-1">from 11</span>
                    </div>

                    <div class="col-auto">
                        <span class="badge bg-soft-success text-success p-1">
                            <i class="bi-graph-up"></i> 1.2%
                        </span>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">New/returning</h6>

                <div class="row align-items-center gx-2">
                    <div class="col">
                        <span class="js-counter display-4 text-dark" data-value="56">56</span>
                        <span class="display-4 text-dark">%</span>
                        <span class="text-body fs-5 ms-1">from 48.7</span>
                    </div>

                    <div class="col-auto">
                        <span class="badge bg-soft-danger text-danger p-1">
                            <i class="bi-graph-down"></i> 2.8%
                        </span>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Card -->
    </div>

    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2">Active members</h6>

                <div class="row align-items-center gx-2">
                    <div class="col">
                        <span class="js-counter display-4 text-dark" data-value="28">28</span>
                        <span class="display-4 text-dark">%</span>
                        <span class="text-body fs-5 ms-1">from 28.6%</span>
                    </div>

                    <div class="col-auto">
                        <span class="badge bg-soft-secondary text-secondary p-1">0.0%</span>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Card -->
    </div>
</div>
<div class="card">
    <div class="card-header card-header-content-md-between">
        <div class="mb-2 mb-md-0">
            <form>
                <div class="input-group input-group-merge input-group-flush">
                    <div class="input-group-prepend input-group-text">
                        <i class="bi-search"></i>
                    </div>
                    <input id="datatableSearch" type="search" class="form-control" placeholder="Поиск игрока" aria-label="Search users">
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive datatable-custom position-relative">
        <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
            <div class="dt-buttons"> <button class="dt-button buttons-copy buttons-html5 d-none" tabindex="0" aria-controls="datatable" type="button"><span>Copy</span></button> <button class="dt-button buttons-excel buttons-html5 d-none" tabindex="0" aria-controls="datatable" type="button"><span>Excel</span></button> <button class="dt-button buttons-csv buttons-html5 d-none" tabindex="0" aria-controls="datatable" type="button"><span>CSV</span></button> <button class="dt-button buttons-pdf buttons-html5 d-none" tabindex="0" aria-controls="datatable" type="button"><span>PDF</span></button> <button class="dt-button buttons-print d-none" tabindex="0" aria-controls="datatable" type="button"><span>Print</span></button> </div>
            <div id="datatable_filter" class="dataTables_filter"><label>Search:<input type="search" class="" placeholder="" aria-controls="datatable"></label></div>
            <table id="datatable" class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer" data-hs-datatables-options="{
                   &quot;columnDefs&quot;: [{
                      &quot;targets&quot;: [0, 7],
                      &quot;orderable&quot;: false
                    }],
                   &quot;order&quot;: [],
                   &quot;info&quot;: {
                     &quot;totalQty&quot;: &quot;#datatableWithPaginationInfoTotalQty&quot;
                   },
                   &quot;search&quot;: &quot;#datatableSearch&quot;,
                   &quot;entries&quot;: &quot;#datatableEntries&quot;,
                   &quot;pageLength&quot;: 15,
                   &quot;isResponsive&quot;: false,
                   &quot;isShowPaging&quot;: false,
                   &quot;pagination&quot;: &quot;datatablePagination&quot;
                 }" role="grid" aria-describedby="datatable_info">
                <thead class="thead-light">
                    <tr role="row">
                        <th class="table-column-pe-0 sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 44.9219px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
                                <label class="form-check-label" for="datatableCheckAll"></label>
                            </div>
                        </th>
                        <th class="table-column-ps-0 sorting" style="width: 279.281px;">Имя игрока</th>
                        <th class="sorting" style="width: 199.656px;">Дата регистрации</th>
                        <th class="sorting" style="width: 175.656px;">Депозитов</th>
                        <th class="sorting" style="width: 151.094px;">Статус</th>
                        <th class="sorting" style="width: 200.312px;">IP</th>
                        <th class="sorting" style="width: 113.734px;">Роль</th>
                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 134.344px;"></th>
                    </tr>
                </thead>
                <tbody id="all_users">
                            @foreach($users as $u)
                            @php
                            $deps = \App\Payment::where('user_id', $u->id)->where('status', 1)->sum('sum');
                            $withdraws = \App\Withdraw::where('user_id', $u->id)->where('status', 1)->sum('sum');
                            @endphp
                    <tr role="row" class="odd">
                        <td class="table-column-pe-0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll1">
                                <label class="form-check-label" for="datatableCheckAll1"></label>
                            </div>
                        </td>
                        <td class="table-column-ps-0">
                            <a class="d-flex align-items-center"  href="user/{{$u->id}}" @if($u->admin == 1) class="text-danger" @endif>
                                <div class="avatar avatar-circle">
                                    <img class="avatar-img" src="{{$u->avatar}}" alt="Image Description">
                                </div>
                                <div class="ms-3">
                                    <span class="d-block h5 text-inherit mb-0">{{$u->name}} #{{$u->id}}</span>
                                    <span class="d-block fs-5 text-body">{{$u->email}}</span>
                                </div>
                            </a>
                        </td>
                        <td>
                            <span class="d-block h5 mb-0">Баланс: {{$u->balance}} RUB</span>
                            <span class="d-block fs-5">{{date('d.m.y в H:i:s', strtotime($u->created_at))}}</span>
                        </td>
                        <td>{{number_format($deps, 2, ',', ' ')}}</td>
                        <td>
                            <span class="legend-indicator bg-success"></span>Активный
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fs-5 me-2">{{$u->ip}}</span>
                            </div>
                        </td>
                        <td>
                        @if($u->admin == 1)
                        <span class="badge bg-danger rounded-pill">Администратор</span>
                        @else
                        <span class="badge bg-primary rounded-pill">Пользователь</span>
                        @endif
                        @if($u->admin == 2)
                        <span class="badge bg-danger rounded-pill">Модератор</span>
                        @else
                        @endif
                    </td>
                        <td>
                            <a type="button" class="btn btn-white btn-sm" href="user/{{$u->id}}">
                                <i class="bi-pencil-fill me-1"></i> Перейти в профиль
                             </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
            <div class="col-sm-auto">
                <div class="d-flex justify-content-center justify-content-sm-end">
                <div style="margin-bottom: 5px;">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  </div>
@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection