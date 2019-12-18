var vm = new Vue({
    el: '#main',

    data: {
        isLogin: false,

        userTournamentsActive: [
            'All sports Fre4all',
            'Weekend NFL',
            'Thursday Basketball',
            'Jefferson'
        ],

        home: {
            show: false,

            tournaments: [
                {
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
                },
            ],

            info: {
                games: {
                    show: true,
                }
            }
        },

        tournament: {
            betting: {
                pending: {
                    show: true,
                }
            },
        },
    },
})
