<template>
    <div class="invalid-feedback">
        <div v-for="error in errors" :key="error">{{ error }}</div>
    </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
    name: "FormFeedback",

    props: {
        errors: {
            type: Array,
            default: () => [],
        },
    },
});
</script>
