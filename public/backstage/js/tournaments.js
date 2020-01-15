var vm = new Vue({
    el: '#main',

    data: {
        money: {
            decimal: ',',
            thousands: ',',
            prefix: '$ ',
            suffix: '',
            precision: 0,
            userName: '',
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
        switchNameSport: function (a){
            switch(a){   
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
            // axios.get('/put-something-here').then(response => this.post = response.data);
            axios.post('/put-something-here',{
                userName: 'Fred',
                userEmail: 'Flintstone@gmail.com'
            })

            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        }

    },

    computed: {
        customFilter: function() {
            return this.events.filter((event) => {
                return event.MatchTime.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || event.HomeTeam.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                    || event.AwayTeam.toLowerCase().indexOf(this.search.toLowerCase()) >= 0
                ;
            });
        },
    },
})

$(document).ready(function(){
    $("#enlaceajax").click(function(evento){
       evento.preventDefault();
       
       $("#destino").load("TournamentsController", {nombre: "Pepe", edad: 45}, function(){
          alert("recibidos los datos por ajax");
       });
    });
 })
