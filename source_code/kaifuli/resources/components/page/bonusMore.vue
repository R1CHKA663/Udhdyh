<template>
    <div class="bonusMore col-12">
        <div class="col-4 bonusCards">
            <div>
                <div class="bonusTopBox">
                    <div class="btn-default yellow flex">
                        <div>до 10.00</div>
                        <div class="valutaBox bonus">
                            <div class="valuta"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-hm">
                    <h6>Ежедневный бонус</h6>
                    <h5 class="time" v-if="!give.day">{{this.time.day}}
                    </h5>
                </div>
                <div class="desc mt-3">Для получения бонусов, вам необходимо привязать ВК и TG
                </div>
                <div class="flex gmm mt-3">
                    <div class="">
                        <a :href="user.social ? user.social.vk : null" target="_blank"><img src="/img/vk.svg"
                                height="25px" /></a>
                        <a :href="user.social ? user.social.tg : null" target="_blank"><img src="/img/tg.svg"
                                height="25px" /></a>
                    </div>
                    <div class="getBonusText flex" @click="free('day')" v-if="give.day">Получить
                        <img src="/img/arrow.svg" class="arrow">
                    </div>
                    <div class="getBonusText flex not" v-else>ПОЛУЧЕНО</div>
                </div>
            </div>
        </div>
        <div class="col-4 bonusCards">
            <div>
                <div class="bonusTopBox">
                    <div class="btn-default yellow flex">
                        <div>до 3.00</div>
                        <div class="valutaBox bonus">
                            <div class="valuta"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-hm">
                    <h6>Ежечасный бонус</h6>
                    <h5 class="time" v-if="!give.hourly">{{this.time.hourly}}
                    </h5>
                </div>
                <div class="desc mt-3">Для получения бонусов, вам необходимо привязать ВК и TG
                </div>
                <div class="flex gmm mt-3">
                    <div class="">
                        <a :href="user.social ? user.social.vk : null" target="_blank"><img src="/img/vk.svg"
                                height="25px" /></a>
                        <a :href="user.social ? user.social.tg : null" target="_blank"><img src="/img/tg.svg"
                                height="25px" /></a>
                    </div>
                    <div class="getBonusText flex" @click="free('hourly')" v-if="give.hourly">Получить
                        <img src="/img/arrow.svg" class="arrow">
                    </div>
                    <div class="getBonusText flex not" v-else>ПОЛУЧЕНО</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],
    data: () => {
        return {
            userGet: {
            },
            time: {
                hourly: null,
                day: null
            },
            give: {
                hourly: false,
                day: false
            },
        }
    },
    methods: {
        async free(type) {
            const result = await this.$axios.post('/api/bonus/more/free', { type })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.sum
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Вы успешно получили ' + item.sum + ' румбиков'
                })
                this.timer(item.date, type, item.finished); this.give[type] = false;
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async get() {
            const result = await this.$axios.post('/api/bonus/more/get')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.userGet = item.user
                this.timer(item.user.hourly_bonus, 'hourly')
                this.timer(item.user.day_bonus, 'day')
                this.$emit('isLoading')
            }
        },
        getTimeRemaining(endtime) {
            var t = Number(endtime) - Date.now();
            if (t <= 0) {
                return false
            }
            var seconds = Math.floor((t / 1000) % 60);
            var minutes = Math.floor((t / 1000 / 60) % 60);
            var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            days = days < 10 ? '0' + days : days
            hours = hours < 10 ? '0' + hours : hours
            minutes = minutes < 10 ? '0' + minutes : minutes
            seconds = seconds < 10 ? '0' + seconds : seconds
            return {
                'total': t,
                'days': days,
                'hours': hours,
                'minutes': minutes,
                'seconds': seconds
            };
        },
        async countdownTimer(type, finish) {
            const result = await this.getTimeRemaining(finish)
            if (!result) {
                this.give[type] = true
                return
            }
            if (type == 'day') return `${result.hours}:${result.minutes}:${result.seconds}`
            if (type == 'hourly') return `${result.minutes}:${result.seconds}`
        },
        async timer(finish, type) {
            if (!finish) {
                this.give[type] = true
                return
            }
            this.time[type] = finish
            let timerId = null;

            this.time[type] = await this.countdownTimer(type, finish)
            timerId = await setInterval(async () => {
                this.time[type] = await this.countdownTimer(type, finish)
                if (this.time[type]) {
                    // this.give[type] = false
                }
            }, 1000);
        }
    },
    mounted() {
        this.get()
    }
}
</script>
<style>
.bonusMore {
    display: flex;
}

@media(max-width: 1000px) {
    .bonusMore {
        display: block;
    }
}
</style>