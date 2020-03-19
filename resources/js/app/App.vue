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

                    <div class="tab-frm" v-for="tournament in tournaments">
                        <router-link
                            tag="button"
                            type="button"
                            class="tab"
                            :to="`/tournaments/${tournament.id}`"
                        >
                            {{ tournament.name }}
                        </router-link>
                        <div
                            class="delete"
                            style="margin-left: -5px"
                            @click="closeTournament(tournament)"
                        ></div>
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
import { Tournament } from "./types/tournament";

export default Vue.extend({
    name: "App",

    computed: {
        tournaments(): Tournament[] {
            return Object.values(this.$store.state.tournaments.tournaments);
        },
    },

    methods: {
        closeTournament(tournament: Tournament): void {
            this.$store.commit("tournaments/removeTournament", tournament.id);
        },
    },
});
</script>
