<template>
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
            <div
                class="delete"
                style="margin-left: 5px; margin-top: -15px;"
                @click="closeWindow(window)"
            />
        </div>
    </section>
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
            closedWindow: 0,
        };
    },

    computed: {
        windows(): Window[] {
            return Object.values(this.$stock.getters["window/windows"]);
        },
    },

    methods: {
        closeWindow(window: Window): void {
            this.closedWindow = 1;
            this.$stock.commit("window/closeWindow", window.id);
        },

        selectWindowTabs(window_id: number) {
            this.windowId = window_id;
            if (this.closedWindow === 1) {
                this.windowId = -1;
                this.closedWindow = 0;
            }
            if (this.windowId === -1) {
                this.$router.push("/");
            } else {
                this.$router.push(`/tournaments/${this.windowId}`);
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
