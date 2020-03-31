import Vue from "vue";
import VueRouter from "vue-router";
import Vuex, { Store } from "vuex";
import ToastsPlugin from "vue-bootstrap-toasts";
import App from "./App.vue";
import { createRouter } from "./routing";
import { createStore } from "./store";
import { toDateTime } from "./utils/date/utils";
import { diffHumanReadable, formatCurrency, formatDollars, formatOdd } from "./utils/game/bet";
import { RootState } from "./store/types";

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(ToastsPlugin);
Vue.filter("toDateTime", toDateTime);
Vue.filter("formatOdd", formatOdd);
Vue.filter("formatCurrency", formatCurrency);
Vue.filter("formatDollars", formatDollars);
Vue.filter("diffHumanReadable", diffHumanReadable);

Object.defineProperty(Vue.prototype, "$stock", {
    get(): Store<RootState> {
        return this.$store;
    },
});

const router = createRouter();
const store = createStore();

store.dispatch("user/load").catch(console.error);
store.dispatch("bet/load").catch(console.error);
store.dispatch("tournamentList/load").catch(console.error);
store.dispatch("sport/load").catch(console.error);
store.dispatch("odd/load").catch(console.error);

setInterval(() => store.dispatch("odd/reload").catch(console.error), 300 * 1000);

new Vue({
    el: "#main",
    router,
    store,
    render: h => h(App),
});
