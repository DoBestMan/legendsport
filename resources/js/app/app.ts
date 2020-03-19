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

new Vue({
    el: "#main",
    router,
    store,
    components: { App },
    data: {
        isLogin: true,
        userTournamentsActive: ["All sports Fre4all", "Weekend NFL", "Thursday Basketball"],
    },
    methods: {
        istabselected() {
            return true;
        },

        showtab() {
            // TODO Implement it
        },
    },
});
