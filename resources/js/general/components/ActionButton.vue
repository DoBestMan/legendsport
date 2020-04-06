<template>
    <button
        class="btn"
        :type="type"
        :disabled="disabled || loading"
        v-bind="$attrs"
        v-on="$listeners"
    >
        <span v-if="loading" class="spinner-border spinner-border-sm text-light mr-2"></span>
        <slot />
    </button>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "ActionButton",
    props: {
        disabled: {
            type: Boolean,
            default: false,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: "submit",
        },
    },
});
</script>

<style scoped>
.btn {
    display: flex;
    align-items: center;
}
</style>
