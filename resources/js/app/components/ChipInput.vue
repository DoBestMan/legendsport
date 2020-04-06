<template>
    <input
        type="number"
        class="form-control text-right"
        :min="min"
        :value="value"
        @input="onInput"
        @keypress.enter="onChange"
        @blur="onChange"
    />
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "ChipInput",

    props: {
        value: Number,
        min: {
            type: Number,
            default: 100,
        },
    },

    methods: {
        onInput(e: any) {
            e.target.value = Math.floor(e.target.value);

            const value = Number(e.target.value);
            if (value >= this.min) {
                this.$emit("input", value);
            }
        },

        onChange(e: any) {
            e.target.value = Math.max(this.min, Math.floor(e.target.value));
            this.$emit("input", Number(e.target.value));
        },
    },
});
</script>
