<template>
    <div v-if="isLoaderVisible" class="full-loader">
        <SpinnerSection />
    </div>
</template>

<script>
import loaderStore from "../stores/loaderStore";
import SpinnerSection from "../../general/components/SpinnerSection";

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
