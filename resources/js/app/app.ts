import Pusher from "pusher-js";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex, { Store } from "vuex";
import ToastsPlugin from "vue-bootstrap-toasts";
import App from "./App.vue";
import { createRouter } from "./routing";
import { createStore } from "./store";
import { toTime } from "./utils/date/utils";
import { diffHumanReadable, formatOdd } from "./utils/game/bet";
import { RootState } from "./store/types";
import echo from "./echo";
import { Echo } from "./utils/websockets/Echo";
import { mapOdd, mapResult, mapTournament } from "./api/mappings";
import { formatChip, formatCurrency, formatDollars, toDateTime } from "../general/utils/filters";

// @ts-ignore
window.Pusher = Pusher;

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(ToastsPlugin);
Vue.filter("toDateTime", toDateTime);
Vue.filter("toTime", toTime);
Vue.filter("formatOdd", formatOdd);
Vue.filter("formatChip", formatChip);
Vue.filter("formatCurrency", formatCurrency);
Vue.filter("formatDollars", formatDollars);
Vue.filter("diffHumanReadable", diffHumanReadable);

Object.defineProperty(Vue.prototype, "$stock", {
    get(): Store<RootState> {
        return this.$store;
    },
});

Object.defineProperty(Vue.prototype, "$echo", {
    get(): Echo {
        return echo;
    },
});

const router = createRouter();
const store = createStore();

store.dispatch("user/load").catch(console.error);
store.dispatch("tournamentList/load").catch(console.error);
store.dispatch("sport/load").catch(console.error);
store.dispatch("odd/load").catch(console.error);
store.dispatch("result/load").catch(console.error);

echo.channel("general")
    .listen("odds", (data: any) => {
        store.commit("odd/markAsLoaded", data.odds.map(mapOdd));
    })
    .listen("results", ({ results }: any) => {
        store.commit("result/markAsLoaded", results.map(mapResult));
    })
    .listen("tournament", ({ tournament }: any) => {
        store.commit("tournamentList/createOrUpdateTournament", mapTournament(tournament));
    });

new Vue({
    el: "#main",
    router,
    store,
    render: h => h(App),
});
