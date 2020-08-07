<template>
    <section class="layout__header">
        <!-- <HorizontallyScrollable class="col tabs-row-frm">
            <div class="tabs-frm">
                <div class="tab-frm">
                    <router-link tag="button" class="btn tab" to="/" exact>
                        <i class="icon fas fa-home"></i>
                        Home
                    </router-link>
                </div>

                <div class="tab-frm" v-for="window in windows" :key="window.id">
                    <router-link tag="button" class="btn tab" :to="`/tournaments/${window.id}`">
                        {{ window.tournament.name }}
                    </router-link>
                    <div class="delete" style="margin-left: -5px" @click="closeWindow(window)" />
                </div>
            </div>
        </HorizontallyScrollable> -->
        <div>
            <router-link class="tab" to="/" exact>
                <i class="icon icon--home icon--micro m--r--2"></i>
                Home
            </router-link>
        </div>

        <div v-for="window in windows" :key="window.id">
            <router-link class="tab" :to="`/tournaments/${window.id}`">
                <i class="icon icon--cup icon--micro m--r--2"></i>
                {{ window.tournament.name }}
            </router-link>
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

    computed: {
        windows(): Window[] {
            return Object.values(this.$stock.getters["window/windows"]);
        },
    },

    methods: {
        closeWindow(window: Window): void {
            this.$stock.commit("window/closeWindow", window.id);
        },
    },
});
</script>
