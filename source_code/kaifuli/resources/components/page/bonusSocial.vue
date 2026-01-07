<template>
    <div class="bonusSocial col-12">
        <div class="col-4 bonusCards">
            <div>
                <div class="bonusTopBox">
                    <div class="btn-default yellow flex">
                        <div>{{bonus.tg.toFixed(2)}}</div>
                        <div class="valutaBox bonus">
                            <div class="valuta"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-hm">
                    <h6>Бонус за TELEGRAM</h6>
                </div>
                <div class="desc mt-3"><a :href="user.social.tg" target="_blank">Подпишитесь на наш
                        канал</a> и пришлите <a :href="user.social.bot_tg" target="_blank">нашему
                        боту</a> команду <br><strong>/link
                        {{user.ref_link}}</strong>
                </div>
                <div class="flex gmm mt-3">
                    <div class="socialImg" v-if="user.social">
                        <a :href="user.social ? user.social.vk : null" target="_blank"><img src="/img/vk.svg"
                                height="25px" /></a>
                        <a :href="user.social ? user.social.tg : null" target="_blank"><img src="/img/tg.svg"
                                height="25px" /></a>
                    </div>
                    <div class="getBonusText flex" @click="free('tg')" v-if="!userGet.bonus_tg">Получить <img
                            src="/img/arrow.svg" class="arrow">
                    </div>
                    <div class="getBonusText flex not" v-else>ПОЛУЧЕНО
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 bonusCards">
            <div>
                <div class="bonusTopBox">
                    <div class="btn-default yellow flex">
                        <div>{{bonus.vk.toFixed(2)}}</div>
                        <div class="valutaBox bonus">
                            <div class="valuta"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-hm">
                    <h6>Бонус за ВКОНТАКТЕ</h6>
                </div>
                <div class="desc mt-3"><a :href="user.social.vk" target="_blank">Подпишитесь на группу ВК</a> и напишите
                    <a href="#">любое сообщение в лс</a>
                </div>
                <div class="flex gmm mt-3">
                    <div class="socialImg">
                        <a :href="user.social ? user.social.vk : null" target="_blank"><img src="/img/vk.svg"
                                height="25px" /></a>
                        <a :href="user.social ? user.social.tg : null" target="_blank"><img src="/img/tg.svg"
                                height="25px" /></a>
                    </div>
                    <div class="getBonusText flex" @click="free('vk')" v-if="!userGet.bonus_vk">Получить <img
                            src="/img/arrow.svg" class="arrow">
                    </div>
                    <div class="getBonusText flex not" v-else>ПОЛУЧЕНО
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: [
        'user'
    ],
    data: () => {
        return {
            userGet: {},
            bonus: {
                vk: 0,
                tg: 0
            }
        }
    },
    methods: {
        async free(type) {
            const result = await this.$axios.post('/api/bonus/social/free', { type })
            const data = result.data
            if (data.success) {
                const item = data.success
                this.$emit('updateBalance', {
                    balance: item.balance,
                    win: item.sum
                })
                this.$emit('notify', {
                    type: 'success',
                    text: 'Вы успешно получили ' + item.sum + ' румбиков'
                })
                if (type == 'vk')
                    return this.userGet.bonus_vk = true
                if (type == 'tg')
                    return this.userGet.bonus_tg = true

            }
            if (data.error) {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        },
        async get() {
            const result = await this.$axios.post('/api/bonus/social/get')
            const data = result.data
            if (data.success) {
                const item = data.success
                this.bonus = item.bonus
                this.userGet = item.user
                this.$emit('isLoading')
            }
        }
    },
    mounted() {
        this.get()

    }
}
</script>
<style>
.bonusSocial {
    display: flex;
}

.socialImg img {
    height: 25px;
}
</style>