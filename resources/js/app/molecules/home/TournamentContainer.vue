<template>
    <section id="tab-home-frm" class="tab-content-frm row">
        <div class="col">
            <section id="filters-frm">
                <FilterContainer />
            </section>

            <section id="tournaments-frm" class="row">
                <div class="col-9">
                    <TournamentList :selectedTournament.sync="selectedTournament" />
                </div>

                <div class="col-3">
                    <TournamentDetails :tournamentId="selectedTournament" />
                </div>
            </section>
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import FilterContainer from "./FilterContainer.vue";
import TournamentList from "./TournamentList.vue";
import TournamentDetails from "./TournamentDetails.vue";
import { Nullable } from "../../../general/types/types";

export default Vue.extend({
    name: "TournamentContainer",
    components: { FilterContainer, TournamentDetails, TournamentList },

    data() {
        return {
            selectedTournament: null as Nullable<number>,
        };
    },
});
</script>
