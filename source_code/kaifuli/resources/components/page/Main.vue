<template>
    <div class="games__items">
        <router-link to="/jackpot" class="games__item">
            <img src="/img/jackpot__img.png" height="200px">
        </router-link>
        <router-link to="/dice" class="games__item">
            <img src="/img/dice__img.png" height="200px">
        </router-link>
        <router-link to="/mines" class="games__item">
            <img src="/img/mines__img.png" height="200px">
        </router-link>
        <router-link to="/bubbles" class="games__item">
            <img src="/img/bubbles__img.png" height="200px">
        </router-link>
    </div>
</template>
<style>
.games__items {
    display: flex;
}

.games__item {
    flex: auto;
    margin-right: 50px;
}

.games__item:last-child {
    margin-right: 0;
}

.games__item img {
    width: 100%;
    height: 100%;
}

.games__item:hover {
    cursor: pointer;
    transform: translateY(-15px);
    transition: transform .7s;
}

@media(max-width: 1000px) {
    .games__items {
        display: block;
    }

    .games__item {
        margin-right: 0;
    }

    .games__item:last-child {
        margin-bottom: 0;
    }

    .games__item img {
        padding: 15px 0;
    }

}
</style>