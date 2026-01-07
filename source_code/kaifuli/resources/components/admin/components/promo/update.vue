<template>
    <div class="col-6">
        <div>
            <label>Тип промокода</label>
            <select class="form-select  form-select-lg" v-model="updatePromo.type">
                <option value="0" selected>Денежный</option>
                <option value="1">К депозиту</option>
            </select>
        </div>
        <div>
            <label>Статус промокода</label>
            <select class="form-select  form-select-lg" v-model="updatePromo.status">
                <option value="0" selected>Включен</option>
                <option value="1">Выключен</option>
            </select>
        </div>
        <div class="mt-3">
            <input type="text" class="form-control" placeholder="Название промокода" v-model="updatePromo.name">
        </div>
        <div class="mt-3">
            <input type="text" class="form-control" placeholder="Количество активаций" v-model="updatePromo.limit">
        </div>
        <div class="mt-3">
            <input type="text" class="form-control" placeholder="Награда за промокод" v-model="updatePromo.reward">
        </div>
        <div class="mt-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="updatePromo.deposit">
                <label class="form-check-label" for="flexCheckDefault">
                    Активация доступна только для депозитеров
                </label>
            </div>
        </div>
        <div class="mt-3">
            <div class="btn-default blue active" @click="updatePromoSave()">Изменить промокод</div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            updatePromo: {}
        }
    },
    methods: {
        async createPromo() {
            const result = await this.$axios.post('/panel/api/promo/create', { promo: this.promo })
            const data = result.data
            if (data.success) {
                this.$emit('notify', {
                    type: 'success',
                    text: data.success
                })
            } else {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async getUpdatePromo(id) {
            const result = await this.$axios.post(`/panel/api/promo/getUpdate`, { id })
            const data = result.data
            if (data.success) {
                this.updatePromo = data.success
            }
        },
        async updatePromoSave() {
            const result = await this.$axios.post('/panel/api/promo/update', { updatePromo: this.updatePromo })
            const data = result.data
            if (data.success) {
                this.$emit('notify', {
                    type: 'success',
                    text: data.success
                })
            } else {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        }
    },
    async mounted() {
        this.id = this.$route.params.id
        this.getUpdatePromo(this.id)
    }
}
</script>