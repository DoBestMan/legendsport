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
        <div class="gap m--b--6">
            <div class="gap__item">
                <div class="form__control">
                    <label class="label label--large">CARD NUMBER</label>
                    <input class="input input--large" placeholder="1234 5678 9012 3456" />
                </div>
            </div>
            <div class="gap__item">
                <div class="form__control">
                    <label class="label label--large">NAME ON CARD</label>
                    <input class="input input--large" placeholder="Ex. John Doe" />
                </div>
            </div>
        </div>
        <div class="gap m--b--10">
            <div class="gap__item">
                <div class="form__control">
                    <label class="label label--large">EXPIRATION DATE</label>
                    <input class="input input--large" placeholder="__/__" />
                </div>
            </div>
            <div class="gap__item">
                <label class="label label--large">SECURITY CODE</label>
                <div class="form__control">
                    <div class="form__control__icon form__control__icon--right">
                        <i class="icon icon--micro icon--wallet icon--color--light-1"></i>
                    </div>
                    <input class="input input--large" placeholder="CVV" />
                </div>
            </div>
        </div>
        <button type="submit" class="button button--large">SUBMIT</button>
    </form>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    name: "CreditCardForm",
});
</script>
