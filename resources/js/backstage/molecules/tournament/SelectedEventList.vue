<template>
    <div>
        <h5>Included Events</h5>
        <div class="table-responsive" style="max-height: 230px">
            <table
                class="table table-fixed table-sm table-light table-striped table-borderless table-hover"
            >
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" width="200px">External Id</th>
                        <th scope="col" width="220px">Date</th>
                        <th scope="col" width="220px">Home Team</th>
                        <th scope="col" width="220px">Away Team</th>
                        <th scope="col" width="200px">Sport</th>
                        <th scope="col" width="200px">Status</th>
                        <th scope="col" width="250px">Bets Graded/Placed (Bots)</th>
                        <th scope="col" width="200px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.external_id">
                        <td class="text-truncate" width="100px">
                            {{ event.external_id }}
                        </td>
                        <td class="text-truncate" width="220px">
                            {{ event.starts_at | toDateTime }}
                        </td>
                        <td class="text-truncate" width="220px">
                            {{ event.team_home }}
                        </td>
                        <td class="text-truncate" width="220px">
                            {{ event.team_away }}
                        </td>
                        <td class="text-truncate" width="200px">
                            {{ getSportName(event.sport_id) }}
                        </td>
                        <td class="text-truncate" width="200px">
                            {{ event.status }}
                        </td>
                        <td class="text-truncate" width="250px">
                            {{ event.bets_graded }}/{{ event.bets_placed }} ({{ event.bot_bets_graded }}/{{ event.bot_bets_placed }})
                        </td>
                        <td class="text-truncate" width="200px">
                            <button
                                v-if="hasRemoveListener"
                                type="button"
                                class="btn btn-dark"
                                @click="removeEvent(event)"
                            >
                                remove
                            </button>
                        </td>
                    </tr>
                    <TableNoRecords v-if="!events.length" />
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import TableNoRecords from "../../../general/components/TableNoRecords";
import sportStore from "../../stores/sportStore";
import { Event } from "../../types/event";

export default Vue.extend({
    name: "SelectedEventList",
    components: { TableNoRecords },

    props: {
        events: Array as PropType<Event[]>,
    },

    methods: {
        removeEvent(event: any) {
            this.$emit("remove", event);
        },

        getSportName(sportId: string): string {
            return sportStore.sportDictionary.get(sportId) ?? String(sportId);
        },
    },

    computed: {
        hasRemoveListener() {
            return this.$listeners && this.$listeners.remove;
        },
    },
});
</script>
