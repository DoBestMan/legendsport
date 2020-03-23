import Vue from "vue";
import { createRouter } from "./routing";
import App from "./App.vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import { createStore } from "./store";

Vue.use(VueRouter);
Vue.use(Vuex);

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
