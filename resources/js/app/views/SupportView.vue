<template>
    <div class="layout__center">
        <div class="layout__center__wrapper">
            <h2 class="subtitle subtitle--light text--center">CONTACT US</h2>
            <form class="form">
                <div class="form__control m--b--4">
                    <label class="label label--large">E-MAIL</label>
                    <input class="input input--large" placeholder="Ex. john@google.com" />
                </div>
                <div class="form__control m--b--10">
                    <label class="label label--large">YOUR MESSAGE</label>
                    <textarea
                        class="input input--large"
                        placeholder="Enter your message..."
                        rows="4"
                    ></textarea>
                </div>
                <button type="submit" class="button button--large m--b--6">SUBMIT</button>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
export default Vue.extend({
    name: "SupportView",
});
</script>
