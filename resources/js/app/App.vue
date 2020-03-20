<template>
    <div style="display: contents">
        <section class="row">
            <div class="col tabs-row-frm">
                <div class="tabs-frm">
                    <div class="tab-frm">
                        <router-link tag="button" type="button" class="tab" to="/" exact>
                            <i class="icon fas fa-home"></i>
                            Home
                        </router-link>
                        <span class="separator">|</span>
                    </div>

                    <div class="tab-frm" v-for="tab in tabs">
                        <router-link
                            tag="button"
                            type="button"
                            class="tab"
                            :to="`/tournaments/${tab.id}`"
                        >
                            {{ tab.tournament.name }}
                        </router-link>
                        <div class="delete" style="margin-left: -5px" @click="closeTab(tab)"></div>
                        <span class="separator">|</span>
                    </div>
                </div>
            </div>
        </section>

        <router-view />
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import { Tab } from "./store/modules/tabs";

export default Vue.extend({
    name: "App",

    computed: {
        tabs(): Tab[] {
            return Object.values(this.$store.getters["tabs/tabs"]);
        },
    },

    methods: {
        closeTab(tab: Tab): void {
            this.$store.commit("tabs/closeTab", tab.id);
        },
    },
});
</script>
