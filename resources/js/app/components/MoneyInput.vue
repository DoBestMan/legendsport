<template>
    <Money
        class="form-control text-right"
        decimal="."
        thousands=","
        prefix="$ "
        :precision="2"
        :value="value"
        @input="onInput"
    ></Money>
</template>

<script lang="ts">
import Vue from "vue";
// @ts-ignore
import { Money } from "v-money";

export default Vue.extend({
    name: "MoneyInput",
    components: { Money },
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
