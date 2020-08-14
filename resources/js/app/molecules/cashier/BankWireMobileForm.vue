<template>
    <section>
        <div class="paging">
            <div class="paging__item">
                <i
                    class="icon icon--left icon--large icon--color--light-1 m--r--4"
                    @click="goToCashier"
                ></i>
                <div class="paging__item__title">Cashier</div>
            </div>
        </div>

        <div class="container container--small">
            <BankWireForm />
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import BankWireForm from "./BankWireForm.vue";
export default Vue.extend({
    name: "BankWireMobileForm",

    components: { BankWireForm },

    methods: {
        goToCashier(): void {
            this.$router.push("/cashier");
        },
    },
});
</script>
