<template>
    <div class="walletBox">
        <div class="cardHeaderBox container text-center">
            <div class="cardHeader">
                <h5 class="cards-title">КАССА</h5>
            </div>
        </div>
        <div class="cards container">
            <div class="wallet-header">
                <div class="flex">
                    <router-link to="/wallet/payment" class="btn-default"
                        :class="$route.name === 'payment' ? 'blue' : 'wallet'" ripple>
                        Пополнение</router-link>
                    <router-link to="/wallet/withdraw" class="btn-default"
                        :class="$route.name === 'withdraw' ? 'blue' : 'wallet'" ripple>Вывод</router-link>
                </div>
            </div>
            <router-view name="wallet" @notify="notify" @updateBalance="updateBalance" :user="user"></router-view>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],
    methods: {
        notify(i) {
            this.$emit('notify', i)
        },
        updateBalance(i) {
            this.$emit('updateBalance', i)
        },
    }
}
</script>
<style type="text/css">
.st0 {
    fill: #24A84D;
}

.st1 {
    fill: url(#SVGID_1_);
}

.st2 {
    fill: #F59D1A;
}

.st3 {
    fill: #E7001B;
}

.st4 {
    fill: #FC5F01;
}

.st5 {
    fill: #E30520;
}

.st6 {
    fill: #0081C4;
}

.st7 {
    fill: #0F5787;
}

.st8 {
    fill: url(#SVGID_2_);
}

.wallet-item.active p {
    font-size: 25px;
    margin: 0;
    font-weight: 600;
    color: #2a56d9;
}
</style>