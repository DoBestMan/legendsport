<template>
    <b-modal
        modal-class="modal-text-lg modal-auth"
        :hide-footer="true"
        :visible="isVisible"
        @change="updateVisibility"
    >
        <template v-slot:modal-header>
            <div class="nav nav-tabs nav-auth">
                <div
                    class="nav-item nav-link"
                    :class="{ active: isSignInActive }"
                    @click="activateSignInTab"
                >
                    Sign In
                </div>
                <div
                    class="nav-item nav-link"
                    :class="{ active: isSignUpActive }"
                    @click="activateSignUpTab"
                >
                    Sign Up
                </div>
            </div>
        </template>

        <template v-slot:default>
            <SignInForm v-if="isSignInActive" @success="onSuccess" />
            <SignUpForm v-else @success="onSuccess" />
        </template>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import { AuthModalTab } from "../../store/modules/authModal";
import SignUpForm from "./SignUpForm.vue";
import SignInForm from "./SignInForm.vue";

export default Vue.extend({
    name: "ModalAuth",
    components: { BModal, SignInForm, SignUpForm },

    computed: {
        isVisible(): boolean {
            return this.$stock.state.authModal.isVisible;
        },

        isSignInActive(): boolean {
            return this.$stock.state.authModal.tab === AuthModalTab.SignIn;
        },

        isSignUpActive(): boolean {
            return this.$stock.state.authModal.tab === AuthModalTab.SignUp;
        },
    },

    methods: {
        updateVisibility(visible: boolean): void {
            this.$stock.commit("authModal/updateVisibility", visible);
        },

        activateSignInTab(): void {
            this.$stock.commit("authModal/updateTab", AuthModalTab.SignIn);
        },

        activateSignUpTab(): void {
            this.$stock.commit("authModal/updateTab", AuthModalTab.SignUp);
        },

        onSuccess(): void {
            this.$stock.dispatch("user/reload");
            this.$stock.commit("authModal/updateVisibility", false);
        },
    },
});
</script>
