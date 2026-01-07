import * as Vue from "vue";
import { createRouter, createWebHistory } from "vue-router";
import stats from "./components/stats.vue";
import settings from "./components/settings.vue";
import payment from "./components/payment.vue";

import players_all from "./components/players/all.vue";
import players_get from "./components/players/get.vue";

import promocode from "./components/promocode.vue";
import promo_all from "./components/promo/all.vue";
import promo_create from "./components/promo/create.vue";
import promo_update from "./components/promo/update.vue";

import withdraws from "./components/withdraw/all.vue";
import withdrawsGet from "./components/withdraw/get.vue";

const routes = [
  {
    name: "stats",
    path: "/panel",
    components: {
      page: stats,
    },
  },
  {
    name: "promocode",
    path: "/panel/promocode",
    components: {
      page: promocode,
    },
    children: [
      {
        path: "",
        name: "promo_all",
        components: {
          promo: promo_all,
        },
      },
      {
        path: "create",
        name: "promo_create",
        components: {
          promo: promo_create,
        },
      },
      {
        path: "/panel/promocode/update/:id",
        name: "promo_update",
        components: {
          promo: promo_update,
        },
      },
    ],
  },
  {
    name: "withdraws",
    path: "/panel/withdraws",
    components: {
      page: withdraws,
    },
  },
  {
    name: "withdrawsGet",
    path: "/panel/withdraw/:id",
    components: {
      page: withdrawsGet,
    },
  },
  {
    name: "payment",
    path: "/panel/payment",
    components: {
      page: payment,
    },
  },
  {
    name: "settings",
    path: "/panel/settings",
    components: {
      page: settings,
    },
  },
  {
    name: "users",
    path: "/panel/users/:id",
    components: {
      page: players_all,
    },
  },
  {
    name: "get_user",
    path: "/panel/user/:id",
    components: {
      page: players_get,
    },
  },
];

const router = createRouter({
  routes,
  history: createWebHistory(process.env.BASE_URL),
});
export default router;
