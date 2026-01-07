<template>
    <div class="sectionWallet">
        <div class="walletTop">
            <div class="walletTopBody">
                <div class="title">Выберите метод вывода</div>
                <div class="walletMethodsBox">
                    <div class="wallet-item" :class="{'active': withdraw.system == 'qiwi'}"
                        @click="withdraw.system = 'qiwi'">
                        <img src="/img/qiwi1.svg" width="60px" />
                    </div>
                    <div class="wallet-item" :class="{'active': withdraw.system == 'card'}"
                        @click="withdraw.system = 'card'">
                        <img src="/img/card.svg" width="60px" />
                    </div>
                    <div class="wallet-item" :class="{'active': withdraw.system == 'fkwallet'}"
                        @click="withdraw.system = 'fkwallet'">
                        <img src="/img/fkwallet.svg" width="60px" />
                    </div>
                    <div class="wallet-item" :class="{'active': withdraw.system == 'yoomoney'}"
                        @click="withdraw.system = 'yoomoney'">
                        <img src="/img/yoomoney.svg" width="60px" />
                    </div>
                </div>
            </div>
        </div>
        <div class="walletBottom">
            <div class="walletBottomBody pb-6">
                <div class="d-block col-4 mms">
                    <div class="formWallet mb-3">
                        <div class="title">{{updateInfo.title}}</div>
                        <input type="text" class="form-wallet" :placeholder="updateInfo.placeholder"
                            v-model="withdraw.system_number">
                    </div>
                    <div class="formWallet">
                        <div class="title">Сумма <span v-if="user.balance < withdraw.sum">Недостаточно средств</span>
                        </div>
                        <input type="text" class="form-wallet radius" placeholder="100" v-model="withdraw.sum"
                            @change="newWrite()" @keyup="newWrite()">
                        <div class="amount flex">
                            <button class="btn-default" :class="{ 'active': withdraw.sum == 100 }"
                                @click="withdraw.sum = 100">100</button>
                            <button class="btn-default" :class="{ 'active': withdraw.sum == 300 }"
                                @click="withdraw.sum = 300">300</button>
                            <button class="btn-default" :class="{ 'active': withdraw.sum == 500 }"
                                @click="withdraw.sum = 500">500</button>
                            <button class="btn-default" :class="{ 'active': withdraw.sum == 1000 }"
                                @click="withdraw.sum = 1000">1000</button>
                            <button class="btn-default" :class="{ 'active': withdraw.sum == 5000 }"
                                @click="withdraw.sum = 5000">5000</button>
                        </div>
                        <div class="r">₽</div>
                    </div>
                    <div class="wallet-info desctop">Комиссия: <span>{{this.updateInfo.procent}}% +
                            {{this.updateInfo.rub}}
                            ₽</span> / Лимит одной выплаты:
                        <span>{{this.updateInfo.min}} ₽ - {{this.updateInfo.max}} ₽</span>
                    </div>
                    <div class="wallet-info mobile">Комиссия: <span>{{this.updateInfo.procent}}% +
                            {{this.updateInfo.rub}}
                            ₽</span> <br> Лимит одной выплаты:
                        <span>{{this.updateInfo.min}} ₽ - {{this.updateInfo.max}} ₽</span>
                    </div>
                </div>
                <div class="formWallet">
                    <div class="title">К зачислению</div>
                    <div class="formWallet result m-0 mt-2" style="
                ">{{withdraw.toBeSum.toFixed(2)}} ₽</div>
                </div>
                <div class="formWallet">
                    <button class="btn-default blue wallet" @click="out()" ripple>Вывести <img
                            src="/img/arrow-white.svg" class="iconBtn"></button>
                </div>
            </div>
            <table class="wallet-table mt-3" id="walletTable"
                v-if="withdraw.result.data && withdraw.result.data.length">
                <thead>
                    <tr>
                        <th>СПОСОБ</th>
                        <th>КОШЕЛЕК</th>
                        <th>СУММА</th>
                        <th>РЕЗУЛЬТАТ</th>
                    </tr>
                </thead>
                <tbody :class="{'filter' : withdraw.loading}">
                    <tr class="gameHistory" v-for="key in withdraw.result.data" :key="key.id">
                        <td>
                            <img src="/img/qiwi.png" class="systemIcon" v-if="key.system == 'qiwi'">
                            <img src="/img/card.png" class="systemIcon" v-if="key.system == 'card'">
                            <img src="/img/fkwallet.png" class="systemIcon" v-if="key.system == 'fkwallet'" />
                            <img src="/img/yoomoney.png" class="systemIcon" v-if="key.system == 'yoomoney'">
                        </td>
                        <td>
                            {{key.system_number}}
                        </td>
                        <td class="flex">
                            <div class="valutaBox">
                                <div class="valuta"></div>
                            </div>{{key.sum.toFixed(2)}}
                        </td>
                        <td>
                            <span style="color: #2a56d9" v-if="key.status == 0" class="btn-action-wallet cancel"
                                @click="cancel(key.id)">Отменить</span>
                            <span style="color: #f13b3b" v-if="key.status == 1">Отмена</span>
                            <span style="color: #f13b3b" v-if="key.status == 2">{{key.comment}}</span>
                            <span style="color: #2aad50" v-if="key.status == 3">Выплачено</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav class="mt-3" v-if="withdraw.result.data && withdraw.result.data.length">
                <ul class="pagination">
                    <li class="page-item" :class="{'active' : paginate.active}"
                        v-for="paginate in withdraw.result.links" :key="paginate.id"
                        @click="withdraw.page = Number(paginate.label);getOut()" :disabled="paginate.active">
                        <a class="page-link">
                            {{normalPaginate(paginate.label)}}
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="nou" v-else><strong>N/A</strong>У вас не было выводов</div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],
    data: () => {
        return {
            withdraw: {
                system: 'qiwi',
                system_number: null,
                sum: null,
                page: 1,
                loading: false,
                get: 0,
                toBeSum: 0,
                result: [],
                textSum: null
            },
            checkInfo: [
                {
                    system: 'qiwi',
                    title: 'Номер кошелька',
                    placeholder: '+7XXXXXXXXX',
                    min: 150,
                    max: 5000,
                    procent: 4,
                    rub: 1
                },
                {
                    system: 'card',
                    title: 'Номер карты',
                    placeholder: '4400 XXXX XXXX XXXX',
                    min: 1000,
                    max: 5000,
                    procent: 4,
                    rub: 70
                },
                {
                    system: 'fkwallet',
                    title: 'FK кошелек',
                    placeholder: 'FXXXXXXX',
                    min: 150,
                    max: 10000,
                    procent: 0,
                    rub: 0
                },
                {
                    system: 'yoomoney',
                    title: 'Введите yoomoney',
                    placeholder: '###########',
                    min: 500,
                    max: 5000,
                    procent: 3,
                    rub: 0
                }
            ]
        }
    },
    methods: {
        async out() {
            const result = await this.$axios.post('/api/withdraw/out', {
                system: this.withdraw.system,
                system_number: this.withdraw.system_number,
                sum: this.withdraw.sum,
                videocard: this.$getVideoCardInfo()
            })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.withdraw.result.data.unshift(item.data)
                this.$emit('updateBalance', {
                    balance: item.balance,
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Заявка на вывод создана'
                })
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async cancel(id) {
            const result = await this.$axios.post('/api/withdraw/cancel', { id })
            const data = result.data
            if (data.success) {
                const item = data.success
                var updateWithdraw = this.withdraw.result.data.find(el => el.id == item.id)
                updateWithdraw.status = 1
                this.$emit('updateBalance', {
                    balance: item.balance,
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Вы успешно отменили вывод'
                })
            }
        },
        async getOut() {
            this.withdraw.loading = true
            const result = await this.$axios.post('/api/withdraw/getOut', { page: this.withdraw.page })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.withdraw.loading = false
                this.withdraw.result = item.data
            }
        },
        normalPaginate(e) {
            if (e == 'pagination.previous') return 'Назад'
            if (e == 'pagination.next') return 'Вперед'
            return e
        },
        newWrite() {
            if (this.withdraw.sum < 0) this.withdraw.sum = 1
            if (this.withdraw.sum > 5000) this.withdraw.sum = 5000
            if (this.withdraw.sum >= 100) {
                return this.withdraw.toBeSum = this.withdraw.sum - (this.withdraw.sum * (this.updateInfo.procent / 100)) - this.updateInfo.rub
            }

        }
    },
    computed: {
        updateInfo() {
            return this.checkInfo.find(el => el.system == this.withdraw.system);
        },
        system() {
            return this.withdraw.system
        },
        sum() {
            return this.withdraw.sum
        }
    },
    watch: {
        page() {
            this.getOut()
        },
        system() {
            this.newWrite()
        },
        sum() {
            this.newWrite()
        }


    },
    mounted() {
        this.newWrite()
        this.getOut()
    }
}
</script>
<style>
.pb-6 {
    padding-bottom: 3rem !important;
}

.title span {
    color: #e85b5b;
    position: absolute;
    right: 0;
}

.wallet-info {
    font-weight: 500;
    font-size: 14px;
    color: #556471;
    margin-top: 1rem;
    position: absolute;
    top: 20px;
    right: 30px;
    border: 2px dashed #3f51b526;
    padding: 10px 14px;
    border-radius: 5px;
}

.wallet-info span {
    color: rgb(42, 86, 217)
}

.walletBottomBody {
    position: relative;
}

.wallet-info.mobile {
    display: none;
}

@media(max-width: 1000px) {
    .wallet-info {
        position: inherit;
        padding: 3px 5px;
    }

    .wallet-info.mobile {
        display: block;
    }

    .wallet-info.desctop {
        display: none;
    }
}

@media(max-width: 360px) {
    .wallet-info {
        font-size: 13px;
    }
}

@media(max-width: 400px) {

    .wallet-table tr th,
    .wallet-table tr td {
        font-size: 10px;
    }
}
</style>