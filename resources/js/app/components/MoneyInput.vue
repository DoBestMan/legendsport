<template>
    <BaseMoney
        class="form-control text-right"
        decimal="."
        thousands=","
        prefix="$ "
        suffix=""
        :precision="2"
        :value="value"
        @input="$emit('input', $event)"
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
});
</script>
