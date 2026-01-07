import router from "./router.js";
import home from "./components/home.vue";
import { createRouter, VueRouter } from "vue-router";
import { createApp } from "vue";
import axios from "axios";
import Notifications from "@kyvg/vue3-notification";

const app = createApp(home);
import "./style.css";
app.config.globalProperties.$axios = axios;

app.config.globalProperties.$normalPaginate = (e) => {
  if (e == "&laquo; Previous") return "Назад";
  if (e == "Next &raquo;") return "Вперед";
  return e;
};
Array.prototype.diff = function (a) {
  return this.filter(function (i) {
    return a.indexOf(i) < 0;
  });
};
app.use(router);
app.use(Notifications);
app.mount("#root");

//app.use(VueRouter)
