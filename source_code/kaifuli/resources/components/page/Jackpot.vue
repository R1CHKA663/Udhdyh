<template>
    <div class="jackpotBox">
        <div class="cardHeaderBox container" v-if="false">
            <div class="cardHeader">
                Jackpot »
                <span class="text-body tittle">Вытянешь счастливый билет?</span>
            </div>
        </div>
        <div class="cards">
            <div class="container">
                <div class="JackpotContainer">
                    <div class="jackpotTop" style="width: 100%">
                        <div class="jackpot-header mobile">
                            <div class="jackpot-status">
                                <div class="circle">
                                    <span>i</span>
                                </div>
                                <div class="text">{{jackpot.title}}</div>
                            </div>
                            <div class="d-flex mt-3">
                                <div class="jackpot-na-cony">
                                    <div class="copilka">
                                        <img src="/img/copilka.svg" alt="">
                                    </div>
                                    <div class="text">НА КОНУ:</div>
                                    <div class="sum">
                                        <count-up :end-val="bank" :options="$countUpFormat.options">
                                        </count-up>
                                    </div>
                                </div>
                                <div class="jackpot-my-chance">
                                    <div class="text">Шанс:</div>
                                    <count-up class="my-chance" :end-val="chance" :options="$countUpFormat.options" />
                                </div>
                            </div>
                        </div>
                        <div class="jackpot-header">
                            <div class="jackpot-status">
                                <div class="circle">
                                    <span>i</span>
                                </div>
                                <div class="text">{{jackpot.title}}</div>
                            </div>
                            <div class="jackpot-na-cony">
                                <div class="copilka">
                                    <img src="/img/copilka.svg" alt="">
                                </div>
                                <div class="text">НА КОНУ:</div>
                                <div class="sum">
                                    <count-up :end-val="bank" :options="$countUpFormat.options">
                                    </count-up>
                                </div>
                            </div>
                            <div class="jackpot-my-chance">
                                <div class="text">Ваш шанс:</div>
                                <count-up class="my-chance" :class="{
                                    'bg25': chance < 25 && chance != 0,
                                    'bg75': chance < 75 && chance > 25,
                                    'bg100': chance > 75
                                }" :end-val="chance" :options="$countUpFormat.options" />
                            </div>
                        </div>
                        <div class="timerJackpot">
                            <div class="time">0</div>
                            <div class="time">0</div>
                            <div class="ls">:</div>
                            <div class="time">{{jackpot.time[0]}}</div>
                            <div class="time">{{jackpot.time[1]}}</div>
                        </div>
                        <div class="jackpotCenter" style="display: none">
                            <div class="inbox">
                                <div class="arrows-jackpot"></div>
                                <div class="mytopShadow"></div>
                                <div class="mytopShadow rightss"></div>
                                <div class="roulette">

                                </div>
                                <div class="winnerBox">
                                    <div><strong>{{jackpot.winner.name}}</strong></div>
                                    <div><strong> забирает <span
                                                style="color: #4383FF">{{jackpot.winner.bank.toFixed(2)}}</span> с
                                            шансом
                                            <span
                                                style="color: #4383FF">{{jackpot.winner.chance.toFixed(2)}}%</span></strong>
                                    </div>
                                </div>
                                <div class="players" v-if="false">
                                    <div class="players-item">
                                        <div class="avatarBox">
                                            <img
                                                src="https://sun1-47.userapi.com/s/v1/ig2/rmLAeYHP1zw8a_gsqawx74jvB3U6h5YWwbK54ldng_oL2VaWqFLQ3ZrcWPKZR_-gkx5KI3hNkjS2XGh6mygEo2w-.jpg?size=200x200&amp;quality=95&amp;crop=151,33,300,300&amp;ava=1">
                                            <div class="chance">30%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jackpotBottom">
                            <div class="list-bet">
                                <div class="flex">
                                    <div class="waitPlayers" v-if="!jackpot.betList.length">
                                        <img src="/img/wait.svg" alt="">
                                        <div>Ожидание игроков...</div>
                                    </div>
                                </div>
                                <TransitionGroup name="list">
                                    <div class="item-bet" v-for="key in jackpot.betList" :key="key.id">
                                        <div class="flex">
                                            <div class="avatarBox">
                                                <img :src="key.img">
                                            </div>
                                            <div class="nameBox">
                                                <span>{{key.name}}</span>
                                            </div>
                                        </div>
                                        <div class="chanceBox">
                                            <span>{{chanceBet(key.user_id)}}%</span>
                                        </div>
                                        <div class="tickets">
                                            {{key.fromTicket}} - {{key.toTicket}}
                                        </div>
                                        <div class="winJackpotBox ml-auto">
                                            <span>{{key.bet.toFixed(2)}}</span>
                                        </div>
                                    </div>
                                </TransitionGroup>
                            </div>
                            <div class="bet-col">
                                <div class="form-jack d-flex">
                                    <div class="amount" @click="jackpot.bet /= 2">x/2</div>
                                    <div class="amount" @click="jackpot.bet *= 2">x2</div>
                                    <input type="number" v-model="jackpot.bet" class="jackpot-bet">
                                    <div class="amount" @click="jackpot.bet = 1">min</div>
                                    <div class="amount" @click="jackpot.bet = user.balance">max</div>

                                </div>
                                <div class="btn-default jackpot" @click="newBet()">Сделать ставку</div>
                                <span @click="startGame() " v-if="false">go</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import CountUp from "vue-countup-v3";
import $ from 'jquery'
export default {
    components: { CountUp },
    props: ['user'],
    data: () => {
        return {
            jackpot: {
                bet: 10,
                betList: [],
                bank: 0,
                chance: [{
                    chance: 0,
                    user_id: 0
                }],
                winner: {
                    bank: 0,
                    chance: 0
                },
                time: '25',
                title: 'Прием ставок'
            }
        }
    },
    sockets: {
        newBetJackpot(e) {
            this.jackpot.chance = e.user_chance
            this.jackpot.bank += e.bet
            this.jackpot.betList.unshift(e)
        },
        startSliderJackpot(data) {
            this.startGameMethods(data, 20)
        },
        jackTime(e) {
            this.jackpot.time = e.time
            this.jackpot.title = e.title
        },
        getJackpot(e) {
            this.jackpot.betList = e.getJackpot.listBet.reverse()
            this.jackpot.chance = e.getJackpot.user_chance

            if (e.getJackpot.start != false) {
                this.startGameMethods(e.getJackpot.start, e.getJackpot.time)
            }
        },
        winner(e) {
            if (e.user_id != this.user.id) return
            this.$emit('notify', {
                type: 'success',
                text: 'Поздравляем! Вы выиграли ' + e.win
            })
            this.$emit('updateBalance', {
                balance: this.user.balance + e.win,
                win: e.win
            })
        }
    },
    computed: {
        userChance() {
            if (this.jackpot.chance.find(el => el.user_id == this.user.id)) {
                return this.jackpot.chance.find(el => el.user_id == this.user.id).chance
            }
            return 0
        },
        bank() {
            var banks = 0
            this.jackpot.betList.forEach((e) => {
                banks += e.bet
            })
            return banks.toFixed(2)
        },
        chance() {
            const hm = this.jackpot.chance.find(el => el.user_id == this.user.id)
            if (hm) {
                return hm.chance
            }
            return "0.00"
        },
        bet() {
            return this.jackpot.bet
        }
    },
    watch: {
        bet() {
            if (this.jackpot.bet < 0) this.jackpot.bet = 1
            if (this.jackpot.bet > 1000) this.jackpot.bet = 1000
        },
    },
    methods: {
        startGameMethods(data, time) {
            $('.roulette').html('')

            data.slider.forEach((e) => {
                $('.roulette').append(`<div class="avatarBox"><img src="${e.img}"></div>`)
            })
            this.jackpot.title = 'Игра идет'
            this.startGame(data, time)
        },
        async newBet() {
            const result = await this.$axios.post('/api/jackpot/play', {
                bet: Number(this.jackpot.bet)
            })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance,
                })
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        getRand(min, max) {
            return Math.random() * (max - min) + min;
        },
        async startGame(data, time) {
            $('.jackpotBottom').css({ 'opacity': '.5' })
            $('.jackpotCenter').show()
            $('.mytopShadow').show()
            this.jackpot.title = 'Игра идет'
            await this.$nextTick()
            const slider = 80 * ($('.avatarBox').width() + 15)
            const inbox = $('.inbox').width() / 2
            $('.roulette').css(
                {
                    'transition': '0ms cubic-bezier(0, 0, 0, 1) -100ms',
                    'transform': 'translate3d(-0px, 0, 0)'
                })
            await this.$nextTick()
            $('.roulette').css(
                {
                    'transition': time * 1000 + 'ms cubic-bezier(0, 0, 0, 1) -100ms',
                    'transform': 'translate3d(-' + ((slider - inbox) - (data.rand)) + 'px, 0, 0)'
                })
            setTimeout(() => {
                $('.roulette .avatarBox').css({ 'opacity': '.5' })
                $('.mytopShadow').show()
                $('.roulette  .avatarBox:nth-of-type(80)').addClass('winner').css({ 'opacity': '1' })
                $('.winnerBox').show()
                $('.mytopShadow').hide()
                this.jackpot.winner = data.winUser
                this.jackpot.title = 'Победитель определен'
                setTimeout(() => {
                    $('.jackpotCenter').hide()
                    this.jackpot.betList = []
                    this.jackpot.chance = []
                    $('.jackpotBottom').css({ 'opacity': '1' })
                    $('.roulette').css(
                        {
                            'transition': '0ms cubic-bezier(0, 0, 0, 1) -100ms',
                            'transform': 'translate3d(-0px, 0, 0)'
                        })
                    $('.winnerBox').hide()
                    this.jackpot.time = '25'
                    this.jackpot.title = 'Прием ставок'
                }, 5000)
            }, time * 1000)
        },
        chanceBet(user_id) {
            const chanceBets = this.jackpot.chance.find(el => el.user_id == user_id)
            if (chanceBets) {
                return chanceBets.chance
            }
            return 0
        }
    },
    mounted() {
        this.$socket.emit('getJackpot')
    }
}
</script>
<style>
.JackpotContainer {
    padding-top: 1rem;
    display: flex;
}

.jackpot-win-items {
    width: 25%;
}

.jackpot-win-item {
    width: 100%;
    height: 56px;
    border-radius: 21px;
    display: flex;
    align-items: center;
    padding: 5px 20px;
    box-shadow: 0px 7px 40px 0px rgb(0 0 0 / 5%);
    font-weight: 500;
    margin-bottom: 1rem;
    color: rgba(131, 133, 153, 1);
    font-size: 13px;
}

.avatarBox img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
}

.avatarBox {
    transition: opacity .5s;
    position: relative;
}

.winJackpotBox {
    margin-left: auto;
    color: rgba(53, 60, 85, 1);
}

.jackpot-win-item .nameBox {
    color: rgba(53, 60, 85, 1);
    margin-left: 5px;
}

.chanceBox {
    margin-left: 0px;
}

.nameBox {
    width: 65px;
}

.jackpot-header {
    display: flex;
    width: 100%;
    justify-content: space-between;
    font-size: 13px;
}

.jackpot-status {
    display: flex;
    background-color: #F0F2FF;
    border-radius: 50px;
    color: rgba(53, 60, 85, 1);
    font-weight: 500;
    align-items: center;
    padding: 5px 10px;
    padding-right: 20px;
}

.jackpot-status .circle {
    background-color: #4383FF;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-weight: 600;
}

.jackpot-status .text {
    margin-left: 8px;
    font-weight: 600;
}

.jackpot-my-chance .text {
    font-weight: 600;
}

.copilka img {
    height: 25px;
}

.jackpot-na-cony {
    display: flex;
    align-items: center;
    font-weight: 700;
    width: 180px;
    position:
        absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center;
}

.jackpot-na-cony .text {
    color: #353C55;
    padding-left: 7px;
}

.jackpot-my-chance {
    display: flex;
    align-items: center;
    color: #353C55;
    font-weight: 500;
    background-color: #F0F2FF;
    border-radius: 50px;
    padding: 5px 10px;
    padding-left: 20px;
}

.jackpot-my-chance .my-chance {
    background-color: #3f51b5;
    border-radius: 15px;
    padding: 3px 8px;
    color: white;
    margin-left: 6px;
    font-size: 11px;
    width: 60px;
    text-align: center;
    width: 70px;
    transition: background-color .5s;
}

.jackpot-my-chance .my-chance.bg25 {
    background-color: #ff4343;
}

.jackpot-my-chance .my-chance.bg75 {
    background-color: #FF9D43;
}

.jackpot-my-chance .my-chance.bg100 {
    background-color: #2ecb51;
}

.my-chance::after {
    content: ' %';
}

.jackpotTop {
    width: 75%;
    padding: 0 20px;
}

.jackpot-na-cony .sum {
    padding-left: 7px;
    background: linear-gradient(118.74deg, #2ABAD9 0%, #822AD9 104.48%);
    color: transparent;
    -webkit-background-clip: text;
    background-clip: text;
}

.timerJackpot {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1.4rem;
}

.timerJackpot .time {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    background-color: #F0F2FF;
    color: rgba(53, 60, 85, 1);
    font-size: 18px;
    font-weight: 800;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timerJackpot .ls {
    margin-right: 10px;
}

.jackpotBottom .bet-col {
    width: 32%;
}

.jackpotBottom .list-bet {
    width: 68%;
    padding-right: 20px;
    overflow: scroll;
    max-height: 250px;
    min-height: 250px;
}

.btn-default.jackpot {
    background: linear-gradient(118.74deg, #2ABAD9 0%, #822AD9 104.48%), linear-gradient(0deg, #D9D9D9, #D9D9D9);
    color: white;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.jackpot-bet {
    background-color: transparent;
    width: 70px;
    text-align: center;
    border: none;
    color: rgba(53, 60, 85, 1);
    font-weight: 600;
    font-size: 17px;
    padding: 0;
}

.form-jack {
    background-color: #F0F2FF;
    padding: 13px 10px;
    border-radius: 7px;
    font-weight: 600;
    color: rgba(131, 133, 153, 1);
    margin-bottom: 1rem;
    align-items: center;
    text-align: center;
    text-transform: uppercase;
    font-size: 13px;
}

.jackpotBottom {
    display: flex;
}

.jackpotBottom {
    margin-top: 2rem;
}

::-webkit-scrollbar {
    width: 0;
}

.item-bet {
    display: flex;
    align-items: center;
    border: 2px solid #F1F3FF;
    padding: 6px 14px;
    border-radius: 10px;
    font-weight: 600;
    color: rgba(131, 133, 153, 1);
    font-size: 12px !important;
    margin-bottom: 1rem;
}

.item-bet .nameBox,
.item-bet .winJackpotBox {
    color: rgba(53, 60, 85, 1);
}

.item-bet .tickets {
    margin: 0 auto;
}

.amount {
    font-weight: 600;
    font-size: 12px;
}

.roulette {
    display: flex;
    transition: none
}

.inbox {
    margin-top: 2rem;
}

.roulette .avatarBox img {
    width: 60px !important;
    height: 60px !important;

}

.avatarBox.winner:after {
    content: '';
    background-image: url(/img/winner-circle.svg);
    background-size: 100%;
    position: absolute;
    display: inline-block;
    width: 80px;
    height: 80px;
    left: -10px;
    top: -10px;
    z-index: 1;
}

.waitPlayers {
    display: flex;
    align-items: center;
    background-color: #F0F2FF;
    padding: 8px 17px;
    border-radius: 27px;
    font-weight: 600;
    color: #353C55;
}

.waitPlayers img {
    height: 30px;
    margin-right: 10px;
    animation: rotating 2s linear infinite;
}

@keyframes rotating {
    from {
        -ms-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    to {
        -ms-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -webkit-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

.rotating .waitPlayers {
    display: flex;
    align-items: center;
    background-color: #F0F2FF;
    color: #353C55;
    font-weight: 700;
    padding: 10px;
    border-radius: 30px;
}

.jackpotCenter {
    width: 100%;
    padding: 10px;
    overflow: hidden;
    position: relative;
}

.arrows-jackpot {
    width: 2px;
    height: 60px;
    background: rgb(66 147 217);
    box-shadow: rgb(196 157 255) 0px 0px 5px 1px;
    z-index: 1;
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center;
}

.winnerBox {
    color: #353C55;
    text-align: center;
    margin-top: 1rem;
    display: none;
    transition: 1s;
}

.players {
    background-color: #F0F2FF;
    padding: 10px;
    border-radius: 10px;
    margin-top: 1rem;
}

.players img {
    border-radius: 10px;
    width: 70px;
    height: 70px;
}

.players-item {
    position: relative;
}

.players {
    display: flex;
}

.players-item .chance {
    position: absolute;
    bottom: 0;
    height: 22px;
    background-color: #4a4a4acf;
    font-size: 12px;
    padding: 4px;
    text-align: center;
    color: white;
    border-radius: 0 5px;
    top: 0;
    right: 0;
}

.jackpot-header.mobile {
    display: none;
}

.mytopShadow {
    position: absolute;
    height: 80px;
    width: 40%;
    background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);
    z-index: 1;
    bottom: 0;
    left: 0;
}

.mytopShadow.rightss {
    right: 0;
    left: auto;
    background: linear-gradient(to left, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);
}

@media(max-width: 1000px) {
    .jackpotBottom {
        flex-direction: column-reverse;
    }

    .jackpotBottom .bet-col {
        width: 100%;
    }

    .list-bet {
        width: 100% !important;
        padding-right: 0px !important;
        min-height: 0px !important;
    }

    .item-bet .tickets {
        display: none;
    }

    .jackpotTop {
        padding: 0;
    }

    .cards {
        padding: 0;
    }

    .list-bet {
        margin-top: 1rem;
    }

    .jackpot-header {
        display: none;
    }

    .jackpot-header.mobile {
        display: block;
    }

    .jackpot-na-cony {
        position: relative;
        flex: auto;
    }

    .jackpot-my-chance {
        padding-left: 0px;
        justify-content: right;
        flex: auto;
    }
}
</style>