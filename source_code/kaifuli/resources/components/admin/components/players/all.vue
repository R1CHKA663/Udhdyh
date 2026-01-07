<template>
    <div class="col-12">
        <div class="col-12">
            <input type="text" class="form-control" placeholder="Данные для поиска" v-model="data">
        </div>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Баланс</th>
                    <th>Профиль вк</th>
                    <th>IP</th>
                    <th>Бан</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-list" v-for="key in getUsersPage.data" :key="key.id">
                    <td>{{key.id}}</td>
                    <td>{{key.name}}</td>
                    <td class="text-success">{{key.balance}}</td>
                    <td class="text-info"><a :href="`https://vk.com/id${key.vk_id}`">Перейти</a></td>
                    <td>{{(key.ip)}}</td>
                    <td :class="{'text-danger':key.is_ban == 1,'text-success':key.is_ban == 0}">{{(key.is_ban == 1 ?
                    'Да':'Нет')}}
                    </td>
                    <td>
                        <router-link class="text-info" :to="`/panel/user/${key.id}`">Просмотр</router-link>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="..." v-if="getUsersPage.links">
            <ul class="pagination">
                <router-link :to="`/panel/users/${paginate.label}`" class="page-item"
                    :class="{'active' : paginate.active}" v-for="paginate in getUsersPage.links" :key="paginate.id"
                    @click="page = paginate.label">
                    <a class="page-link">
                        {{$normalPaginate(paginate.label)}}
                    </a>
                </router-link>
            </ul>
        </nav>
    </div>
</template>
<script>
import { useRoute } from "vue-router";
export default {
    data: () => {
        return {
            getUsersPage: [],
            get: [],
            user_id: null,
            user: [],
            page: 1,
            data: null
        }
    },
    methods: {
        async getUsers() {
            const route = await useRoute();
            console.log(route)
            const id = this.$route.params.id
            if (!Number(id)) return
            const result = await this.$axios.get(`/panel/api/users/get?page=${this.page}&data=${this.data}`)
            const data = result.data
            if (data.success) {
                this.getUsersPage = data.success
            }
        }
    },
    computed: {
        page() {
            return this.$route.params.id
        }
    },
    watch: {
        page() {
            this.getUsers()
        },
        data() {
            this.getUsers()
        }
    },
    mounted() {
        this.getUsers()
    }
}
</script>