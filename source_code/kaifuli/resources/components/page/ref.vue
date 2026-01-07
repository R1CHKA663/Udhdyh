<template>
    <div class="refBox">
        <div class="cardHeaderBox container text-center">
            <div class="cardHeader">
                <h5 class="cards-title">Реферальная программа </h5>
            </div>
        </div>
        <div class="cards  p-0">
            <div class="container  p-0">
                <div class="refContainer mb-5 flex align-items-start">
                    <div class="loading" v-if="loading">
                        <div class="lds-facebook">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="leftRefBox col-8">
                        <div class="topLeftRefBox">
                            <div class="refCards info-desc">
                                <strong>Информация</strong>
                                <div class="mt-3 d-flex">
                                    <strong style="color: rgb(42 86 217);display: flex;">5.00 <div class="valutaBox">
                                            <div class="valuta"></div>
                                        </div></strong> за приведенного вами реферала, если он привяжет VK и TG
                                </div>
                                <div class="mt-2"><strong>{{lvlData('procent')}} %</strong> за каждое пополнение вашим
                                    рефералом</div>
                                <div class="mt-2">Ваш текущий результат - <strong>{{lvlData('lvl')}} уровень</strong>
                                </div>
                            </div>
                            <div class="refCards mt-4">
                                <div>Реферальная ссылка</div>
                                <div class="position-relative">
                                    <input type="text" :value="`https://kaifuli.cash/ref/${ref.ref_link}`"
                                        class="form-ref" id="link">
                                    <img src="/img/copy.svg" class="iconRefCopy" @click="copy('link')">
                                </div>
                            </div>
                        </div>
                        <div class="bottomLeftRefBox mt-4">
                            <div class="itemStat refCards">
                                <h3>{{ ref.clicked }}</h3>
                                <div>Перешло</div>
                            </div>
                            <div class="itemStat refCards">
                                <h3>{{ ref.income_all.toFixed(2) }}</h3>
                                <div>Доход</div>
                            </div>
                            <div class="itemStat refCards">
                                <h3>{{ ref.referalov }}</h3>
                                <div>Рефералов</div>
                            </div>
                            <div class="itemStat rewading">
                                <h6 class="refCards flex">{{ ref.income.toFixed(2) }} <div class="valutaBox">
                                        <div class="valuta"></div>
                                    </div>
                                </h6>
                                <button class="btn-default blue mt-2" @click="out()" ripple>Вывести</button>
                            </div>
                        </div>
                    </div>
                    <div class="rightRefBox col-4">
                        <div class="refCards ">
                            <div class="refProgessItem active">
                                <div class="lvlRef">Уровень 1</div>
                                <div class="procRef ml-1">8%</div>
                                <div class="countRef">
                                    <div>0</div>
                                    <img src="/img/profile.svg" class="iconRefProgress">
                                </div>
                            </div>
                            <div class="refProgessItem">
                                <div class="lvlRef">Уровень 2</div>
                                <div class="procRef ml-1">10%</div>
                                <div class="countRef">
                                    <div>50</div>
                                    <img src="/img/profile.svg" class="iconRefProgress">
                                </div>
                            </div>
                            <div class="refProgessItem">
                                <div class="lvlRef">Уровень 3</div>
                                <div class="procRef ml-1">12%</div>
                                <div class="countRef">
                                    <div>150</div>
                                    <img src="/img/profile.svg" class="iconRefProgress">
                                </div>
                            </div>
                            <div class="refProgessItem">
                                <div class="lvlRef">Уровень 4</div>
                                <div class="procRef ml-1">15%</div>
                                <div class="countRef">
                                    <div>500</div>
                                    <img src="/img/profile.svg" class="iconRefProgress">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            loading: true,
            ref: {
                id: 0,
                clicked: 0,
                income_all: 0,
                referalov: 0,
                income: 0
            },
        }
    },
    methods: {
        async getRef() {
            const result = await this.$axios.post('/api/user/getRef')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.ref = item.user
            }
            this.loading = false
        },
        async out() {
            const result = await this.$axios.post('/api/user/outRef')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.sum
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Вы успешно сняли ' + item.sum
                })
                this.ref.income = 0
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        copy(x) {
            var copyText = document.getElementById(x);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            this.$emit('notify', {
                type: 'success',
                text: 'Скопировано!'
            })
        },
        lvlData(e) {
            const referalov = this.ref.referalov
            var procent = 8
            var lvl = 1
            if (referalov >= 0 && referalov < 50) procent = '8.00', lvl = 1
            if (referalov >= 50 && referalov < 150) procent = '10.00', lvl = 2
            if (referalov >= 150 && referalov < 500) procent = '12.00', lvl = 3
            if (referalov >= 500) procent = '15.00', lvl = 4
            if (e == 'procent') return procent
            if (e == 'lvl') return lvl
        }
    },
    mounted() {
        this.getRef()
    }
}
</script>
<style scoped>
strong {
    font-weight: bolder;
    color: #404d58;
}
</style>