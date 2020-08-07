import Pusher from "pusher-js";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex, { Store } from "vuex";
import ToastsPlugin from "vue-bootstrap-toasts";
import App from "./App.vue";
import { createRouter } from "./routing";
import { createStore } from "./store";
import { toTime } from "./utils/date/utils";
import { diffHumanReadable, signedNumber } from "./utils/game/bet";
import { RootState } from "./store/types";
import echo from "./echo";
import { Echo } from "./utils/websockets/Echo";
import { mapMe, mapOdd, mapTournament } from "./api/mappings";
import {
    formatChip,
    formatCurrency,
    formatDollars,
    toDateTime,
    capitalize,
} from "../general/utils/filters";
import { saveWindows } from "./utils/local-storage/LocalStorageManager";
import { score } from "./utils/game/result";
import BootstrapVue from "bootstrap-vue";

import "../../sass/style.css";

// @ts-ignore
window.Pusher = Pusher;

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(BootstrapVue);
Vue.use(ToastsPlugin);
Vue.filter("toDateTime", toDateTime);
Vue.filter("toTime", toTime);
Vue.filter("signedNumber", signedNumber);
Vue.filter("formatChip", formatChip);
Vue.filter("formatCurrency", formatCurrency);
Vue.filter("formatDollars", formatDollars);
Vue.filter("diffHumanReadable", diffHumanReadable);
Vue.filter("capitalize", capitalize);
Vue.filter("score", score);

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

store.watch(state => state.window._windows, saveWindows);

store.watch(
    state => state.user.user,
    (newVal, oldVal) => {
        if (newVal && !oldVal) {
            echo.private(`user.${newVal.id}`).listen("me", ({ user }: any) => {
                store.commit("user/markAsLoaded", mapMe(user));
            });
        } else if (!newVal && oldVal) {
            echo.leave(`private-user.${oldVal.id}`);
        }
    },
);

echo.channel("general")
    .listen("odds", (data: any) => {
        store.commit("odd/markAsLoaded", data.odds.map(mapOdd));
    })
    .listen("tournament", ({ tournament }: any) => {
        store.commit("tournamentList/createOrUpdateTournament", mapTournament(tournament));
    });

store
    .dispatch("user/load")
    .then(function() {
        if (!!store.state.user.user) {
            store.dispatch("tournamentHistoryList/load").catch(console.error);
        }
    })
    .catch(console.error);
store.dispatch("tournamentList/load").catch(console.error);
store.dispatch("sport/load").catch(console.error);
store.dispatch("odd/load").catch(console.error);

new Vue({
    el: "#main",
    router,
    store,
    render: h => h(App),
});
