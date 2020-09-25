import Vue from "vue";
import { setup } from "../utils/setup";

import FullLoader from "../components/FullLoader";
import loaderStore from "../stores/loaderStore";
import axios from "axios";
import notificationStore from "../stores/notificationStore";
import AdminForm from "../molecules/admin/AdminForm";
import ActionButton from "../../general/components/ActionButton";
import ModalStart from "../molecules/book/ModalStart";
import ModalCancel from "../molecules/book/ModalCancel";
import ModalFinish from "../molecules/book/ModalFinish";
setup();

new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
        ModalCancel,
        ModalStart,
        ModalFinish,
        AdminForm,
    },

    data: {
        cancelModalId: null,
        cancelModalDesc: null,
        startModalId: null,
        startModalDesc: null,
        finishModalId: null,
        finishModalDesc: null,
    },

    created() {
    },

    mounted() {
        notificationStore.loadAndShow();
    },


    methods: {
        openCancelModal(id, description) {
            this.cancelModalId = id;
            this.cancelModalDesc = description;
        },
        openStartModal(id, description) {
            this.startModalId = id;
            this.startModalDesc = description;
        },
        openFinishModal(id, description) {
            this.finishModalId = id;
            this.finishModalDesc = description;
        },
        closeCancelModal() {
            this.cancelModalId = null;
            this.cancelModalDesc = null;
        },
        closeStartModal() {
            this.startModalId = null;
            this.startModalDesc = null;
        },
        closeFinishModal() {
            this.finishModalId = null;
            this.finishModalDesc = null;
        },

        async cancelEvent(eventId) {
            loaderStore.show();

            try {
                await axios.post(`/book/manage/${eventId}/cancel`);
                this.closeCancelModal();
                notificationStore.infoSync("Event has been cancelled; it may be a short time before this takes effect");

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

        async startEvent(eventId) {
            loaderStore.show();

            try {
                await axios.post(`/book/manage/${eventId}/start`);
                this.closeStartModal();
                notificationStore.infoSync("Event has been moved to in play; it may be a short time before this takes effect");

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

        async finishEvent(args) {
            let eventId = args['id'], homeScore = args['homeScore'], awayScore = args['awayScore'];
            loaderStore.show();

            try {
                await axios.post(`/book/manage/${eventId}/finish`, {homeScore: homeScore, awayScore: awayScore});
                this.closeFinishModal();
                notificationStore.infoSync("Event final results has been recorded; it may be a short time before this takes effect");

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
