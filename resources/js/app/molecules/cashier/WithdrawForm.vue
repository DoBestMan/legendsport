<template>
    <form class="form" @submit.prevent="withdraw">
        <label class="label label--large">AMOUNT TO DEPOSIT</label>
        <div class="form__control">
            <div class="form__control__icon form__control__icon--right">
                <i class="icon icon--micro icon--usd icon--color--light-1"></i>
            </div>
            <FormInput
                id="form-amount"
                inputClass="input input--large"
                placeHolder="Minimum of $50"
                type="text"
                :errors="errors.amount"
                v-model="amount"
                required
            />
            <div class="error" v-if="errors.amount">
                <span v-for="error in errors.amount"> {{ error }} </span>
            </div>
        </div>
        <div class="seperator"></div>
        <div class="form__control m--b--6">
            <label class="label label--large">BTC WALLET</label>
            <FormInput
                id="form-btc-address"
                inputClass="input input--large"
                type="text"
                :errors="errors.btcAddress"
                v-model="btcAddress"
                required
            />
            <div class="error" v-if="errors.btcAddress">
                <span v-for="error in errors.btcAddress"> {{ error }} </span>
            </div>
        </div>
        <button class="button button--large">SUBMIT</button>
    </form>
</template>

<script lang="ts">
import Vue from "vue";
import FormInput from "../../components/FormInput.vue";
import { AxiosError } from "axios";
export default Vue.extend({
    name: "WithdrawForm",
    components: { FormInput },
    data() {
        return {
            errors: {},
            amount: null,
            btcAddress: "",
        };
    },
    methods: {
        async withdraw() {
            try {
                await this.$stock.state.api.withdraw({
                    amount: this.amount,
                    btcAddress: this.btcAddress,
                });
                this.$router.push("/lobby");
                this.$toast.info("Your withdrawal has been queued for processing");
            } catch (e) {
                this.$toast.error((e as AxiosError).response?.data.message);
                this.errors = (e as AxiosError).response?.data.errors ?? {};
            }
        },
    },
});
</script>
