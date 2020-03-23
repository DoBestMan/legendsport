<template>
    <b-modal
        title="Available Events"
        size="xl"
        :hide-footer="true"
        :visible="value"
        @change="$emit('input', $event)"
    >
        <FilterContainer
            :sports.sync="sports"
            :eventDate.sync="eventDate"
            @includeAll="includeAll"
        ></FilterContainer>

        <LoadingOverlay
            :loading="loadEventsIsLoading"
            :failed="loadEventsFailed"
            @retry="loadEvents"
        >
            <div class="table-responsive" style="max-height: 230px">
                <table
                    class="table table-fixed table-sm table-light table-striped table-borderless table-hover"
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
                        <tr v-for="event in filteredEvents" :key="event.ID">
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
                        <TableNoRecords v-if="!filteredEvents.length" />
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
import moment from "moment";
import { Event } from "../../types/event";
import LoadingOverlay from "../../../general/components/LoadingOverlay";
import FilterContainer from "./FilterContainer";
import { Nullable } from "../../../general/types/types";
import { empty } from "../../../general/utils/utils";
import TableNoRecords from "../../../general/components/TableNoRecords.vue";
import sportStore from "../../stores/sportStore";

export default Vue.extend({
    name: "ModalAvailableEventList",
    components: { BModal, FilterContainer, LoadingOverlay, TableNoRecords },

    props: {
        value: Boolean,
    },

    data() {
        return {
            sports: [] as number[],
            eventDate: null as Nullable<string>,

            events: [] as Event[],
            loadEventsIsLoading: false,
            loadEventsFailed: false,
        };
    },

    computed: {
        filteredEvents(): Event[] {
            return this.events.filter(event => {
                return (
                    (!this.eventDate || moment(event.MatchTime).isSame(this.eventDate, "day")) &&
                    (empty(this.sports) || this.sports.includes(event.Sport))
                );
            });
        },
    },

    methods: {
        async loadEvents(): Promise<void> {
            this.loadEventsIsLoading = true;

            try {
                const response = await axios.get("/api/events");
                this.events = response.data;
                this.loadEventsFailed = false;
            } catch (e) {
                this.loadEventsFailed = true;
            } finally {
                this.loadEventsIsLoading = false;
            }
        },

        getSportName(sportId: number): string {
            return sportStore.sportDictionary.get(sportId) ?? String(sportId);
        },

        includeEvent(event: any): void {
            this.$emit("select", event);
        },

        includeAll(): void {
            for (const event of this.filteredEvents) {
                this.includeEvent(event);
            }
        },
    },

    watch: {
        value(newVal) {
            if (newVal && empty(this.events)) {
                this.loadEvents();
            }
        },
    },
});
</script>
