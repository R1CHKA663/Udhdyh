<template>
  <div style="display: flex" v-if="loadingUser">
    <div v-if="user.is_ban" class="col-12 h-100 flex position-absolute">
      <div class="container">
        <div class="d-block">
          <div class="logoBox position-static">
            <img src="/img/logo.svg" alt="" />
          </div>
          <h5 class="mt-2">Вы получили ограничение доступа к сайту.</h5>
          <div class="mt-2">Причина: <span class="text-danger">{{user.is_ban_comment}}</span></div>
          <div class="mt-2">
            <div class="btn-default blue">Техподдержка</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12" v-else>
      <div class="headerBox shadow">
        <router-link to="/" class="logoBox col-2 flex">
          <img src="/img/logo.svg" alt="" />
          <div class="header__online">
            {{online}}</div>
        </router-link>
        <div class="container flex">
          <div class="headerList col-10">
            <div class="hdls" :class="{'show': menu,'none':!menu}">
              <router-link to="/" tag="button" class="headerItem">
                <button class="btn-default" :class="{ 'page': $route.name == 'main' }">Главная</button>
              </router-link>
              <router-link to="/faq" tag="button" class="headerItem">
                <button class="btn-default" :class="{ 'page': $route.name == 'faq' }">FAQ</button>
              </router-link>
              <template v-if="user.auth">
                <router-link to="/bonus" tag="button" class="headerItem">
                  <button class="btn-default" :class="{ 'page': $route.name == 'bonus' }">Бонусы</button>
                </router-link>
                <router-link to="/ref" class="headerItem">
                  <button class="btn-default" :class="{ 'page': $route.name == 'ref' }">Партнерка</button>
                </router-link>
              </template>
              <router-link to="/contact" class="headerItem">
                <button class="btn-default" :class="{ 'page': $route.name == 'contact' }">Контакты</button>
              </router-link>
              <a class="headerItem mobile" href="#chatraChatExpanded" v-if="false">
                <button class="btn-default">Техподдержка</button>
              </a>
            </div>
            <div class="infoUser">
              <div class="balikBox" v-if="user.auth">
                <span class="balikSpan">
                  <div class="balik">
                    <Vue3Odometer :options="options" class="bold transition" :value="user.balance"
                      :formatFunction="(e)=> {return e.toFixed(2)}" />

                    <TransitionGroup name="animateBalance">
                      <div class="animateBalance" v-for="key in animateBalance.sum" :key="key.ids">+{{
                      key.win.toFixed(2)
                      }}
                      </div>
                    </TransitionGroup>
                  </div>
                </span>
                <router-link to="/wallet/payment" class="btn-default btnCashPay" v-if="user.auth" ripple><img
                    src="/img/wallet.svg" height="25px">
                </router-link>
              </div>
              <div class="dropdown" v-if="user.auth" @click="dropUser = !dropUser">
                <div>
                  <img src="/img/tochki.svg" height="25px" class="tochki" />
                </div>
                <div class="shadow dropMy" :class="{'show': dropUser,'none':!dropUser}" v-if="dropUser"
                  v-click-away="()=>{this.dropUser = !this.dropUser}">
                  <div class="d-flex infoUsers">
                    <div class="avatarUser"><img :src="user.img" alt="" /></div>
                    <div>
                      <div class="userName">{{user.name}}</div>
                      <span class="regData">{{user.created_at}}</span>
                    </div>
                  </div>
                  <div class="drops">
                    <a :href="user.social.vk" target="_blank"><img src="/img/vk.svg" height="25px" />Группа ВК</a>
                  </div>
                  <div class="drops">
                    <a :href="user.social.tg" target="_blank"><img src="/img/tg.svg" height="25px" />Телеграм канал</a>
                  </div>
                  <div class="drops" @click="exit()">Выход</div>
                </div>
              </div>
              <div class="btn-default blue" v-if="!user.auth" @click="auth()">Авторизация</div>
            </div>
          </div>
        </div>
      </div>
      <div class="contentBox container m-auto mt-3">
        <div class="leftBox">
          <div class="menuBox">
            <router-link class="menu-item shadow" to="/jackpot" :class="{ 'active': $route.name == 'jackpot' }">
              <div><img src="/img/jackpot.svg" alt="" />
                <div class="games-menu-item">Джекпот</div>
              </div>
            </router-link>
            <router-link class="menu-item shadow" to="/dice" :class="{ 'active': $route.name == 'dice' }">
              <div><img src="/img/dice.svg" alt="" />
                <div class="games-menu-item">Dice</div>
              </div>
            </router-link>
            <router-link class="menu-item shadow" to="/mines" :class="{ 'active': $route.name == 'mines' }">
              <div><img src="/img/bomb.svg" alt="" />
                <div class="games-menu-item">Mines</div>
              </div>
            </router-link>
            <router-link class="menu-item shadow" to="/bubbles" :class="{ 'active': $route.name == 'bubbles' }">
              <div><img src="/img/bubbles.svg" alt="" />
                <div class="games-menu-item">Bubbles</div>
              </div>
            </router-link>
            <div class="menu-item menu" @click="menu = !menu" v-click-away="()=>{menu = false}">
              <div><img src="/img/menu__icon.svg" alt="" />
              </div>
            </div>
          </div>
        </div>
        <div class="content container mt-5">
          <div class="rightBox col-10 m-auto">
            <div style="border-radius: 25px;"
              :class="{ 'shadow': ['dice', 'mines', 'bubbles','jackpot'].find(el => el == $route.name) }">

              <router-view name="page" @updateBalance="updateBalance" @notify="notify" :user="user"></router-view>

            </div>
            <History class="historyTable mt-5"
              v-if="['mines', 'dice', 'main', 'bubbles'].find(el => el == $route.name)" />
            <footer>
              <div class="item">© 2022 KAIFULI.CASH</div>
              <div class="item" @click="$router.push('/policy')">Политика конфиденциальности</div>
              <div class="item" @click="$router.push('/terms')">Пользовательское соглашение</div>
            </footer>
          </div>
        </div>
      </div>
    </div>
    <notifications position="bottom right" />
  </div>
</template>

<script>
import Vue3Odometer from 'vue3-odometer';
import 'odometer/themes/odometer-theme-default.css'
import History from './page/History.vue'
import Chatra from '@chatra/chatra'

export default {
  components: {
    Vue3Odometer,
    History
  },
  data: () => {
    return {
      loadingUser: false,
      online: 0,
      width: 0,
      user: {
        auth: false,
        balance: 0,
        img: null,
        social: {
          bot_tg: "https://t.me/kaifuli_play_bot",
          tg: "https://t.me/kaifuli_play",
          vk: "https://vk.com/kaifuli_play"
        }
      },
      dropUser: false,
      menu: false,
      animateBalance: {
        show: false,
        sum: []
      },
      options: {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.',
        prefix: ',',
        suffix: ',',
      },
      historyGames: []
    }
  },
  methods: {
    updateBalance(i) {
      if (i.win > 0) {
        this.animateBalance.show = true
        const ids = Math.floor(Math.random() * 10000)
        this.animateBalance.sum.unshift({
          ids,
          win: i.win
        })
        this.animateBalance.sum.splice(10, 1)
        setTimeout((ids) => {
          const indexId = this.animateBalance.sum.findIndex(el => el.ids == ids)
          this.animateBalance.sum.splice(indexId, 1)
        }, 1000)
      }
      this.user.balance = i.balance
    },
    async get() {
      await this.$axios.post('/api/get', { videocard: this.$getVideoCardInfo() }).then(result => {
        const data = result.data
        this.loadingUser = true
        if (data.success) {
          const item = data.success
          this.user = item.user
          this.updateBalance({ balance: item.user.balance })
        }
      }).catch(e => {
        this.loadingUser = true
      })
    },
    async exit() {
      const result = await this.$axios.post('/api/exit')
      const data = result.data
      if (data.success) {
        window.location.href = ''
      }
    },
    notify(e) {
      this.$notify({
        type: e.type,
        position: 'bottom right',
        title: e.type == 'success' ? 'Успешно' : 'Ошибка',
        text: e.text
      });
    },
    auth() {
      location.href = `https://oauth.vk.com/authorize?client_id=51436894&redirect_uri=https://kaifuli.cash/api/vk/auth&response_type=code`
    },
    async openSlide(number_slide) {
      window.scrollTo({
        top: -1000,
        behavior: 'smooth'
      });
    }
  },
  watch: {
    $route() {
      this.menu = false;
      this.openSlide('.content')
    },
    pass() {
      if (this.pass == this.password) {
        return this.passwordView = true
      }
    }
  },
  sockets: {
    bindTg(i) {
      if (i.user_id == this.user.id) {
        this.$notify({
          type: e.type,
          position: 'bottom right',
          title: e.type == 'success' ? 'Успешно' : 'Ошибка',
          text: 'Вы успешно привязали телеграм'
        });
      }
    },
    jackpotesWin(e) {
      if (this.user.id == e.user_id) {
        this.notify({
          text: 'Вы успешно сорвали джекпот на сумму ' + e.jackpot_sum.toFixed(2),
          type: 'success'
        })
        this.user.balance += e.jackpot_sum
        return
      }
      this.notify({
        text: 'Игрок ' + e.name + ' сорвал джекпот на сумму ' + e.jackpot_sum.toFixed(2),
        type: 'success'
      })
    },
    online(e) {
      this.online = e
    }
  },
  mounted() {
    let config = {
      ID: 'JbM9b6JZcFD56XPa6',
      setup: {
        //  disabledOnMobile: true,
        colors: {
          buttonText: '#fff',
          buttonBg: '#2A56D9'
        },
        startHidden: true

      }
    }
    //Chatra('init', config)
    this.get()
  }
}
</script>

<style scoped>

</style>