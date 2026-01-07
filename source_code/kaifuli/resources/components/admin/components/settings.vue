<template>
    <div class="settings">
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Банк мин</label>
                    <input type="text" class="form-control" v-model="bank.mines">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Банк дайсов</label>
                    <input type="text" class="form-control" v-model="bank.dice">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Банк бабблса</label>
                    <input type="text" class="form-control" v-model="bank.bubbles">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Нормальное состояние мин</label>
                    <input type="text" class="form-control" v-model="bank.normal_mines">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Нормальное состояние дайсов</label>
                    <input type="text" class="form-control" v-model="bank.normal_dice">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Нормальное состояние бабблса</label>
                    <input type="text" class="form-control" v-model="bank.normal_bubbles">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Комиссия мин</label>
                    <input type="text" class="form-control" v-model="bank.fee_mines">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Комиссия дайсов</label>
                    <input type="text" class="form-control" v-model="bank.fee_dice">
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 stats-itemBox">
                <div class="stats-item">
                    <label for="">Комиссия бабблса</label>
                    <input type="text" class="form-control" v-model="bank.fee_bubbles">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="btn-default active col-3" @click="save()">Сохранить</div>
            <div class="btn-default ml-2 col-3" @click="get()">Обновить</div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            bank: {}
        }
    },
    methods: {
        async get() {
            const result = await this.$axios.get(`/panel/api/bank/get`)
            const data = result.data
            if (data.success) {
                this.bank = data.success
                this.$emit('notify', {
                    type: 'success',
                    text: 'Обновлено'
                })
            }
        },
        async save() {
            if (!this.bank) return
            const result = await this.$axios.post(`/panel/api/bank/save`, { bank: this.bank })
            const data = result.data
            if (data.success) {
                this.$emit('notify', {
                    type: 'success',
                    text: 'Сохранено'
                })
            } else {
                this.$emit('notify', {
                    type: 'error',
                    text: 'ошибка'
                })
            }
        }
    },
    mounted() {
        this.get()
    }
}
</script>