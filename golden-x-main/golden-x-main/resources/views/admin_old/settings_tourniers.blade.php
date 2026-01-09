  <div class="content" >
 	<div class="flex " >
 		<div class="col" style="max-width: 100%;margin: 0px auto;">
 			<div class="flex no_padding wrap">
 				<div class="col">
 					<div class="flex no_padding wrap">
 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_site');">Настройки сайта</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;" >
 								<button class="btn-dep w-100" onclick="load('admin/settings_withdraw');">Настройки вывода</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_deps');">Настройки пополнения</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_bonus');">Настройки бонуса</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_random');">Настройки  Random.Org</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_partner');">Настройки сотрудничества</button>
 							</div>
 						</div>

 						<div class="col-lg-3">
 							<div class="content-area " style="min-height: 10px;">
 								<button class="btn-dep w-100" onclick="load('admin/settings_anti');">Настройки антиминуса</button>
 							</div>
 						</div>

                        <div class="col-lg-3">
                            <div class="content-area " style="min-height: 10px;">
                                <button class="btn-dep w-100" onclick="load('admin/settings_status');">Настройки привилегий</button>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="content-area " style="min-height: 10px;">
                                <button class="btn-auth w-100" onclick="load('admin/settings_tourniers');">Настройки турниров</button>
                            </div>
                        </div>
 					</div>
 					@php
 					$setting = \App\Setting::first();
 					$tourniers = \App\Tourniers::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
 					@endphp
 					<div class="flex no_padding wrap">
 						<div class="col-lg-5">
 							<div class="flex no_padding wrap" id="all_tourniers">
 								@foreach($tourniers as $t)
 									@php
 										$start = date("d.m H:i", $t->start);
 										$end = date("d.m H:i", $t->end);
 									@endphp
 									<div class="col">
 									<div class="content-area">
                                    <span class="text-secondary" style="font-size:16px">{{$t->name}}</span>
 										<div class="header_system mb-20" style="margin-top:10px;">
 											<div class="w-100 comm_w" style="text-align: left;"><span class="text-secondary">@if($t->status == 1) Активен @else Не активен @endif</span></div>
 											<div class="w-100 comm_w" style="text-align: center;"><span class="text-secondary">{{$start}} - {{$end}}</span></div>
 											<div class="w-100 comm_w" style="text-align: right;"><span class="text-secondary">{{$t->prize}} руб / {{$t->places}} места.</span></div>
 										</div>

 										<div class="flex no_padding wrap">
 											<div class="col-5"><button class="btn-dep w-100" onclick="deleteTournier('+e.id+')">Удалить</button></div>
 											<div class="col-5"><button class="btn-dep w-100" onclick="editTournier('+e.id+', `'+e.name+'`, `'+e.img+'`, `'+e.comm_percent+'`, `'+e.comm_rub+'`,  '+e.min_sum+')">Редактировать</button></div>
 										</div>
 									</div>
 								</div>
 								@endforeach
 							</div>
 							
 						</div>
 						<div class="col-lg-5">
 							<div class="content-area">
 								<span class="text-secondary" id="title_s_w">Добавление турниров</span>
 								<div class="flex no_padding wrap" style="margin-top: 20px;">
 									

                                    <input type="hidden" id="id_ww"  name="">

 									<div class="col mb-20">
 										<label>Название</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="" class="secodary_input" id="name_t" value="MINES BATTLE" name="">
 											</div>
 										</div>   
 									</div>

                                    <div class="col mb-20">
                                        <label>Мест</label>
                                        <div class="flex no_padding wrap">
                                            <div style="position:relative;margin-top: 10px;" class="col">
                                                <input type="" class="secodary_input" id="places_t" onkeyup="placesTourniers()" value="3" name="">
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="col">
                                    	<div class="flex no_padding wrap" id="plasec_input_t">
	                                    	<div class="col-5 mb-20">
		 										<label>Приз за 1 место</label>
		 										<div class="flex no_padding wrap">
		 											<div style="position:relative;margin-top: 10px;" class="col">
		 												<input type="" class="secodary_input" id="place_1_t" value="500" name="">
		 											</div>
		 										</div>
	 										</div>

	 										<div class="col-5 mb-20">
		 										<label>Приз за 2 место</label>
		 										<div class="flex no_padding wrap">
		 											<div style="position:relative;margin-top: 10px;" class="col">
		 												<input type="" class="secodary_input" id="place_2_t" value="300" name="">
		 											</div>
		 										</div>
	 										</div>

	 										<div class="col-5 mb-20">
		 										<label>Приз за 3 место</label>
		 										<div class="flex no_padding wrap">
		 											<div style="position:relative;margin-top: 10px;" class="col">
		 												<input type="" class="secodary_input" id="place_3_t" value="200" name="">
		 											</div>
		 										</div>
	 										</div>
 										</div>
                                    </div>
                                    
                                    <div class="col mb-20">
 										<label>Начало</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="datetime-local" class="secodary_input" id="start_t" value="" name="">
 											</div>
 										</div>
 									</div>

 									<div class="col mb-20">
 										<label>Конец</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="datetime-local" class="secodary_input" id="end_t" value="" name="">
 											</div>
 										</div>
 									</div>

 									<div class="col mb-20">
 										<label>Ссылка на игру</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="" class="secodary_input" id="class_t" value="mines" name="">
 											</div>
 										</div>
 									</div>

 									<div class="col mb-20">
 										<label>Название игры</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="" class="secodary_input" id="game_t" value="Mines" name="">
 											</div>
 										</div>
 									</div>

 									<div class="col mb-20">
 										<label>Номер игры</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<input type="" class="secodary_input" id="game_id_t" value="2" name="">
 											</div>
 										</div>
 									</div>

 									<div class="col mb-20">
 										<label>Описание</label>
 										<div class="flex no_padding wrap">
 											<div style="position:relative;margin-top: 10px;" class="col">
 												<textarea type="" rows="5"  class="secodary_input" style="height:auto;" id="desc_t" name="">Турнир по режиму Mines. Чем больше сумма общих выигрышей у вас будет на момент конца турнира, тем выше будет ваш приз.</textarea>
 											</div>
 										</div>
 									</div>

                                    
                                        <div class="col mb-20 buttons_s_w_1">
                                            <button class="btn-auth w-100" onclick="addTournier()">Добавить</button>
                                        </div>
                                    
                                        <div class="col-lg-5 mb-20 buttons_s_w_2" style="display: none;">
                                            <button class="btn-dep w-100" onclick="closeEditTournier()">Закрыть</button>
                                        </div>
                                        <div class="col-lg-5 mb-20 buttons_s_w_2" style="display: none;">
                                            <button class="btn-auth w-100" onclick="saveTournier()">Сохранить</button>
                                        </div>
                                    
 									

 								</div>
 							</div>

 						</div>
 					</div>

 				</div>
 			</div>
 		</div>
 	</div>
 </div>

 <script type="text/javascript">
 	function getTourniers() {
 		$.post('/tourniers/all',{_token: csrf_token}).then(e=>{
 			$('#all_systems').html('')
 			e.systems.forEach((e)=>{
 				if(e.comm_rub == 0){
 					comm_rub = '';
 				}else{
 					comm_rub = '+ '+e.comm_rub+' руб.';
 				}		
 				$('#all_systems').append('<div class="col-lg-5">\
 									<div class="content-area">\
                                    <span class="text-secondary" style="font-size:16px">'+e.name+'</span>\
 										<div class="header_system mb-20" style="margin-top:10px;">\
 											<img src="'+e.img+'">\
 											<div class="w-100 comm_w" style="text-align: right;"><span class="text-secondary">'+e.comm_percent+'% '+comm_rub+'</span></div>\
 											<div class="w-100 comm_w" style="text-align: right;"><span class="text-secondary">От '+e.min_sum+' руб.</span></div>\
 										</div>\
\
 										<div class="flex no_padding wrap">\
 											<div class="col-5"><button class="btn-dep w-100" onclick="deleteSystemWithdraw('+e.id+')">Удалить</button></div>\
 											<div class="col-5"><button class="btn-dep w-100" onclick="editSystemWithdraw('+e.id+', `'+e.name+'`, `'+e.img+'`, `'+e.comm_percent+'`, `'+e.comm_rub+'`,  '+e.min_sum+')">Редактировать</button></div>\
 										</div>\
 									</div>\
 								</div>')
 			});
 		});
 	}
 	// getTourniers()
 </script>
