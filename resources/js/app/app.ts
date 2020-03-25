import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import App from "./App.vue";
import { createRouter } from "./routing";
import { createStore } from "./store";
import { toDateTime } from "./utils/date/utils";
import { formatOdd } from "./utils/game/bet";

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.filter("toDateTime", toDateTime);
Vue.filter("formatOdd", formatOdd);

const router = createRouter();
const store = createStore();

store.dispatch("tournamentList/load").catch(console.error);
store.dispatch("sport/load").catch(console.error);
store.dispatch("odd/reload").catch(console.error);

setInterval(() => store.dispatch("odd/reload").catch(console.error), 30 * 1000);

new Vue({
    el: "#main",
    router,
    store,
    render: h => h(App),
});
