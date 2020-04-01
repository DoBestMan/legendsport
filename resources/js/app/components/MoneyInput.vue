<template>
    <BaseMoney
        class="form-control text-right"
        decimal="."
        thousands=","
        prefix="$ "
        suffix=""
        :precision="2"
        :value="value"
        @input="onInput"
    ></BaseMoney>
</template>

<script lang="ts">
import Vue from "vue";
// @ts-ignore
import { Money as BaseMoney } from "v-money";

export default Vue.extend({
    name: "MoneyInput",
    components: { BaseMoney },
    props: {
        value: Number,
    },

    methods: {
        onInput(value: number) {
            if (this.value !== value) {
                this.$emit("input", value);
            }
        },
    },
});
</script>
