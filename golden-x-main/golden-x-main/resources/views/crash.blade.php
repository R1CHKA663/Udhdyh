<script type="text/javascript" src="js/chart.min.js"></script>
<div class="wrapper">
    <div style="margin-top: 35px;" class="crash">
        <div class="crash__top d-flex align-stretch justify-space-between">
            <div class="crash__left d-flex flex-column">
            <div class="gx-row" style="margin-bottom: 15px;">
				 <div class="gx-con">
					 <div class="icon lg" style="background: linear-gradient(180deg, #629BFA 0%, #1D58BA 100%);">
                     <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
  <g clip-path="url(#clip0_533_24776)">
    <path d="M16.6404 0.0326119C13.5313 -0.0232006 9.98514 1.60314 7.7402 4.12739C5.5948 4.16827 3.50614 5.04821 1.96802 6.58786C1.92413 6.6313 1.89317 6.68607 1.87858 6.74607C1.86399 6.80607 1.86635 6.86894 1.88539 6.92768C1.90485 6.98648 1.94031 7.03871 1.98779 7.07849C2.03527 7.11827 2.0929 7.14404 2.1542 7.15289L4.71174 7.51964L4.39617 7.87343C4.2787 8.00496 4.28467 8.20527 4.40911 8.32983L8.67502 12.6C8.70579 12.631 8.74239 12.6556 8.78272 12.6723C8.82304 12.6891 8.86629 12.6977 8.90995 12.6977C8.98861 12.6977 9.06827 12.6698 9.13099 12.613L9.48439 12.2971L9.85074 14.8572C9.86867 14.9818 9.96724 15.0735 10.0857 15.1133C10.1163 15.1232 10.1482 15.1282 10.1803 15.1282C10.2739 15.1282 10.3704 15.0874 10.4371 15.0216C11.9543 13.5039 12.8324 11.4131 12.8732 9.26558C15.3979 7.01439 17.0356 3.46571 16.9639 0.356487C16.9609 0.180081 16.8176 0.0375807 16.6404 0.0326119ZM13.582 5.76771C13.2585 6.09158 12.8334 6.25302 12.4093 6.25302C11.9842 6.25302 11.5591 6.09158 11.2355 5.76771C10.5895 5.11996 10.5895 4.06661 11.2355 3.41886C11.8826 2.77111 12.935 2.77111 13.582 3.41886C14.2291 4.06661 14.2291 5.11996 13.582 5.76771Z" fill="white"/>
    <path d="M2.84152 11.2472C2.12896 11.9588 1.15199 15.1763 1.04321 15.5406C1.00827 15.6583 1.04021 15.7851 1.12702 15.8709C1.15766 15.902 1.19418 15.9266 1.23444 15.9434C1.27471 15.9602 1.31792 15.9688 1.36155 15.9687C1.39349 15.9687 1.42543 15.9637 1.45733 15.9548C1.82158 15.846 5.0379 14.8679 5.75043 14.1554C6.55277 13.353 6.55277 12.0486 5.75043 11.2472C4.94808 10.4449 3.6438 10.4459 2.84146 11.2472H2.84152Z" fill="white"/>
  </g>
  <defs>
    <clipPath id="clip0_533_24776">
      <rect width="16" height="16" fill="white" transform="translate(0.998047 -3.05176e-05)"/>
    </clipPath>
  </defs>
</svg>
					</div>
					 <div class="title">
						 <span style="font-size: 14px;
font-style: normal;
font-weight: 700;
line-height: 48px;">Crash</span>
						 </div>
						 </div> 
						</div>
                <div class="bx-input d-flex flex-column">
                    <div class="bx-input__input d-flex align-center justify-space-between">

                            <input class="fullInputWidth" style="text-align: left; color: #fff; font-size: 16px;" placeholder="0.00" id="crashSum" type="text" value="1.00" placeholder="0.00">
                            <svg class="icon money"><use xlink:href="images/symbols.svg#coins"></use></svg>

                    </div>
                    <div class="x30__bet-placed d-flex align-center justify-space-between">
                        <a onclick="$('#crashSum').val((Number($('#crashSum').val()) + 10).toFixed(2));">+10</a>
                        <a onclick="$('#crashSum').val((Number($('#crashSum').val()) + 100).toFixed(2));">+100</a>
                        <a onclick="$('#crashSum').val((Number($('#crashSum').val()) + 1000).toFixed(2));">+1000</a>
                        <a onclick="$('#crashSum').val((Number($('#crashSum').val()) * 2).toFixed(2));">x2</a>
                        <a onclick="$('#crashSum').val(Math.max((Number($('#crashSum').val()) / 2), 1).toFixed(2));">1/2</a>
                    </div>
                </div>
                <div class="bx-input d-flex flex-column">
                    <div class="bx-input__input d-flex align-center justify-space-between">
                        <label class="d-flex align-center">Авто-стоп:</label>
                        <div class="d-flex align-center">
                            <input id="crashAuto" style="color: #fff; font-size: 16px;" type="text" value="2" placeholder="1.10">
                        </div>
                    </div>
                </div>
                <div class="bx-input d-flex flex-column">
                    <a id="btnCrash" onclick="disable(this);crashBet(this)" class="btn btn--blue is-ripples flare d-flex align-center justify-center"><span>Начать игру</span></a>
                </div>

                <div class="bx-input">
                    <div class="crash__history">
                        <div class="crash__scroll d-flex">

                        </div>
                    </div>
                </div>
            </div>
            <div class="crash__right">
                <div class="crash__canvas">
                    <div class="crash__x-number"><span>00:10</span></div>
                    <canvas id="crashChart"></canvas>

                </div>

            </div>

        </div>

        @auth
        @if(\Auth::user()->admin == 1)
        <div class="crash__top d-flex align-stretch justify-space-between" style="margin-top: 30px;">
            <div class="crash__left d-flex flex-column">

                <div class="bx-input d-flex flex-column">
                    <a onclick="crashBoom()" class="btn btn--blue is-ripples flare d-flex align-center justify-center"><span>Взорвать</span></a>
                </div>


            </div>
            <div class="crash__right">
                <div class="bx-input d-flex flex-column">
                    <div class="bx-input__input d-flex align-center justify-space-between">
                     <label class="d-flex align-center">Икс:</label>
                     <div class="d-flex align-center">
                        <input id="crashIks" type="text" value="2.22" placeholder="1.10">
                    </div>
                    <a onclick="crashGo()" class="btn btn--blue is-ripples flare d-flex align-center justify-center"><span>Подкрутить</span></a>
                </div>

            </div>


        </div>

    </div>

    <script type="text/javascript">
        function crashBoom() {
            const info = {
                _token: csrf_token
            }
            $.post('/crash/boom',info).then(e=>{
                if(e.success){
                    notification('success', 'Успешно')
                }
                if(e.error){
                    notification('error',e.error)
                }
            })
        }

        function crashGo() {
            const info = {
                _token: csrf_token,
                coeff: $('#crashIks').val()
            }
            $.post('/crash/go',info).then(e=>{
                if(e.success){
                    notification('success', 'Успешно')
                }
                else{
                    notification('error',e.mess)
                }
            })
        }
    </script>

    @endif
    @endauth


</div>
<div class="crash__history-users history">

</div>
</div>

<script type="text/javascript">
    var timeStartPing = 0

    function ping(){
        timeStartPing = Date.now();
        socket.emit('PING_CONNECT')

    }

    socket.on('PING_GET',e=>{
        console.log(Date.now() - timeStartPing);

    })



    $(document).ready(function() {

        if (localStorage.getItem('crashAgree') != 'true') {
            showPopup('popup--crash-info')
        }

    });


        // ping()





        let canvas = document.getElementById('crashChart'),
        ctx    = canvas.getContext('2d')



        Chart.pluginService.register({
            afterDraw: function(chart) {
                var ctx2 = chart,
                max = ctx2.chartArea.left-5,
                width = ctx2.width,
                height = ctx2.height - 10;
                ctx.save(),
                ctx.globalCompositeOperation = "destination-over";
                var lr = Math.round((width - 6) / 83.5) + 1,
                td = Math.round((height - 1) / 82.5) + 1;
                ctx.lineWidth = .5,
                ctx.strokeStyle = "rgba(33,39,57,40.15)";

                for (var u = 0; u < td; u++) {
                    var h = height - (88.8 * u + (u + 1 === td ? 1 : 0)),
                    l = width - 6 - .5 - 9;
                    ctx.beginPath(),
                    ctx.setLineDash([4, 3]),
                    0 === u && ctx.setLineDash([]),
                    ctx.moveTo(max + 6, h),
                    ctx.lineTo(l + max, h),
                    ctx.stroke(),
                    ctx.closePath()
                }
                ctx.globalCompositeOperation = "source-over",
                ctx.restore()
            }
        });

        let shadowLine = Chart.controllers.line.extend({
            initialize: function () {
                Chart.controllers.line.prototype.initialize.apply(this, arguments)

                var ctx = this.chart.ctx
                var originalStroke = ctx.stroke
                ctx.stroke = function () {
                    ctx.save()
                    ctx.shadowColor = 'rgba(33,39,57,40.15)'
                    ctx.shadowOffsetX = 4
                    ctx.shadowOffsetY = 4
                    ctx.shadowBlur = 15
                    originalStroke.apply(this, arguments)
                    ctx.restore()
                }
            }
        })
        Chart.controllers.shadowLine = shadowLine

        let myChart = new Chart(ctx, {
            type: 'shadowLine',
            data: {
                labels: [0],
                datasets: [{
                    label: '',
                    backgroundColor: '#7485b7)',
                    borderColor: '#7485b7',
                    pointRadius: 0,
                    borderWidth: 1.4,
                    data: [0],
                }]
            },
            options: {
                animation: false,
                title: {
                    display: false
                },
                legend: {
                    display: false,
                },
                layout: {
                    padding: {
                        left: 7
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            min: 1,
                            stepSize: 1,
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            beginAtZero:true,
                            padding: 10,
                            min: 1,
                            max: 2,
                            stepSize: 0.3,

                            fontColor: '#7485b7',
                            color: '#7485b7',
                            font: {
                                size: 12,
                                family: 'Gotham Pro',
                                weight: 600
                            },

                            padding: 15,

                            callback: function(value, index, values) {
                                if(value != '' && value.toFixed(1) == 1) return '';
                                if(!(index % parseInt(values.length / 5))) {
                                    return value.toFixed(1) + 'x';
                                }
                            }
                        },

                    }]
                }
            }
        })




        var last = 1
        var last_zabr = 1
        socket.on('crashTitle',e=>{
            if(e.play == 1){
                if(bet_user == 1){
                    zabr = Number(e.text) * Number($('#crashSum').val())
                    zabr = zabr.toFixed(2)

                    $({numberValue: last_zabr}).animate({numberValue: zabr}, {
                     duration: e.start_time - 20,
                     easing: "linear",
                     step: function(val) {
                       $('#btnCrash span').text('Забрать '+parseFloat(val).toFixed(2).toLocaleString());
                   }
               });


                    last_zabr = zabr



                }else{
                    $('#btnCrash span').html('Ожидание игры...')
                    disable('#btnCrash')
                }
                text = Number(e.text)

                $('.crash__x-number span').text(text+'x');


                last = text

                myChart.data.labels = e.label;
                myChart.data.datasets[0].data = e.data;
                myChart.options.scales.yAxes[0].ticks.max = Math.max.apply(2, e.data) + 1;
                myChart.update();


            }else{
                $('.crash__x-number span').text(e.text)
            }
        })




        function get(){
            const info = {
                _token: csrf_token
            }
            $.post('/crash/get',info).then(e=>{
                give = e.give
                if (give == 1){
                    bet_user = 1;
                    $('#btnCrash').attr('onclick', 'disable(this);crashGive(this)')
                    $('#btnCrash span').html('Забрать 0.00')
                    $('#crashSum').val(e.bet)
                    $('#crashAuto').val(e.auto)

                    undisable('#btnCrash')
                    disable('#crashSum')
                    disable('#crashAuto')
                }

                if (give == 2){
                    $('#crashSum').val(e.bet)
                    bet_user = 0;
                    $('#btnCrash span').html('Ожидание игры...')
                    disable('#btnCrash')
                    disable('#crashSum')
                    disable('#crashAuto')
                }
                e.last.forEach((e)=>{
                    var str = String(e.num.toFixed(2));
                    if(e.num < 1.5){
                        class_h = 'x1'
                    }else if(e.num >= 1.5 && e.num < 5){
                        class_h = 'x2'
                    }else if(e.num >= 5 && e.num < 10){
                        class_h = 'x3'
                    }else if(e.num >= 10 && e.num < 40){
                        class_h = 'x4'
                    }else if(e.num >= 40 && e.num < 70){
                        class_h = 'x5'
                    }else{
                        class_h = 'x6'
                    }
                    $('.crash__scroll').append('<a href="#" class="crash__history-item '+class_h+'">\
                        <span>x'+str+'</span>\
                        </a>')
                })
                e.history.forEach((e)=>{
                    res = e.result
                    type = '';
                    result = '-';
                    class_h = '';
                    if(res != 0){
                        type = "win";
                        result = res.toFixed(2)

                        if(result < 1.5){
                            class_h = 'x1'
                        }else if(result >= 1.5 && result < 5){
                            class_h = 'x2'
                        }else if(result >= 5 && result < 10){
                            class_h = 'x3'
                        }else if(result >= 10 && result < 40){
                            class_h = 'x4'
                        }else if(result >= 40 && result < 70){
                            class_h = 'x5'
                        }else{
                            class_h = 'x6'
                        }

                    }


                    $('.crash__history-users').prepend(' <div id="game_crash_id_'+e.id+'"class="crash__history-item-user crash__history-item-user--'+type+' d-flex align-center justify-space-between">\
                        <div class="history__user d-flex align-center justify-center">\
                        <div class="history__user-avatar" style="background: url('+e.img+') no-repeat center center / cover;"></div>\
                        <span>'+e.login+'</span>\
                        </div>\
                        <div class="d-flex align-center">\
                        <div class="d-flex align-center">\
                        <span class="bx-input__text">'+Number(e.bet).toFixed(2)+'</span>\
                        <svg class="icon money"><use xlink:href="images/symbols.svg#coins"></use></svg>\
                        </div>\
                        <div class="crash__history-user-x d-flex align-center justify-space-between">\
                        <div class="d-flex align-center">\
                        <span class="bx-input__text">'+Number((e.bet * Number(result))).toFixed(2)+'</span>\
                        <svg class="icon money"><use xlink:href="images/symbols.svg#coins"></use></svg>\
                        </div>\
                        <a href="#" class="crash__history-item '+class_h+'">\
                        <span>x'+result+'</span>\
                        </a>\
                        </div>\
                        </div>\
                        </div>')
                })
            })
}

get()

socket.on('crashClear',e=>{
    $('#btnCrash span').html('Начать игру')

    $('.crash__x-number').html('<span>00:10</span>')
    $('.crash__history-users').html('')
    $('.crash__x-number').css('color', "#fff")

    myChart.data.labels = [0];
    myChart.data.datasets[0].data = [0];
    myChart.options.scales.yAxes[0].ticks.max = 2;
    myChart.data.datasets[0].backgroundColor = 'rgba(73, 134, 245, 0.65)';
    myChart.data.datasets[0].borderColor = 'rgba(128, 179, 255)';
    myChart.update();


})

socket.on('crashDead',e=>{
    $('.crash__x-number').html(parseFloat(e.text).toFixed(2).toLocaleString()+'x')
    last = 1
    bet_user = 0;
    last_zabr  = 1

    myChart.data.datasets[0].borderColor = 'rgba(255, 128, 128)';
    myChart.data.datasets[0].backgroundColor = 'rgba(167, 76, 92, 0.65)';
    myChart.update();

    // myChart.data.datasets[0].borderColor = "#ff5249";
    // myChart.data.datasets[0].backgroundColor = "rgba(255,82,73,0.65)";
    // myChart.update()
    $('.crash__x-number').css('color', "#ff5249")
    $('#btnCrash').attr('onclick', 'disable(this);crashBet(this)')
    $('#btnCrash span').html('Начать игру')


    undisable('#btnCrash')
    undisable('#crashSum')
    undisable('#crashAuto')
    if (location.pathname == '/crash') {
        audio.boom.currentTime = 0;
            audio.boom.play();

    }
    $('.crash__x-number').html('<span>'+parseFloat(e.text).toFixed(2).toLocaleString()+'x</span>')

})


function animateCrashNumber(first, second, time){
    $({numberValue: first}).animate({numberValue: second}, {
     duration: time - 20,
     easing: "linear",
     step: function(val) {
      $('.crash__x-number span').text(parseFloat(val).toFixed(2).toLocaleString()+'x');
  }
});
}

socket.on('crashUpdate',e=>{
    if(e.type == 2){
        e.publishMassiv.forEach((e)=>{
            crash_userId = e.user_id
            crash_userBet = e.bet
            gameId = e.id

            crash_userCoeff = e.auto
            crash_userWin = crash_userCoeff * crash_userBet

            if(crash_userId == USER_ID){
                bet_user = 0;
                notification('success', 'Вы успешно забрали')
                $('#btnCrash').html('<span>Ожидание игры...</span>')
                disable('#btnCrash')
            }
            $('#game_crash_id_'+gameId).removeClass('crash__history-item-user--lose').addClass('crash__history-item-user--win')
            $('#game_crash_id_'+gameId+' .crash__history-user-x .bx-input__text').text(crash_userWin.toFixed(2))
            $('#game_crash_id_'+gameId+' .crash__history-user-x .crash__history-item span').text('x'+crash_userCoeff.toFixed(2))

            result = crash_userCoeff

            if(result < 1.5){
                class_h = 'x1'
            }else if(result >= 1.5 && result < 5){
                class_h = 'x2'
            }else if(result >= 5 && result < 10){
                class_h = 'x3'
            }else if(result >= 10 && result < 40){
                class_h = 'x4'
            }else if(result >= 40 && result < 70){
                class_h = 'x5'
            }else{
                class_h = 'x6'
            }

            $('#game_crash_id_'+gameId+' .crash__history-user-x .crash__history-item').addClass(class_h)
        })
    }
    if(e.type == 1){
        if(e.user_id == USER_ID){
            bet_user = 0;
            notification('success', 'Вы успешно забрали')
            $('#btnCrash').html('<span>Ожидание игры...</span>')
            disable('#btnCrash')
        }
        $('#game_crash_id_'+e.game_id).removeClass('crash__history-item-user--lose').addClass('crash__history-item-user--win')
        $('#game_crash_id_'+e.game_id+' .crash__history-user-x .bx-input__text').text(e.win_user.toFixed(2))
        $('#game_crash_id_'+e.game_id+' .crash__history-user-x .crash__history-item span').text('x'+e.coeff.toFixed(2))

        result = e.coeff

        if(result < 1.5){
            class_h = 'x1'
        }else if(result >= 1.5 && result < 5){
            class_h = 'x2'
        }else if(result >= 5 && result < 10){
            class_h = 'x3'
        }else if(result >= 10 && result < 40){
            class_h = 'x4'
        }else if(result >= 40 && result < 70){
            class_h = 'x5'
        }else{
            class_h = 'x6'
        }

        $('#game_crash_id_'+e.game_id+' .crash__history-user-x .crash__history-item').addClass(class_h)
    }


})

socket.on('crashGo',e=>{
    if(bet_user == 1){
        undisable('#btnCrash')
        $('#btnCrash').attr('onclick', 'disable(this);crashGive(this)')

    }
})

socket.on('crashNoty',e=>{
    if(e.user_id == USER_ID){
        notification('success','Вы выиграли '+e.win.toFixed(2)+' монет')
        balanceUpdate(e.balanceLast, e.balanceNew)
    }
})

socket.on('crashFinish',e=>{
    e.arr_win.forEach((e)=>{
        // if(e.user_id == USER_ID){
        //     notification('success','Вы выиграли '+e.win_user.toFixed(2)+' монет')
        // }
    })
    e.arr_lose.forEach((e)=>{
        $('#game_crash_id_'+e.id).removeClass('crash__history-item-user--win').addClass('crash__history-item-user--lose')
    })


    $('.crash__scroll').html('')
    e.s.forEach((e)=>{
        var str = String(e.num.toFixed(2));
        if(e.num < 1.5){
            class_h = 'x1'
        }else if(e.num >= 1.5 && e.num < 5){
            class_h = 'x2'
        }else if(e.num >= 5 && e.num < 10){
            class_h = 'x3'
        }else if(e.num >= 10 && e.num < 40){
            class_h = 'x4'
        }else if(e.num >= 40 && e.num < 70){
            class_h = 'x5'
        }else{
            class_h = 'x6'
        }

        $('.crash__scroll').append('<a href="#" class="crash__history-item '+class_h+'">\
            <span>x'+str+'</span>\
            </a>')

    })
})


setTimeout(async function run() {
  await ping();
  setTimeout(run, 3000);
}, 2000);
</script>


<style type="text/css">
    .crash__canvas canvas {
        position: relative;
        top: 10px;
        left: 0px;
    }
</style>
@auth
<script type="text/javascript">
    socket.emit('subscribe', 'roomGame_5_{{\Auth::user()->id}}');
</script>
@endauth