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
            <BitcoinForm />
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import BitcoinForm from "./BitcoinForm.vue";
export default Vue.extend({
    name: "BitcoinMobileForm",

    components: { BitcoinForm },

    methods: {
        goToCashier(): void {
            this.$router.push("/cashier");
        },
    },
});
</script>
