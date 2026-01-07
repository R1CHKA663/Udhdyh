//require('./bootstrap');

import router from "./router.js";
import home from "../components/home.vue";
import { createStore } from "vuex";
import { createRouter, VueRouter } from "vue-router";
import { createApp } from "vue";
import axios from "axios";
import Notifications from "@kyvg/vue3-notification";
import VueClickAway from "vue3-click-away";
import VueSocketIO from "vue-3-socket.io";
import SocketIO from "socket.io-client";
import CountUp from "vue-countup-v3";

const app = createApp(home);

Array.prototype.diff = function (a) {
  return this.filter(function (i) {
    return a.indexOf(i) < 0;
  });
};
const port = 8443;
app.use(
  new VueSocketIO({
    debug: false,
    connection: SocketIO(
      `${window.location.protocol}//${window.location.hostname}:${port}`,
      { transports: ["websocket"] }
    ), //options object is Optional
  })
);
app.config.globalProperties.$axios = axios;
app.config.globalProperties.$format = (e) => {
  return e.toFixed(2);
};
app.config.globalProperties.$getVideoCardInfo = () => {
  var t = document.createElement("canvas").getContext("webgl");
  if (!t)
    return {
      error: "no webgl",
    };
  var e = t.getExtension("WEBGL_debug_renderer_info");
  return e
    ? {
        vendor: t.getParameter(e.UNMASKED_VENDOR_WEBGL),
        renderer: t.getParameter(e.UNMASKED_RENDERER_WEBGL),
      }
    : {
        error: "no WEBGL_debug_renderer_info",
      };
};
app.config.globalProperties.$countUpFormat = {
  options: {
    useGrouping: false,
    decimalPlaces: 2,
    formattingFn: (e) => {
      return e.toFixed(2);
    },
  },
};
app.use(CountUp);
app.use(router);
app.use(Notifications);
app.use(VueClickAway);
app.mount("#root");

//app.use(VueRouter)
