<template>
    <div>
        <section class="layout__header">
            <div
                class="tab"
                :class="{
                    'tab--active': isHomeSelected(),
                }"
                @click="selectWindowTabs(-1)"
            >
                <i
                    class="icon icon--home icon--micro m--r--2"
                    :class="{ 'b--yellow-1': isHomeSelected() }"
                ></i>
                Home
            </div>

            <div
                v-for="window in windows"
                :key="window.id"
                class="tab"
                :class="{
                    'tab--active': isWindowsSelected(window.id),
                }"
                @click="selectWindowTabs(window.id)"
            >
                <i
                    class="icon icon--cup icon--micro m--r--2"
                    :class="{ 'b--yellow-1': isWindowsSelected(window.id) }"
                ></i>
                {{ window.tournament.name }}
            </div>
        </section>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import HorizontallyScrollable from "../../components/HorizontallyScrollable.vue";
import { Window } from "../../types/window";

export default Vue.extend({
    name: "WindowBar",
    components: { HorizontallyScrollable },

    data() {
        return {
            windowId: -1,
        };
    },

    computed: {
        windows(): Window[] {
            return Object.values(this.$stock.getters["window/windows"]);
        },
    },

    methods: {
        closeWindow(window: Window): void {
            this.$stock.commit("window/closeWindow", window.id);
        },

        selectWindowTabs(window_id: number) {
            this.windowId = window_id;
            if (window_id === -1) {
                this.$router.push("/");
            } else {
                this.$router.push(`/tournaments/${window_id}`);
            }
        },

        isHomeSelected(): boolean {
            return this.windowId === -1;
        },

        isWindowsSelected(window_id: number): boolean {
            return window_id === this.windowId;
        },
    },
});
</script>
