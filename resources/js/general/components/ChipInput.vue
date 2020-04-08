<template>
    <Money
        class="form-control"
        :class="customClass"
        thousands=","
        :precision="0"
        :min="min"
        :value="value"
        @input="onInput"
        ref="money"
    />
</template>

<script lang="ts">
import Vue from "vue";
// @ts-ignore
import { Money } from "v-money";

export default Vue.extend({
    name: "ChipInput",
    components: { Money },

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

    mounted(): void {
        (this.$refs.money as Vue).$el.addEventListener("blur", this.onBlur);
    },

    beforeDestroy(): void {
        (this.$refs.money as Vue).$el.removeEventListener("blur", this.onBlur);
    },

    methods: {
        onInput(value: number): void {
            if (this.value !== value) {
                this.$emit("input", value);
            }
        },

        onBlur(): void {
            const value = this.value === 0 ? 0 : Math.max(this.min, this.value);
            this.$emit("input", value);
        },
    },
});
</script>
