import Vue from "vue";
import TournamentList from "../molecules/home/TournamentList";
import FilterContainer from "../molecules/home/FilterContainer";

new Vue({
    el: "#main",

    components: {
        FilterContainer,
        TournamentList,
    },

    data: {
        isLogin: true,
        userTournamentsActive: ["All sports Fre4all", "Weekend NFL", "Thursday Basketball"],

        home: {
            info: {
                games: {
                    show: false,
                },
            },
        },
    },
});
