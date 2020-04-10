import Vue from "vue";
import { setup } from "../utils/setup";
import ModalDelete from "../components/ModalDelete";
import FullLoader from "../components/FullLoader";
import loaderStore from "../stores/loaderStore";
import axios from "axios";
import notificationStore from "../stores/notificationStore";
import AdminForm from "../molecules/admin/AdminForm";
import ActionButton from "../../general/components/ActionButton";

setup();

new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
        ModalDelete,
        AdminForm,
    },

    data: {
        name: "",
        password: "",
        errors: {},

        modalDeleteId: null,
        modalDeleteDescription: null,
    },

    created() {
        this.name = phpVars.name;
        this.password = phpVars.password;
    },

    mounted() {
        notificationStore.loadAndShow();
    },

    computed: {
        adminId() {
            return location.pathname.toString().split("/")[2];
        },
    },

    methods: {
        openDeleteModal(id, description) {
            this.modalDeleteId = id;
            this.modalDeleteDescription = description;
        },

        closeDeleteModal() {
            this.modalDeleteId = null;
            this.modalDeleteDescription = null;
        },

        async deleteAdmin(adminId) {
            loaderStore.show();

            try {
                await axios.delete(`/admins/${adminId}`);
                this.closeDeleteModal();
                notificationStore.info("Admin's been deleted.");
                window.location = "/admins";
            } catch (e) {
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async createAdmin() {
            loaderStore.show();

            try {
                await axios.post("/admins", {
                    name: this.name,
                    email: this.email,
                    balance: this.balance * 100,
                    password: this.password,
                });

                notificationStore.info("New admin's been created.");
                window.location = "/admins";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async updateAdmin() {
            loaderStore.show();

            try {
                const body = {
                    name: this.name,
                    email: this.email,
                    balance: this.balance * 100,
                };

                if (this.password) {
                    body.password = this.password;
                }

                await axios.patch(`/admins/${this.adminId}`, body);

                notificationStore.info("Admin's been updated.");
                window.location = "/admins";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },
    },
});
