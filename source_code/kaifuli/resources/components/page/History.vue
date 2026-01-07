<template>
    <div class="historyTable" v-if="true">
        <table>
            <thead>
                <tr>
                    <th>ИГРА</th>
                    <th class="mobile-history">НИКНЕЙМ</th>
                    <th>СТАВКА</th>
                    <th class="mobile-history">КОЭФФИЦИЕНТ</th>
                    <th>РЕЗУЛЬТАТ</th>
                </tr>
            </thead>
            <tbody>
                <TransitionGroup name="list">
                    <tr class="gameHistory" v-for="key in historyGames" :key="key.id">
                        <td>
                            <img src="/img/dice__icon.svg" class="historyIcon" v-if="key.game == 'dice'" />
                            <img src="/img/mines__icon.svg" class="historyIcon" v-if="key.game == 'mines'" />
                            <img src="/img/bubbles__icon.svg" class="historyIcon" v-if="key.game == 'bubbles'" />
                        </td>
                        <td class="mobile-history">{{ key.name }}</td>
                        <td class="textResult" :class="{
                          'win': key.result == 'win',
                          'lose': key.result == 'lose'
                        }">
                            <div class="d-flex">
                                <div class="valutaBox">
                                    <div class="valuta"></div>
                                </div>{{ key.bet}}
                            </div>
                        </td>
                        <td class="mobile-history">x{{ key.coff}}</td>
                        <td class="textResult" :class="{
                          'win': key.result == 'win',
                          'lose': key.result == 'lose'
                        }">
                            <div class="flex">
                                <div class="valutaBox">
                                    <div class="valuta"></div>
                                </div> {{ key.win }}
                            </div>
                        </td>
                    </tr>
                </TransitionGroup>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            historyGames: [],
            show: false,
            width: 1500
        }
    },
    sockets: {
        newHistory(game) {
            this.historyGames.splice(9, 2)
            this.historyGames.unshift(game)
        },
        getHistory(e) {
            this.historyGames = e.history
        }
    },
    methods: {
        updateWidth() {
            this.width = window.innerWidth;
        },
        showed() {
            if (this.$route.name == 'history') {
                return this.show = true
            }
        }
    },
    created() {
        window.addEventListener('resize', this.updateWidth);
    },
    watch: {
        width() {
            if (this.width > 1000) {
                this.show = true
            } else {
                this.show = false
            }
            this.showed()
        }
    },
    mounted() {
        this.$socket.emit('getHistory', e => {
            this.historyGames = e.history.reverse()
        })
        this.showed()
        this.updateWidth()
    }
}
</script>
<style scoped>
@media(max-width: 1000px) {
    .mobile-history {
        display: none;
    }

    tr .valutaBox {
        display: none;
    }
}
</style>