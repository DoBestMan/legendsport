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
        Buy_in: 0,
        Commission: 0,
    },
})