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
        name: '',
        playersLimit: '',
        buyIn: 0,
        commission: 0,
        chips: 0,
        lateRegister: '',
        interval: '',
        lateRegisterValue: '',
        prizePool: '',
        prizePoolValue: '',
        prizes: '',
        state: '',
        search: '',
        selectedEvents: [],
        selected: '',
        errors: [],
        message: '',
    },

    created: function () {
        this.name = phpVars.name;
        this.buyIn = phpVars.buyIn;
        this.events = this.getEvents('all');
        this.selectedEvents = phpVars.apiSelectedSports || [];

        if (this.chips != '' || phpVars.config == '') {
            this.chips = phpVars.chips;
            this.commission = phpVars.commission;
        } else {
            this.commission = phpVars.config['commission'];
            this.chips = phpVars.config['chips'];
        }

        this.lateRegister = phpVars.lateRegister;
        this.interval = phpVars.interval;
        this.lateRegisterValue = phpVars.value;
        this.prizePool = phpVars.prizePool;
        this.prizePoolValue = phpVars.prizePoolValue;
        this.prizes = phpVars.prizes;
        this.playersLimit = phpVars.playersLimit;
        this.state = phpVars.state;
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

        sendServer: function () {
            axios.get('', {
                userName: 'Fred',
                userEmail: 'Flintstone@gmail.com'
            })
                .then(function (response) {
                    console.log(response);
                })
                .catch(exceptions => console.log(exceptions));
        },

        includeEvent: function (event) {
            if (this.selectedEvents.includes(event) === false) {
                this.selectedEvents.push(event);
            }
        },

        getEvents: function (sport_name) {
            axios.post('/tournaments/get-events', {
                SelectSport: `${sport_name}`
            })
                .then(response => {
                    this.events = response.data;
                })
                .catch(exceptions => console.log(exceptions));
        },

        removeEvent: function (event) {
            this.selectedEvents = [...this.selectedEvents.filter(
                selected =>
                    selected.HomeTeam !== event.HomeTeam ||
                    selected.AwayTeam !== event.AwayTeam ||
                    selected.Sport !== event.Sport
            )];
        },

        saveEvents: function () {
            var eventsType = [];

            for (selectedEvent of this.selectedEvents) {
                eventsType.push(selectedEvent.Sport);
            }

            axios.post('/tournaments', {
                ApiData: this.selectedEvents,
                name: this.name,
                type: eventsType,
                players_limit: this.playersLimit,
                buy_in: this.buyIn,
                chips: this.chips,
                commission: this.commission,
                late_register: (this.playersLimit == 'Unlimited') ? this.lateRegister : '',
                late_register_rule: {
                    interval: (this.playersLimit == 'Unlimited') ? this.interval : '',
                    value: (this.playersLimit == 'Unlimited') ? this.lateRegisterValue : '',
                },
                prize_pool: {
                    type: this.prizePool || '',
                    fixed_value: this.prizePoolValue || '',
                },
                prizes: {
                    type: this.prizes || '',
                },
                state: this.state,
            })
                .then(response => {
                    if (response.status == '200') {
                        location.replace('/tournaments');
                    }
                })
                .catch(exceptions => {
                    this.errors = exceptions.response.data.errors;
                    this.$toast.error(exceptions.response.data.message, {
                        showProgress: false,
                        rtl: false,
                        timeOut: 5000,
                        closeable: true
                    });
                });
        },
        updateEvent: function () {
            var pathName = location.pathname.toString().split('/');

            var eventsType = [];

            for (event of this.selectedEvents) {
                eventsType.push(event.Sport);
            }

            axios.post('/tournaments/' + pathName[2], {
                ApiData: this.selectedEvents,
                name: this.name,
                type: eventsType,
                players_limit: this.playersLimit,
                buy_in: this.buyIn,
                chips: this.chips,
                commission: this.commission,
                late_register: (this.playersLimit == 'Unlimited') ? this.lateRegister : '',
                late_register_rule: {
                    interval: (this.playersLimit == 'Unlimited') ? this.interval : '',
                    value: (this.playersLimit == 'Unlimited') ? this.lateRegisterValue : '',
                },
                prize_pool: {
                    type: this.prizePool || '',
                    fixed_value: this.prizePoolValue || '',
                },
                prizes: {
                    type: this.prizes || '',
                },
                state: this.state,
                _method: 'patch'
            })
                .then(response => {
                    if (response.status == '200') {
                        location.replace('/tournaments');
                    }
                })
                .catch(exceptions => {
                    this.errors = exceptions.response.data.errors;
                    this.$toast.error(exceptions.response.data.message, {
                        showProgress: false,
                        rtl: false,
                        timeOut: 5000,
                        closeable: true
                    });
                });
        },
    },

    computed: {

    },
})