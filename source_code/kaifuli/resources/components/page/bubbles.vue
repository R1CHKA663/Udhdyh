<template>
    <div class="bubblesBox">
        <div class="cardHeaderBox container">
            <div class="cardHeader">
                Bubbles »
                <span class="text-body tittle">Лопни пузырь!</span>
            </div>
        </div>
        <div class="cards">
            <div class="bubblesContainer container flex container mb-5 position-relative">
                <div class="freeBonusBox flex">
                    <div>
                        <div class="jackpot-na-cony" style="
                                        position: initial;
                                    ">
                            <div class="copilka"><img src="/img/copilka.svg" alt=""></div>
                            <div class="text">ДЖЕКПОТ:</div>
                            <div class="sum">
                                <count-up :start-val="1" :end-val="jackpotSum" :options="$countUpFormat.options">
                                </count-up>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="diceLeft col-4">
                    <div class="diceInputRow d-block mt-5">
                        <div class="form-floating">
                            <input type="text" class="form-control text-center gameInput" v-model="bet"
                                @change="updateForm('bet')" />
                            <label for="floatingInput">Сумма игры</label>
                            <div class="amount flex">
                                <button class="btn-default" @click="bet *= 2;updateForm('bet')">x2</button>
                                <button class="btn-default"
                                    @click="bet >  2 ? bet /= 2:bet = 1;updateForm('bet')">x/2</button>
                                <button class="btn-default" @click="bet = 1;updateForm('bet')">min</button>
                                <button class="btn-default" @click="bet = user.balance.toFixed(2)">max</button>
                            </div>
                        </div>
                        <div class="form-floating mt-4">
                            <input type="text" class="form-control text-center gameInput" v-model="purple"
                                @change="updateForm('purple')" />
                            <label for="floatingInput">Цель игры</label>
                            <div class="amount flex">
                                <button class="btn-default" @click="purple = 3;updateForm('purple')">x3</button>
                                <button class="btn-default" @click="purple =5;updateForm('purple')">x5</button>
                                <button class="btn-default" @click="purple = 10;updateForm('purple')">x10</button>
                                <button class="btn-default" @click="purple = 15;updateForm('purple')">x15</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="m-auto col-8 mh">
                        <div class="vozmWin text-center mt-5">
                            <h3 class="flex mt-3">
                                <div class="valutaBox">
                                    <div class="valuta"></div>
                                </div>
                                <count-up :end-val="win" :options="$countUpFormat.options" class="vozmWin"></count-up>
                            </h3>
                            <h6>Возможный выигрыш</h6>
                        </div>
                        <div class="diceBtnRow mt-4 ml-0">
                            <div class="diceBtn p-0 ml-0">
                                <button class="btn-default blue" @click="play()" ripple>Играть</button>
                            </div>
                            <div class="myAlert m-auto mt-3 flex" :class="result.status" v-if="result.status">
                                <template v-if="result.status == 'success'">
                                    Выигрыш
                                    <div class="valutaBox wh">
                                        <div class="valuta wh"></div>
                                    </div>
                                    {{ result.win.toFixed(2) }}
                                </template>
                                <template v-if="result.status == 'error'">
                                    {{ result.text }}
                                </template>
                                <template v-if="result.status == 'info'">
                                    <div class="lds-facebook">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </template>
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
export default {
    components: { CountUp },
    props: ['user'],
    data: () => {
        return {
            bet: 1,
            purple: 1.5,
            win: 0,
            result: {
                text: null,
                status: null
            },
            jackpotSum: 0
        }
    },
    sockets: {
        jackpotGameSum(e) {
            console.log(e)
            this.jackpotSum = e
        }
    },
    methods: {
        update() {
            this.win = this.bet * this.purple
        },
        keyNewUpdate() {
            if (this.bet < 0) return this.bet = "1.00"
            if (this.bet > 1000) return this.bet = "1000.00"
            if (this.purple < 1) return this.purple = "1.05"
            if (this.purple >= 1000000) return this.purple = "1000000.00"
            this.bet = this.bet
            this.purple = this.purple
            this.update()
        },
        async play() {
            if (this.result.status == 'info') return
            this.result.status = 'info'
            const result = await this.$axios.post('/api/bubbles/play', {
                bet: Number(this.bet),
                purple: Number(this.purple)
            })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.result = item
                item.status == 'win' ? this.result.status = 'success' : this.result.status = 'error'
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.win - item.bet
                })
                //const sound = new Audio('/sound/bubbles.mp3')
                //sound.play()

            }
            if (data.error) {
                this.result.status = 'error'
                this.result.text = data.error
            }
        },
        updateForm(e) {
            if (e == 'bet') return this.bet > 0 ? this.bet = Number(this.bet).toFixed(2) : this.bet = "1.00"
            if (e == 'purple') return this.purple > 0 ? this.purple = Number(this.purple).toFixed(2) : this.purple = "1.05"
        }
    },
    watch: {
        bet() {
            this.keyNewUpdate()
        },
        purple() {
            this.keyNewUpdate()
        }
    },
    mounted() {
        this.updateForm('bet')
        this.updateForm('purple')
        this.keyNewUpdate()
    }
}
</script>