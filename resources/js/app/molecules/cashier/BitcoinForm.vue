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
            <label class="label label--large">BTC WALLET</label>
            <input class="input input--large" />
        </div>
        <div class="form__control m--b--10">
            <label class="label label--large">COUNTRY</label>
            <div class="dropdown">
                <div class="form__control">
                    <input class="input input--large" type="text" value="USA" readonly="readonly" />
                    <div class="form__control__icon--right">
                        <i class="icon icon--micro icon--down"></i>
                    </div>
                </div>
                <div class="dropdown__content">
                    <div class="dropdown__content__item">
                        USA
                        <i class="icon icon--smaller icon--check icon--color--yellow-1"></i>
                    </div>
                    <div class="dropdown__content__item">England</div>
                </div>
            </div>
        </div>
        <button class="button button--large">SUBMIT</button>
    </form>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    name: "BitcoinForm",
});
</script>
