<template>
    <div class="sectionWallet">
        <div class="walletTop">
            <div class="walletTopBody">
                <div class="title">Выберите метод оплаты</div>
                <div class="walletMethodsBox">
                    <div class="wallet-item" :class="{'active': system == 'qiwi'}" @click="system = 'qiwi'">
                        <img src="/img/qiwi1.svg" width="60px" />
                    </div>
                    <div class="wallet-item" :class="{'active': system == 'linepay'}" @click="system = 'linepay'">
                        <img src="/img/linepay.svg" width="60px" />
                    </div>
                    <div class="wallet-item" :class="{'active': system == 'fkwallet'}" @click="system = 'fkwallet'">
                        <img src="/img/fkwallet.svg" width="60px" />
                    </div>
                </div>
            </div>
        </div>
        <div class="walletBottom">
            <div class="walletBottomBody">
                <div class="formWallet">
                    <div class="title">Введите сумму пополнения</div>
                    <input type="text" class="form-wallet radius" placeholder="300" v-model="sum">
                    <div class="amount flex">
                        <button class="btn-default" :class="{ 'active': sum == 100 }" @click="sum = 100">100</button>
                        <button class="btn-default" :class="{ 'active': sum == 300 }" @click="sum = 300">300</button>
                        <button class="btn-default" :class="{ 'active': sum == 500 }" @click="sum = 500">500</button>
                        <button class="btn-default" :class="{ 'active': sum == 1000 }" @click="sum = 1000">1000</button>
                        <button class="btn-default" :class="{ 'active': sum == 5000 }" @click="sum = 5000">5000</button>
                    </div>
                    <div class="r">₽</div>
                </div>
                <div class="formWallet">
                    <div class="title">Промокод (если есть)</div>
                    <input type="text" class="form-wallet" placeholder="FREECASH" v-model="promo">
                </div>
                <div class="formWallet">
                    <button class="btn-default blue wallet" @click="newPayment()">Пополнить <img
                            src="/img/arrow-white.svg" class="iconBtn"></button>
                </div>
            </div>
        </div>
        <table class="wallet-table mt-3" id="walletTable" v-if="payment.result.data && payment.result.data.length">
            <thead>
                <tr>
                    <th>СПОСОБ</th>
                    <th>СУММА</th>
                    <th>РЕЗУЛЬТАТ</th>
                </tr>
            </thead>
            <tbody :class="{'filter' : payment.loading}">
                <tr class="gameHistory" v-for="key in payment.result.data" :key="key.id">
                    <td>
                        <img src="/img/fkwallet.png" class="systemIcon" v-if="key.system == 'fkwallet'" />
                        <img src="/img/linepayIcon.png" class="systemIcon" v-if="key.system == 'linepay'" />
                        <img src="/img/qiwi.png" class="systemIcon" v-if="key.system == 'qiwi'" />
                    </td>
                    <td>{{key.sum.toFixed(2)}} ₽
                    </td>
                    <td>
                        <span v-if="key.status == 0" :class="{'text-warning': key.status == 0}">Ожидание</span>
                        <span v-if="key.status == 1" :class="{'text-success': key.status == 1}">Оплачено</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav class="mt-3" v-if="payment.result.data && payment.result.data.length">
            <ul class="pagination">
                <li class="page-item" :class="{'active' : paginate.active}" v-for="paginate in payment.result.links"
                    :key="paginate.id" @click="payment.page = Number(paginate.label);getPayment()"
                    :disabled="paginate.active">
                    <a class="page-link">
                        {{normalPaginate(paginate.label)}}
                    </a>
                </li>
            </ul>
        </nav>
        <div class="nou" v-else><strong>N/A</strong>У вас не было пополнений</div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            sum: 100,
            promo: null,
            system: 'qiwi',
            payment: {
                page: 1,
                result: [],
                loading: false
            }
        }
    },
    methods: {
        async newPayment() {
            const result = await this.$axios.post('/api/payment/new', { sum: this.sum, promo: this.promo, system: this.system })
            const data = result.data
            if (data.success) {
                const item = data.success
                window.location.href = item.link
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async getPayment() {
            this.payment.loading = true
            const result = await this.$axios.post('/api/payment/get', { page: this.payment.page })
            const data = result.data
            if (data.success) {
                const item = data.success
                console.log(this.payment.result)
                this.payment.loading = false
                this.payment.result = item.data
            }
        },
        normalPaginate(e) {
            if (e == 'pagination.previous') return 'Назад'
            if (e == 'pagination.next') return 'Вперед'
            return e
        },
    },
    mounted() {
        this.getPayment()
    }
}
</script>