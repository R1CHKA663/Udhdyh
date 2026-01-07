<template>
    <div class="rolldiceBox pt-5">
        <div class="text-center">DICE ROLLER</div>
        <div class="rolldice">
            <div class="rolldiceSpan m-auto pt-3 pb-3" :class="{ 'active': animate }">
                <template v-for="key in roll" :key="key">
                    <svg class="die roll-1" style="animation-delay:298ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 1">
                        <title>d6:1</title>
                        <desc>
                            <p>d6:1</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="30" cy="30" r="4" fill="var(--text)"></circle>
                    </svg>
                    <svg class="die roll-2" style="animation-delay:224ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 2">
                        <title>d6:2</title>
                        <desc>
                            <p>d6:2</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="16" cy="44" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="16" r="4" fill="var(--text)"></circle>
                    </svg>
                    <svg class="die roll-2" style="animation-delay:54ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 3">
                        <title>d6:3</title>
                        <desc>
                            <p>d6:3</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="16" cy="44" r="4" fill="var(--text)"></circle>
                        <circle cx="30" cy="30" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="16" r="4" fill="var(--text)"></circle>
                    </svg>
                    <svg class="die roll-2" style="animation-delay:140ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 4">
                        <title>d6:4</title>
                        <desc>
                            <p>d6:4</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="16" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="16" cy="44" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="44" r="4" fill="var(--text)"></circle>
                    </svg>
                    <svg class="die roll-1" style="animation-delay:41ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 5">
                        <title>d6:5</title>
                        <desc>
                            <p>d6:5</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="16" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="16" cy="44" r="4" fill="var(--text)"></circle>
                        <circle cx="30" cy="30" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="44" r="4" fill="var(--text)"></circle>
                    </svg>
                    <svg class="die roll-2" style="animation-delay:15ms" viewBox="0 0 60 60" height="60" width="60"
                        v-if="key == 6">
                        <title>d6:6</title>
                        <desc>
                            <p>d6:6</p>
                        </desc>
                        <path d="M56 8a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v44a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V8Z"
                            fill="var(--glare)"></path>
                        <path d="M5 52a3 3 0 0 0 3 3h44a3 3 0 0 0 3-3H5Z" fill="var(--e)"></path>
                        <circle cx="16" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="16" cy="30" r="4" fill="var(--text)"></circle>
                        <circle cx="16" cy="44" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="16" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="30" r="4" fill="var(--text)"></circle>
                        <circle cx="44" cy="44" r="4" fill="var(--text)"></circle>
                    </svg>
                </template>
                <div class="rollSumBox">
                    <div class="rollSum">{{ games.sum }}</div>
                </div>
            </div>
            <div class="container mt-5 p-3 flex">
                <div class="col-4">
                    <div class="form-floating">
                        <input type="number" class="form-control text-center gameInput" value="30">
                        <label for="floatingInput">Сумма игры</label>
                        <div class="amount flex">
                            <button class="btn-default">x2</button>
                            <button class="btn-default">x/2</button>
                            <button class="btn-default">min</button>
                            <button class="btn-default">max</button>
                        </div>
                    </div>
                    <div class="form-floating mt-5">
                        <input type="number" class="form-control text-center gameInput" v-model="inpRoll">
                        <label for="floatingInput">Количество кубиков</label>
                        <div class="amount flex">
                            <button class="btn-default">x2</button>
                            <button class="btn-default">x/2</button>
                            <button class="btn-default">min</button>
                            <button class="btn-default">max</button>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="flex">
                        <div class="btn-default blue col-5 p-2">Чет</div>
                        <div class="btn-default blue col-5 p-2 ml-1" style="margin-left: 1rem;">Нечет</div>
                    </div>
                    <div class="flex mt-2">
                        <div class="btn-default blue col-5 p-2">Меньше {{ Math.round(inpRoll * 6 / 2) }}</div>
                        <div class="btn-default blue col-5 p-2 ml-1" style="margin-left: 1rem;">Больше
                            {{ Math.round(inpRoll * 6 / 2) }}</div>
                    </div>
                    <div class="flex">
                        <div class="myAlert error m-auto mt-3 flex col-10">
                            xx
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            roll: [3, 2, 5, 4, 5, 6],
            inpRoll: 3,
            games: {
                sum: 0
            },
            animate: true
        }
    },
    methods: {
        getRandom(min, max) {
            // min and max included
            return Math.floor(Math.random() * (max - min + 1) + min)
        },
        async newRender() {
            this.roll = []
            for (let i = 0; i < this.inpRoll; i++) {
                this.roll.push(await this.getRandom(1, 6))
            }
            this.games.sum = this.roll.reduce((p, a) => p + a, 0);
        }
    },
    watch: {
        inpRoll() {
            this.newRender()
        }
    },
    mounted() {
        this.newRender()
    }
}
</script>
<style scoped>
.rolldice {
    position: relative;
}

.pl-0 {
    padding-left: 0px !important;
}

.pr-0 {
    padding-left: 0.5rem !important;
}

.rolldiceBox {
    --glare: white;
    --e: #2A56D9
}

.rolk .btn-default {
    flex: auto;
}

.die {
    zoom: 1.5;
}

.rolldiceSpan.active .die {
    transform: translateY(-300%) rotate(-20deg);
    animation: roll1 1.3s both;
}

.rolldiceSpan {
    justify-content: center;
    text-align: center;
    background-color: #f0f1f6;
    overflow-x: hidden;
}

.rollInp {
    background-color: #f0f1f6;
    border-radius: 3px;
    padding: 2px 0;
    border: 0;
    width: 53px;
    margin-left: 15px;
    text-align: center;
    margin-top: .5rem;
}

.rollSumBox {
    position: absolute;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center;
}

.rollSum {
    background-color: #ced8e8;
    width: 100px;
    border-radius: 4px;
    padding: 8px;
    margin: 0 auto;
    font-size: 20px;
    text-align: center;
    box-shadow: inset 0 0 4px 1px #93a3bc;
}

@keyframes roll1 {
    0% {
        transform: translateY(-300%) rotate(20deg);
        animation-timing-function: ease-in
    }

    38% {
        transform: translateY(0) rotate(-20deg);
        animation-timing-function: ease-out
    }

    55% {
        transform: translateY(-70%) rotate(0);
        animation-timing-function: ease-in
    }

    72% {
        transform: translateY(0) rotate(15deg);
        animation-timing-function: ease-out
    }

    81% {
        transform: translateY(-40%) rotate(0);
        animation-timing-function: ease-in
    }

    90% {
        transform: translateY(0) rotate(-8deg);
        animation-timing-function: ease-out
    }

    95% {
        transform: translateY(-10%) rotate(0);
        animation-timing-function: ease-in
    }

    100% {
        transform: translateY(0) rotate(0);
        animation-timing-function: ease-out
    }
}

@keyframes roll2 {
    0% {
        transform: translateY(-300%) rotate(-20deg);
        animation-timing-function: ease-in
    }

    38% {
        transform: translateY(0) rotate(20deg);
        animation-timing-function: ease-out
    }

    55% {
        transform: translateY(-70%) rotate(0);
        animation-timing-function: ease-in
    }

    72% {
        transform: translateY(0) rotate(-15deg);
        animation-timing-function: ease-out
    }

    81% {
        transform: translateY(-40%) rotate(0);
        animation-timing-function: ease-in
    }

    90% {
        transform: translateY(0) rotate(8deg);
        animation-timing-function: ease-out
    }

    95% {
        transform: translateY(-10%) rotate(0);
        animation-timing-function: ease-in
    }

    100% {
        transform: translateY(0) rotate(0);
        animation-timing-function: ease-out
    }
}
</style>