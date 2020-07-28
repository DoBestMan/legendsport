import Vue from "vue";
import axios from "axios";
import { setup } from "../utils/setup.js";
import loaderStore from "../stores/loaderStore";
import FullLoader from "../components/FullLoader";
import ActionButton from "../../general/components/ActionButton";

setup();

const vm = new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
    },

    data: {
        money: {
            decimal: ",",
            thousands: ",",
            prefix: "$ ",
            suffix: "",
            precision: 0,
        },

        formatNumber: {
            decimal: ",",
            thousands: ",",
            precision: 0,
            max: 10000,
        },

        commission: phpVars.commission / 100,
        chips: phpVars.chips,
        keepCompleted: phpVars.keepCompleted,
        providers: phpVars.providers,
    },

    methods: {
        async updateConfig() {
            loaderStore.show();

            try {
                await axios.put("/config", {
                    config: {
                        commission: this.commission * 100,
                        chips: this.chips,
                        keep_completed: this.keepCompleted,
                        providers: this.providers,
                    },
                });

                this.$toast.info("Config's been updated.", {
                    showProgress: false,
                });
            } finally {
                loaderStore.hide();
            }
        },
    },
});
