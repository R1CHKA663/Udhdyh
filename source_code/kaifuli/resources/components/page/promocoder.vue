<template>
    <div class="promocoder col-12 p-0" v-if="user.is_promocoder">
        <div class="bonusCards col-5">
            <div class="mt-2">
                <span>Количество активаций</span>
                <input type="text" v-model="userGet.limit" class="form-control text-center mt-1" disabled>
            </div>
            <div class="mt-2">
                <span>Награда</span>
                <input type="text" v-model="userGet.reward" class="form-control text-center mt-1" disabled>
            </div>
            <div class="mt-2" v-if="give.promo">
                <div class=" btn-default blue" @click="create()">Создать</div>
            </div>
            <div class="mt-2" v-else>
                <div class="btn-default">{{time.promo}}</div>
            </div>
        </div>
        <div class="bonusCards col-7 align-innerit d-block">
            <div>Мои промокоды:</div>
            <div class="uve">
                <table>
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Активаций</th>
                            <th>Осталось</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="key in promo" :key="key.id">
                            <td>{{key.name}}</td>
                            <td>{{key.limit}}</td>
                            <td>{{key.limit - key.limited}}</td>
                            <td class="text-success">{{key.reward.toFixed(2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script scoped>
export default {
    props: ['user'],
    data: () => {
        return {
            userGet: [],
            promo: [],
            time: {
                promo: null
            },
            give: {
                promo: null
            }
        }
    },
    methods: {
        async create() {
            const result = await this.$axios.post('/api/promocoder/create')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('notify', {
                    type: 'success',
                    text: 'Промокод создан'
                })
                this.get()
                //this.timer(item.date, type, item.finished); this.give[type] = false;
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async get() {
            const result = await this.$axios.post('/api/promocoder/get')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.promo = item.promo.reverse()
                this.userGet = item.user
                this.timer(item.user.time, 'promo')
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
            if (type == 'promo') return `${result.hours}:${result.minutes}:${result.seconds}`
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
                    this.give.promo = false
                }
            }, 1000);
        }
    },
    mounted() {
        this.get()
        this.$emit('isLoading')
    }
}
</script>
<style>
.promocoder {
    display: flex;
}

table th {
    color: #353C55 !important;
}

.uve {
    overflow-y: scroll;
    max-height: 200px;
    scrollbar-width: none;
}

.uve::-webkit-scrollbar {
    width: 0;
    height: 0;
}

@media(max-width: 1000px) {
    .promocoder {
        display: block;
    }
}
</style>