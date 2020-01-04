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

        lateRegister: '',
        playersLimit: '',
        buy_in: 0,
        commission: 0,
        chips: 0,
        prizePool: '',
        prizes: '',
    },

    created: function () {
        this.buy_in = phpVars.buy_in;
        this.commission = phpVars.commission;
        this.chips = phpVars.chips;
        this.lateRegister = phpVars.lateRegister;
        this.prizePool = phpVars.prizePool;
        this.prizes = phpVars.prizes;
        this.playersLimit = phpVars.playersLimit;
    }
})