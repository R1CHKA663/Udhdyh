@extends('admin.layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')




<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-3">
						<label>Название промокода</label>
						<input type="" id="name_promo" class="form-control" name="">
					</div>
					<div class="col-lg-3">
						<label>Сумма</label>
						<input type="" id="sum_promo" class="form-control" name="" value="10">
					</div>
					<div class="col-lg-3">
						<label>Активаций</label>
						<input type="" id="active_promo" class="form-control" name="" value="1">
					</div>
					<div class="col-lg-3">
						<label></label>
						<button onclick="createPromo();generate();" class="btn btn-soft-success w-100 btn-sml">Создать промокод</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			function generate() {
       var abc = "abcdefghijklmnopqrstuvwxyz0123456789";
       var rs = "";
       while (rs.length < 6) {
         rs += abc[Math.floor(Math.random() * abc.length)];
       }
       var promo = rs.toUpperCase();
       $('#name_promo').val('GX_' + promo);
     }
		 generate();
		</script>
	</div>
	
</div>
<br>
<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Созданные промокоды</h4>
  </div>

  <!-- Table -->
  <div class="table-responsive datatable-custom">
    <table class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
           data-hs-datatables-options='{
                   "order": [],
                   "isResponsive": false,
                   "isShowPaging": false,
                   "pagination": "datatableWithPaginationPagination"
                 }'>
      <thead class="thead-light">
      <tr>
        
        <th>Создатель</th>
        <th>Промокод</th>
        <th>Активаций</th>
        <th>Действие</th>
      </tr>
      </thead>

      <tbody>
      @foreach($data['promo'] as $p)
							@php
								$active = \Cache::get('promo.name.'.$p->name.'.active');
						        $actived = \Cache::get('promo.name.'.$p->name.'.active.count');
						        $sum = \Cache::get('promo.name.'.$p->name.'.sum');
							@endphp
      <tr>
        <td>
      <a class="d-flex align-items-center">
      <div class="avatar avatar-soft-primary avatar-circle">
                            <span class="avatar-initials">{{$p->id}}</span>
                          </div>
        <div class="ms-3">
          <span class="d-block h5 text-inherit mb-0">{{$p->user_name}} </span>
          <span class="d-block fs-5 text-body">Дата создания: {{date('d.m.y в H:i:s', strtotime($p->created_at))}}</span>
        </div>
      </a>
    </td>
        <td>
          <span class="d-block h5 mb-0">{{$p->name}}</span>
          <span class="d-block fs-5">На {{number_format($sum, 2, ',', ' ')}} RUB</span>
        </td>
        <td>{{$actived}} / {{$active}}</td>
        <td>
        <button onclick="deletePromo({{$p->id}})" class="btn btn-soft-danger btn-sm">Удалить</button>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <!-- End Table -->

  <!-- Footer -->
  <div class="card-footer">
    <!-- Pagination -->
    <div class="d-flex justify-content-center justify-content-sm-end">
    <div style="margin-bottom: 5px;">
						{{ $data['promo']->links() }}
					</div>

    </div>
    <!-- End Pagination -->
  </div>
  <!-- End Footer -->
</div>

@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="/assets/js/pages/dashboard.init.js?v={{time()}}"></script>
@endsection
