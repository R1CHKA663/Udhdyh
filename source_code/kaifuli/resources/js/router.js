import * as Vue from "vue";
import { createRouter, createWebHistory } from "vue-router";
import error from "../components/page/error.vue";
import main from "../components/page/Main.vue";
import dice from "../components/page/dice.vue";
import bonus from "../components/page/bonus.vue";
import mines from "../components/page/mines.vue";
import bubbles from "../components/page/bubbles.vue";
import Jackpot from "../components/page/Jackpot.vue";
import x100 from "../components/page/x100.vue";

import monetrain from "../components/page/monetrain.vue";
import ref from "../components/page/ref.vue";
import contact from "../components/page/contact.vue";
import faq from "../components/page/faq.vue";
import terms from "../components/page/terms.vue";
import policy from "../components/page/policy.vue";

import bonusPromocode from "../components/page/bonusPromocode.vue";
import bonusMore from "../components/page/bonusMore.vue";
import bonusRepost from "../components/page/bonusRepost.vue";
import bonusSocial from "../components/page/bonusSocial.vue";
import bonusRakeback from "../components/page/bonusRakeback.vue";
import promocoder from "../components/page/promocoder.vue";

import wallet from "../components/page/wallet/wallet.vue";
import payment from "../components/page/wallet/payment.vue";
import withdraw from "../components/page/wallet/withdraw.vue";
import History from "../components/page/History.vue";

const routes = [
  {
    name: "error",
    path: "/:catchAll(.*)",
    components: {
      page: error,
    },
  },
  {
    name: "main",
    path: "",
    components: {
      page: main,
    },
  },
  {
    name: "history",
    path: "/history",
    components: {
      page: History,
    },
  },
  {
    name: "dice",
    path: "/dice",
    components: {
      page: dice,
    },
  },
  {
    name: "jackpot",
    path: "/jackpot",
    components: {
      page: Jackpot,
    },
  },
  {
    name: "x100",
    path: "/x100",
    components: {
      page: x100,
    },
  },
  {
    name: "monetrain",
    path: "/monetrain",
    components: {
      page: monetrain,
    },
  },
  {
    name: "bonus",
    path: "/bonus",
    components: {
      page: bonus,
    },
    children: [
      {
        path: "",
        name: "bonusPromocode",
        components: {
          bonus: bonusPromocode,
        },
      },
      {
        path: "more",
        name: "bonusMore",
        components: {
          bonus: bonusMore,
        },
      },
      {
        path: "repost",
        name: "bonusRepost",
        components: {
          bonus: bonusRepost,
        },
      },
      {
        path: "social",
        name: "bonusSocial",
        components: {
          bonus: bonusSocial,
        },
      },
      {
        path: "rakeback",
        name: "bonusRakeback",
        components: {
          bonus: bonusRakeback,
        },
      },
      {
        path: "promocoder",
        name: "promocoder",
        components: {
          bonus: promocoder,
        },
      },
    ],
  },
  {
    name: "faq",
    path: "/faq",
    components: {
      page: faq,
    },
  },
  {
    name: "mines",
    path: "/mines",
    components: {
      page: mines,
    },
  },
  {
    name: "wallet",
    path: "/wallet",
    components: {
      page: wallet,
    },
    children: [
      {
        path: "/wallet/withdraw",
        name: "withdraw",
        components: {
          wallet: withdraw,
        },
      },
      {
        path: "/wallet/payment",
        name: "payment",
        components: {
          wallet: payment,
        },
      },
    ],
  },
  {
    name: "bubbles",
    path: "/bubbles",
    components: {
      page: bubbles,
    },
  },
  {
    name: "ref",
    path: "/ref",
    components: {
      page: ref,
    },
  },
  {
    name: "contact",
    path: "/contact",
    components: {
      page: contact,
    },
  },
  {
    name: "terms",
    path: "/terms",
    components: {
      page: terms,
    },
  },
  {
    name: "policy",
    path: "/policy",
    components: {
      page: policy,
    },
  },
];

const router = createRouter({
  routes,
  history: createWebHistory(),
});
export default router;
