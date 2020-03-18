import Vue from "vue";
import TournamentContainer from "../molecules/home/TournamentContainer.vue";

new Vue({
    el: "#main",

    components: {
        TournamentContainer,
    },

    data: {
        isLogin: true,
        userTournamentsActive: ["All sports Fre4all", "Weekend NFL", "Thursday Basketball"],
    },
});
