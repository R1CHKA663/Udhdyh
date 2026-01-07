<template>
    <div class="main">
        <div class="panel-header flex">
            <div class="text-logo">Панель управления</div>
            <router-link class="btn-default blue" :class="{'active' : $route.name == 'stats'}" to="/panel">
                Статистика</router-link>
            <router-link class="btn-default blue" to="/panel/payment" :class="{'active' : $route.name == 'payment'}">
                Пополнения</router-link>
            <router-link class="btn-default blue" :class="{'active' : $route.name == 'withdraws'}"
                to="/panel/withdraws">Выводы
            </router-link>
            <router-link class="btn-default blue" :class="{'active' : $route.name == 'players'}" to="/panel/users/1">
                Игроки
            </router-link>
            <router-link class="btn-default blue" :class="{'active' : $route.name == 'promocode'}"
                to="/panel/promocode">
                Промокоды</router-link>
            <router-link class="btn-default blue" :class="{'active' : $route.name == 'settings'}" to="/panel/settings">
                Настройки</router-link>
        </div>
        <div class="container">
            <div class="content">
                <router-view name="page" @notify="notify"></router-view>
            </div>
        </div>
        <notifications position="bottom right" />
    </div>
</template>
<script>
export default {
    methods: {
        notify(e) {
            this.$notify({
                type: e.type,
                position: 'bottom right',
                title: e.type == 'success' ? 'Успешно' : 'Ошибка',
                text: e.text
            });
        },
    }
}
</script>
<style>
body {
    font-family: 'Rubik' !important;
    font-size: 16px !important;
}

.content {
    margin-top: 3rem;
    margin-bottom: 1rem;
}

.stats-itemBox {
    padding: 20px;
}

.stats-item {
    background-color: white;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    margin-right: 15px;
    padding: 10px 8px;
    font-size: 17px;
}

.flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-default {
    background-color: #d7e0ee;
    border-radius: 5px;
    border: none;
    font-size: 16px;
    font-weight: 500;
    color: #484b5e;
    font-size: 14px;
    text-align: center;
    border-radius: 14px;
    padding: 10px 26px;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 15px;
}

.btn-default:hover {
    cursor: pointer;
    background-image: linear-gradient(180deg, #2A56D9, #2A56D9);
    color: white;
}

.btn-default.active {
    background-image: linear-gradient(180deg, #2A56D9, #2A56D9);
    box-shadow: 0 4px 15px #218cf933;
    color: white;
}

.panel-header {
    margin-top: 20px;
}

.panel-header .btn-default {
    margin-right: 10px;
}

.text-logo {
    font-weight: 600;
    padding: 25px;
    font-size: 21px;
}

tr th:first-child {
    border-bottom-left-radius: 0px;
    border-top-left-radius: 25px;
}

tr th:last-child {
    border-bottom-right-radius: 0px;
    border-top-right-radius: 25px;
}

table {
    width: 100%;
    color: #484b5e;
    text-align: center;
    font-weight: 500;
    color: #556471;
}

th,
td {
    background-color: white !important;
}

table th {
    background-color: white;
    padding: 20px 0;
    font-size: 14px;
    font-weight: 600;
    color: #556471;
    border-collapse: unset;
}

tbody tr:last-child td:first-child {
    border-bottom-left-radius: 25px;
}

tbody tr:last-child td:last-child {
    border-bottom-right-radius: 25px;
}
</style>