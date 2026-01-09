<div class="wrapper">
	<style>
		.providersSlots {
			background: #181B27;
			border-radius: 12px;
			margin-top: -30px;
			padding: 40px 15px 70px 15px;
			margin-bottom: 15px;
			display: grid;
			grid-template-columns: repeat(6, 1fr);
			grid-column-gap: 10px;
			grid-row-gap: 10px;
			z-index: 1;
			position: relative;
		}
		}

		@media (max-width: 1200px) {
			.providersSlots {
				grid-template-columns: repeat(5, 1fr);
			}
		}

		@media (max-width: 650px) {
			.providersSlots {
				grid-template-columns: repeat(4, 1fr);
			}
		}

		@media (max-width: 450px) {
			.providersSlots {
				grid-template-columns: repeat(3, 1fr);
			}
		}

		@media (max-width: 370px) {
			.providersSlots {
				grid-template-columns: repeat(2, 1fr);
			}
		}

		.slots--notFound {
			grid-column: 1 / -1;
			text-align: center;
			padding: 40px;
			font-weight: 500;
			font-size: 18px;
		}

		.providersSlots::after {
			content: '';
			position: absolute;
			width: 100%;
			height: 55px;
			background: url(../images/shape-2.svg) no-repeat center center/contain;
			-webkit-transform: rotate(180deg);
			transform: rotate(360deg);
			bottom: 0;
		}

		.providersSlots .provider {
			background: #1b2030;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 10px;
			height: 75px;
			cursor: pointer;
		}

		.providersSlots .provider img {
			width: 100%;
			height: 100%;
			transition: .2s;
			object-fit: contain;
			filter: grayscale(3);
			opacity: .4;
		}

		.provider.active img {
			filter: grayscale(0);
			opacity: 1;
		}

		@media (max-width: 725px) {
			.btn-up {
				right: 20px;
			}
		}

		.slots__container {
			border-radius: 0px 0px 12px 12px;
  			background: #181B27;
		}

		.slotsLeftBox {
			display: flex;
			margin: 10px;
			align-content: center;
			align-items: center;
		}

		.slotsLeftBox img {
			width: 100%;
			height: 100%;
			border-radius: 15px;
			object-fit: cover;
		}

		.slotsLeftBox span {
			font-size: 1.25rem;
			font-weight: 700;
			margin-left: 10px;
		}

		.slotsLoad {
			grid-column: 1 / -1;
			height: 200px;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.headSlots {
			display: flex;
			align-items: center;
			border-radius: 12px 12px 0px 0px;
			background: #181B27;
			justify-content: space-between;
			height: 70px;
			padding: 10px;
			position: relative;
			z-index: 2;
			border-bottom: 1px solid #1C202F;
		}

		.searchSlots {
			display: flex;
			align-items: center;
			width: 240px;
			justify-content: space-between;
			height: 50px;
			padding: 0 20px;
			border-radius: 10px;
			background: #1C202F;
		}

		.searchSlots input {
			height: 40px;
			width: calc(100% - 3px);
			border: 0px;
			font-weight: 600;
			color: #fff;
			background-color: transparent;
		}

		.slotsLoad .wave {
			width: 2px;
			height: 100px;
			background: linear-gradient(45deg, #6080b0, #b7cdef);
			margin: 10px;
			animation: wave 1s linear infinite;
			/* border-radius: 20px; */
		}

		.slotsLoad .wave:nth-child(2) {
			animation-delay: 0.1s;
		}

		.slotsLoad .wave:nth-child(3) {
			animation-delay: 0.2s;
		}

		.slotsLoad .wave:nth-child(4) {
			animation-delay: 0.3s;
		}

		.slotsLoad .wave:nth-child(5) {
			animation-delay: 0.4s;
		}

		.slotsLoad .wave:nth-child(6) {
			animation-delay: 0.5s;
		}

		.slotsLoad .wave:nth-child(7) {
			animation-delay: 0.6s;
		}

		.slotsLoad .wave:nth-child(8) {
			animation-delay: 0.7s;
		}

		.slotsLoad .wave:nth-child(9) {
			animation-delay: 0.8s;
		}

		.slotsLoad .wave:nth-child(10) {
			animation-delay: 0.9s;
		}

		.icon slotsred {
			color: #fff;
			background: var(--XX5, linear-gradient(180deg, #812722 0%, #E54F49 100%));
		}

		.btnSlots {
			cursor: pointer;
			border: 0px;
			height: 50px;
			border-radius: 8px;
			padding: 0 20px;
			width: 240px;
			font-weight: 600;
			font-size: 14px;
			margin-left: 10px;
			background: linear-gradient(109.64deg, #397ce6 5.39%, #397ce6 63.15%);
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: .25s ease;
		}

		.btnSlots.active svg {
			transform: rotate(180deg);
		}

		.slots {
			display: -ms-grid;
			display: grid;
			-ms-grid-columns: (1fr)[5];
			grid-template-columns: repeat(5, 1fr);
			grid-gap: 16px;
			position: relative;
			margin-bottom: 25px;
			border-radius: 15px;
		}

		@media (max-width: 945px) {
			.slots {
				-ms-grid-columns: (1fr)[3];
				grid-template-columns: repeat(3, 1fr);
			}
		}

		@media (max-width: 580px) {
			.slots {
				-ms-grid-columns: (1fr)[2];
				grid-template-columns: repeat(2, 1fr);
			}
		}

		.slots_game {
			height: 100%;
			width: 100%;
			max-height: 280px;
			background: #1b2030;
			position: relative;
			border-radius: 15px;
			overflow: hidden;
			color: #fff;
			transition: all .3s cubic-bezier(0.39, 0.58, 0.57, 1);
		}

		.slot__animation__play svg {
			height: 60px;
			width: 60px;
			padding: 10px;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-right: -50%;
			transform: translate(-50%, -50%);
		}

		.slot__title {
			font-weight: 700;
			text-align: center;
			left: 50%;
			display: block;
			color: #fff;
			margin-top: 15px;
			font-size: 19px;
			position: absolute;
			top: 18%;
			margin-right: -50%;
			transform: translate(-50%, -50%);
		}

		.slot__titleProvider {
			font-weight: 700;
			text-align: center;
			left: 50%;
			display: block;
			color: #fff;
			margin-top: 15px;
			font-size: 19px;
			position: absolute;
			top: 68%;
			margin-right: -50%;
			transform: translate(-50%, -50%);
		}

		.slots_game:hover {
			transform: scaleX(1.05) scaleY(1.05);
		}

		.slots_game:hover .slot__animation__play {
			opacity: 1;
		}

		.slot__animation__play {
			position: absolute;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			opacity: 0;
			backdrop-filter: blur(3px);
			text-align: center;
			transition: all .2s ease;
			background-color: rgba(0, 0, 0, .3);
		}

		.slots_game img {
			pointer-events: none;
			height: 100%;
			width: 100%;
			border-radius: 15px;
		}

		.shape {
			position: relative;
			border-radius: 0px 0px 12px 12px;
			background: #181B27;
			padding: 10px 10px 10px;
		}

		.shape span {
			margin: 0;
			position: absolute;
			top: 20px;
			left: 50%;
			color: #6a809f;
			font-size: 20px;
			font-weight: 900;
			text-transform: uppercase;
			z-index: 1;
			margin-right: -50%;
			transform: translate(-50%, -50%);
		}
	</style>
	<div class="headSlots">
		<div class="gx-con ">
			<div class="icon lg" style="
    border-radius: 12px;
    background: var(--XX5, linear-gradient(180deg, #812722 0%, #E54F49 100%));
"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
					<path d="M4.21875 5.27344C5.02419 5.27344 5.73363 5.05357 6.31947 4.77301C5.7247 5.89211 5.36833 7.18753 5.29761 8.52347C2.91948 8.77876 1.05469 10.7739 1.05469 13.2188C1.05469 15.8358 3.1837 18 5.80078 18C6.60704 18 7.37938 17.7729 8.07207 17.3929C6.99747 16.3394 6.32812 14.839 6.32812 13.2188C6.32812 11.5985 6.99747 10.1332 8.07207 9.07979C7.53868 8.78714 6.9575 8.58815 6.35065 8.51083C6.57999 4.78345 9.39496 1.70892 13.0244 1.14793C12.1001 2.44569 11.6016 3.99422 11.6016 5.62006V8.52594C9.23497 8.79222 7.38281 10.7821 7.38281 13.2188C7.38281 15.8358 9.51183 18 12.1289 18C14.746 18 16.875 15.8358 16.875 13.2188C16.875 10.7821 15.0228 8.79222 12.6562 8.52594V5.62006C12.6562 3.83711 13.3504 2.16087 14.6111 0.900192L15.5113 0H14.2383C11.7614 0 9.51663 1.01006 7.89244 2.63933C7.46603 2.22995 6.09879 1.05469 4.21875 1.05469C1.99402 1.05469 0.471725 2.71239 0.398117 2.78297L0 3.16406L0.398117 3.54515C0.471725 3.61574 1.99402 5.27344 4.21875 5.27344ZM12.1289 11.6367C11.2566 11.6367 10.5469 12.3464 10.5469 13.2188H9.49219C9.49219 11.765 10.6751 10.582 12.1289 10.582V11.6367ZM5.80078 11.6367C4.92847 11.6367 4.21875 12.3464 4.21875 13.2188H3.16406C3.16406 11.765 4.34702 10.582 5.80078 10.582V11.6367Z" fill="white"></path>
				</svg></div>
			<div class="title">
				<div class="">
					<h4 style="font-size: 14px;">Слоты</h4>

				</div>
			</div>
		</div>
		<div class="searchSlots">
			<input type="text" onkeyup="searchSlot(this)" id="search-slots" placeholder="Поиск..." />
		</div>
		<button class="btnSlots btn is-ripples flare d-flex align-center has-ripple" data-color="#fff" data-opacity="0.1" data-duration="0.3" onclick="toggleProviders()" style="gap: 5px;">
			Провайдеры
			<svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 22 20" fill="none" style="
    width: 23.048px;
    height: 22px;
    border-radius: 6px;
    background: #4E8BF1;
">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M16.0081 7.71967C15.7013 7.42678 15.2038 7.42678 14.897 7.71967L11.0002 11.4393L7.10339 7.71967C6.79655 7.42678 6.29906 7.42678 5.99222 7.71967C5.68538 8.01257 5.68538 8.48744 5.99222 8.78033L10.4446 13.0303C10.7515 13.3232 11.249 13.3232 11.5558 13.0303L16.0082 8.78033C16.315 8.48744 16.315 8.01256 16.0081 7.71967Z" fill="white"></path>
			</svg>
		</button>
	</div>

	<div class="providersSlots" style="display: none">
		<div class="provider" data-provider="0" onclick="slotProvider(this)">
			<img src="/images/providers/allproviders.png">
		</div>
		<div class="provider" data-provider="12" onclick="slotProvider(this)">
			<h4>Netent</h4>
		</div>
		<div class="provider" data-provider="19" onclick="slotProvider(this)">
			<h4>YggDrasil</h4>
		</div>
		<div class="provider" data-provider="11" onclick="slotProvider(this)">
			<h4>Igrosoft</h4>
		</div>
		<div class="provider" data-provider="8" onclick="slotProvider(this)">
			<h4>Novomatic Deluxe</h4>
		</div>
		<div class="provider" data-provider="21" onclick="slotProvider(this)">
			<h4>BET IN HELL</h4>
		</div>
		<div class="provider" data-provider="16" onclick="slotProvider(this)">
			<h4>Belatra</h4>
		</div>
		<div class="provider" data-provider="17" onclick="slotProvider(this)">
			<h4>Unicum</h4>
		</div>
		<div class="provider" data-provider="13" onclick="slotProvider(this)">
			<h4>Megajack</h4>
		</div>
		<div class="provider" data-provider="9" onclick="slotProvider(this)">
			<h4>Playtech</h4>
		</div>
		<div class="provider" data-provider="20" onclick="slotProvider(this)">
			<h4>Play'n GO</h4>
		</div>
		<div class="provider" data-provider="15" onclick="slotProvider(this)">
			<h4>Erotic</h4>
		</div>
		<div class="provider" data-provider="18" onclick="slotProvider(this)">
			<h4>Microgaming</h4>
		</div>
		<div class="provider" data-provider="22" onclick="slotProvider(this)">
			<h4>Pragmatic Play</h4>
		</div>
	</div>
	<!--<div class="slotsLoad">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>-->
	<div class="shape">
		<div class="slots">
		</div>
	</div>
	<div class="slots__container" style="display: none;">
		<div style="display: flex;justify-content: space-between;align-items: center;">
			<div style="float: left;">
				<div class="slotsLeftBox">
					<div style="width: 4.563rem;height: 4.125rem;">
						<img id="imagesSlotsBox" src="/images/slots/bonus.png" />
					</div>
					<span id="nameSlotsBox"></span>
				</div>
			</div>
			<div style="float: right;">
				<a style="margin: 10px;" href="/slots" class="btn d-flex align-center has-ripple" data-color="#6080B0" data-opacity="0.3" data-duration="0.3">
					<div style="" class="btn__ico d-flex align-center justify-center">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10.3017 12L14.7682 6.64018C15.1218 6.2159 15.0644 5.58534 14.6402 5.23177C14.2159 4.87821 13.5853 4.93553 13.2318 5.35981L8.23175 11.3598C7.92272 11.7307 7.92272 12.2693 8.23175 12.6402L13.2318 18.6402C13.5853 19.0645 14.2159 19.1218 14.6402 18.7682C15.0644 18.4147 15.1218 17.7841 14.7682 17.3598L10.3017 12Z" fill="white" />
						</svg>
					</div>
					<div class="btn__content">
						<span>Назад</span>
					</div>
				</a>
			</div>
		</div>
		<div class="default-screen-slot" style="display: block">
			<iframe style="borer-color: #20273a;width: 100%; border-radius: 0px 0px 15px 15px;display: none" src="" id="frameslot" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true" align="center" height="669.6">
				Ваш браузер не поддерживает плавающие фреймы!
			</iframe>
		</div>
	</div>
	<script type="text/javascript">
		var slots_observe = 0;
		var slots_page = 1;
		var slots_timeout = null;

		function open_url_slot() {
			window.open($('button[data-url]').attr('data-url'))
		}

		function searchSlot(inp) {
			clearTimeout(slots_timeout)

			slots_timeout = setTimeout(() => {

				$('.slotsLoad').show()
				$('.slots').html('')
				slots_page = 1
				load_slots()

			}, 500);
		}

		function slotProvider(e) {
			let hasActive = $(e).hasClass('active')
			slots_page = 1

			$('.provider').removeClass('active')
			$('.slots').html('')
			$('.slotsLoad').show()

			if (hasActive) {
				return load_slots()
			}

			$(e).addClass('active')
			load_slots()
			//toggleProviders()
		}

		function toggleProviders() {
			$('.btnSlots').toggleClass('active').css({
				pointerEvents: 'none'
			})

			if ($('.btnSlots').hasClass('active')) {
				$('.providersSlots').slideDown(250)
			} else {
				$('.providersSlots').slideUp(250)
			}

			setTimeout(() => $('.btnSlots').css({
				pointerEvents: 'auto'
			}), 200)
		}

		function connectObserver() {
			slots_observe = 1

			var cb = function(entries, observer) {
				if (entries[0].isIntersecting) {
					load_slots()
				}
			};

			let target = document.querySelector('.slots_game:last-child')
			observer = new IntersectionObserver(cb);
			observer.observe(target)
		}

		function load_slots() {
			let attrs = {}
			let search = $('#search-slots').val()
			if (search) {
				attrs['search'] = search
			}

			$('.slots__container').hide()

			if (slots_observe) {
				observer.disconnect();
			}

			$.post("/slots/getGames", {
					_token: $('meta[name="csrf-token"]').attr("content"),
					page: slots_page,
					provider: $('.provider.active').attr('data-provider'),
					...attrs
				})
				.then(response => {
					$(".slotsLoad").hide()
					$(".slots").show()

					response.games.map(item => {
						$('.slots').append(
							getSlotItem(item)
						)
					})

					if (!response.games.length) {
						$('.slots').html(
							notFound()
						)
					}

					if (response.games.length == 30) {
						connectObserver()
					}
					slots_page++
				});
		}

		function getSlotItem({
			game_id,
			game_icon,
			title,
			provider
		}) {
			return `
		        <a class="slots_game" target="#" style="cursor: pointer;" onclick="openSlot(${game_id})">
		            <img src="${game_icon}" />
		            <div class="slot__animation__play">
		            <svg class="icon"><use xlink:href="/images/symbols.svg?v=1#icon-play"></use></svg>
		                <div class="slot__title">${title}</div>
		                <div class="slot__titleProvider">${provider.title}</div>
		            </div>
		        </a>
		    `
		}

		function notFound() {
			return `<div class="slots--notFound">Ничего не найдено</div>`
		}

		function openSlot(gameId) {
			$.post('/slots/getUrl', {
					_token: $('meta[name="csrf-token"]').attr("content"),
					gameId
				})
				.then(response => {
					if (response.error) {

						notification('error', response.message)
						return;
					}

					$('.slotsLoad').show()
					$(".shape, #frameslot").hide();
					$(".slots__container").show();
					$('.btnSlots').removeClass('active')
					$('.providersSlots').slideUp(250)
					$('#frameslot').show()
					$('#frameslot').css('border-color', '#1b2030')
					$('#balance').html('');
					$('#nameSlotsBox').html(response.name);
					$("#imagesSlotsBox").attr("src", response.image);
					$("#frameslot").attr("src", response.url);
					$('button[data-url]').attr('data-url', response.url)
					$('.slotsLoad').hide()
				})
		}

		load_slots();
	</script>
	<div class="btn-up" style="display:none">
		<div class="btn__ico d-flex align-center justify-center">
			<svg class="icon">
				<use xlink:href="../images/symbols.svg#arrow-up"></use>
			</svg>
		</div>
	</div>
</div>