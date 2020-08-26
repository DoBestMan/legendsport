<template>
    <div class="layout">
        <component
            :is="this.$router.currentRoute.meta.layout && this.$router.currentRoute.meta.layout[0]"
        />

        <component
            :is="
                this.$router.currentRoute.meta.layout &&
                    this.$router.currentRoute.meta.layout.length === 2 &&
                    this.$router.currentRoute.meta.layout[1]
            "
        />

        <router-view />
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
