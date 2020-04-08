import Vue from "vue";
import { setup } from "../utils/setup";
import ModalDelete from "../components/ModalDelete";
import FullLoader from "../components/FullLoader";
import loaderStore from "../stores/loaderStore";
import axios from "axios";
import notificationStore from "../stores/notificationStore";
import UserForm from "../molecules/user/UserForm";
import ActionButton from "../../general/components/ActionButton";

setup();

new Vue({
    el: "#main",

    components: {
        ActionButton,
        FullLoader,
        ModalDelete,
        UserForm,
    },

    data: {
        name: "",
        email: "",
        password: "",
        balance: 0,
        errors: {},

        modalDeleteId: null,
        modalDeleteDescription: null,
    },

    created() {
        this.name = phpVars.name;
        this.email = phpVars.email;
        this.balance = phpVars.balance / 100;
        this.password = phpVars.password;
    },

    mounted() {
        notificationStore.loadAndShow();
    },

    computed: {
        userId() {
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

        async deleteUser(userId) {
            loaderStore.show();

            try {
                await axios.delete(`/users/${userId}`);
                this.closeDeleteModal();
                notificationStore.info("User's been deleted.");
                window.location = "/users";
            } finally {
                loaderStore.hide();
            }
        },

        async createUser() {
            loaderStore.show();

            try {
                await axios.post("/users", {
                    name: this.name,
                    email: this.email,
                    balance: this.balance * 100,
                    password: this.password,
                });

                notificationStore.info("New user's been created.");
                window.location = "/users";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },

        async updateUser() {
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

                await axios.patch(`/users/${this.userId}`, body);

                notificationStore.info("User's been updated.");
                window.location = "/users";
            } catch (e) {
                this.errors = e.response.data.errors;
                notificationStore.errorSync(e.response.data.message);
            } finally {
                loaderStore.hide();
            }
        },
    },
});
