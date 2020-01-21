var vm = new Vue({
    el: '#main',

    data: {
        money: {
            decimal: ',',
            thousands: ',',
            prefix: '$ ',
            suffix: '',
            precision: 0,
        },

        formatNumber: {
            decimal: ',',
            thousands: ',',
            precision: 0,
        },

        events: [],
        lateRegister: '',
        playersLimit: '',
        buy_in: 0,
        commission: 0,
        chips: 0,
        prizePool: '',
        prizes: '',
        search: '',
        selectedEvents: [],
        selected: ''
    },

    created: function () {
        this.buy_in = phpVars.buy_in;
        this.events = phpVars.apiSports;

        if (this.chips != '' || phpVars.config == '') {
            this.chips = phpVars.chips;
            this.commission = phpVars.commission;
        } else {
            this.commission = phpVars.config['commission'];
            this.chips = phpVars.config['chips'];
        }

        this.lateRegister = phpVars.lateRegister;
        this.prizePool = phpVars.prizePool;
        this.prizes = phpVars.prizes;
        this.playersLimit = phpVars.playersLimit;
    },

    methods: {
        switchNameSport: function (a) {
            switch (a) {
                case 1: return "NBA";
                case 2: return "NCAAB";
                case 3: return "NCAAF";
                case 4: return "NFL";
                case 5: return "NHL";
                case 7: return "SOCCER";
                case 11: return "MMA (UFC)";
                case 14: return "KHL";
                case 15: return "AHL";
                case 16: return "SHL";
                case 17: return "17";
                default: return a;
            }
        },

        sendServer: function () {
            axios.get('', {
                userName: 'Fred',
                userEmail: 'Flintstone@gmail.com'
            })

                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        includeEvent: function (event) {
            if (this.selectedEvents.includes(event) === false) {
                this.selectedEvents.push(event);
            }
        },

        removeEvent: function (event) {
            this.selectedEvents =
                [...this.selectedEvents.filter(
                    selected =>
                        selected.HomeTeam !== event.HomeTeam || selected.AwayTeam !== event.AwayTeam
                        || selected.Sport !== event.Sport
                )];
        },
    },

    computed: {

    },
})
