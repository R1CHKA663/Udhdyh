<template>
    <div class="bonusRaceback col-12">
        <div class="headerRaceback racebackContent shadow col-12">
            <strong>РЕЙКБЭК</strong>
            <div class="racebackInfo">Играя на сайте ты можешь получать до 3.00% <strong
                    class="text-uppercase">rakeback</strong> от
                суммы ставок,
                доступно на всех
                режимах.<br>Уровень рэйкбэка повышается за депозиты</div>
        </div>
        <div class="d-flex mt-5 jdssd">
            <div class="col-4 bonuses">
                <div class="bonusCards">
                    <div class="text-center">
                        <h6>Бонусный баланс</h6>
                        <strong>
                            <h1 class="bonusRub">
                                <Vue3Odometer class="bold transition" :value="user.raceback.toFixed(2)" />
                            </h1>
                        </strong>
                        <div>Доступно к выводу</div>
                        <div>
                            <div class="btn-default blue flex getBonus" @click="out() " ripple>Перевести<img
                                    src="/img/arrow-white.svg" class="iconBtn" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8 bonusCards">
                <div class="racebackContent weeks col-12">
                    <h5>Игра недели</h5>
                    <div class="weekGameBox">
                        <div class="d-flex itemsRaceback">
                            <div class="weekGameIconBox">
                                <img :src="`/img/${week.game}.svg`" alt="dice">
                            </div>
                            <div class="weekGameInfo">
                                <strong>BUBBLES</strong>
                                <div>Получай умноженный рейкбэк на <strong>x{{week.procent}}</strong> в игре <strong
                                        class="text-uppercase">«{{week.game}}»</strong> до конца этой
                                    недели.
                                </div>
                            </div>
                        </div>
                        <div class="racbkSum">Рейкбэк в x{{week.procent}} засчитывается за ставки от 15.00 <div
                                class="valutaBox">
                                <div class="valuta"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <h5>Система уровней для повышения рейкбэка</h5>
            <div class="d-flex mt-5 itemsRaceback">
                <div class="racebackLvl shadow" v-for="key in racebackLvl" :key="key.lvl">
                    <div class="racebackmyLvlBox" v-if="key.lvl == currentLvl">
                        <div class="racebackmyLvl">Текущий уровень</div>
                    </div>
                    <div class="racebackLvlHeader">
                        <div class="racbackLvlKvadrat">{{key.lvl}}</div>
                        <div class="racbackLvlInfo">уровень</div>
                    </div>
                    <div class="racbackInfos">
                        <div class="racbackFrom">{{key.from}}</div>
                        <div class="racbackProcent" :style="{'color': key.colorText,'backgroundColor': key.color}">
                            {{key.raceback.toFixed(2)}}%</div>
                        <div class="racbackFrom">{{key.to}}</div>
                    </div>
                    <div class="racbackProgress">
                        <div class="racbackProgsWidth"
                            :style="{'width': progressDeposit(key)+'%','backgroundColor': key.colorText}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vue3Odometer from 'vue3-odometer';
import 'odometer/themes/odometer-theme-default.css'

export default {
    components: {
        Vue3Odometer
    },
    data: () => {
        return {
            user: {
                raceback: 0,
            },
            week: {},
            currentLvl: 1,
            racebackLvl: [
                {
                    lvl: 1,
                    from: 0,
                    to: 500,
                    raceback: 1,
                    color: '#EEF5FF',
                    colorText: '#0048BA'
                },
                {
                    lvl: 2,
                    from: 500,
                    to: 5000,
                    raceback: 1.25,
                    color: 'rgb(214 254 217)',
                    colorText: 'rgb(0 186 50)'
                },
                {
                    lvl: 3,
                    from: 5000,
                    to: 15000,
                    raceback: 1.5,
                    color: 'rgb(255 250 204)',
                    colorText: 'rgb(186 170 23)'
                },
                {
                    lvl: 4,
                    from: 15000,
                    to: '~',
                    raceback: 2,
                    color: '#fceeff',
                    colorText: 'rgb(188 44 238)'
                }
            ]
        }
    },
    methods: {
        async get() {
            const result = await this.$axios.post('/api/raceback/get')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.user = item.user
                this.week = item.week
                this.$emit('isLoading')
            }
        },
        async out() {
            const result = await this.$axios.post('/api/raceback/out')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.sum
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Вы успешно сняли ' + item.sum + ' румбиков'
                })
                this.user.raceback = item.raceback
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        progressDeposit(key) {
            if (this.user.deposit >= key.from && key.lvl == 4) return 50
            if (key.to < this.user.deposit) return 100
            if (key.from <= this.user.deposit && key.to >= this.user.deposit) {
                this.currentLvl = key.lvl
                return (this.user.deposit - key.from) / (key.to - key.from) * 100
            }
            return 0
        }
    },
    mounted() {
        this.get()
    }
}
</script>
<style>
.racebackLvlHeader {
    display: flex;
    align-items: center;
}

.racbackProgress {
    width: 100%;
    height: 9px;
    background-color: #EEF5FF;
    border-radius: 3px;
    margin-top: 10px;
}

.racbackInfos {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 200px;
    margin-top: 0.7rem;
    color: #ACACAC;
    font-size: 14px;
}

.racbackProcent {
    background-color: #EEF5FF;
    padding: 4px 10px;
    border-radius: 5px;
    color: #0048BA;
}

.racebackLvl {
    padding: 15px 20px;
    border-radius: 5px;
    margin-right: 15px;
    flex: auto;
    position: relative;
}

.racebackmyLvlBox {
    text-align: center;
    top: -27px;
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
}

.racebackmyLvl {
    background-color: #2a56d9;
    padding: 5px 40px;
    color: white;
    border-radius: 5px 5px 0px 0px;
}

.racebackLvl:last-child {
    margin-right: 0;
}

.racbackLvlKvadrat {
    min-width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #2a56d9;
    border-radius: 5px;
    color: white;
    font-size: 20px;
}

.racbackProgsWidth {
    height: 100%;
    border-radius: 3px;
    opacity: 0.7;
}

.racbackLvlInfo {
    margin-left: 8px;
}

.headerRaceback {
    display: grid;
    justify-content: center;
    text-align: center;
    background-image: linear-gradient(45deg, #F4F5F9, #F2F6F9);
    padding: 20px;
}

.bonusCards.bonuses {
    margin-left: 0 !important;
}

.bonusRaceback {
    display: grid;
}

.getBonus {
    border-radius: 37px;
    width: 70% !important;
    margin: 0 auto;
    margin-top: 1rem;
}

.racebackInfo {
    padding: 15px 0;
    color: #525e72;
}

.racbkSum {
    display: flex;
    align-items: center;
    font-size: 14px;
    padding-top: 15px;
}

.racebackContent.weeks {
    padding-left: 2rem;
}

.weekGameIconBox {
    min-width: 65px;
    height: 65px;
    border-radius: 5px;
    background-color: #2a56d9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.weekGameBox {
    padding: 15px;
    border-radius: 10px;
}

.weekGameIconBox img {
    height: 40px;
    filter: invert(100%) sepia(100%) saturate(2%) hue-rotate(272deg) brightness(109%) contrast(101%);
}

.weekGameInfo {
    margin-left: 2rem;
    font-size: 14px;
}

@media(max-width: 1000px) {
    .bonusRaceback {
        display: block !important;
    }

    .jdssd {
        display: block !important;
    }

    .jdssd .col-4,
    .jdssd .col-8 {
        width: 100%;
    }

    .itemsRaceback {
        display: block !important;
    }

    .itemsRaceback .racebackLvl {
        margin-bottom: 2rem;
    }

    .racebackLvl {
        margin-right: 0;
    }

    .weekGameBox {
        padding: 0;
    }

    .weekGameInfo {
        margin-left: 0;
        margin-top: 1rem;
    }

    .racbkSum .valutaBox {
        display: none;
    }

    .weekGameIconBox img {
        height: 50px;
    }

    .weekGameIconBox {
        max-width: 65px;
    }
}
</style>