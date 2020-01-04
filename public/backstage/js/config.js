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

        commission: 0,
        chips: 0,
    },

    created: function () {
        this.commission = phpVars.commission;
        this.chips = phpVars.chips;
    }
})
