var vm = new Vue({
    el: '#main',

    data: {
        isLogin: true,
        search: '',

        userTournamentsActive: [
            'All sports Fre4all',
            'Weekend NFL',
            'Thursday Basketball',
        ],

        home: {
            tournaments: [
                {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '299',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: true,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                }, {
                    starts: 'Nov 30 22:00',
                    sports: 'NFL',
                    buy_in: '30k',
                    name: 'Weekend NFL',
                    time_frame: '2 days',
                    status: 'Running',
                    enrolled: '271',
                    players: 'Unlimited',
                    players_db: [
                        {
                            image_url: '',
                            name: 'Paris Michelle',
                            price: '1,000',
                        }, {
                            image_url: '',
                            name: 'Elvis Aaron',
                            price: '900',
                        }, {
                            image_url: '',
                            name: 'Whitney Elizabeth',
                            price: '700',
                        }, {
                            image_url: '',
                            name: 'Debon Smith',
                            price: '650',
                        }, {
                            image_url: '',
                            name: 'Jean Motse',
                            price: '175',
                        },
                    ],
                    selected: false,
                },
            ],

            info: {
                games: {
                    show: false,
                }
            }
        },
    },

    computed: {
        customFilter: function() {
            return this.home.tournaments.filter((tournament) => {
                return tournament.starts.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.sports.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.buy_in.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.name.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.time_frame.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.status.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.enrolled.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || tournament.players.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                ;
            });
        }
      },
    
})