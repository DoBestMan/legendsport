<template>
    <form class="form">
        <label class="label label--large">AMOUNT TO DEPOSIT</label>
        <div class="form__control">
            <div class="form__control__icon form__control__icon--right">
                <i class="icon icon--micro icon--usd icon--color--light-1"></i>
            </div>
            <input class="input input--large" placeholder="Minimum of $50" />
        </div>
        <div class="seperator"></div>
        <div class="form__control m--b--6">
            <label class="label label--large">ACCOUNT NUMBER</label>
            <input class="input input--large" placeholder="123456789-1234567890" />
        </div>
        <div class="form__control m--b--10">
            <label class="label label--large">SWIFT CODE</label>
            <input class="input input--large" placeholder="Ex. NFBKUS33" />
        </div>
        <button type="submit" class="button button--large">SUBMIT</button>
    </form>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    name: "BankWireForm",
});
</script>
