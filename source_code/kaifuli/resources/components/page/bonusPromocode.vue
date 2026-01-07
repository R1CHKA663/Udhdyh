<template>
    <div class="bonusPromocode col-12">
        <div class="cardsPromo col-5">
            <div class="bonusCards">
                <h6>Активация промокода</h6>
                <input type="text" placeholder="Введите промокод" class="mt-3 promo-form" v-model="promo.name" />
                <button class="btn-default blue mt-3" @click="activePromo()">Активировать <img
                        src="/img/arrow-white.svg" class="iconBtn"></button>
            </div>
        </div>
        <div class="cardsPromo col-7">
            <div class="bonusCards">
                <h6 class="infoStrong">Где можно получать промокоды?</h6>
                <div class="infoBonus">
                    <div class="plusBox">
                        + </div>
                    <div>У наших партнеров (VK, TG)</div>
                </div>
                <div class="infoBonus">
                    <div class="plusBox">
                        + </div>
                    <div>В нашей <strong>группе ВК</strong></div>
                </div>
                <div class="infoBonus">
                    <div class="plusBox">
                        + </div>
                    <div>В нашем <strong>телеграм канале</strong></div>
                </div>
                <div class="infoBonus">
                    <div class="plusBox">
                        + </div>
                    <div>На стримах наших партнеров</div>
                </div>
                <div class="imgBonusCard gift">

                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            promo: {
                name: null
            }
        }
    },
    methods: {
        async activePromo() {
            const result = await this.$axios.post('/api/promo/active', { name: this.promo.name })
            const data = result.data
            if (data.success) {
                this.$emit('updateBalance', {
                    balance: data.balance,
                    win: data.reward
                })
                this.$emit('notify', {
                    type: 'success',
                    text: data.success
                })
            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        }
    },
    mounted() {
        this.$emit('isLoading')
    }
}
</script>
<style>
.bonusPromocode {
    display: flex;
}

.infoBonus {
    display: flex;
    align-items: center;
    margin-bottom: 0.8rem;
}

.infoStrong {
    margin-top: 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
}


.imgBonusCard {
    position: absolute;
    right: 63px;
    top: 100px;
    height: 100px;
    width: 100px;
}

.imgBonusCard.gift {
    background-image: url('/img/gift.svg');
    background-size: 100%;
    opacity: .8;
}

@media(max-width: 1000px) {
    .bonusPromocode {
        display: grid;
    }

    .bonusPromocode .cardsPromo {
        width: 100%;
    }
}

@media(max-width: 450px) {
    .imgBonusCard.gift {
        display: none;
    }
}
</style>