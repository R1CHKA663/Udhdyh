<template>
    <div class="withdraws">
        <div class="col-12">
            <div class="col-12">
                <input type="text" class="form-control" placeholder="Данные для поиска">
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Проверка</th>
                        <th>Система</th>
                        <th>Номер</th>
                        <th>Сумма</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-list" v-for="key in getWithdrawsPage.data" :key="key.id">
                        <td>{{key.id}}</td>
                        <td>{{key.name}}</td>
                        <td
                            :class="{'text-danger': key.verified == 0,'text-warning': key.verified == 1,'text-success': key.verified == 2}">
                            <span v-if="key.verified == 0">Не проверен</span>
                            <span v-if="key.verified == 1">Подозрительный</span>
                            <span v-if="key.verified == 2">Проверен</span>
                        </td>
                        <td>{{key.system}}</td>
                        <td>{{key.system_number}}</td>
                        <td>{{key.sum}}</td>
                        <td>
                            <router-link :to="`/panel/withdraw/${key.id}`">Обработать</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="..." v-if="getWithdrawsPage.links">
                <ul class="pagination">
                    <li class="page-item" :class="{'active' : paginate.active}"
                        v-for="paginate in getWithdrawsPage.links" :key="paginate.id"
                        @click="id = Number(paginate.label);getWithdraw()">
                        <a class="page-link">
                            {{$normalPaginate(paginate.label)}}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            getWithdrawsPage: [],
            id: 1
        }
    },
    methods: {
        async getWithdraw() {
            const result = await this.$axios.get(`/panel/api/withdraws/all?page=${this.id}`)
            const data = result.data
            if (data.success) {
                this.getWithdrawsPage = data.success
            }
        },
    },
    mounted() {
        this.getWithdraw()
    }
}
</script>