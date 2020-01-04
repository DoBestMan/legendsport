var vm = new Vue({
    el: '#main',

    data: {
        money: {
            decimal: ',',
            thousands: '.',
            prefix: '$ ',
            suffix: '',
            precision: 0,
        },

        input: '',
        inputLimit: '',
        lateRegister: '',
        prizePool: '',
        prize: '',
        buy_in: 0,
        commission: 0,
    },

    created: function () {
        this.buy_in = phpVars.buy_in;
        this.commission = phpVars.commission;
        this.lateRegister = phpVars.lateRegister;
        this.prizePool = phpVars.prizePool;
        this.prizePool = phpVars.prize;
    }
})