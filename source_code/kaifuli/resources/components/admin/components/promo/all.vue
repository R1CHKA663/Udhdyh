<template>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Сумма</th>
                    <th>Активаций</th>
                    <th>Осталось</th>
                    <th>Тип</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-list" v-for="key in getPromoPage.data" :key="key.id">
                    <td>{{key.id}}</td>
                    <td :style="{'color: red': key.status == 1}">{{key.name}}</td>
                    <td :class="{'text-success' : key.type == 1,'text-info': key.type == 0}">{{key.reward}}
                        {{key.type == 1 ? '%' : 'рублей'}}</td>
                    <td>{{key.limit}}</td>
                    <td>{{(key.limit - key.limited)}}</td>
                    <td>{{key.type == 0 ? 'Денежный' : 'К депозиту'}}</td>
                    <td class="text-info">
                        <router-link :to="`/panel/promocode/update/${key.id}`">Редактировать</router-link>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="..." v-if="getPromoPage.links">
            <ul class="pagination">
                <li class="page-item" :class="{'active' : paginate.active}" v-for="paginate in getPromoPage.links"
                    :key="paginate.id" @click="get = Number(paginate.label);getPromo()">
                    <a class="page-link">
                        {{$normalPaginate(paginate.label)}}
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            getPromoPage: [],
            id: 0,
        }
    },
    methods: {
        async getPromo() {
            const result = await this.$axios.get(`/panel/api/promo/get?page=${this.id}`)
            const data = result.data
            if (data.success) {
                this.getPromoPage = data.success
            }
        },
    },
    mounted() {
        this.getPromo()
    }
}
</script>