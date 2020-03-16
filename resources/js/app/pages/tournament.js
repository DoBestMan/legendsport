import Vue from "vue";

const vm = new Vue({
    el: "#main",

    data: {
        isLogin: true,

        tournamentSelected: 1,
        balance: 0,
        title: "selected",
        status: "selected",
        hours: 0,
        players: 0,
        buy: 0,

        userTournamentsActive: ["All sports Fre4all", "Weekend NFL", "Thursday Basketball"],

        tournament: {
            betting: {
                pending: {
                    show: true,
                    show2: false,
                    show3: false,
                    show4: false,
                },
            },
        },
    },

    methods: {
        istabselected: function(indextab) {
            return { active: indextab == this.tournamentSelected };
        },

        showtab: function(index) {
            this.tournamentSelected = index;
            this.balance = 10000;
            this.title = "prueba";
            this.status = "prueba";
            this.hours = 10;
            this.players = 300;
            this.buy = 50;
        },

        pending: function() {
            this.tournament.betting.pending.show = !this.tournament.betting.pending.show;
            this.tournament.betting.pending.show2 = false;
            this.tournament.betting.pending.show3 = false;
            this.tournament.betting.pending.show4 = false;
        },

        history: function() {
            this.tournament.betting.pending.show2 = !this.tournament.betting.pending.show2;
            this.tournament.betting.pending.show = false;
            this.tournament.betting.pending.show3 = false;
            this.tournament.betting.pending.show4 = false;
        },

        straight: function() {
            this.tournament.betting.pending.show3 = !this.tournament.betting.pending.show3;
            this.tournament.betting.pending.show = false;
            this.tournament.betting.pending.show2 = false;
            this.tournament.betting.pending.show4 = false;
        },

        parlay: function() {
            this.tournament.betting.pending.show4 = !this.tournament.betting.pending.show4;
            this.tournament.betting.pending.show = false;
            this.tournament.betting.pending.show2 = false;
            this.tournament.betting.pending.show3 = false;
        },
    },
});
