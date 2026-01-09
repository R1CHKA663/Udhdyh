<div class="mines d-flex justify-center" style="margin-top:35px">

	<div class="mines__wrapper d-flex justify-space-between align-start flex-wrap">
		<div class="mines__left d-flex flex-column justify-center ">
			<div class="gx-row" style="margin-bottom: 15px;">
				<div class="gx-con">
					<div class="icon lg" style="background: linear-gradient(180deg, #FF726C 0%, #D42D27 100%);">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
							<g clip-path="url(#clip0_560_70323)">
								<path d="M14.4602 4.33524C14.6799 4.11551 14.6799 3.75955 14.4602 3.53983C14.2405 3.3201 13.8845 3.3201 13.6648 3.53983L12.5398 4.66483L12.2102 4.99442L11.4829 4.2671C11.044 3.82765 10.331 3.82765 9.89209 4.2671L9.32959 4.8296C9.28526 4.87393 9.24859 4.9227 9.21322 4.97202C8.44896 4.67105 7.6198 4.50001 6.75 4.50001C3.02811 4.50001 0 7.52813 0 11.25C0 14.9719 3.02811 18 6.75 18C10.4719 18 13.5 14.9719 13.5 11.25C13.5 10.3802 13.329 9.55102 13.028 8.78679C13.0773 8.75139 13.1261 8.71476 13.1704 8.67042L13.7329 8.10792C14.1724 7.66875 14.1724 6.95627 13.7329 6.5171L13.0056 5.78979L13.3352 5.4602L14.4602 4.33524ZM6.75 7.87501C4.8889 7.87501 3.375 9.38895 3.375 11.25C3.375 11.5607 3.12314 11.8125 2.8125 11.8125C2.50186 11.8125 2.25 11.5607 2.25 11.25C2.25 8.76876 4.26874 6.75001 6.75 6.75001C7.06064 6.75001 7.3125 7.00187 7.3125 7.31251C7.3125 7.62315 7.06064 7.87501 6.75 7.87501Z" fill="white" />
								<path opacity="0.45" d="M16.1379 5.23758C15.9182 5.01785 15.5623 5.01785 15.3425 5.23758C15.1228 5.45731 15.1228 5.81326 15.3425 6.03299L15.905 6.59549C16.0149 6.70535 16.1588 6.7603 16.3028 6.7603C16.4467 6.7603 16.5906 6.70535 16.7005 6.59549C16.9202 6.37576 16.9202 6.01981 16.7005 5.80008L16.1379 5.23758Z" fill="white" />
								<path opacity="0.45" d="M11.9675 2.65799C12.0774 2.76785 12.2213 2.8228 12.3652 2.8228C12.5091 2.8228 12.6531 2.76785 12.7629 2.65799C12.9827 2.43826 12.9827 2.08231 12.7629 1.86258L12.2004 1.30008C11.9807 1.08035 11.6248 1.08035 11.405 1.30008C11.1853 1.51981 11.1853 1.87576 11.405 2.09549L11.9675 2.65799Z" fill="white" />
								<path opacity="0.45" d="M14.0625 2.25C14.3734 2.25 14.625 1.99814 14.625 1.6875V0.5625C14.625 0.251859 14.3734 0 14.0625 0C13.7516 0 13.5 0.251859 13.5 0.5625V1.6875C13.5 1.99814 13.7516 2.25 14.0625 2.25Z" fill="white" />
								<path opacity="0.45" d="M17.4375 3.3956H16.3125C16.0016 3.3956 15.75 3.64746 15.75 3.9581C15.75 4.26874 16.0016 4.5206 16.3125 4.5206H17.4375C17.7484 4.5206 18 4.26874 18 3.9581C18 3.64746 17.7484 3.3956 17.4375 3.3956Z" fill="white" />
								<path opacity="0.45" d="M15.3621 2.65799C15.4719 2.76785 15.6158 2.8228 15.7598 2.8228C15.9037 2.8228 16.0476 2.76785 16.1575 2.65799L17.2825 1.53299C17.5022 1.31326 17.5022 0.957306 17.2825 0.737579C17.0628 0.517853 16.7068 0.517853 16.4871 0.737579L15.3621 1.86258C15.1423 2.08231 15.1423 2.43826 15.3621 2.65799Z" fill="white" />
							</g>
							<defs>
								<clipPath id="clip0_560_70323">
									<rect width="18" height="18" fill="white" />
								</clipPath>
							</defs>
						</svg>
					</div>
					<div class="title">
						<span style="font-size: 14px;font-style: normal; font-weight: 700; line-height: 48px;">Mines</span>
					</div>
				</div>
			</div>
			<div class="bx-input d-flex flex-column">
				<div class="bx-input__input d-flex justify-space-between align-center">

					<input class="fullInputWidth" style="text-align: left;" placeholder="Введите сумму ставки" type="text" value="1.00" id="BetMines" onkeyup="updateMinesXNew()">
					<svg class="icon money">
						<use xlink:href="images/symbols.svg#coins"></use>
					</svg>

				</div>
				<div class="x30__bet-placed d-flex align-center justify-space-between">
					<a onclick="$('#BetMines').val(Number($('#BetMines').val()) + 10)">+10</a>
					<a onclick="$('#BetMines').val(Number($('#BetMines').val()) + 100)">+100</a>
					<a onclick="$('#BetMines').val(Number($('#BetMines').val()) + 1000)">+1000</a>
					<a onclick="$('#BetMines').val((Number($('#BetMines').val()) * 2).toFixed(2));updateMinesXNew()">x2</a>
					<a onclick="$('#BetMines').val(Math.max(($('#BetMines').val()/2), 1).toFixed(2));">1/2</a>
				</div>
			</div>
			<div class="bx-input">
				<div class="bx-input__input d-flex justify-space-between align-center">
					<label class="d-flex align-center">Кол-во бомб:</label>
					<div class="d-flex align-center">
						<input type="text" id="BombMines" onkeyup="updateMinesXNew()" value="3" style="width: 35px;text-align: center;padding-right: 8px;">
						<div class="mines__bomb Bomb d-flex align-center">
							<a class="mines__bomb--active bomb_3" onclick="$('#BombMines').val(3);$('.mines__bomb.Bomb a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateMinesXNew()">3</a>
							<a class="bomb_5" onclick="$('#BombMines').val(5);$('.mines__bomb.Bomb a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateMinesXNew()">5</a>
							<a class="bomb_10" onclick="$('#BombMines').val(10);$('.mines__bomb.Bomb a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateMinesXNew()">10</a>
							<a class="bomb_24" onclick="$('#BombMines').val(24);$('.mines__bomb.Bomb a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateMinesXNew()">24</a>
						</div>
					</div>

				</div>
			</div>

			<div class="bx-input">
				<input type="hidden" id="LevelMines" value="25" name="">
				<div class="bx-input__input d-flex justify-space-between align-center">
					<label class="d-flex align-center">Уровень:</label>
					<div class="mines__bomb Level d-flex align-center">
						<a class="level_16" onclick="$('#LevelMines').val(16);$('.mines__bomb.Level a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateLevel()">1</a>
						<a class="mines__bomb--active level_25" onclick="$('#LevelMines').val(25);$('.mines__bomb.Level a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateLevel()">2</a>
						<a class="level_36" onclick="$('#LevelMines').val(36);$('.mines__bomb.Level a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateLevel()">3</a>
						<a class="level_49" onclick="$('#LevelMines').val(49);$('.mines__bomb.Level a').removeClass('mines__bomb--active');$(this).addClass('mines__bomb--active');updateLevel()">4</a>
					</div>
				</div>
			</div>
			<div class="bx-input start_block_mine" style="display: none;">
				<a onclick="disable(this);startGameMineNew(this)" class="btn btn--blue d-flex align-center justify-center is-ripples flare"><span>Начать игру</span></a>

			</div>
			<div class="bx-input mines__buttons play_block_mine" style="display:none">
				<a onclick="disable(this);disable('.mines__path-item');finishGameMineNew(this)" class="btn btn--blue d-flex align-center justify-center is-ripples flare"><span>Забрать <span id="winMine">0.00</span></span></a>
				<a onclick="disable(this);autoClickMineNew(this)" class="btn d-flex align-center justify-center is-ripples flare" style=" border-radius: 10px;
    background: #F1AC44;
    color: #181B27;"><span>Авто-выбор</span></a>
			</div>
			<div class="bx-input">
				<div class="mines__x">
					<div class="mines__scroll d-flex align-center">

					</div>
				</div>
			</div>
		</div>
		<div class="mines__right">
			<div class="mines__path d-flex justify-space-between flex-wrap">

			</div>
		</div>
		<div class="x30__bonus mines__bonus d-flex align-center" style="display: none;">
			<div class="x30__bonus-cursor"></div>
			<div class="x30__bonus-scroll d-flex align-center">
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x30 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x30 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x30 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x2 d-flex align-center justify-center">x2</div>
				<div class="x30__bonus-item x3 d-flex align-center justify-center">x3</div>
				<div class="x30__bonus-item x5 d-flex align-center justify-center">x3</div>
			</div>
		</div>
	</div>
</div>
<div class="wrapper">
	@include('layouts.history')
</div>

<script type="text/javascript">
	createMinePole(25)
	updateMinesXNew()
	getGameMineNew()
</script>

@auth
<script type="text/javascript">
	socket.emit('subscribe', 'roomGame_4_{{\Auth::user()->id}}');
</script>
@endauth