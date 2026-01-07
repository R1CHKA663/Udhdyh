<template>
    <div class="promocode">
        <div class="panel-header flex">
            <router-link to="/panel/promocode/create" class="btn-default blue"
                :class="{'active': $route.name == 'promo_create'}">Создать
                промокод</router-link>
            <router-link to="/panel/promocode" class="btn-default blue" :class="{'active': $route.name == 'promo_all'}">
                Все промокоды</router-link>
        </div>
        <div class="content">
            <router-view name="promo" @notify="notify"></router-view>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            page: 'create',
            get: 1,
            promo: {
                name: null,
                reward: null,
                limit: null,
                type: 0,
                deposit: 0
            },
            getPromoPage: [],
            updatePromo: {}
        }
    },
    methods: {
        notify(i) {
            this.$emit('notify', i)
        },
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
        async getPromo() {
            const result = await this.$axios.get(`/panel/api/promo/get?page=${this.get}`)
            const data = result.data
            if (data.success) {
                this.getPromoPage = data.success
            }
        },
        async getUpdatePromo(id) {
            const result = await this.$axios.post(`/panel/api/promo/getUpdate`, { id })
            const data = result.data
            if (data.success) {
                this.updatePromo = data.success
                this.page = 'update';
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
}
</script>