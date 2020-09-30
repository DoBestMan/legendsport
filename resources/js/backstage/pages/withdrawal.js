import Vue from "vue";
import { setup } from "../utils/setup";

import FullLoader from "../components/FullLoader";
import loaderStore from "../stores/loaderStore";
import axios from "axios";
import notificationStore from "../stores/notificationStore";
import ActionButton from "../../general/components/ActionButton";
import ModalProcess from "../molecules/withdrawal/ModalProcess";
setup();

new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
        ModalProcess,
    },

    data: {
        processModalId: null,
        processModalDesc: null,
    },

    created() {
    },

    mounted() {
        notificationStore.loadAndShow();
    },


    methods: {
        openProcessModal(id, description) {
            this.processModalId = id;
            this.processModalDesc = description;
        },
        closeProcessModal() {
            this.processModalId = null;
            this.processModalDesc = null;
        },

        async processPayment(withdrawalId) {
            loaderStore.show();

            try {
                await axios.post(`/withdrawals/${withdrawalId}/process`);
                this.closeProcessModal();
                notificationStore.info("Payment marked as processed");
                window.location = "/withdrawals/pending";

            } catch (e) {
                let message = e.response.data.message;
                if (message !== undefined && message !== '') {
                    notificationStore.errorSync(message);
                } else {
                    notificationStore.errorSync("Unknown error occurred");
                }
            } finally {
                loaderStore.hide();
            }
        },


    },
});
