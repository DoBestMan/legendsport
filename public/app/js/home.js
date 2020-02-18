var vm = new Vue({
    el: '#main',

    data: {
        isLogin: true,
        search: '',
        type: '',
        sport: '',
        buyIn: '',
        timeFrame: '',
        upcoming: '',
        userTournamentsActive: [
            'All sports Fre4all',
            'Weekend NFL',
            'Thursday Basketball',
        ],

        home: {
            tournaments: null,

            info: {
                games: {
                    show: false,
                }
            }
        },
    },
    created: function () {
        this.home.tournaments = phpVars.tournaments;
    },
    computed: {
        customFilter: function () {
            return this.home.tournaments.filter((tournament) => {
                return tournament.name.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.time_frame.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.state.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.enrolled.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.players.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    ;
            });
        }
    },
    methods: {
        switchNameSport: function (a) {
            switch (a) {
                case 1:
                    return "NBA";
                case 2:
                    return "NCAAB";
                case 3:
                    return "NCAAF";
                case 4:
                    return "NFL";
                case 5:
                    return "NHL";
                case 7:
                    return "SOCCER";
                case 11:
                    return "MMA (UFC)";
                case 14:
                    return "KHL";
                case 15:
                    return "AHL";
                case 16:
                    return "SHL";
                case 17:
                    return "17";
                default:
                    return a;
            }
        },
        filterEvents: function (selected) {
            console.log(selected, this.home.tournaments);
        },
    },
})