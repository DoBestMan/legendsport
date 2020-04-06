<template>
    <div class="app container-fluid">
        <NavBar />
        <WindowBar />
        <router-view />
        <Footer />
        <FullLoader v-if="isLoaderVisible" />
        <Toasts :timeOut="7000" :closeable="true" />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import FullLoader from "../general/components/FullLoader.vue";
import NavBar from "./molecules/layout/NavBar.vue";
import Footer from "./molecules/layout/Footer.vue";
import WindowBar from "./molecules/layout/WindowBar.vue";

export default Vue.extend({
    name: "App",
    components: { Footer, FullLoader, NavBar, WindowBar },

    computed: {
        isLoaderVisible(): boolean {
            return this.$stock.state.loader.isVisible;
        },
    },
});
</script>
