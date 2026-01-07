<template>
  <div class="bonusBox">
    <div class="cardHeaderBox container">
      <div class="cardHeader text-center">
        <h5 class="cards-title">Бонусы</h5>
      </div>
    </div>
    <div class="wallet-header">
      <div class="btnsSection flex">
        <router-link to="/bonus" class="btn-default wallet" :class="{'blue':$route.name == 'bonusPromocode'}" ripple>
          Промокоды
        </router-link>
        <router-link to="/bonus/more" class="btn-default wallet" :class="{'blue':$route.name == 'bonusMore'}" ripple>
          Многоразовые
        </router-link>
        <router-link to="/bonus/repost" class="btn-default wallet" :class="{'blue':$route.name == 'bonusRepost'}">За
          репосты</router-link>
        <router-link to="/bonus/social" class="btn-default wallet" :class="{'blue':$route.name == 'bonusSocial'}"
          ripple>За VK
          И TG</router-link>
        <router-link to="/bonus/rakeback" class="btn-default wallet" :class="{'blue':$route.name == 'bonusRakeback'}"
          ripple>
          RAKEBACK</router-link>
        <router-link to="/bonus/promocoder" class="btn-default wallet" :class="{'blue':$route.name == 'promocoder'}"
          v-if="user.is_promocoder">
          Партнерам</router-link>
      </div>
    </div>
    <div class="cards d-flex position-relative">
      <div class="loading" v-if="loading" style="margin-top: -10px">
        <div class="lds-facebook">
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
      <router-view name="bonus" @notify="notify" @updateBalance="updateBalance" :user="user" @isLoading="isLoading">
      </router-view>
    </div>
  </div>
</template>
<script>
export default {
  props: ['user'],
  data: () => {
    return {
      loading: true
    }
  },
  methods: {
    notify(i) {
      this.$emit('notify', i)
    },
    updateBalance(i) {
      this.$emit('updateBalance', i)
    },
    isLoading() {
      this.loading = false
    }
  },
  watch: {
    $route() {
      this.loading = true
    }
  }
}
</script>
<style>
@media(max-width: 1000px) {
  .btnsSection {
    display: flex !important;
    flex-wrap: wrap;
    justify-content: flex-start !important;
  }

  .btnsSection .btn-default {
    background-image: linear-gradient(45deg, #eff1f7, #f2f4f9);
    color: #72829c;
    display: block;
    background-color: white;
    text-align: center;
    flex: 0 0 50%;
    width: 50%;
    margin-right: 0;
  }

  .wallet-header .btn-default:last-child {
    margin-left: 0px !important;
  }

}
</style>