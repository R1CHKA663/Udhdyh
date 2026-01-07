<template>
    <div class="stats">
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-success">{{stats.payment_sum_today}}</p>
                    <strong>Пополнений за день</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-success">{{stats.payment_sum_7days}}</p>
                    <strong>Пополнений за неделю</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-success">{{stats.payment_sum_Month}}</p>
                    <strong>Пополнений за месяц</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-success">{{stats.payment_sum_all}}</p>
                    <strong>Пополнений за все время</strong>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-danger">{{stats.withdraw_sum_today}}</p>
                    <strong>Выводов за день</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-danger">{{stats.withdraw_sum_7days}}</p>
                    <strong>Выводов за неделю</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-danger">{{stats.withdraw_sum_Month}}</p>
                    <strong>Выводов за месяц</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-danger">{{stats.withdraw_sum_all}}</p>
                    <strong>Выводов за все время</strong>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-warning">{{stats.withdraw_count_active}}</p>
                    <strong>Требуют вывода</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-danger">{{stats.withdraw_sum_active}}</p>
                    <strong>Сумма активных выводов</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-info">{{stats.player_all}}</p>
                    <strong>Игроков всего</strong>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3 stats-itemBox">
                <div class="stats-item">
                    <p class="text-info">{{stats.player_all_today}}</p>
                    <strong>Игроков за сегодня</strong>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            stats: {}
        }
    },
    methods: {
        async get() {
            const result = await this.$axios.post(`/panel/api/stats/get`)
            const data = result.data
            if (data.success) {
                this.stats = data.success
                this.$emit('notify', {
                    type: 'success',
                    text: 'Обновлено'
                })
            }
        }
    },
    mounted() {
        this.get()
    }
}
</script>