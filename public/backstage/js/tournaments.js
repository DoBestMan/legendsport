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
        prize_pool: 0,
        buy_in: 0,
        commission: 0,
    },

    created: function () {
        this.prize_pool = phpVars.prize_pool;
        this.buy_in = phpVars.buy_in;
        this.commission = phpVars.commission;
        this.lateRegister = phpVars.lateRegister;
    }
})