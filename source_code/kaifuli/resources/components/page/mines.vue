<template>
    <div class="minesBox">
        <div class="cardHeaderBox container">
            <div class="cardHeader">
                Mines »
                <span class="text-body tittle">Угадаешь победную ячейку?</span>
            </div>
        </div>
        <div class="cards container flex">
            <div class="minesBottom flex container">
                <div class="minesLeft col-4">
                    <div class="form-floating">
                        <input type="number" class="form-control text-center gameInput" v-model="bet"
                            @change="updateForm('bet')" :disabled="statusForm" />
                        <label for="floatingInput">Сумма игры</label>
                        <div class="amount flex">
                            <button class="btn-default" :disabled="statusForm"
                                @click="bet *= 2;updateForm('bet')">x2</button>
                            <button class="btn-default" :disabled="statusForm"
                                @click="bet >  2 ? bet /= 2:bet = 1;updateForm('bet')">x/2</button>
                            <button class="btn-default" :disabled="statusForm"
                                @click="bet = 1;updateForm('bet')">min</button>
                            <button class="btn-default" :disabled="statusForm"
                                @click="bet = user.balance.toFixed(2)">max</button>
                        </div>
                    </div>
                    <div class="form-floating mt-4">
                        <input type="number" class="form-control text-center gameInput" v-model="bomb"
                            @change="updateForm('bomb')" :disabled="statusForm" />
                        <label for="floatingInput">Количество бомб</label>
                        <div class="amount flex">
                            <button class="btn-default" :disabled="statusForm" :class="{ 'active': bomb == 3 }"
                                @click="bomb = 3;updateForm('bomb')">3</button>
                            <button class="btn-default" :disabled="statusForm" :class="{ 'active': bomb == 5 }"
                                @click="bomb = 5;updateForm('bomb')">5</button>
                            <button class="btn-default" :disabled="statusForm" :class="{ 'active': bomb == 10 }"
                                @click="bomb = 10;updateForm('bomb')">10</button>
                            <button class="btn-default" :disabled="statusForm" :class="{ 'active': bomb == 15 }"
                                @click="bomb = 15;updateForm('bomb')">15</button>
                            <button class="btn-default" :disabled="statusForm" :class="{ 'active': bomb == 24 }"
                                @click="bomb = 24;updateForm('bomb')">24</button>
                        </div>
                    </div>
                    <div class="form-floating mt-4">
                        <button class="btn-default blue minesBtn" @click="play()" v-if="btn == 'play'" ripple>Начать
                            игру</button>
                        <button class="btn-default blue minesBtn" @click="take()" v-if="btn == 'take'" ripple>Забрать
                            <strong class="border">
                                <count-up :end-val="win" :options="$countUpFormat.options" class="contents"></count-up>
                            </strong>
                        </button>
                        <button class="btn-default minesBtn   mt-2" @click="autoSelection()" v-if="btn == 'take'"
                            ripple>Автовыбор</button>
                        <div class="myAlert m-auto mt-2 flex alerts" :class="{
                            'success': noty.status == true, 'error': noty.status == false
                        }" v-if="noty.show">{{
                        noty.text
                        }}
                        </div>
                    </div>

                </div>
                <div class="minesRight col-6 flex">
                    <div class="minefieldsBox flex">
                        <transition name="show">
                            <div class="winBox shadow" v-if="tableWin.show">
                                <h6 class="text-typing">{{tableWin.text}}</h6>
                                <h1 class=" flex">
                                    <div class="valutaBox" style="display: none">
                                        <div class="valuta"></div>
                                    </div>
                                    <count-up :start-val="1" :end-val="tableWin.win" :options="$countUpFormat.options">
                                    </count-up>
                                </h1>
                                <h6>
                                    <count-up :start-val="1" :end-val="tableWin.coff" :options="$countUpFormat.options"
                                        class="coffif">
                                    </count-up>
                                </h6>
                            </div>
                        </transition>
                        <div class="minefields">
                            <div class="cell flex" v-for="i in cell.num" :key="i" @click="press(i)" :class="{
                                'win isShow': cell.clicked.find(el => el == i),
                                'isHide': cell.isHide.find(el => el == `hide-${i}`),
                                'lose isShow': cell.loseCell == i,
                                'lose opacity': cell.mines.find(el => el == i && el != cell.loseCell),
                                'win opacity': cell.winMines.find(el => el == i)
                            }"></div>
                        </div>
                    </div>
                </div>
                <div class="minesCoef col-2">
                    <Splide ref="splide" :options="options" class="minesCoefBox">
                        <SplideSlide class="coff-item" v-for="i in (25 - bomb)" :class="{
                            'win': cell.clicked.length >= i && statusGame,
                            'active': cell.clicked.length + 1 == i && statusGame && !cell.loseCell,
                            'lose': cell.clicked.length + 1 == i && statusGame && cell.loseCell
                        }">
                            <div class="coff-top">
                                <div>{{ i }} ход</div>
                                <div>x{{ normalNum((getCoff(bomb, i))) }}</div>
                            </div>
                            <div class="coff-bottom">{{ normalNum((getCoff(bomb, i) * bet)) }}</div>
                        </SplideSlide>
                    </Splide>
                </div>
                <div class="loading" v-if="loading">
                    <div class="lds-facebook">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import CountUp from "vue-countup-v3";
import { Splide, SplideSlide, SplideTrack } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';
export default {
    props: ['user'],
    components: { SplideSlide, Splide, SplideTrack, CountUp },
    data: () => {
        return {
            bet: 1,
            bomb: 3,
            win: 0,
            statusGame: false,
            statusForm: false,
            loading: true,
            btn: 'play',
            noty: {
                status: null,
                text: null,
                show: false
            },
            tableWin: {
                show: false,
                win: 0,
                coff: 0
            },
            cell: {
                num: 25,
                arr: [],
                isHide: [],
                clicked: [],
                loseCell: null,
                winMines: [],
                mines: []
            },
            clickedAuto: [],
            options: {
                speed: 200,
                rewindSpeed: 200,
                height: 330,
                direction: 'ttb',
                paginationDirection: 'ltr',
                perPage: 5,
                perMove: 5,
                gap: 5,
                prev: false,
                arrows: false,
                pagination: false,
                breakpoints: {
                    1000: {
                        direction: 'ltr',
                        perPage: 2,
                        perMove: 1,
                        height: 65
                    }
                }
            }
        }
    },
    methods: {
        getCoff(t, e) {
            for (var n = 1, a = 0; a < 25 - t && e > a; a++)
                n *= (25 - a) / (25 - t - a);
            return n;
        },
        async play() {
            this.noty.show = false
            this.clear()
            const result = await this.$axios.post('/api/mines/play', {
                bet: this.bet,
                bomb: this.bomb
            })
            const data = result.data
            if (data.success) {
                this.statusForm = true
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance
                })
                this.statusGame = true
                this.btn = 'take'
            }
            if (data.error) {
                this.noty.show = true
                this.noty.text = data.error
                this.noty.status = false
            }
        },
        clear() {
            this.win = 0
            this.statusGame = false
            this.tableWin.show = false
            this.noty = {
                status: null,
                text: null,
                show: false
            }
            this.tableWin = {
                show: false
            }
            this.cell = {
                num: 25,
                arr: [],
                isHide: [],
                clicked: [],
                loseCell: null,
                winMines: [],
                mines: []
            }
            this.$refs.splide.go(0)
        },
        async press(cell) {
            if (this.cell.clicked.find(el => el == cell)) return
            if (cell < 1 && cell > 25 || !cell) return

            this.noty.show = false
            const result = await this.$axios.post('/api/mines/press', { cell })
            const data = result.data
            const item = data.success
            if (data.error) {
                //this.noty.show = true
                //this.noty.text = data.error
                //this.noty.status = false
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
                return
            }
            if (item.status == 'win') {
                this.statusForm = true
                this.win = item.win
                this.cell.clicked = item.clicked
                if (window.screen.width > 1000) {
                    this.$refs.splide.go(`>${this.cell.clicked.length / 5}`)
                } else {
                    this.$refs.splide.go(`>${this.cell.clicked.length / 2}`)
                }
                this.btn = 'take'
                //const sound = new Audio('/sound/mines-win.mp3')
                //sound.play()
                this.clickedAuto.push(cell)
            }
            if (item.status == 'finish') {
                this.statusForm = false
                this.tableWin.show = true
                this.tableWin.win = item.win
                this.tableWin.coff = item.coff
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.win
                })
                this.btn = 'play'
                this.cell.mines = item.mines
                this.cell.winMines = item.winMines
                this.cell.clicked = item.clicked
                this.clickedAuto.push(cell)
                this.tableWin.text = this.randomText()
            }
            if (item.status == 'lose') {
                this.statusForm = false
                this.cell.loseCell = item.loseCell
                this.cell.mines = item.mines
                this.cell.winMines = item.winMines
                this.btn = 'play'
                //const sound = new Audio('/sound/mines-lose.mp3')
                //sound.play()
            }

        },
        async get() {
            const result = await this.$axios.post('/api/mines/get')
            const data = result.data
            if (data.success) {
                this.statusForm = true
                const item = data.success
                this.win = item.win
                this.btn = 'take'
                this.statusGame = true
                this.bet = item.bet
                this.bomb = item.num_bomb
                this.cell.clicked = item.clicked
                if (window.screen.width > 1000) {
                    this.$refs.splide.go(`>${this.cell.clicked.length / 5}`)
                } else {
                    this.$refs.splide.go(`>${this.cell.clicked.length / 2}`)
                }
            }
            this.loading = false
        },
        replayMode() {
            var time = 500;
            for (let i = 0; i < this.clickedAuto.length; i++) {
                setTimeout(() => {
                    this.press(this.clickedAuto[i])
                }, time * i)
            }
        },
        randomText() {
            const text = ['Кайфули заебули', 'Победитель ❤', 'Красавчик ❤', 'Умница!', 'Сюда его...', 'Ого, а ты магешь :D', 'GIVE CASH', 'Поставил... Нажал и победил :)', 'Ура, выигрыш!', 'Хватит на ламборгини?', 'Кайфули - не, ну а хули?', 'Как это возможно?', 'Поставил,Выиграл,Вывел :)', 'Блаженство...', 'Молодые боссы', 'Вырубай читы!', 'Как же приятно...', 'Сайт на выдаче?', 'Неплохо, может еще разок :)', 'Крутой 😎', 'Угадал ячейки :)']
            return text[this.getRandom(0, text.length - 1)]
        },
        async take() {
            const result = await this.$axios.post('/api/mines/take')
            const data = result.data

            if (data.success) {
                const item = data.success
                this.tableWin.show = true
                this.statusForm = false
                this.tableWin.win = item.win
                this.tableWin.coff = item.coff
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.win
                })
                this.btn = 'play'
                this.cell.mines = item.mines
                this.cell.winMines = item.winMines
                this.tableWin.text = this.randomText()
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        getRandom(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min)
        },
        async autoSelection() {
            const arr = []
            for (let i = 1; i <= 25; i++) {
                arr.push(i)
            }
            const result = await arr.diff(this.cell.clicked)
            this.press(result[await this.getRandom(0, result.length)])
        },
        normalNum(e) {
            e = Number(e)
            if (e > 1000 && e < 1000000) return (e / 1000).toFixed(2) + 'k';
            if (e > 1000000) return (e / 1000000).toFixed(2) + 'kk';
            return e.toFixed(2)
        },
        updateForm(e) {
            if (e == 'bet') return this.bet > 0 ? this.bet = Number(this.bet).toFixed(2) : this.bet = "1.00"
            if (e == 'bomb') return this.bomb > 2 ? this.bomb = Number(this.bomb) : this.bomb = 2
        }
    },
    watch: {
        bet() {
            if (this.bet < 0) this.bet = 1
            if (this.bet > 1000) this.bet = 1000
        },
        bomb() {
            if (this.bomb < 0) this.bomb = 2
            if (this.bomb > 24) this.bomb = 24
        }
    },
    mounted() {
        this.updateForm('bet')
        this.updateForm('bomb')
        this.get()
    }
}
</script>
<style scoped>
.alerts {
    font-size: 13px;
    height: 40px;
}

.border {
    border: none !important;
    border-bottom: 2px solid white !important;
}

.coffif::before {
    content: 'x ';
}

.text-typing {
    width: 100%;
    animation: type 2s steps(50, end);
    overflow: hidden;
    white-space: nowrap;
}

@keyframes type {
    from {
        width: 0;
        zoom: 0
    }
}

.contents {
    display: contents;
}
</style>