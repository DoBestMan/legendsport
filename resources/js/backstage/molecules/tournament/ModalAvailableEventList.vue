<template>
    <b-modal
        title="Available Events"
        size="xl"
        :hide-footer="true"
        :visible="value"
        @change="$emit('input', $event)"
    >
        <FilterContainer
            :selectedSports.sync="selectedSports"
            @includeAll="includeAll"
        ></FilterContainer>

        <LoadingOverlay
            :loading="loadEventsIsLoading"
            :failed="loadEventsFailed"
            @retry="loadEvents"
        >
            <div class="table-responsive">
                <table
                    class="headerFixed table table-sm table-light table-striped table-borderless table-hover"
                >
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" width="280px">Date</th>
                            <th scope="col" width="220px">Home team</th>
                            <th scope="col" width="230px">Away Team</th>
                            <th scope="col" width="200px">Sport</th>
                            <th scope="col" width="200px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events">
                            <td class="text-truncate" width="300px">
                                {{ event.MatchTime }}
                            </td>
                            <td class="text-truncate" width="210px">
                                {{ event.HomeTeam }}
                            </td>
                            <td class="text-truncate" width="230px">
                                {{ event.AwayTeam }}
                            </td>
                            <td class="text-truncate" width="200px">
                                {{ getSportName(event.Sport) }}
                            </td>
                            <td class="text-truncate" width="200px">
                                <button
                                    type="button"
                                    class="btn btn-dark"
                                    @click="includeEvent(event)"
                                >
                                    include
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!events.length">
                            <td colspan="10" class="p-4 text-center w-100">
                                No records
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <hr />
            </div>
        </LoadingOverlay>
    </b-modal>
</template>

<script lang="ts">
import Vue from "vue";
import { BModal } from "bootstrap-vue";
import axios from "axios";
import debounce from "lodash.debounce";
import LoadingOverlay from "../../../general/components/LoadingOverlay";
import { getSportName } from "../../../general/utils/sportUtils";
import FilterContainer from "./FilterContainer";

export default Vue.extend({
    name: "ModalAvailableEventList",
    components: { BModal, FilterContainer, LoadingOverlay },

    props: {
        value: Boolean,
    },

    data() {
        return {
            selectedSports: [],
            events: [],
            loadEventsIsLoading: false,
            loadEventsFailed: false,
            // @ts-ignore
            loadDebounced: debounce(this.loadEvents, 500),
        };
    },

    methods: {
        async loadEvents() {
            this.loadEventsIsLoading = true;

            try {
                const response = await axios.post("/tournaments/get-events", {
                    SelectSport: this.selectedSports,
                });
                this.events = response.data;
                this.loadEventsFailed = false;
            } catch (e) {
                this.loadEventsFailed = true;
            } finally {
                this.loadEventsIsLoading = false;
            }
        },

        getSportName(sportId: number): string {
            return getSportName(sportId);
        },

        includeEvent(event: any) {
            this.$emit("select", event);
        },

        includeAll() {
            for (const event of this.events) {
                this.includeEvent(event);
            }
        },
    },

    watch: {
        selectedSports(newVal) {
            this.loadDebounced(newVal);
        },

        value(newVal) {
            if (newVal && !this.events.length) {
                this.loadEvents();
            }
        },
    },
});
</script>
