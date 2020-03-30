<template>
    <div class="full-loader">
        <SpinnerSection />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import SpinnerSection from "./SpinnerSection.vue";

export default Vue.extend({
    name: "FullLoader",
    components: { SpinnerSection },
});
</script>
