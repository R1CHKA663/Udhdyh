<template>
    <div>
        <div class="">
            <p class="text-danger" v-if="getWithdraws.multi1 > 1">ЕСТЬ ПОХОЖИЙ IP АДРЕС!</p>
            <p class="text-danger" v-if="getWithdraws.multi2 > 1">ЕСТЬ ПОХОЖАЯ ВИДЕОКАРТА</p>
            <p class="text-danger" v-if="getWithdraws.is_ban">Пользователь заблокирован</p>
            <p class="text-danger" v-if="getWithdraws.vivod > 0">У пользователя есть подтвержденный вывод</p>
            <div class="form col-6">
                <label>Игрок</label>
                <strong class="ml-2">
                    <router-link :to="`/panel/user/${getWithdraws.user_id}`">{{getWithdraws.name}}</router-link>
                </strong>
            </div>
            <div class="form col-6">
                <label>Статус игрока</label>
                <strong class="ml-2">
                    <span v-if="getWithdraws.verified == 0">Не проверен</span>
                    <span v-if="getWithdraws.verified == 1">Подозрительный</span>
                    <span v-if="getWithdraws.verified == 2">Проверен</span>
                </strong>
            </div>
            <div class="form col-6">
                <label>Комментарий админа для пользователя</label>
                <input type="text" class="form-control" v-model="getWithdraws.comment_admin">
            </div>
            <div class="form col-6">
                <label>Система</label>
                <input type="text" class="form-control" v-model="getWithdraws.system">
            </div>
            <div class="form col-6">
                <label>Номер</label>
                <input type="text" class="form-control" v-model="getWithdraws.system_number">
            </div>
            <div class="form col-6">
                <label>Сумма</label>
                <input type="text" class="form-control" v-model="getWithdraws.sum">
            </div>
            <div class="form col-6">
                <label>IP игрока</label>
                <input type="text" class="form-control" v-model="getWithdraws.ip">
            </div>
            <div class="form col-6">
                <label>Видеокарта</label>
                <input type="text" class="form-control" v-model="getWithdraws.videocard">
            </div>
            <div class="form col-6">
                <label>Статус вывода</label>
                <select class="form-select  form-select-lg" v-model="getWithdraws.status">
                    <option value="0" selected>В ожидании</option>
                    <option value="1">Отменен</option>
                    <option value="2">Отменен админом</option>
                    <option value="3">Оплачено</option>
                </select>
            </div>
            <div class="form col-6" v-if="Number(getWithdraws.status) == 2">
                <label>Комментарий, если вывод отменен</label>
                <input type="text" class="form-control" v-model="getWithdraws.comment" />
            </div>
            <div class="row">
                <div class="btn-default active mt-3 col-3" @click="withdrawUpdate">Изменить</div>
                <div class="btn-default  mt-3 col-3" @click="getWithdraw()">Обновить</div>
            </div>
        </div>
    </div>
</template>
<script>
import { useRoute } from 'vue-router'

export default {
    data: () => {
        return {
            getWithdraws: [],
            id: 1
        }
    },
    methods: {
        async getWithdraw() {
            const route = useRoute();
            const id = route.params.id;

            const result = await this.$axios.post(`/panel/api/withdraws/get`, { id })
            const data = result.data
            if (data.success) {
                this.getWithdraws = data.success
            }
        },
        async withdrawUpdate() {
            const result = await this.$axios.post(`/panel/api/withdraws/update`, { data: this.getWithdraws })
            const data = result.data
            if (data.success) {
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
        },
    },
    mounted() {
        this.getWithdraw()
    }
}
</script>