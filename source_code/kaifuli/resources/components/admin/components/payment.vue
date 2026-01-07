<template>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" v-model="not">
        <label class="form-check-label" for="flexCheckDefault">
            {{not ? 'СЕЙЧАС ВКЛЮЧЕНЫ НЕ ЗАЧИСЛЕННЫЕ' : 'ВКЛЮЧЕННЫ ЗАЧИСЛЕННЫЕ'}}
        </label>
    </div>
    <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
        id="ajax-users" role="grid" aria-describedby="ajax-users_info" style="width: 1167px;">
        <thead>
            <tr>
                <th>Игрок</th>
                <th>Ник</th>
                <th>Система</th>
                <th>Сумма</th>
                <th>Время</th>
                <th v-if="not">Действие</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="key in payment.data" :key="key.id">
                <td>
                    <router-link :to="`/panel/user/${key.user_id}`">#{{key.user_id}}</router-link>
                </td>
                <th>{{key.name}}</th>
                <th>{{key.system}}</th>
                <td>{{key.sum}}</td>
                <td>{{key.created_at}}</td>
                <td class="text-success" v-if="not" @click="paymentOk(key.id,key.system)">Зачислить</td>
            </tr>
        </tbody>
    </table>
    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item" :class="{'active' : paginate.active}" v-for="paginate in payment.links"
                :key="paginate.id" @click="page = Number(paginate.label);getPayment()" :disabled="paginate.active">
                <a class="page-link">
                    {{paginate.label}}
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
export default {
    data: () => {
        return {
            page: 1,
            payment: [],
            not: false
        }
    },
    methods: {
        async getPayment() {
            const result = await this.$axios.get(`/panel/api/payment/all?page=${this.page}&not=${this.not}`)
            const data = result.data
            if (data.success) {
                const item = data.success
                this.payment = item
            } else {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async paymentOk(id, system) {
            var check = confirm("Ты уверен?");
            if (check) {
                if (system == 'linepay') {
                    var apiSystem = 'linepay'
                    const result = await this.$axios.post(`/api/payment/linepay`, { order_id: id })
                }
                if (system == 'fkwallet') {
                    var apiSystem = 'fk'
                    const result = await this.$axios.post(`/api/payment/fk`, { MERCHANT_ORDER_ID: id })
                }
                const data = result
                this.$emit('notify', {
                    type: 'success',
                    text: data
                })
            }
        }
    },
    watch: {
        not() {
            this.getPayment()
        }
    },
    mounted() {
        this.getPayment()
    }
}
</script>