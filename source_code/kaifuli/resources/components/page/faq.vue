<template>
    <div class="faqBox">
        <div class="faqContainer">
            <div class="faq-box shadow" v-for="key in data" :key="key.id" :class="{'active': true}">
                <div class="faq-top">
                    <div class="faq-num">{{key.id}}</div>
                    <div class="faq-title">{{key.title}}</div>
                </div>
                <div class="faq-bottom" v-if="!key.show">{{key.bottom}}
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            data: [{
                id: 1,
                title: 'Через сколько придет выплата?',
                bottom: 'Вывод средств осуществляется от 5 минут до 24 часов с момента создания заявки.'
            },
            {
                id: 2,
                title: 'Как работает реферальная система?',
                bottom: 'Реферальная система - это система вознаграждения участников за привлечение новых клиентов. Наличие этой системы позволяет Вам приглашать людей по своей уникальной ссылке и получать от 15% суммы каждого депозита на счет, процент зависит от реферального уровня.'
            },
            {
                id: 3,
                title: 'Реферал зарегистрировался по моей ссылке и не появился в списке?',
                bottom: 'Значит пользователь ранее переходил по чужой реферальной ссылке.'
            },
            {
                id: 4,
                title: 'Нужен ли депозит для вывода?',
                bottom: 'Да, необходим минимальный депозит для вывода средств.'
            }
                ,
            {
                id: 5,
                title: 'Нужен ли отыгрыш?',
                bottom: 'Да, отыгрыш на депозиты x3'
            },
            {
                id: 6,
                title: 'Есть ли ограничения при выводе бонусных денег?',
                bottom: 'Да, есть ограничения. У активных игроков никаких ограничений.'
            }]
        }
    }
}
</script>
<style scoped>
.faq-top {
    display: flex;
    align-items: center;
}

.faq-box {
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 2rem;
    cursor: pointer;
}

.faq-num {
    font-size: 30px;
    font-weight: 800;
    color: #484b5e;
    padding-right: 15px;
}

.faqBox {
    font-size: 14px;
}

.faq-title {
    font-weight: 600;
}

.faq-bottom {
    animation: height .6s;
}

@keyframes height {
    0% {
        height: 0%;
        opacity: 0;
    }

    100% {
        height: 100%;
        opacity: 1;
    }
}

.faq-box.active .faq-top {
    color: #4b83d9;
}
</style>