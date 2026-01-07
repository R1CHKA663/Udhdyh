<template>
    <div class="col-12">
        <div class="bonusReposts">
            <div class="col-4 bonusCards">
                <div>
                    <div class="bonusTopBox">
                        <div class="btn-default yellow flex">
                            <div>До 5.00</div>
                            <div class="valutaBox bonus">
                                <div class="valuta"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-hm">
                        <h6>Бонус за репост</h6>
                    </div>
                    <div class="desc mt-3">Сделайте репост 5-ти последних записей и получите бонус</div>
                    <div class="flex gmm mt-3">
                        <div class="">
                            <a :href="user.social ? user.social.vk : null" target="_blank"><img src="/img/vk.svg"
                                    height="25px" /></a>
                            <a :href="user.social ? user.social.tg : null" target="_blank"><img src="/img/tg.svg"
                                    height="25px" /></a>
                        </div>
                        <div class="getBonusText flex" @click="check()">Проверить <img src="/img/arrow.svg"
                                class="arrow">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="bonusCards">
                    <div class="text-center">
                        <h6>Бонусный баланс</h6>
                        <strong>
                            <h1 class="bonusRub">
                                <count-up :end-val="users.income_repost" :options="$countUpFormat.options"></count-up>
                            </h1>

                        </strong>
                        <div>Доступно к выводу</div>
                        <div>
                            <div class="btn-default blue flex getBonus" @click="transfer()">Перевести<img
                                    src="/img/arrow-white.svg" class="iconBtn" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="bonusCards">
                    <h6 class="infoStrong">Информация</h6>
                    <div class="infoBonus">
                        <div class="plusBox">
                            ! </div>
                        <div>Делай репосты постов и получай бонус!</div>
                    </div>
                    <div class="infoBonus">
                        <div class="plusBox">
                            ! </div>
                        <div>Чем больше репостов, тем больше бонус</div>
                    </div>
                    <div class="infoBonus">
                        <div class="plusBox">
                            ! </div>
                        <div>Учитываются только 5 последних постов</div>
                    </div>
                    <div class="infoBonus">
                        <div class="plusBox">
                            !</div>
                        <div>Аккаунт должен быть открыт</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <h5>Система уровней</h5>
            <div class="d-flex mt-5 items">
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
                            {{key.bonusRepost.toFixed(2)}}</div>
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
import CountUp from "vue-countup-v3";

export default {
    components: { CountUp },

    props: [
        'user'
    ],
    data: () => {
        return {
            currentLvl: 1,
            users: {
                income_repost: 0,
                repost: 0
            },
            racebackLvl: [
                {
                    lvl: 1,
                    from: 0,
                    to: 25,
                    bonusRepost: 0.4,
                    color: '#EEF5FF',
                    colorText: '#0048BA'
                },
                {
                    lvl: 2,
                    from: 25,
                    to: 100,
                    bonusRepost: 0.6,
                    color: 'rgb(214 254 217)',
                    colorText: 'rgb(0 186 50)'
                },
                {
                    lvl: 3,
                    from: 100,
                    to: 300,
                    bonusRepost: 0.8,
                    color: 'rgb(255 250 204)',
                    colorText: 'rgb(186 170 23)'
                },
                {
                    lvl: 4,
                    from: 300,
                    to: '~',
                    bonusRepost: 1,
                    color: '#fceeff',
                    colorText: 'rgb(188 44 238)'
                }
            ]
        }
    },
    methods: {
        progressDeposit(key) {
            if (this.user.repost >= key.from && key.lvl == 4) return 50
            if (key.to <= this.users.repost) return 100
            if (key.from <= this.users.repost && key.to >= this.users.repost) {
                this.currentLvl = key.lvl
                return (this.users.repost - key.from) / (key.to - key.from) * 100
            }
            return 0
        },
        async check() {
            const result = await this.$axios.post('/api/bonus/repost/check')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.users = item.user
                this.$emit('notify', {
                    type: 'success',
                    text: `Вам начислено ${this.users.repost_bonus} румбиков за ${this.users.make_repost} репоста`
                })
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async get() {
            const result = await this.$axios.post('/api/bonus/repost/get')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.users = item.user
                this.$emit('isLoading')
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async transfer() {
            const result = await this.$axios.post('/api/bonus/repost/transfer')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.users = item.user
                this.$emit('notify', {
                    type: 'success',
                    text: `Вам начислено ${this.users.transfer} румбиков`
                })
                this.$emit('updateBalance', {
                    balance: this.users.balance,
                    win: this.users.transfer
                })
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        }
    },
    mounted() {
        this.get()
    }
}
</script>
<style scoped>
.bonusReposts {
    display: flex;
    align-items: center;
}

.bonusRub {
    color: #424446;
}

.plusBox {
    font-size: 20px;
}

.getBonus {
    border-radius: 37px;
    width: 70% !important;
    margin: 0 auto;
    margin-top: 1rem;
}

@media(max-width: 1000px) {
    .bonusReposts {
        display: block;
    }

    .bonusReposts .col-4 {
        width: 100%;
    }

    .items {
        display: block !important;
    }

    .items .racebackLvl {
        margin-bottom: 2rem;
    }

    .racebackLvl {
        margin-right: 0;
    }
}
</style>