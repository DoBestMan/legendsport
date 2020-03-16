<template>
    <div class="retry-box">
        <h4 class="mb-4 text-danger">
            Something went wrong
        </h4>

        <ActionButton class="btn btn-secondary" type="button" :loading="loading" @click="retry">
            Try again
        </ActionButton>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import ActionButton from "./ActionButton.vue";

export default Vue.extend({
    name: "RetryFailed",
    components: { ActionButton },
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
.retry-box {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    margin: 3rem 0;
}
</style>
