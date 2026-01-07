<template>
    <div class="players-get">
        <div class="container row">
            <div class="col-4 text-center">
                <img :src="user.img" class="avatar" />
                <div class="mt-2">
                    <router-link :to="`/panel/user/${user.invited}`" v-if="user.invited">Пригласил</router-link>
                </div>
                <div class="mt-2"><a :href="`https://vk.com/id${user.vk_id}`" target="_blank">Страница вк</a>
                </div>
                <div class="mt-2">ip: <strong>{{user.ip}}</strong></div>
                <div class="mt-2">videocard: <strong>{{user.videocard ? JSON.parse(user.videocard).renderer :
                null}}</strong></div>
                <div class="mt-2">С такой видеокартой: <strong>{{user.countVideoCards}}</strong></div>
                <div class="mt-2">С таким ip: <strong>{{user.countIp}}</strong></div>
                <div class="mt-2" v-if="user.bonus_vk">В группу <strong>vk</strong> вступил</div>
                <div class="mt-2" v-if="user.bonus_tg">На канал в <strong>tg</strong> подписан</div>
                <div class="mt-2 text-success">Депозитов: <strong>{{user.deposit}}</strong></div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="form col-6">
                        <label>Имя</label>
                        <input type="text" class="form-control" v-model="user.name">
                    </div>
                    <div class="form col-6">
                        <label>Баланс</label>
                        <input type="text" class="form-control" v-model="user.balance">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form col-6">
                        <label>Бан</label>
                        <select class="form-select  form-select-lg" v-model="user.is_ban">
                            <option value="0" selected>Нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                    <div class="form col-6">
                        <label>Причина</label>
                        <input type="text" class="form-control" v-model="user.is_ban_comment">
                    </div>
                </div>
                <div class="row">
                    <div class="form col-6">
                        <label>Админ?</label>
                        <select class="form-select  form-select-lg" v-model="user.is_admin">
                            <option value="0" selected>нет</option>
                            <option value="1">Админ</option>
                        </select>
                    </div>
                    <div class="form col-6">
                        <label>Модер?</label>
                        <select class="form-select  form-select-lg" v-model="user.is_moder">
                            <option value="0" selected>нет</option>
                            <option value="1">Модер</option>
                        </select>
                    </div>
                    <div class="form col-6">
                        <label>Ютубер?</label>
                        <select class="form-select  form-select-lg" v-model="user.is_youtuber">
                            <option value="0" selected>нет</option>
                            <option value="1">Ютубер</option>
                        </select>
                    </div>
                    <div class="form col-6">
                        <label>Проверен</label>
                        <select class="form-select  form-select-lg" v-model="user.verified">
                            <option value="0" selected>Не проверен</option>
                            <option value="1">Подозрительный</option>
                            <option value="2">Проверен</option>
                        </select>
                    </div>
                    <div class="form col-6">
                        <label>Комментарий админа</label>
                        <input type="text" class="form-control" v-model="user.comment_admin">
                    </div>
                    <div class="form col-6">
                        <label>Рефералов</label>
                        <input type="text" class="form-control" v-model="user.referalov" disabled>
                    </div>
                    <div class="form col-6">
                        <label>Заработок с рефералов</label>
                        <input type="text" class="form-control" v-model="user.income" disabled>
                    </div>
                    <div class="form col-6">
                        <label>Перешло по рефке</label>
                        <input type="text" class="form-control" v-model="user.clicked" disabled>
                    </div>
                    <div class="form col-6">
                        <label>Сливать его?</label>
                        <select class="form-select  form-select-lg" v-model="user.is_drain">
                            <option value="0" selected>нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                    <div class="form col-6" v-if="Number(user.is_drain)">
                        <label>Шанс слива от (0 до 100)</label>
                        <input type="text" class="form-control" v-model="user.is_drain_chance">
                    </div>
                    <div class="form col-6">
                        <label>Промокодер?</label>
                        <select class="form-select  form-select-lg" v-model="user.is_promocoder">
                            <option value="0" selected>нет</option>
                            <option value="1">Промокодер</option>
                        </select>
                    </div>
                    <div class="form col-6" v-if="Number(user.is_promocoder)">
                        <label>Количество активаций</label>
                        <input type="text" class="form-control" v-model="user.promo_limit">
                    </div>
                    <div class="form col-6" v-if="Number(user.is_promocoder)">
                        <label>Награда за промокод</label>
                        <input type="text" class="form-control" v-model="user.promo_reward">
                    </div>
                    <div class="form col-6" v-if="Number(user.is_promocoder)">
                        <label>Каждые N часов можно создавать промо</label>
                        <input type="text" class="form-control" v-model="user.promo_hours">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="btn-default blue active col-3" @click="updateUser()">Сохранить</div>
                    <div class="btn-default blue col-3 ml-3" @click="getUser()">Обновить</div>
                    <router-link to="/panel/users/1" class="btn-default col-3 ml-3">Назад</router-link>
                </div>
            </div>

            <input type="text" value="10" class="form-control" v-if="false">
            <table
                class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline mt-3">
                <thead>
                    <tr>
                        <th>Тип</th>
                        <th>Информация</th>
                        <th>oldBalance</th>
                        <th>newBalance</th>
                        <th>Время</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="key in log.data" :key="key.id">
                        <td>{{key.type}}</td>
                        <td>{{key.info}}</td>
                        <td>{{key.oldBalance}}</td>
                        <td>{{key.newBalance}}</td>
                        <td>{{key.created_at}}</td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item" :class="{'active' : paginate.active}" v-for="paginate in log.links"
                        :key="paginate.id" @click="page = Number(paginate.label);getLog()" :disabled="paginate.active">
                        <a class="page-link">
                            {{paginate.label}}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
import { useRoute } from "vue-router";

export default {
    data: () => {
        return {
            user: {},
            id: 1,
            log: [],
            page: 1
        }
    },
    methods: {
        async getUser() {
            const result = await this.$axios.post(`/panel/api/user/get`, { id: this.$route.params.id })
            const data = result.data
            if (data.success) {
                this.user = data.success
                this.getLog()
                this.$emit('notify', {
                    type: 'success',
                    text: 'Данные получены'
                })
            }
        },
        async updateUser() {
            const result = await this.$axios.post(`/panel/api/user/update`, { user: this.user })
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
        async getLog() {
            const result = await this.$axios.get(`/panel/api/user/log?user_id=${this.$route.params.id}&page=${this.page}`)
            const data = result.data
            if (data.success) {
                const item = data.success
                this.log = item
            } else {
                this.$emit('notify', {
                    type: 'error',
                    text: data.error
                })
            }
        }
    },
    computed: {
        newId() {
            return this.$route.params.id
        }
    },
    watch: {
        newId() {
            this.getUser()
        }
    },
    async mounted() {
        this.getUser()
    }
}
</script>
<style>
.avatar {
    height: 200px;
    border-radius: 15px;
}
</style>
