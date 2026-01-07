<template>
    <div class="form col-6">
        <div>
            <label>Тип промокода</label>
            <select class="form-select  form-select-lg" v-model="promo.type">
                <option value="0" selected>Денежный</option>
                <option value="1">К депозиту</option>
                <option value="2">Игра в минах</option>
            </select>
        </div>
        <div class="mt-3">
            <label for="" @click="makeid()">Генерация имени</label>
            <input type="text" class="form-control" placeholder="Название промокода" v-model="promo.name">
        </div>
        <div class="mt-3">
            <label for="">Количество активаций</label>
            <input type="text" class="form-control" placeholder="Количество активаций" v-model="promo.limit">
        </div>
        <div class="mt-3" v-if="promo.type == 2">
            <label for="">Сумма от</label>
            <input type="text" class="form-control" placeholder="Сумма от" v-model="promo.mines.min">
        </div>
        <div class="mt-3" v-if="promo.type == 2">
            <label for="">Сумма до</label>
            <input type="text" class="form-control" placeholder="Сумма до" v-model="promo.mines.max">
        </div>
        <div class="mt-3" v-if="promo.type == 2">
            <label for="">Количество бомб</label>
            <input type="text" class="form-control" placeholder="Количество бомб" v-model="promo.mines.bomb">
        </div>
        <div class="mt-3" v-if="promo.type == 2">
            <label for="">Количество шагов надо сделать</label>
            <input type="text" class="form-control" placeholder="Количество шагов надо сделать"
                v-model="promo.mines.step">
        </div>
        <template v-if="promo.type < 2">
            <div class="mt-3">
                <label for="">Награда за промокод</label>
                <input type="text" class="form-control" placeholder="Награда за промокод" v-model="promo.reward">
            </div>
        </template>
        <div class="mt-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="promo.deposit">
                <label class="form-check-label" for="flexCheckDefault">
                    Активация доступна только для депозитеров
                </label>
            </div>
        </div>
        <div class="mt-3">
            <div class="btn-default blue active" @click="createPromo()">Создать промокод</div>
            <template v-if="promo.type < 2"> {{promo.type == 0 ? `Денежный промокод ${promo.name} - ${promo.reward}
            рублей - ${promo.limit} активаций` :
            `Бонусный промокод ${promo.name} - ${promo.reward}% - ${promo.limit} активаций`}}</template>
        </div>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            promo: {
                name: null,
                reward: 0,
                limit: 0,
                type: 0,
                deposit: 0,
                mines: {
                }
            },
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
        makeid() {
            const length = 7
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() *
                    charactersLength));
            }
            this.promo.name = result;
        }
    },
    mounted() {

    }
}
</script>