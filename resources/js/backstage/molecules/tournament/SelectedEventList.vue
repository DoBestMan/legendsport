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
                                v-if="hasRemoveListener"
                                type="button"
                                class="btn btn-dark"
                                @click="removeEvent(event)"
                            >
                                remove
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
        </div>
    </div>
</template>

<script>
import { getSportName } from "../../../general/utils/sportUtils";

export default {
    name: "SelectedEventList",

    props: {
        events: {
            type: Array,
        },
    },

    methods: {
        removeEvent(event) {
            this.$emit("remove", event);
        },

        getSportName(sportId) {
            return getSportName(sportId);
        },
    },

    computed: {
        hasRemoveListener() {
            return this.$listeners && this.$listeners.remove;
        },
    },
};
</script>
