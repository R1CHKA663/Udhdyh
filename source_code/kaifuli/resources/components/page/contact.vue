<template>
    <div class="contactBox">
        <p>Наши официальные и единственные аккаунты в социальных сетях!</p>
        <div class="cntc shadow">
            <img src="/img/tg.svg" />
            <div>
                <div>
                    Наш единственный телеграм канал, где мы <strong>постоянно</strong> публикуем
                    <strong>промокоды</strong> на наш
                    сервис.
                    <div><strong class="contact-spec">
                            <div class="plusBox"> + </div>Экслюзивные новости о сайте
                        </strong></div>

                </div>
                <div class="mt-1">
                    <a :href="user.social ? user.social.tg : null" target="_blank">Перейти в наш телеграм канал</a>
                </div>
            </div>
        </div>
        <div class="cntc shadow">
            <img src="/img/vk.svg" />
            <div>
                <div>
                    Наша единственная группа ВКонтакте, где мы <strong>постоянно</strong> публикуем
                    <strong>промокоды</strong> на наш
                    сервис.
                    <div><strong class="contact-spec">
                            <div class="plusBox"> + </div>Постим честные отзывы от наших игроков
                        </strong></div>
                    <div><strong class="contact-spec">
                            <div class="plusBox"> + </div>Промокоды в рассылке
                        </strong></div>
                </div>
                <div class="mt-1">
                    <a :href="user.social ? user.social.vk : null" target="_blank">Перейти в нашу группу ВКонтакте</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user']
}
</script>
<style scoped>
p {
    color: #424446;
    font-weight: 600;
}

img {
    height: 50px;
    padding-right: 2rem;
}

.contact-spec {
    display: flex;
    align-items: center;
    margin: 10px 0;
}

.cntc {
    display: flex;
    align-items: center;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 2rem;
}

strong {
    color: #424446;
}

@media(max-width: 1000px) {
    .cntc {
        display: grid;
    }

    img {
        margin: 0 auto;
        padding-right: 0;
        margin-bottom: 1rem;
        margin-top: 1rem;
    }
}
</style>