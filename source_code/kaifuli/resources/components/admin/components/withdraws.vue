<template>
    <div class="withdraws">
        <div class="col-12" v-if="page == 'all'">
            <div class="col-12">
                <input type="text" class="form-control" placeholder="Данные для поиска">
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Баланс</th>
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
                        <td class="text-success">{{key.balance}}</td>
                        <td class="text-info"><a :href="`https://vk.com/id${key.vk_id}`">Перейти</a></td>
                        <td>{{(key.ip)}}</td>
                        <td :class="{'text-danger':key.ban == 1,'text-success':key.ban == 0}">{{(key.ban == 1 ?
                        'Да':'Нет')}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="..." v-if="getWithdrawsPage.links">
                <ul class="pagination">
                    <li class="page-item" :class="{'active' : paginate.active}"
                        v-for="paginate in getWithdrawsPage.links" :key="paginate.id"
                        @click="get = Number(paginate.label);getUsers()">
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
            getWithdrawsPage: []
        }
    }
}
</script>