<template>
    <div class="loading-overlay">
        <RetryFailed v-if="failed" :loading="loading" @retry="retry" />

        <slot v-else />

        <SpinnerSection v-if="loading && !failed" />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import SpinnerSection from "./SpinnerSection.vue";
import RetryFailed from "./RetryFailed.vue";

export default Vue.extend({
    name: "LoadingOverlay",
    components: { SpinnerSection, RetryFailed },
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        failed: {
            type: Boolean,
            default: false,
        },
    },

    methods: {
        retry() {
            this.$emit("retry");
        },
    },
});
</script>

<style scoped>
.loading-overlay {
    position: relative;
    min-height: 200px;
}
</style>
