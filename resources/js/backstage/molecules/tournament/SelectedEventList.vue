<template>
    <div>
        <h5>Included Events</h5>
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
                    <tr v-for="event in events" :key="event.ID">
                        <td class="text-truncate" width="300px">
                            {{ event.starts_at | toDateTime }}
                        </td>
                        <td class="text-truncate" width="210px">
                            {{ event.home_team }}
                        </td>
                        <td class="text-truncate" width="230px">
                            {{ event.away_team }}
                        </td>
                        <td class="text-truncate" width="200px">
                            {{ getSportName(event.sport_id) }}
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
import Vue from "vue";
import TableNoRecords from "../../../general/components/TableNoRecords";
import sportStore from "../../stores/sportStore";

export default Vue.extend({
    name: "SelectedEventList",
    components: { TableNoRecords },

    props: {
        events: {
            type: Array,
        },
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
