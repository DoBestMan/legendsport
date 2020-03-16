<template>
    <div v-if="isLoaderVisible" class="full-loader">
        <SpinnerSection />
    </div>
</template>

<script>
import SpinnerSection from "./SpinnerSection";
import loaderStore from "../stores/loaderStore";

export default {
    name: "FullLoader",
    components: { SpinnerSection },

    computed: {
        isLoaderVisible() {
            return loaderStore.isVisible;
        },
    },
};
</script>

<style lang="scss">
.full-loader {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 100vw;
}
</style>
