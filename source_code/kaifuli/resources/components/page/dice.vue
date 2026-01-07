<template>
  <div class="diceBox">
    <div class="cardHeaderBox container">
      <div class="cardHeader">
        Dice »
        <span class="text-body tittle">Угадаешь победный диапазон?</span>
      </div>
    </div>
    <div class="cards">
      <div class="container">
        <div class="diceBottom flex mb-5">
          <div class="freeBonusBox flex">
            <div>
              <div class="jackpot-na-cony" style="
                position: initial;
            ">
                <div class="copilka"><img src="/img/copilka.svg" alt=""></div>
                <div class="text">ДЖЕКПОТ:</div>
                <div class="sum">
                  <count-up :start-val="1" :end-val="jackpotSum" :options="$countUpFormat.options">
                  </count-up>
                </div>
              </div>
            </div>
          </div>
          <div class=" diceLeft col-4">
            <div class="diceInputRow d-block mt-5">
              <div class="form-floating">
                <input type="text" class="form-control text-center gameInput" v-model="bet"
                  @change="updateForm('bet')" />
                <label for="floatingInput">Сумма игры</label>
                <div class="amount flex">
                  <button class="btn-default" @click="bet *= 2;updateForm('bet')">x2</button>
                  <button class="btn-default" @click="bet >  2 ? bet /= 2:bet = 1;updateForm('bet')">x/2</button>
                  <button class="btn-default" @click="bet = 1;updateForm('bet')">min</button>
                  <button class="btn-default" @click="bet = user.balance.toFixed(2)">max</button>
                </div>
              </div>
              <div class="form-floating mt-4">
                <input type="text" class="form-control text-center gameInput" v-model="chance"
                  @change="updateForm('chance')" />
                <label for="floatingInput">Процент игры</label>
                <div class="amount flex">
                  <button class="btn-default" @click="chance *= 2;updateForm('chance')">x2</button>
                  <button class="btn-default"
                    @click="chance > 2 ? chance /= 2:chance = 1;;updateForm('chance')">x/2</button>
                  <button class="btn-default" @click="chance = 1;updateForm('chance')">min</button>
                  <button class="btn-default" @click="chance = 95;updateForm('chance')">max</button>
                </div>
              </div>
            </div>
          </div>
          <div class="diceRight col-8">
            <div class="m-auto col-8 mh">
              <div class="vozmWin text-center mt-5">
                <h3 class="flex mt-3">
                  <div class="valutaBox">
                    <div class="valuta"></div>
                  </div>
                  <count-up :start-val="1" :end-val="win" :options="$countUpFormat.options" class="vozmWin">
                  </count-up>
                </h3>
                <h6>Возможный выигрыш</h6>
              </div>
              <div class="diceBtnRow flex mt-4 ml-0">
                <div class="diceBtn p-0 ml-0" style="padding-right: 5px !important">
                  <div class="mm text-body text-center">
                    0 - {{ min }}
                  </div>
                  <button class="btn-default blue" @click="play('down')">Меньше</button>
                </div>
                <div class="diceBtn" style="padding-left: 5px !important">
                  <div class="mm text-body text-center">
                    {{ max }} - 999999
                  </div>
                  <button class="btn-default blue" @click="play('up')">Меньше</button>
                </div>
              </div>
              <div class="myAlert m-auto mt-3 flex" :class="result.status" v-if="result.status">
                <template v-if="result.status == 'success'">
                  Выигрыш
                  <div class="valutaBox wh">
                    <div class="valuta wh"></div>
                  </div>
                  {{ result.win.toFixed(2) }}
                </template>
                <template v-if="result.status == 'error'">
                  {{ result.text }}
                </template>
                <template v-if="result.status == 'info'">
                  <div class="lds-facebook">
                    <div></div>
                    <div></div>
                    <div></div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import CountUp from "vue-countup-v3";

export default {
  components: { CountUp },
  props: ['user'],
  data: () => {
    return {
      bet: 1,
      chance: 50,
      win: 2,
      min: 0,
      max: 0,
      result: {
        status: null,
        rand_num: null,
        win: null,
        text: null
      },
      jackpotSum: 0
    }
  },
  sockets: {
    jackpotGameSum(e) {
      console.log(e)
      this.jackpotSum = e
    }
  },
  methods: {
    updateData() {
      if (this.bet < 0) this.bet = 1.00
      if (this.bet > 1000) this.bet = 1000
      if (this.chance < 0) this.chance = 1.00
      if (this.chance > 95) this.chance = 95.00
      this.win = 100 / this.chance * this.bet
      this.min = Math.floor((this.chance / 100) * 999999);
      this.max = Math.floor(999999 - (this.chance / 100) * 999999);
    },
    async play(btn) {
      if (this.result.status == 'info') return
      this.result.status = 'info'
      const result = await this.$axios.post('/api/dice/play', {
        bet: Number(this.bet),
        chance: Number(this.chance),
        btn
      })
      const data = result.data
      if (data.success) {
        const item = data.success
        this.result = item
        item.status == 'win' ? this.result.status = 'success' : this.result.status = 'error'
        this.$emit('updateBalance', {
          balance: item.balance,
          win: item.win - item.bet
        })
      }
      if (data.error) {
        this.result.status = 'error'
        this.result.text = data.error
      }
    },
    updateForm(e) {
      if (e == 'bet') return this.bet > 0 ? this.bet = Number(this.bet).toFixed(2) : this.bet = "1.00"
      if (e == 'chance') return this.chance > 0 ? this.chance = Number(this.chance).toFixed(2) : this.chance = "1.00"
    }
  },
  watch: {
    bet: function () {
      return this.updateData()
    },
    chance: function () {
      return this.updateData()
    }
  },
  async mounted() {
    this.updateForm('bet')
    this.updateForm('chance')
    this.updateData()
  }
}
</script>