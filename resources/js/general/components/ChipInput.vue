<template>
    <div class="form__control">
        <div class="form__control__icon form__control__icon--left">
            <i class="icon icon--micro icon--usd icon--color--light-1"></i>
        </div>
        <input
            class="input input--padding--left"
            type="number"
            :class="customClass"
            :value="!value ? '' : value"
            :min="min"
            @input="$emit('input', parseInt($event.target.value))"
        />
    </div>
</template>
​
<script lang="ts">
import Vue from "vue";
// @ts-ignore
import { Money } from "v-money";
export default Vue.extend({
    name: "ChipInput",
    props: {
        value: Number,
        customClass: {
            type: String,
            default: "text-right",
        },
        min: {
            type: Number,
            default: 100,
        },
    },
});
</script>
