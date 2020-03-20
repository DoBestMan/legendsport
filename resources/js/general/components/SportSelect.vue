<template>
    <multiselect
        selectLabel=""
        deselectLabel=""
        selectGroupLabel=""
        deselectGroupLabel=""
        trackBy="id"
        label="name"
        groupValues="items"
        groupLabel="name"
        :groupSelect="true"
        placeholder="Type to filter..."
        :closeOnSelect="false"
        :options="sports"
        :value="formattedSelectedSports"
        :limit="0"
        @input="changeSports"
        multiple
    >
        <template v-slot:placeholder>
            Select sports
        </template>

        <template v-slot:singleLabel>
            {{ label }}
        </template>

        <template v-slot:limit>
            <span></span>
        </template>
    </multiselect>
</template>

<script lang="ts">
import Vue, { PropType } from "vue";
import Multiselect from "vue-multiselect";
import { Sport, sports, sportsMap } from "../utils/sportUtils";

export default Vue.extend({
    name: "SportSelect",
    components: { Multiselect },

    props: {
        value: Array as PropType<number[]>,
    },

    computed: {
        sports(): any[] {
            return [
                {
                    name: this.value.length === sports.length ? "Deselect all" : "Select all",
                    items: sports,
                },
            ];
        },

        formattedSelectedSports(): Sport[] {
            return this.value.map(sportId => sportsMap.get(sportId)!);
        },

        label(): string {
            if (this.value.length === sports.length) {
                return "All";
            }

            if (this.value.length === 1) {
                return this.formattedSelectedSports[0].name;
            }

            if (this.value.length > 1) {
                return "Custom";
            }

            return "";
        },
    },

    methods: {
        changeSports(sports: Sport[]) {
            const sportsIds = (sports || []).map(item => item.id);
            this.$emit("input", sportsIds);
        },
    },
});
</script>
