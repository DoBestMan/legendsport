import Vue from "vue";

export default new Vue({
    data() {
        return {
            isVisible: false,
        };
    },

    methods: {
        show() {
            this.isVisible = true;
        },

        hide() {
            this.isVisible = false;
        },
    },
});
