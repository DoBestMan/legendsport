<template>
    <input
        :class="[inputClass, { 'is-invalid': errors.length }]"
        :value="value"
        :placeholder="placeHolder"
        v-bind="$attrs"
        v-on="listeners"
    />
</template>

<script lang="ts">
import Vue from "vue";
import FormFeedback from "../../general/components/FormFeedback.vue";

export default Vue.extend({
    name: "FormInput",
    inheritAttrs: false,
    components: { FormFeedback },

    props: {
        value: String,
        errors: {
            type: Array,
            default: () => [],
        },
        inputClass: String,
        placeHolder: String,
    },

    computed: {
        listeners(): object {
            return {
                ...this.$listeners,
                input: this.onInput,
            };
        },
    },

    methods: {
        onInput(e: any): void {
            this.$emit("input", e.target.value);
        },
    },
});
</script>
